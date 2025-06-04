<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metoda nie dozwolona']);
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
    
    // Get ammunition data with associated gun information
    $query = "SELECT a.ID_ammo, a.name as ammo_name, a.amount, a.price, 
                     g.name as gun_name, g.availability as gun_available
              FROM ammo a
              LEFT JOIN guns g ON a.ID_ammo = g.ID_ammo
              WHERE a.amount > 0
              ORDER BY 
                CASE 
                    WHEN g.name LIKE '%Beretta%' OR g.name LIKE '%Colt%' OR g.name LIKE '%Glock%' OR g.name LIKE '%HK%' OR g.name LIKE '%Vis%' OR g.name LIKE '%Magnum%' THEN 1
                    WHEN g.name LIKE '%AR-%' OR g.name LIKE '%Beryl%' OR g.name LIKE '%Galil%' OR g.name LIKE '%Grot%' OR g.name LIKE '%Kałasznikow%' OR g.name LIKE '%M4%' THEN 2
                    WHEN g.name LIKE '%PM-%' OR g.name LIKE '%UZI%' OR g.name LIKE '%MP%' THEN 3
                    ELSE 4
                END, g.name";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $ammunition = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group ammunition by category
    $categorized_ammo = [
        'Broń Krótka' => [],
        'Broń Długa' => [],
        'Pistolety Maszynowe' => []
    ];
    
    foreach ($ammunition as $ammo) {
        $gun_name = $ammo['gun_name'] ?: 'Uniwersalna';
        
        // Categorize based on gun name patterns
        if (preg_match('/(Beretta|Colt|Glock|HK|Vis|Magnum)/i', $gun_name)) {
            $category = 'Broń Krótka';
        } elseif (preg_match('/(AR-|Beryl|Galil|Grot|Kałasznikow|M4)/i', $gun_name)) {
            $category = 'Broń Długa';
        } elseif (preg_match('/(PM-|UZI|MP)/i', $gun_name)) {
            $category = 'Pistolety Maszynowe';
        } else {
            $category = 'Broń Krótka'; // Default category
        }
        
        $categorized_ammo[$category][] = [
            'id' => $ammo['ID_ammo'],
            'gun_name' => $gun_name,
            'ammo_name' => $ammo['ammo_name'],
            'price' => (float)$ammo['price'],
            'amount' => (int)$ammo['amount'],
            'gun_available' => (bool)$ammo['gun_available']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'ammunition' => $categorized_ammo
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Wystąpił błąd serwera']);
    error_log("Get ammunition error: " . $e->getMessage());
}
?>