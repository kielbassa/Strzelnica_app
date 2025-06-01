<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../config/database.php";
require_once "../classes/User.php";

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Metoda nie dozwolona"]);
    exit();
}

// Get posted data
$data = json_decode(file_get_contents("php://input"), true);

// If no JSON data, try to get from POST
if (!$data) {
    $data = $_POST;
}

// Validate required fields
$required_fields = [
    "firstName",
    "lastName",
    "email",
    "password",
    "confirmPassword",
];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty(trim($data[$field]))) {
        echo json_encode([
            "success" => false,
            "message" => "Wszystkie pola są wymagane",
        ]);
        exit();
    }
}

// Check if passwords match
if ($data["password"] !== $data["confirmPassword"]) {
    echo json_encode([
        "success" => false,
        "message" => "Hasła nie są identyczne",
    ]);
    exit();
}

try {
    // Database connection
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        echo json_encode([
            "success" => false,
            "message" => "Błąd połączenia z bazą danych",
        ]);
        exit();
    }

    // Create user object
    $user = new User($db);

    // Set user properties
    $user->first_name = trim($data["firstName"]);
    $user->last_name = trim($data["lastName"]);
    $user->email = trim(strtolower($data["email"]));

    // Validate email format
    if (!$user->validateEmail($user->email)) {
        echo json_encode([
            "success" => false,
            "message" => "Nieprawidłowy format adresu e-mail",
        ]);
        exit();
    }

    // Validate password strength
    if (!$user->validatePassword($data["password"])) {
        echo json_encode([
            "success" => false,
            "message" => "Hasło musi mieć co najmniej 8 znaków",
        ]);
        exit();
    }

    // Check if email already exists
    if ($user->emailExists()) {
        echo json_encode([
            "success" => false,
            "message" => "Konto z tym adresem e-mail już istnieje",
        ]);
        exit();
    }

    // Hash password
    $user->password_hash = password_hash($data["password"], PASSWORD_DEFAULT);

    // Try to register user
    if ($user->register()) {
        echo json_encode([
            "success" => true,
            "message" =>
                "Rejestracja przebiegła pomyślnie! Możesz się teraz zalogować.",
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Wystąpił błąd podczas rejestracji",
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Wystąpił błąd serwera",
    ]);
    error_log("Registration error: " . $e->getMessage());
}
?>
