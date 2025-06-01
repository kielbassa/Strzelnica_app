<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../classes/User.php';
require_once '../classes/Client.php';
require_once '../classes/Session.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metoda nie dozwolona']);
    exit;
}

// Check if user is logged in
$session = new Session();
if (!$session->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Musisz być zalogowany']);
    exit;
}

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// If no JSON data, try to get from POST
if (!$data) {
    $data = $_POST;
}

// Validate required fields
$required_fields = ['membershipType'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        echo json_encode(['success' => false, 'message' => 'Typ członkostwa jest wymagany']);
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
    
    // Start transaction
    $db->beginTransaction();
    
    $user_id = $session->getUserId();
    $membership_type = trim($data['membershipType']);
    
    // Validate membership type
    $valid_types = ['standard', 'premium', 'vip'];
    if (!in_array($membership_type, $valid_types)) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Nieprawidłowy typ członkostwa']);
        exit;
    }
    
    // Get pricing information
    $pricing = [
        'standard' => 50,
        'premium' => 100,
        'vip' => 200
    ];
    
    $price = $pricing[$membership_type];
    
    // Create membership record
    $activation_date = date('Y-m-d');
    $expiration_date = date('Y-m-d', strtotime('+1 month'));
    
    $membership_query = "INSERT INTO membership (type, activation_date, expiration_date) 
                        VALUES (:type, :activation_date, :expiration_date)";
    
    $membership_stmt = $db->prepare($membership_query);
    $membership_stmt->bindParam(':type', ucfirst($membership_type));
    $membership_stmt->bindParam(':activation_date', $activation_date);
    $membership_stmt->bindParam(':expiration_date', $expiration_date);
    
    if (!$membership_stmt->execute()) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Błąd podczas tworzenia członkostwa']);
        exit;
    }
    
    $membership_id = $db->lastInsertId();
    
    // Get or create client record
    $client = new Client($db);
    
    if (!$client->getByUserId($user_id)) {
        // Client doesn't exist, create one
        $user = new User($db);
        if ($user->getUserById($user_id)) {
            $client->user_id = $user_id;
            $client->name = $user->first_name;
            $client->surname = $user->last_name;
            $client->ID_membership = $membership_id;
            
            if (!$client->create()) {
                $db->rollBack();
                echo json_encode(['success' => false, 'message' => 'Błąd podczas tworzenia profilu klienta']);
                exit;
            }
        } else {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Nie znaleziono użytkownika']);
            exit;
        }
    } else {
        // Client exists, update membership
        if (!$client->assignMembership($membership_id)) {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Błąd podczas przypisywania członkostwa']);
            exit;
        }
    }
    
    // Commit transaction
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Członkostwo zostało pomyślnie zakupione!',
        'membership' => [
            'id' => $membership_id,
            'type' => ucfirst($membership_type),
            'price' => $price,
            'activation_date' => $activation_date,
            'expiration_date' => $expiration_date,
            'client_id' => $client->ID_client
        ]
    ]);
    
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd serwera']);
    error_log("Membership purchase error: " . $e->getMessage());
}
?>