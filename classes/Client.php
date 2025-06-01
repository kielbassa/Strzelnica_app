<?php
class Client
{
    private $conn;
    private $table_name = "clients";

    public $ID_client;
    public $user_id;
    public $name;
    public $surname;
    public $ID_membership;
    public $membership_type;
    public $activation_date;
    public $expiration_date;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Create a new client record
     * @return bool
     */
    public function create()
    {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                      (user_id, name, surname, ID_membership)
                      VALUES (:user_id, :name, :surname, :ID_membership)";

            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                error_log("Client::create() - Failed to prepare statement");
                return false;
            }

            // Sanitize input
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));

            // Bind parameters
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":surname", $this->surname);
            $stmt->bindParam(":ID_membership", $this->ID_membership);

            if ($stmt->execute()) {
                $this->ID_client = $this->conn->lastInsertId();
                error_log("Client::create() - Success! New client ID: " . $this->ID_client);
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Client::create() - Execute failed: " . print_r($errorInfo, true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("Client::create() - PDO Exception: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Client::create() - General Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get client by user ID
     * @param int $user_id
     * @return bool
     */
    public function getByUserId($user_id)
    {
        try {
            $query = "SELECT c.ID_client, c.user_id, c.name, c.surname, c.ID_membership,
                             m.type as membership_type, m.activation_date, m.expiration_date
                      FROM " . $this->table_name . " c
                      LEFT JOIN membership m ON c.ID_membership = m.ID_membership
                      WHERE c.user_id = :user_id LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->ID_client = $row['ID_client'];
                $this->user_id = $row['user_id'];
                $this->name = $row['name'];
                $this->surname = $row['surname'];
                $this->ID_membership = $row['ID_membership'];
                $this->membership_type = $row['membership_type'];
                $this->activation_date = $row['activation_date'];
                $this->expiration_date = $row['expiration_date'];

                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Client::getByUserId() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get client by client ID
     * @param int $client_id
     * @return bool
     */
    public function getById($client_id)
    {
        try {
            $query = "SELECT c.ID_client, c.user_id, c.name, c.surname, c.ID_membership,
                             m.type as membership_type, m.activation_date, m.expiration_date
                      FROM " . $this->table_name . " c
                      LEFT JOIN membership m ON c.ID_membership = m.ID_membership
                      WHERE c.ID_client = :client_id LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":client_id", $client_id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->ID_client = $row['ID_client'];
                $this->user_id = $row['user_id'];
                $this->name = $row['name'];
                $this->surname = $row['surname'];
                $this->ID_membership = $row['ID_membership'];
                $this->membership_type = $row['membership_type'];
                $this->activation_date = $row['activation_date'];
                $this->expiration_date = $row['expiration_date'];

                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Client::getById() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update client information
     * @return bool
     */
    public function update()
    {
        try {
            $query = "UPDATE " . $this->table_name . "
                      SET name = :name, surname = :surname, ID_membership = :ID_membership
                      WHERE ID_client = :ID_client";

            $stmt = $this->conn->prepare($query);

            // Sanitize input
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->surname = htmlspecialchars(strip_tags($this->surname));

            // Bind parameters
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":surname", $this->surname);
            $stmt->bindParam(":ID_membership", $this->ID_membership);
            $stmt->bindParam(":ID_client", $this->ID_client);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Client::update() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Assign membership to client
     * @param int $membership_id
     * @return bool
     */
    public function assignMembership($membership_id)
    {
        try {
            $query = "UPDATE " . $this->table_name . "
                      SET ID_membership = :membership_id
                      WHERE ID_client = :client_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":membership_id", $membership_id);
            $stmt->bindParam(":client_id", $this->ID_client);

            if ($stmt->execute()) {
                $this->ID_membership = $membership_id;
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Client::assignMembership() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove membership from client
     * @return bool
     */
    public function removeMembership()
    {
        try {
            $query = "UPDATE " . $this->table_name . "
                      SET ID_membership = NULL
                      WHERE ID_client = :client_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":client_id", $this->ID_client);

            if ($stmt->execute()) {
                $this->ID_membership = null;
                $this->membership_type = null;
                $this->activation_date = null;
                $this->expiration_date = null;
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Client::removeMembership() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if client has active membership
     * @return bool
     */
    public function hasActiveMembership()
    {
        if (!$this->ID_membership || !$this->expiration_date) {
            return false;
        }

        try {
            $expiration = new DateTime($this->expiration_date);
            $now = new DateTime();
            return $expiration > $now;
        } catch (Exception $e) {
            error_log("Client::hasActiveMembership() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get client's full name
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * Get all reservations for this client
     * @return array
     */
    public function getReservations()
    {
        try {
            $query = "SELECT r.*, s.slots as station_slots
                      FROM reservations r
                      LEFT JOIN stations s ON r.ID_station = s.ID_station
                      WHERE r.ID_client = :client_id
                      ORDER BY r.date DESC, r.time DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":client_id", $this->ID_client);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Client::getReservations() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get client's transaction history
     * @return array
     */
    public function getTransactions()
    {
        try {
            $query = "SELECT t.*, a.name as ammo_name, a.price as ammo_price
                      FROM transactions t
                      LEFT JOIN ammo a ON t.ID_ammo = a.ID_ammo
                      WHERE t.ID_client = :client_id
                      ORDER BY t.ID_transaction DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":client_id", $this->ID_client);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Client::getTransactions() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if user already has a client record
     * @param int $user_id
     * @return bool
     */
    public function userHasClient($user_id)
    {
        try {
            $query = "SELECT ID_client FROM " . $this->table_name . " WHERE user_id = :user_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Client::userHasClient() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get membership pricing information
     * @return array
     */
    public static function getMembershipPricing()
    {
        return [
            'standard' => ['price' => 50, 'duration_months' => 1],
            'premium' => ['price' => 100, 'duration_months' => 1],
            'vip' => ['price' => 200, 'duration_months' => 1]
        ];
    }

    /**
     * Delete client record
     * @return bool
     */
    public function delete()
    {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE ID_client = :client_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":client_id", $this->ID_client);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Client::delete() - Exception: " . $e->getMessage());
            return false;
        }
    }
}
?>