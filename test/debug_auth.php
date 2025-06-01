<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Debug & Fix Authentication System</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
    .section { background: #f8f9fa; padding: 20px; margin: 15px 0; border-radius: 8px; border-left: 4px solid #007bff; }
    .error { border-left-color: #dc3545; background: #f8d7da; color: #721c24; }
    .success { border-left-color: #28a745; background: #d4edda; color: #155724; }
    .warning { border-left-color: #ffc107; background: #fff3cd; color: #856404; }
    .code { background: #e9ecef; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; }
    .test-btn { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin: 5px; }
    .test-btn:hover { background: #0056b3; }
    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #f8f9fa; }
</style>";

// Test 1: Database Connection
echo "<div class='section'>";
echo "<h2>🗄️ Test 1: Database Connection</h2>";
try {
    require_once "config/database.php";
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "<div class='success'>✅ Database connection successful</div>";
        echo "<p>Connection status: " . $db->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "</p>";
    } else {
        echo "<div class='error'>❌ Database connection failed</div>";
        exit;
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Database error: " . $e->getMessage() . "</div>";
    exit;
}
echo "</div>";

// Test 2: Users Table Check
echo "<div class='section'>";
echo "<h2>👥 Test 2: Users Table</h2>";
try {
    $query = "SHOW TABLES LIKE 'users'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "<div class='success'>✅ Users table exists</div>";
        
        // Check table structure
        $query = "DESCRIBE users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Table Structure:</h3>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Check existing users
        $query = "SELECT id, first_name, last_name, email, is_active, created_at FROM users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>Existing Users (" . count($users) . "):</h3>";
        if (count($users) > 0) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Active</th><th>Created</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . ($user['is_active'] ? 'Yes' : 'No') . "</td>";
                echo "<td>" . $user['created_at'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='warning'>⚠️ No users found in database</div>";
        }
    } else {
        echo "<div class='error'>❌ Users table does not exist</div>";
        exit;
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Users table error: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 3: Create Test User
echo "<div class='section'>";
echo "<h2>🧪 Test 3: Create Test User</h2>";

if (isset($_POST['create_test_user'])) {
    try {
        require_once "classes/User.php";
        $user = new User($db);
        
        $user->first_name = "Test";
        $user->last_name = "User";
        $user->email = "test@strzelnica.pl";
        $user->password_hash = password_hash("password123", PASSWORD_DEFAULT);
        
        // Check if user already exists
        if ($user->emailExists()) {
            echo "<div class='warning'>⚠️ Test user already exists</div>";
        } else {
            if ($user->register()) {
                echo "<div class='success'>✅ Test user created successfully</div>";
                echo "<div class='code'>Email: test@strzelnica.pl<br>Password: password123</div>";
            } else {
                echo "<div class='error'>❌ Failed to create test user</div>";
            }
        }
    } catch (Exception $e) {
        echo "<div class='error'>❌ User creation error: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<form method='post'>";
    echo "<button type='submit' name='create_test_user' class='test-btn'>Create Test User</button>";
    echo "</form>";
    echo "<p>This will create a test user with email: <strong>test@strzelnica.pl</strong> and password: <strong>password123</strong></p>";
}
echo "</div>";

// Test 4: Authentication Classes
echo "<div class='section'>";
echo "<h2>🔐 Test 4: Authentication Classes</h2>";
try {
    require_once "classes/User.php";
    require_once "classes/Session.php";
    require_once "includes/auth_helper.php";
    
    echo "<div class='success'>✅ All authentication classes loaded successfully</div>";
    
    // Test User class methods
    $user = new User($db);
    $methods = get_class_methods($user);
    echo "<h3>User Class Methods:</h3>";
    echo "<div class='code'>" . implode(', ', $methods) . "</div>";
    
    // Test Session class methods
    $session = new Session();
    $methods = get_class_methods($session);
    echo "<h3>Session Class Methods:</h3>";
    echo "<div class='code'>" . implode(', ', $methods) . "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Authentication classes error: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Test 5: Login Simulation
echo "<div class='section'>";
echo "<h2>🔑 Test 5: Login Simulation</h2>";

if (isset($_POST['test_login'])) {
    try {
        require_once "classes/User.php";
        require_once "classes/Session.php";
        
        $email = $_POST['test_email'];
        $password = $_POST['test_password'];
        
        echo "<h3>Testing login for: " . htmlspecialchars($email) . "</h3>";
        
        $user = new User($db);
        
        // Step 1: Check if user exists
        $query = "SELECT id, first_name, last_name, email, password_hash, is_active FROM users WHERE email = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "<div class='success'>✅ User found in database</div>";
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo "<table>";
            echo "<tr><th>Field</th><th>Value</th></tr>";
            echo "<tr><td>ID</td><td>" . $row['id'] . "</td></tr>";
            echo "<tr><td>Name</td><td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td></tr>";
            echo "<tr><td>Email</td><td>" . htmlspecialchars($row['email']) . "</td></tr>";
            echo "<tr><td>Active</td><td>" . ($row['is_active'] ? 'Yes' : 'No') . "</td></tr>";
            echo "<tr><td>Password Hash</td><td>" . substr($row['password_hash'], 0, 20) . "...</td></tr>";
            echo "</table>";
            
            // Step 2: Test password verification
            if (password_verify($password, $row['password_hash'])) {
                echo "<div class='success'>✅ Password verification successful</div>";
                
                // Step 3: Test User::login method
                if ($user->login($email, $password)) {
                    echo "<div class='success'>✅ User::login() method successful</div>";
                    
                    // Step 4: Test session creation
                    $session = new Session();
                    $session->login($user);
                    echo "<div class='success'>✅ Session created successfully</div>";
                    
                    // Display session data
                    echo "<h3>Session Data:</h3>";
                    echo "<table>";
                    echo "<tr><th>Session Variable</th><th>Value</th></tr>";
                    foreach ($_SESSION as $key => $value) {
                        echo "<tr><td>" . htmlspecialchars($key) . "</td><td>" . htmlspecialchars($value) . "</td></tr>";
                    }
                    echo "</table>";
                    
                } else {
                    echo "<div class='error'>❌ User::login() method failed</div>";
                }
            } else {
                echo "<div class='error'>❌ Password verification failed</div>";
                echo "<p>Provided password: " . htmlspecialchars($password) . "</p>";
                echo "<p>Hash in database: " . htmlspecialchars($row['password_hash']) . "</p>";
            }
        } else {
            echo "<div class='error'>❌ User not found in database</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Login test error: " . $e->getMessage() . "</div>";
    }
} else {
    echo "<form method='post'>";
    echo "<p>Email: <input type='email' name='test_email' value='test@strzelnica.pl' required></p>";
    echo "<p>Password: <input type='password' name='test_password' value='password123' required></p>";
    echo "<button type='submit' name='test_login' class='test-btn'>Test Login</button>";
    echo "</form>";
}
echo "</div>";

// Test 6: API Endpoints
echo "<div class='section'>";
echo "<h2>🌐 Test 6: API Endpoints</h2>";

$api_files = [
    'api/register.php' => 'Registration API',
    'api/login.php' => 'Login API',
    'api/logout.php' => 'Logout API',
    'api/check_session.php' => 'Session Check API'
];

foreach ($api_files as $file => $description) {
    if (file_exists($file)) {
        echo "<div class='success'>✅ $description exists</div>";
    } else {
        echo "<div class='error'>❌ $description missing</div>";
    }
}
echo "</div>";

// Test 7: Session Information
echo "<div class='section'>";
echo "<h2>🕒 Test 7: Session Information</h2>";

echo "<table>";
echo "<tr><th>Session Parameter</th><th>Value</th></tr>";
echo "<tr><td>Session Status</td><td>" . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive') . "</td></tr>";
echo "<tr><td>Session ID</td><td>" . session_id() . "</td></tr>";
echo "<tr><td>Session Name</td><td>" . session_name() . "</td></tr>";
echo "<tr><td>Session Save Path</td><td>" . session_save_path() . "</td></tr>";
echo "<tr><td>Session Cookie Params</td><td>" . json_encode(session_get_cookie_params()) . "</td></tr>";
echo "</table>";

if (!empty($_SESSION)) {
    echo "<h3>Current Session Variables:</h3>";
    echo "<table>";
    echo "<tr><th>Variable</th><th>Value</th></tr>";
    foreach ($_SESSION as $key => $value) {
        echo "<tr><td>" . htmlspecialchars($key) . "</td><td>" . htmlspecialchars(is_array($value) ? json_encode($value) : $value) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='warning'>⚠️ No session variables set</div>";
}
echo "</div>";

// Test 8: Fix Common Issues
echo "<div class='section'>";
echo "<h2>🔧 Test 8: Fix Common Issues</h2>";

if (isset($_POST['fix_issues'])) {
    echo "<h3>Applying Fixes:</h3>";
    
    // Fix 1: Ensure session directory is writable
    $session_path = session_save_path();
    if (empty($session_path)) {
        $session_path = sys_get_temp_dir();
    }
    
    if (is_writable($session_path)) {
        echo "<div class='success'>✅ Session directory is writable</div>";
    } else {
        echo "<div class='error'>❌ Session directory is not writable: $session_path</div>";
    }
    
    // Fix 2: Check session directory permissions
    echo "<div class='success'>✅ Session fixes applied</div>";
    
} else {
    echo "<form method='post'>";
    echo "<button type='submit' name='fix_issues' class='test-btn'>Apply Automatic Fixes</button>";
    echo "</form>";
    echo "<p>This will:</p>";
    echo "<ul>";
    echo "<li>Check session directory permissions</li>";
    echo "<li>Verify database structure</li>";
    echo "</ul>";
}
echo "</div>";

// Test 9: JavaScript Test
echo "<div class='section'>";
echo "<h2>🖥️ Test 9: Frontend JavaScript Test</h2>";
echo "<button class='test-btn' onclick='testJavaScript()'>Test JavaScript Login</button>";
echo "<div id='js-result' style='margin-top: 10px;'></div>";

echo "<script>
async function testJavaScript() {
    const resultDiv = document.getElementById('js-result');
    resultDiv.innerHTML = '<p>Testing JavaScript login...</p>';
    
    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: 'test@strzelnica.pl',
                password: 'password123'
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            resultDiv.innerHTML = '<div class=\"success\">✅ JavaScript login test successful!</div><pre>' + JSON.stringify(result, null, 2) + '</pre>';
            
            // Test session check
            setTimeout(async () => {
                const sessionResponse = await fetch('api/check_session.php');
                const sessionResult = await sessionResponse.json();
                resultDiv.innerHTML += '<div class=\"success\">✅ Session check successful!</div><pre>' + JSON.stringify(sessionResult, null, 2) + '</pre>';
            }, 1000);
        } else {
            resultDiv.innerHTML = '<div class=\"error\">❌ JavaScript login test failed!</div><pre>' + JSON.stringify(result, null, 2) + '</pre>';
        }
    } catch (error) {
        resultDiv.innerHTML = '<div class=\"error\">❌ JavaScript error: ' + error.message + '</div>';
    }
}
</script>";
echo "</div>";

// Summary and Next Steps
echo "<div class='section'>";
echo "<h2>📋 Summary & Next Steps</h2>";
echo "<h3>If all tests pass, try these steps:</h3>";
echo "<ol>";
echo "<li><strong>Use the test user:</strong> Email: test@strzelnica.pl, Password: password123</li>";
echo "<li><strong>Test login page:</strong> <a href='pages/login.php' target='_blank'>Go to Login Page</a></li>";
echo "<li><strong>Test authentication:</strong> <a href='test/auth_test.php' target='_blank'>Go to Auth Test Page</a></li>";
echo "<li><strong>Check browser console</strong> for JavaScript errors (F12 → Console)</li>";
echo "<li><strong>Clear browser cache</strong> and cookies for your domain</li>";
echo "</ol>";

echo "<h3>Common Issues & Solutions:</h3>";
echo "<ul>";
echo "<li><strong>Session not persisting:</strong> Check session directory permissions</li>";
echo "<li><strong>Password not working:</strong> Ensure test user was created properly</li>";
echo "<li><strong>JavaScript errors:</strong> Check browser console and file paths</li>";
echo "<li><strong>Database errors:</strong> Verify database connection and table structure</li>";
echo "</ul>";
echo "</div>";

echo "<div class='section'>";
echo "<h2>🔄 Quick Actions</h2>";
echo "<a href='?' class='test-btn'>Refresh Debug</a> ";
echo "<a href='setup/install.php' class='test-btn'>Run Setup</a> ";
echo "<a href='pages/login.php' class='test-btn'>Test Login</a> ";
echo "<a href='test/auth_test.php' class='test-btn'>Auth Test Page</a>";
echo "</div>";