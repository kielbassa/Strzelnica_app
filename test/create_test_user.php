<?php
// Quick Test User Creation Script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Create Test User</h1>";
echo "<style>body{font-family:Arial;max-width:600px;margin:50px auto;padding:20px;}
.success{background:#d4edda;color:#155724;padding:15px;border-radius:5px;margin:10px 0;}
.error{background:#f8d7da;color:#721c24;padding:15px;border-radius:5px;margin:10px 0;}
.info{background:#d1ecf1;color:#0c5460;padding:15px;border-radius:5px;margin:10px 0;}
.btn{background:#007bff;color:white;border:none;padding:12px 25px;border-radius:5px;cursor:pointer;font-size:16px;}
.btn:hover{background:#0056b3;}
input{width:100%;padding:10px;margin:5px 0;border:1px solid #ddd;border-radius:3px;font-size:16px;}
</style>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        require_once "config/database.php";
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception("Database connection failed");
        }
        
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        
        // Validate input
        if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
            throw new Exception("All fields are required");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }
        
        // Check if user already exists
        $check_stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->execute([$email]);
        
        if ($check_stmt->rowCount() > 0) {
            // Update existing user
            $stmt = $db->prepare("UPDATE users SET first_name = ?, last_name = ?, password_hash = ?, is_active = 1 WHERE email = ?");
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            if ($stmt->execute([$firstName, $lastName, $password_hash, $email])) {
                echo "<div class='success'>✅ User updated successfully!</div>";
            } else {
                throw new Exception("Failed to update user");
            }
        } else {
            // Create new user
            $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password_hash, is_active) VALUES (?, ?, ?, ?, 1)");
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            if ($stmt->execute([$firstName, $lastName, $email, $password_hash])) {
                echo "<div class='success'>✅ User created successfully!</div>";
            } else {
                throw new Exception("Failed to create user");
            }
        }
        
        echo "<div class='info'>";
        echo "<strong>Login Credentials:</strong><br>";
        echo "Email: " . htmlspecialchars($email) . "<br>";
        echo "Password: " . htmlspecialchars($password) . "<br>";
        echo "</div>";
        
        echo "<div class='info'>";
        echo "<a href='pages/login.php'>→ Go to Login Page</a><br>";
        echo "<a href='test/auth_test.php'>→ Test Authentication</a><br>";
        echo "<a href='fix_login.php'>→ Run Full Debug</a>";
        echo "</div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
} else {
    echo "<form method='POST'>";
    echo "<h2>Create Test User</h2>";
    echo "<p>First Name:</p>";
    echo "<input type='text' name='firstName' value='Admin' required>";
    echo "<p>Last Name:</p>";
    echo "<input type='text' name='lastName' value='Test' required>";
    echo "<p>Email:</p>";
    echo "<input type='email' name='email' value='admin@test.com' required>";
    echo "<p>Password:</p>";
    echo "<input type='password' name='password' value='admin123' required>";
    echo "<p></p>";
    echo "<button type='submit' class='btn'>Create/Update User</button>";
    echo "</form>";
    
    echo "<div class='info'>";
    echo "<strong>Default Values:</strong><br>";
    echo "These are pre-filled with test values. You can change them if needed.<br>";
    echo "If a user with this email already exists, it will be updated with the new password.";
    echo "</div>";
}