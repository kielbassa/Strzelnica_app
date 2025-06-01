<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../classes/User.php';
require_once '../classes/Session.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metoda nie dozwolona']);
    exit;
}

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// If no JSON data, try to get from POST
if (!$data) {
    $data = $_POST;
}

// Validate required fields
$required_fields = ['email', 'password'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        echo json_encode(['success' => false, 'message' => 'Email i hasło są wymagane']);
        exit;
    }
}

try {
    // Database connection
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        echo json_encode(['success' => false, 'message' => 'Błąd połączenia z bazą danych']);
        exit;
    }
    
    // Create user object
    $user = new User($db);
    
    // Clean input data
    $email = trim(strtolower($data['email']));
    $password = $data['password'];
    
    // Validate email format
    if (!$user->validateEmail($email)) {
        echo json_encode(['success' => false, 'message' => 'Nieprawidłowy format adresu e-mail']);
        exit;
    }
    
    // Attempt login
    if ($user->login($email, $password)) {
        // Create session
        $session = new Session();
        $session->login($user);
        
        // Update last login time
        $user->updateLastLogin();
        
        echo json_encode([
            'success' => true,
            'message' => 'Zalogowano pomyślnie!',
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'full_name' => $user->first_name . ' ' . $user->last_name
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nieprawidłowy email lub hasło']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd serwera']);
    error_log("Login error: " . $e->getMessage());
}
?>