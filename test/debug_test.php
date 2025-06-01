<?php
// Debug script to test database connection and users table
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Debug Test</h2>";

// Test 1: Include database configuration
echo "<h3>Test 1: Database Configuration</h3>";
try {
    require_once "config/database.php";
    echo "✓ Database configuration loaded successfully<br>";
} catch (Exception $e) {
    echo "✗ Error loading database configuration: " . $e->getMessage() . "<br>";
    exit;
}

// Test 2: Test database connection
echo "<h3>Test 2: Database Connection</h3>";
try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "✓ Database connection successful<br>";
        echo "Connection info: " . $db->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "<br>";
    } else {
        echo "✗ Database connection failed<br>";
        exit;
    }
} catch (Exception $e) {
    echo "✗ Database connection error: " . $e->getMessage() . "<br>";
    exit;
}

// Test 3: Check if users table exists
echo "<h3>Test 3: Users Table Check</h3>";
try {
    $query = "SHOW TABLES LIKE 'users'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "✓ Users table exists<br>";
    } else {
        echo "✗ Users table does not exist<br>";
        echo "<p style='color: red;'>You need to create the users table. Run the SQL from config/add_users_table.sql</p>";
        exit;
    }
} catch (Exception $e) {
    echo "✗ Error checking users table: " . $e->getMessage() . "<br>";
}

// Test 4: Check table structure
echo "<h3>Test 4: Users Table Structure</h3>";
try {
    $query = "DESCRIBE users";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "✗ Error checking table structure: " . $e->getMessage() . "<br>";
}

// Test 5: Test User class
echo "<h3>Test 5: User Class</h3>";
try {
    require_once "classes/User.php";
    $user = new User($db);
    echo "✓ User class loaded successfully<br>";
} catch (Exception $e) {
    echo "✗ Error loading User class: " . $e->getMessage() . "<br>";
}

// Test 6: Test registration process with dummy data
echo "<h3>Test 6: Test Registration Process</h3>";
try {
    // Create a test user
    $user = new User($db);
    $user->first_name = "Test";
    $user->last_name = "User";
    $user->email = "test" . time() . "@example.com"; // Unique email
    $user->password_hash = password_hash("testpassword123", PASSWORD_DEFAULT);
    
    // Check if email exists first
    if ($user->emailExists()) {
        echo "✗ Email already exists (this shouldn't happen with timestamp)<br>";
    } else {
        echo "✓ Email availability check passed<br>";
        
        // Try to register
        if ($user->register()) {
            echo "✓ Test user registration successful<br>";
            echo "Test user email: " . $user->email . "<br>";
        } else {
            echo "✗ Test user registration failed<br>";
            
            // Get last error
            $errorInfo = $db->errorInfo();
            if ($errorInfo[0] !== '00000') {
                echo "Database error: " . $errorInfo[2] . "<br>";
            }
        }
    }
} catch (Exception $e) {
    echo "✗ Error during registration test: " . $e->getMessage() . "<br>";
}

echo "<h3>Debug Complete</h3>";
echo "<p>If all tests pass but registration still doesn't work, check:</p>";
echo "<ul>";
echo "<li>Browser developer console for JavaScript errors</li>";
echo "<li>Network tab to see if API requests are being made</li>";
echo "<li>PHP error logs on your server</li>";
echo "<li>Make sure the database and table exist</li>";
echo "</ul>";
?>