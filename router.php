<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if (preg_match('/^\/api\/(.*)/', $uri, $matches)) {
$scriptPath = __DIR__ . '/core/' . $matches[1] . '.php';
if (file_exists($scriptPath)) {
require $scriptPath;
return;
}
http_response_code(404);
echo "Endpoint not found.";
return;
}

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
return false;
}

require_once __DIR__ . '/index.html';