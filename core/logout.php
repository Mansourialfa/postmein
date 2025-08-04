<?php
require __DIR__ . '/db_connect.php';
session_destroy();
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Déconnexion réussie.']);