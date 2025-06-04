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
    echo json_encode(['success' => false, 'message' => 'Musisz być zalogowany, aby dokonać zakupu']);
    exit;
}

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// If no JSON data, try to get from POST
if (!$data) {
    $data = $_POST;
}

// Validate required fields
if (!isset($data['cart']) || empty($data['cart']) || !is_array($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Koszyk jest pusty lub nieprawidłowy']);
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
    
    $db->beginTransaction();
    
    $user_id = $session->getUserId();
    
    // Get client for this user
    $client = new Client($db);
    if (!$client->getByUserId($user_id)) {
        echo json_encode(['success' => false, 'message' => 'Nie znaleziono profilu klienta']);
        $db->rollBack();
        exit;
    }
    
    $total_amount = 0;
    $purchase_details = [];
    
    foreach ($data['cart'] as $item) {
        if (!isset($item['id']) || !isset($item['quantity']) || $item['quantity'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Nieprawidłowe dane produktu w koszyku']);
            $db->rollBack();
            exit;
        }
        
        $ammo_id = (int)$item['id'];
        $quantity = (int)$item['quantity'];
        
        // Check if ammunition exists and has enough stock
        $check_query = "SELECT ID_ammo, name, amount, price FROM ammo WHERE ID_ammo = :ammo_id";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->bindParam(':ammo_id', $ammo_id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Produkt nie istnieje: ID ' . $ammo_id]);
            $db->rollBack();
            exit;
        }
        
        $ammo = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($ammo['amount'] < $quantity) {
            echo json_encode([
                'success' => false, 
                'message' => 'Niewystarczająca ilość w magazynie dla: ' . $ammo['name'] . ' (dostępne: ' . $ammo['amount'] . ', zamówione: ' . $quantity . ')'
            ]);
            $db->rollBack();
            exit;
        }
        
        // Calculate item total
        $item_total = $ammo['price'] * $quantity;
        $total_amount += $item_total;
        
        // Update ammunition stock
        $update_query = "UPDATE ammo SET amount = amount - :quantity WHERE ID_ammo = :ammo_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':quantity', $quantity);
        $update_stmt->bindParam(':ammo_id', $ammo_id);
        
        if (!$update_stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Błąd podczas aktualizacji magazynu']);
            $db->rollBack();
            exit;
        }
        
        // Create transaction record
        $transaction_query = "INSERT INTO transactions (ID_client, ID_ammo, count) 
                             VALUES (:client_id, :ammo_id, :count)";
        $transaction_stmt = $db->prepare($transaction_query);
        $transaction_stmt->bindParam(':client_id', $client->ID_client);
        $transaction_stmt->bindParam(':ammo_id', $ammo_id);
        $transaction_stmt->bindParam(':count', $quantity);
        
        if (!$transaction_stmt->execute()) {
            echo json_encode(['success' => false, 'message' => 'Błąd podczas tworzenia transakcji']);
            $db->rollBack();
            exit;
        }
        
        $purchase_details[] = [
            'name' => $ammo['name'],
            'quantity' => $quantity,
            'price_per_unit' => (float)$ammo['price'],
            'total' => $item_total,
            'transaction_id' => $db->lastInsertId()
        ];
    }
    
    // Commit transaction
    $db->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Zakup został pomyślnie zrealizowany!',
        'purchase' => [
            'client_id' => $client->ID_client,
            'client_name' => $client->getFullName(),
            'items' => $purchase_details,
            'total_amount' => $total_amount,
            'purchase_date' => date('Y-m-d H:i:s')
        ]
    ]);
    
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd serwera']);
    error_log("Ammunition purchase error: " . $e->getMessage());
}
?>