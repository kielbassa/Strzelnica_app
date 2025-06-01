<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../classes/Session.php';

try {
    $session = new Session();
    
    if ($session->isLoggedIn()) {
        // Check for session timeout (1 hour)
        if ($session->sessionTimeout(3600)) {
            echo json_encode([
                'success' => false,
                'authenticated' => false,
                'message' => 'Sesja wygasła. Zaloguj się ponownie.'
            ]);
            exit;
        }
        
        // Refresh session time
        $session->refreshSession();
        
        echo json_encode([
            'success' => true,
            'authenticated' => true,
            'user' => [
                'id' => $session->getUserId(),
                'email' => $session->getUserEmail(),
                'first_name' => $session->getUserFirstName(),
                'last_name' => $session->getUserLastName(),
                'full_name' => $session->getUserFullName(),
                'login_time' => $session->getLoginTime()
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'authenticated' => false,
            'message' => 'Użytkownik nie jest zalogowany'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'authenticated' => false,
        'message' => 'Wystąpił błąd podczas sprawdzania sesji'
    ]);
    error_log("Session check error: " . $e->getMessage());
}
?>