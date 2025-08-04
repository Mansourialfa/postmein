<?php
// FILE: /router.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Route API requests (e.g., /api/signup.php) to the /core directory
if (preg_match('/^\/api\/(.*)/', $uri, $matches)) {
    $scriptPath = __DIR__ . '/core/' . $matches[1];
    if (file_exists($scriptPath)) {
        require $scriptPath;
        return;
    }
}

// Route requests for CSS and JS files to the /public directory
if (preg_match('/^\/public\//', $uri) && file_exists(__DIR__ . $uri)) {
    // Let the built-in server handle the file if it exists in /public
    return false;
}

// Serve the main HTML files from the root
$htmlFiles = ['/index.html', '/signup.html', '/profile.html'];
if (in_array($uri, $htmlFiles) && file_exists(__DIR__ . $uri)) {
    require_once __DIR__ . $uri;
    return;
}

// Default to index.html if root is requested
if ($uri === '/') {
    require_once __DIR__ . '/index.html';
    return;
}

// If no route matches, let the server try to find the file (useful for favicon, etc.)
if (file_exists(__DIR__ . $uri)) {
    return false;
}

// If still no match, return a 404
http_response_code(404);
echo "404 Not Found";