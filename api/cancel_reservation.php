<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../classes/Session.php';
require_once '../classes/Client.php';

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
    echo json_encode(['success' => false, 'message' => 'Musisz być zalogowany, aby anulować rezerwację']);
    exit;
}

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// If no JSON data, try to get from POST
if (!$data) {
    $data = $_POST;
}

// Validate required fields
if (!isset($data['reservation_id']) || empty($data['reservation_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID rezerwacji jest wymagane']);
    exit;
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
    $reservation_id = (int)$data['reservation_id'];
    
    // Get client for this user
    $client = new Client($db);
    if (!$client->getByUserId($user_id)) {
        echo json_encode(['success' => false, 'message' => 'Nie znaleziono profilu klienta']);
        $db->rollBack();
        exit;
    }
    
    // Check if the reservation exists and belongs to this client
    $query = "SELECT * FROM reservations WHERE ID_reservations = :reservation_id AND ID_client = :client_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':reservation_id', $reservation_id);
    $stmt->bindParam(':client_id', $client->ID_client);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Rezerwacja nie istnieje lub nie należy do bieżącego użytkownika']);
        $db->rollBack();
        exit;
    }
    
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if reservation is in the future
    $reservation_date = strtotime($reservation['date'] . ' ' . $reservation['time']);
    if ($reservation_date < time()) {
        echo json_encode(['success' => false, 'message' => 'Nie można anulować rezerwacji, która już się odbyła']);
        $db->rollBack();
        exit;
    }
    
    // Check if it's less than 24 hours before the reservation
    if ($reservation_date - time() < 86400) { // 24 hours in seconds
        echo json_encode(['success' => false, 'message' => 'Nie można anulować rezerwacji na mniej niż 24 godziny przed terminem']);
        $db->rollBack();
        exit;
    }
    
    // Delete the reservation
    $delete_query = "DELETE FROM reservations WHERE ID_reservations = :reservation_id";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bindParam(':reservation_id', $reservation_id);
    
    if (!$delete_stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Błąd podczas anulowania rezerwacji']);
        $db->rollBack();
        exit;
    }
    
    // Commit transaction
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Rezerwacja została pomyślnie anulowana',
        'reservation_id' => $reservation_id
    ]);
    
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd serwera']);
    error_log("Reservation cancellation error: " . $e->getMessage());
}
?>