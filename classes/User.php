<?php
class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $first_name;
    public $last_name;
    public $email;

    public $password_hash;
    public $created_at;
    public $updated_at;
    public $is_active;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register()
    {
        try {
            // Start transaction to ensure both user and client are created together
            $this->conn->beginTransaction();

            $query =
                "INSERT INTO " .
                $this->table_name .
                "
                      (first_name, last_name, email, password_hash)
                      VALUES (:first_name, :last_name, :email, :password_hash)";

            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                error_log("User::register() - Failed to prepare statement");
                $this->conn->rollBack();
                return false;
            }

            // Sanitize input
            $this->first_name = htmlspecialchars(strip_tags($this->first_name));
            $this->last_name = htmlspecialchars(strip_tags($this->last_name));
            $this->email = htmlspecialchars(strip_tags($this->email));

            // Log the data being inserted
            error_log("User::register() - Attempting to insert: " . 
                     "Name: {$this->first_name} {$this->last_name}, Email: {$this->email}");

            // Bind parameters
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":password_hash", $this->password_hash);

            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                error_log("User::register() - Success! New user ID: " . $this->id);

                // Create corresponding client record
                if ($this->createClientRecord()) {
                    $this->conn->commit();
                    return true;
                } else {
                    error_log("User::register() - Failed to create client record");
                    $this->conn->rollBack();
                    return false;
                }
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("User::register() - Execute failed: " . print_r($errorInfo, true));
                $this->conn->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            error_log("User::register() - PDO Exception: " . $e->getMessage());
            $this->conn->rollBack();
            return false;
        } catch (Exception $e) {
            error_log("User::register() - General Exception: " . $e->getMessage());
            $this->conn->rollBack();
            return false;
        }
    }

    public function emailExists()
    {
        try {
            $query =
                "SELECT id FROM " .
                $this->table_name .
                " WHERE email = :email LIMIT 1";
            $stmt = $this->conn->prepare($query);
            
            if (!$stmt) {
                error_log("User::emailExists() - Failed to prepare statement");
                return false;
            }
            
            $stmt->bindParam(":email", $this->email);
            
            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                error_log("User::emailExists() - Execute failed: " . print_r($errorInfo, true));
                return false;
            }

            $exists = $stmt->rowCount() > 0;
            error_log("User::emailExists() - Email {$this->email} exists: " . ($exists ? 'YES' : 'NO'));
            
            return $exists;
        } catch (PDOException $e) {
            error_log("User::emailExists() - PDO Exception: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("User::emailExists() - General Exception: " . $e->getMessage());
            return false;
        }
    }

    public function login($email, $password)
    {
        $query =
            "SELECT id, first_name, last_name, email, password_hash, is_active
                  FROM " .
            $this->table_name .
            "
                  WHERE email = :email AND is_active = 1 LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $row["password_hash"])) {
                // Set user properties
                $this->id = $row["id"];
                $this->first_name = $row["first_name"];
                $this->last_name = $row["last_name"];
                $this->email = $row["email"];
                $this->is_active = $row["is_active"];

                return true;
            }
        }
        return false;
    }

    public function getUserById($id)
    {
        $query =
            "SELECT id, first_name, last_name, email, is_active
                  FROM " .
            $this->table_name .
            "
                  WHERE id = :id AND is_active = 1 LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row["id"];
            $this->first_name = $row["first_name"];
            $this->last_name = $row["last_name"];
            $this->email = $row["email"];
            $this->is_active = $row["is_active"];

            return true;
        }
        return false;
    }

    public function updateLastLogin()
    {
        $query =
            "UPDATE " .
            $this->table_name .
            "
                  SET updated_at = CURRENT_TIMESTAMP
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function validatePassword($password)
    {
        // Password must be at least 8 characters long
        return strlen($password) >= 8;
    }

    /**
     * Create a client record for the newly registered user
     * @return bool
     */
    private function createClientRecord()
    {
        try {
            $query = "INSERT INTO clients (user_id, name, surname, ID_membership) 
                      VALUES (:user_id, :name, :surname, NULL)";

            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                error_log("User::createClientRecord() - Failed to prepare statement");
                return false;
            }

            // Bind parameters
            $stmt->bindParam(":user_id", $this->id);
            $stmt->bindParam(":name", $this->first_name);
            $stmt->bindParam(":surname", $this->last_name);

            if ($stmt->execute()) {
                $client_id = $this->conn->lastInsertId();
                error_log("User::createClientRecord() - Success! New client ID: " . $client_id);
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("User::createClientRecord() - Execute failed: " . print_r($errorInfo, true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("User::createClientRecord() - PDO Exception: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("User::createClientRecord() - General Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get client information for the current user
     * @return array|null
     */
    public function getClientInfo()
    {
        try {
            $query = "SELECT c.ID_client, c.name, c.surname, c.ID_membership, 
                             m.type as membership_type, m.activation_date, m.expiration_date
                      FROM clients c 
                      LEFT JOIN membership m ON c.ID_membership = m.ID_membership
                      WHERE c.user_id = :user_id LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $this->id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return null;
        } catch (Exception $e) {
            error_log("User::getClientInfo() - Exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user has an active membership
     * @return bool
     */
    public function hasActiveMembership()
    {
        $clientInfo = $this->getClientInfo();
        
        if (!$clientInfo || !$clientInfo['ID_membership']) {
            return false;
        }

        // Check if membership is active (not expired)
        if ($clientInfo['expiration_date']) {
            $expiration = new DateTime($clientInfo['expiration_date']);
            $now = new DateTime();
            return $expiration > $now;
        }

        return false;
    }
}
?>
