<?php
class AdminPanel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Get all guns with their ammunition info
     * @return array
     */
    public function getGuns()
    {
        try {
            $query = "SELECT g.ID_guns, g.name, a.name as ammo_name, g.availability, g.in_use
                      FROM guns g
                      LEFT JOIN ammo a ON g.ID_ammo = a.ID_ammo
                      ORDER BY g.ID_guns";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("AdminPanel::getGuns() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all ammunition types
     * @return array
     */
    public function getAmmo()
    {
        try {
            $query = "SELECT ID_ammo, name, amount, price
                      FROM ammo
                      ORDER BY ID_ammo";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("AdminPanel::getAmmo() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all reservations with client and station info
     * @return array
     */
    public function getReservations()
    {
        try {
            $query = "SELECT r.ID_reservations, r.date, r.time, 
                             CONCAT(c.name, ' ', c.surname) as client_name,
                             r.participants, r.instructor, r.ID_station
                      FROM reservations r
                      LEFT JOIN clients c ON r.ID_client = c.ID_client
                      ORDER BY r.date DESC, r.time DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("AdminPanel::getReservations() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all clients with membership info
     * @return array
     */
    public function getClients()
    {
        try {
            $query = "SELECT c.ID_client, c.name, c.surname, 
                             COALESCE(m.type, 'Brak członkostwa') as membership_type
                      FROM clients c
                      LEFT JOIN membership m ON c.ID_membership = m.ID_membership
                      ORDER BY c.ID_client";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("AdminPanel::getClients() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all memberships with client info
     * @return array
     */
    public function getMemberships()
    {
        try {
            $query = "SELECT m.ID_membership, m.type, 
                             CONCAT(c.name, ' ', c.surname) as client_name,
                             m.activation_date, m.expiration_date
                      FROM membership m
                      LEFT JOIN clients c ON m.ID_membership = c.ID_membership
                      WHERE m.ID_membership != 999
                      ORDER BY m.ID_membership";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("AdminPanel::getMemberships() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all transactions with ammo and client info
     * @return array
     */
    public function getTransactions()
    {
        try {
            $query = "SELECT t.ID_transaction, a.name as ammo_name, t.count,
                             CONCAT(c.name, ' ', c.surname) as client_name
                      FROM transactions t
                      LEFT JOIN ammo a ON t.ID_ammo = a.ID_ammo
                      LEFT JOIN clients c ON t.ID_client = c.ID_client
                      ORDER BY t.ID_transaction DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("AdminPanel::getTransactions() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all shooting stations
     * @return array
     */
    public function getStations()
    {
        try {
            $query = "SELECT ID_station, slots
                      FROM stations
                      ORDER BY ID_station";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("AdminPanel::getStations() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all users
     * @return array
     */
    public function getUsers()
    {
        try {
            $query = "SELECT id, first_name, last_name, email, is_active, is_admin, created_at
                      FROM users
                      ORDER BY id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("AdminPanel::getUsers() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get dashboard statistics
     * @return array
     */
    public function getDashboardStats()
    {
        try {
            $stats = [];
            
            // Total users
            $query = "SELECT COUNT(*) as total FROM users WHERE is_active = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Total clients
            $query = "SELECT COUNT(*) as total FROM clients";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['total_clients'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Active memberships
            $query = "SELECT COUNT(*) as total FROM membership WHERE expiration_date >= CURDATE() AND ID_membership != 999";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['active_memberships'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Today's reservations
            $query = "SELECT COUNT(*) as total FROM reservations WHERE date = CURDATE()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['todays_reservations'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Future reservations
            $query = "SELECT COUNT(*) as total FROM reservations WHERE date > CURDATE()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['future_reservations'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Available guns
            $query = "SELECT COUNT(*) as total FROM guns WHERE availability = 1 AND in_use = 0";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['available_guns'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return $stats;
        } catch (Exception $e) {
            error_log("AdminPanel::getDashboardStats() - Exception: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Toggle gun availability
     * @param int $gun_id
     * @return bool
     */
    public function toggleGunAvailability($gun_id)
    {
        try {
            $query = "UPDATE guns SET availability = NOT availability WHERE ID_guns = :gun_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':gun_id', $gun_id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("AdminPanel::toggleGunAvailability() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle gun in_use status
     * @param int $gun_id
     * @return bool
     */
    public function toggleGunInUse($gun_id)
    {
        try {
            $query = "UPDATE guns SET in_use = NOT in_use WHERE ID_guns = :gun_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':gun_id', $gun_id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("AdminPanel::toggleGunInUse() - Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update ammo amount
     * @param int $ammo_id
     * @param int $new_amount
     * @return bool
     */
    public function updateAmmoAmount($ammo_id, $new_amount)
    {
        try {
            $query = "UPDATE ammo SET amount = :amount WHERE ID_ammo = :ammo_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':amount', $new_amount);
            $stmt->bindParam(':ammo_id', $ammo_id);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("AdminPanel::updateAmmoAmount() - Exception: " . $e->getMessage());
            return false;
        }
    }
}
?>