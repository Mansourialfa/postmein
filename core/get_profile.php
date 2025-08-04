<?php
// FILE: /core/get_profile.php (CLEAN, FINAL VERSION)

// Include the necessary core files
require __DIR__ . '/db_connect.php';
require __DIR__ . '/functions.php';

// Set the response header to indicate JSON content
header('Content-Type: application/json');

// Check if the user is logged in by looking at the session
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Not authorized. Please log in.']);
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Call the function that fetches all profile data
$result = getProfileData($user_id);

// If the function returned an error, set the appropriate HTTP status code
if (!$result['success']) {
    http_response_code($result['code'] ?? 500);
}

// Send the final result back to the JavaScript frontend
echo json_encode($result);