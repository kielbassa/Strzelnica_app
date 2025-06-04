<?php
class Session {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function login($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_first_name'] = $user->first_name;
        $_SESSION['user_last_name'] = $user->last_name;
        $_SESSION['is_admin'] = $user->is_admin;

        $_SESSION['is_logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Regenerate session ID for security
        session_regenerate_id(true);
    }
    
    public function logout() {
        // Unset all session variables
        $_SESSION = array();
        
        // Delete session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        
        // Destroy session
        session_destroy();
    }
    
    public function requireAdmin() {
        if (!$this->isLoggedIn()) {
            header('Location: ../pages/login.php');
            exit;
        }
        
        if (!$this->isAdmin()) {
            header('Location: ../pages/index.php?error=access_denied');
            exit;
        }
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }
    
    public function getUserId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    public function getUserEmail() {
        return isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
    }
    
    public function getUserFirstName() {
        return isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : null;
    }
    
    public function getUserLastName() {
        return isset($_SESSION['user_last_name']) ? $_SESSION['user_last_name'] : null;
    }
    
    public function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }

    public function getUserFullName() {
        if ($this->isLoggedIn()) {
            return $this->getUserFirstName() . ' ' . $this->getUserLastName();
        }
        return null;
    }
    
    public function getLoginTime() {
        return isset($_SESSION['login_time']) ? $_SESSION['login_time'] : null;
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: ../pages/login.php');
            exit;
        }
    }
    
    public function preventLoginAccess() {
        if ($this->isLoggedIn()) {
            header('Location: ../pages/strzelnica.php');
            exit;
        }
    }
    
    public function sessionTimeout($timeout = 3600) { // 1 hour default
        if ($this->isLoggedIn()) {
            $loginTime = $this->getLoginTime();
            if ($loginTime && (time() - $loginTime) > $timeout) {
                $this->logout();
                return true;
            }
        }
        return false;
    }
    
    public function refreshSession() {
        if ($this->isLoggedIn()) {
            $_SESSION['login_time'] = time();
        }
    }
    
    public function setFlashMessage($type, $message) {
        $_SESSION['flash_message'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    public function getFlashMessage() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return $message;
        }
        return null;
    }
}
?>