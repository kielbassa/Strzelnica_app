<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../classes/Session.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metoda nie dozwolona']);
    exit;
}

try {
    // Create session object
    $session = new Session();
    
    // Check if user is logged in
    if (!$session->isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Użytkownik nie jest zalogowany']);
        exit;
    }
    
    // Log out user
    $session->logout();
    
    echo json_encode([
        'success' => true,
        'message' => 'Wylogowano pomyślnie!'
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd podczas wylogowywania']);
    error_log("Logout error: " . $e->getMessage());
}
?>