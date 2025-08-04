<?php
// FILE: /core/functions.php (Final Corrected Version - English)

/**
 * Central function to send requests to the Supabase REST API using cURL.
 * This is the single point of communication with Supabase.
 *
 * @param string $method The HTTP method (GET, POST, PATCH).
 * @param string $endpoint The name of the table or RPC in Supabase.
 * @param array $data The data to send (for POST and PATCH requests).
 * @param string $params URL query parameters (for GET and PATCH requests).
 * @return array The decoded JSON response body and the HTTP status code.
 */
function supabase_request($method, $endpoint, $data = [], $params = '') {
    $url = SUPABASE_URL . '/rest/v1/' . $endpoint . $params;
    $headers = [
        'apikey: ' . SUPABASE_KEY,
        'Authorization: Bearer ' . SUPABASE_KEY,
        'Content-Type: application/json',
        'Prefer: return=representation' // Asks Supabase to return the data after an operation
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    switch (strtoupper($method)) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case 'PATCH': // Method used for updates
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case 'GET':
            // No specific options needed for GET
            break;
    }
    
    $response_body = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return ['code' => $http_code, 'body' => json_decode($response_body, true)];
}


/**
 * Registers a new user AND assigns them a unique, unowned color.
 * THIS VERSION USES PURE PHP LOGIC TO FIND AN AVAILABLE COLOR.
 *
 * @param string $email The user's email.
 * @param string $username The user's chosen username.
 * @param string $password The user's plain-text password.
 * @return array An array containing the success status and a message.
 */
function registerUserAndAssignColor($email, $username, $password) {
    if (empty($email) || empty($username) || empty($password)) {
        return ['success' => false, 'message' => 'All fields are required.', 'code' => 400];
    }

    // --- STEP 1: FIND AN AVAILABLE COLOR (PHP LOGIC) ---
    // We send a GET request to the 'colors' table directly.
    // - owner_id=is.null : This filters for colors that have no owner.
    // - select=id,hex_code,name : We only ask for the data we need.
    // - limit=1 : We only need one color.
    $colorParams = '?owner_id=is.null&select=id,hex_code,name&limit=1';
    $colorResponse = supabase_request('GET', 'colors', [], $colorParams);
    
    // Check if the request was successful and if a color was actually found.
    if ($colorResponse['code'] != 200 || empty($colorResponse['body'])) {
        return ['success' => false, 'message' => 'CRITICAL: Could not find an available color. Please run the population script.', 'code' => 503];
    }
    $color = $colorResponse['body'][0];
    $color_id = $color['id'];

    // --- STEP 2: CREATE THE NEW USER ---
    $userResponse = supabase_request('POST', 'users', [
        'email' => $email,
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    if ($userResponse['code'] != 201) { // 201 = Created
        return ['success' => false, 'message' => 'This email or username is already taken.', 'code' => 409];
    }
    $user_id = $userResponse['body'][0]['id'];

    // --- STEP 3: ASSIGN THE COLOR TO THE USER ---
    $updateParams = '?id=eq.' . $color_id;
    $updateResponse = supabase_request('PATCH', 'colors', ['owner_id' => $user_id], $updateParams);

    if ($updateResponse['code'] != 200) {
        return ['success' => false, 'message' => 'Critical error during color assignment.', 'code' => 500];
    }
    
    // --- STEP 4: LOG THIS EVENT IN THE COLOR'S HISTORY ---
    supabase_request('POST', 'color_history', [
        'color_id' => $color_id,
        'user_id' => $user_id,
        'event_type' => 'creation_assignment'
    ]);

    return ['success' => true, 'message' => "Sign up successful! You are now the owner of the color '{$color['name']}' ({$color['hex_code']})."];
}


/**
 * Logs a user in by checking their credentials and setting a session variable.
 *
 * @param string $email The user's email.
 * @param string $password The user's plain-text password.
 * @return array An array containing the success status and an optional message.
 */
function loginUser($email, $password) {
    if (empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Email and password are required.', 'code' => 400];
    }
    
    $params = '?select=id,password_hash&email=eq.' . urlencode($email);
    $response = supabase_request('GET', 'users', [], $params);

    if ($response['code'] == 200 && !empty($response['body'])) {
        $user = $response['body'][0];
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            return ['success' => true];
        }
    }
    
    return ['success' => false, 'message' => 'Invalid credentials.', 'code' => 401];
}

/**
 * Fetches the complete profile data for a given user ID.
 *
 * @param string $user_id The UUID of the logged-in user.
 * @return array An array containing the success status and the profile data.
 */
function getProfileData($user_id) {
    if (empty($user_id)) {
        return ['success' => false, 'message' => 'User not identified.', 'code' => 401];
    }

    // Step 1: Get user details
    $userParams = '?select=username,email,created_at&id=eq.' . $user_id;
    $userResponse = supabase_request('GET', 'users', [], $userParams);

    if ($userResponse['code'] != 200 || empty($userResponse['body'])) {
        return ['success' => false, 'message' => 'User not found.', 'code' => 404];
    }
    $userData = $userResponse['body'][0];

    // Step 2: Get the user's color
    $colorParams = '?select=name,hex_code,description&owner_id=eq.' . $user_id;
    $colorResponse = supabase_request('GET', 'colors', [], $colorParams);
    
    $colorData = null;
    if ($colorResponse['code'] == 200 && !empty($colorResponse['body'])) {
        $colorData = $colorResponse['body'][0];
    }

    // Step 3: Combine data and return
    $profileData = [
        'user' => $userData,
        'color' => $colorData
    ];

    return ['success' => true, 'data' => $profileData];
}