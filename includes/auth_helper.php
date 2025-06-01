<?php
require_once __DIR__ . "/../classes/Session.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/User.php";

class AuthHelper
{
    private $session;
    private $db;

    public function __construct()
    {
        $this->session = new Session();

        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function requireLogin()
    {
        if (!$this->session->isLoggedIn()) {
            header("Location: ../pages/login.php");
            exit();
        }

        // Check session timeout
        if ($this->session->sessionTimeout(3600)) {
            $this->session->setFlashMessage(
                "error",
                "Sesja wygasła. Zaloguj się ponownie."
            );
            header("Location: ../pages/login.php");
            exit();
        }

        // Refresh session
        $this->session->refreshSession();
    }

    public function preventLoginAccess()
    {
        if ($this->session->isLoggedIn()) {
            header("Location: ../pages/index.php");
            exit();
        }
    }

    public function getCurrentUser()
    {
        if (!$this->session->isLoggedIn()) {
            return null;
        }

        $user = new User($this->db);
        if ($user->getUserById($this->session->getUserId())) {
            return $user;
        }

        return null;
    }

    public function isLoggedIn()
    {
        return $this->session->isLoggedIn();
    }

    public function getUserData()
    {
        if (!$this->session->isLoggedIn()) {
            return null;
        }

        return [
            "id" => $this->session->getUserId(),
            "email" => $this->session->getUserEmail(),
            "first_name" => $this->session->getUserFirstName(),
            "last_name" => $this->session->getUserLastName(),
            "full_name" => $this->session->getUserFullName(),
        ];
    }

    public function logout()
    {
        $this->session->logout();
    }

    public function setFlashMessage($type, $message)
    {
        $this->session->setFlashMessage($type, $message);
    }

    public function getFlashMessage()
    {
        return $this->session->getFlashMessage();
    }

    public function displayFlashMessage()
    {
        $flash = $this->getFlashMessage();
        if ($flash) {
            $class =
                $flash["type"] === "error"
                    ? "error-message"
                    : "success-message";
            $bgColor = $flash["type"] === "error" ? "#ffe6e6" : "#e6ffe6";
            $borderColor = $flash["type"] === "error" ? "#ff0000" : "#00ff00";
            $textColor = $flash["type"] === "error" ? "red" : "green";

            echo "<div class='{$class}' style='color: {$textColor}; margin-bottom: 15px; padding: 10px; background-color: {$bgColor}; border: 1px solid {$borderColor}; border-radius: 4px;'>";
            echo htmlspecialchars($flash["message"]);
            echo "</div>";
        }
    }

    public function getLoginButton()
    {
        if ($this->isLoggedIn()) {
            $userData = $this->getUserData();
            return "
            <div class='user-info'>
                <span>Witaj, {$userData["first_name"]}!</span>
                <button onclick='logout()' class='logout-btn'>Wyloguj</button>
            </div>";
        } else {
            return "
            <div class='auth-buttons'>
                <a href='../pages/login.php' class='login-btn'>Zaloguj się</a>
                <a href='../pages/register.php' class='register-btn'>Zarejestruj się</a>
            </div>";
        }
    }
}

// Global function for easy access
function getAuthHelper()
{
    static $authHelper = null;
    if ($authHelper === null) {
        $authHelper = new AuthHelper();
    }
    return $authHelper;
}

// Convenience functions
function requireLogin()
{
    getAuthHelper()->requireLogin();
}

function isLoggedIn()
{
    return getAuthHelper()->isLoggedIn();
}

function getCurrentUser()
{
    return getAuthHelper()->getCurrentUser();
}

function getUserData()
{
    return getAuthHelper()->getUserData();
}

function displayFlashMessage()
{
    getAuthHelper()->displayFlashMessage();
}
?>
