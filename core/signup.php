<?php
// FILE: /core/signup.php

// Include the necessary core files
require __DIR__ . '/db_connect.php';
require __DIR__ . '/functions.php';

// Set the response header to indicate JSON content
header('Content-Type: application/json');

// Read the raw POST data from the request
$input = json_decode(file_get_contents('php://input'), true);

// Call the main function that handles all the complex logic
$result = registerUserAndAssignColor(
    $input['email'] ?? '', 
    $input['username'] ?? '', 
    $input['password'] ?? ''
);

// If the function returned an error, set the appropriate HTTP status code
if (isset($result['code']) && !$result['success']) {
    http_response_code($result['code']);
}

// Send the final result back to the JavaScript frontend
echo json_encode(['success' => $result['success'], 'message' => $result['message']]);