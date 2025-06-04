<?php
// Script to promote a user to admin status
// Run this once to make yourself an admin, then delete this file for security

error_reporting(E_ALL);
ini_set("display_errors", 1);

echo "<h1>🔧 Promote User to Admin</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
    .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
    .btn { background: #007bff; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; font-size: 16px; }
    .btn:hover { background: #0056b3; }
    .btn.danger { background: #dc3545; }
    .btn.danger:hover { background: #c82333; }
    input { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 3px; font-size: 16px; }
</style>";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        require_once "../config/database.php";
        $database = new Database();
        $db = $database->getConnection();

        if (!$db) {
            throw new Exception("Database connection failed");
        }

        $email = trim($_POST["email"]);

        if (empty($email)) {
            throw new Exception("Email is required");
        }

        // Check if user exists
        $check_stmt = $db->prepare(
            "SELECT id, first_name, last_name, is_admin FROM users WHERE email = ?"
        );
        $check_stmt->execute([$email]);

        if ($check_stmt->rowCount() === 0) {
            throw new Exception("User with email '$email' not found");
        }

        $user = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($user["is_admin"] == 1) {
            echo "<div class='warning'>⚠️ User is already an admin!</div>";
        } else {
            // Promote user to admin
            $stmt = $db->prepare(
                "UPDATE users SET is_admin = 1 WHERE email = ?"
            );

            if ($stmt->execute([$email])) {
                echo "<div class='success'>✅ User promoted to admin successfully!</div>";
                echo "<div class='success'>";
                echo "<strong>Admin User Details:</strong><br>";
                echo "Name: " .
                    htmlspecialchars(
                        $user["first_name"] . " " . $user["last_name"]
                    ) .
                    "<br>";
                echo "Email: " . htmlspecialchars($email) . "<br>";
                echo "Admin Status: ✅ YES";
                echo "</div>";
            } else {
                throw new Exception("Failed to promote user to admin");
            }
        }
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error: " .
            htmlspecialchars($e->getMessage()) .
            "</div>";
    }
} else {
    echo "<div class='warning'>";
    echo "<strong>⚠️ Security Notice:</strong><br>";
    echo "This script promotes a user to admin status. Use it once, then delete it for security.";
    echo "</div>";

    echo "<form method='POST'>";
    echo "<h2>Promote User to Admin</h2>";
    echo "<p>Enter the email of the user you want to promote to admin:</p>";
    echo "<input type='email' name='email' placeholder='user@example.com' required>";
    echo "<p></p>";
    echo "<button type='submit' class='btn'>Promote to Admin</button>";
    echo "</form>";

    // Show existing users
    try {
        require_once "../config/database.php";
        $database = new Database();
        $db = $database->getConnection();

        if ($db) {
            echo "<h3>Existing Users:</h3>";
            $stmt = $db->prepare(
                "SELECT id, first_name, last_name, email, is_admin FROM users ORDER BY id"
            );
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($users) > 0) {
                echo "<table style='width:100%; border-collapse: collapse; margin: 10px 0;'>";
                echo "<tr style='background: #f8f9fa;'>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>ID</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>Name</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>Email</th>";
                echo "<th style='border: 1px solid #ddd; padding: 8px;'>Admin</th>";
                echo "</tr>";

                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" .
                        $user["id"] .
                        "</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" .
                        htmlspecialchars(
                            $user["first_name"] . " " . $user["last_name"]
                        ) .
                        "</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" .
                        htmlspecialchars($user["email"]) .
                        "</td>";
                    echo "<td style='border: 1px solid #ddd; padding: 8px;'>" .
                        ($user["is_admin"] ? "✅ YES" : "❌ NO") .
                        "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No users found. Please register a user first.</p>";
            }
        }
    } catch (Exception $e) {
        echo "<div class='error'>Could not load users: " .
            htmlspecialchars($e->getMessage()) .
            "</div>";
    }
}
?>
