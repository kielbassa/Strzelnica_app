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
    echo json_encode(['success' => false, 'message' => 'Musisz być zalogowany, aby dokonać rezerwacji']);
    exit;
}

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// If no JSON data, try to get from POST
if (!$data) {
    $data = $_POST;
}

// Validate required fields
$required_fields = ['date', 'time', 'people', 'instructor', 'group'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        echo json_encode(['success' => false, 'message' => 'Wszystkie pola formularza są wymagane']);
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
    
    // Get client record for this user
    $client = new Client($db);
    if (!$client->getByUserId($user_id)) {
        echo json_encode(['success' => false, 'message' => 'Nie znaleziono profilu klienta']);
        $db->rollBack();
        exit;
    }
    
    // Clean input data
    $date = trim($data['date']);
    $time = trim($data['time']);
    $people = (int)$data['people'];
    $instructor = ($data['instructor'] === 'tak') ? 1 : 0;
    $group = ($data['group'] === 'tak') ? 1 : 0;
    
    // Validate data
    if ($people < 1 || $people > 8) {
        echo json_encode(['success' => false, 'message' => 'Nieprawidłowa liczba osób (1-8)']);
        $db->rollBack();
        exit;
    }
    
    if (!strtotime($date) || strtotime($date) < strtotime('today')) {
        echo json_encode(['success' => false, 'message' => 'Nieprawidłowa data']);
        $db->rollBack();
        exit;
    }
    
    // Check if the time is valid (HH:MM format)
    if (!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9])$/', $time)) {
        echo json_encode(['success' => false, 'message' => 'Nieprawidłowy format czasu']);
        $db->rollBack();
        exit;
    }
    
    // Find an available station for the given number of people
    $station_query = "SELECT ID_station FROM stations WHERE slots >= :people ORDER BY slots ASC LIMIT 1";
    $station_stmt = $db->prepare($station_query);
    $station_stmt->bindParam(':people', $people);
    $station_stmt->execute();
    
    if ($station_stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Brak dostępnych stanowisk dla wybranej liczby osób']);
        $db->rollBack();
        exit;
    }
    
    $station = $station_stmt->fetch(PDO::FETCH_ASSOC);
    $station_id = $station['ID_station'];
    
    // Check if the station is already reserved at the given date and time
    $check_query = "SELECT * FROM reservations WHERE date = :date AND time = :time AND ID_station = :station_id";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->bindParam(':date', $date);
    $check_stmt->bindParam(':time', $time);
    $check_stmt->bindParam(':station_id', $station_id);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() > 0) {
        // Try to find another available station
        $alt_station_query = "SELECT s.ID_station 
                             FROM stations s
                             WHERE s.slots >= :people
                             AND NOT EXISTS (
                                 SELECT 1 FROM reservations r 
                                 WHERE r.date = :date 
                                 AND r.time = :time 
                                 AND r.ID_station = s.ID_station
                             )
                             ORDER BY s.slots ASC
                             LIMIT 1";
                             
        $alt_station_stmt = $db->prepare($alt_station_query);
        $alt_station_stmt->bindParam(':people', $people);
        $alt_station_stmt->bindParam(':date', $date);
        $alt_station_stmt->bindParam(':time', $time);
        $alt_station_stmt->execute();
        
        if ($alt_station_stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Wybrany termin jest już zajęty. Wybierz inny termin.']);
            $db->rollBack();
            exit;
        }
        
        $station = $alt_station_stmt->fetch(PDO::FETCH_ASSOC);
        $station_id = $station['ID_station'];
    }
    
    // Create reservation
    $reservation_query = "INSERT INTO reservations (date, time, ID_client, participants, instructor, ID_station) 
                         VALUES (:date, :time, :client_id, :participants, :instructor, :station_id)";
    
    $reservation_stmt = $db->prepare($reservation_query);
    $reservation_stmt->bindParam(':date', $date);
    $reservation_stmt->bindParam(':time', $time);
    $reservation_stmt->bindParam(':client_id', $client->ID_client);
    $reservation_stmt->bindParam(':participants', $people);
    $reservation_stmt->bindParam(':instructor', $instructor);
    $reservation_stmt->bindParam(':station_id', $station_id);
    
    if (!$reservation_stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Błąd podczas tworzenia rezerwacji']);
        $db->rollBack();
        exit;
    }
    
    $reservation_id = $db->lastInsertId();
    
    // Commit transaction
    $db->commit();
    
    // Format time for display
    $time_obj = DateTime::createFromFormat('H:i', $time);
    $formatted_time = $time_obj ? $time_obj->format('H:i') : $time;
    
    // Format date for display
    $date_obj = DateTime::createFromFormat('Y-m-d', $date);
    $formatted_date = $date_obj ? $date_obj->format('d.m.Y') : $date;
    
    echo json_encode([
        'success' => true,
        'message' => 'Rezerwacja została pomyślnie utworzona!',
        'reservation' => [
            'id' => $reservation_id,
            'date' => $formatted_date,
            'time' => $formatted_time,
            'people' => $people,
            'instructor' => $instructor === 1 ? 'Tak' : 'Nie',
            'station_id' => $station_id,
            'client_id' => $client->ID_client,
            'client_name' => $client->getFullName()
        ]
    ]);
    
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd serwera']);
    error_log("Reservation error: " . $e->getMessage());
}
?>