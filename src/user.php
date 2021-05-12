<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/session.php';

header('Content-Type: application/json');

try {
    $session = SessionManager::getCurrentSession();
    $username = $session->username;

    foreach ($userCollections as $user) {
        if ($user->username == $session->username) {
            echo json_encode([
                'status' => 200,
                'data' => $user
            ]);
            exit();
        }
    }
    throw new Exception("Invalid user session");
} catch (Exception $exception) {
    echo json_encode([
        'status' => 401,
        'message' => $exception->getMessage(),
        'data' => null
    ]);
}
