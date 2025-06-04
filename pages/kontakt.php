<?php
require_once '../includes/auth_helper.php';

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Set flag to show logout modal
    $_SESSION['show_logout_modal'] = true;
    
    // Clear user session data (but keep modal flag)
    if (isset($_SESSION['user_id'])) {
        unset($_SESSION['user_id']);
    }
    if (isset($_SESSION['user_data'])) {
        unset($_SESSION['user_data']);
    }
    if (isset($_SESSION['welcome_shown'])) {
        unset($_SESSION['welcome_shown']);
    }
    
    // Redirect to clean URL
    header("Location: index.php");
    exit();
}

$auth = getAuthHelper();
$userData = $auth->getUserData();
?>
<!doctype html>
<html lang="pl">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Strzelnica</title>
        <link rel="icon" href="../zdj/logo2.png" type="image/png" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="../css/styles.css" />
        <link rel="stylesheet" href="../css/kontakt.css" />
    </head>
    <body>
        <!-- Flash messages -->
        <?php $auth->displayFlashMessage(); ?>
        
        <!-- logo i nazwa -->
        <header class="header-section">
            <div class="sm-container">
                <div class="sm-item">
                    <img
                        src="../zdj/instagram.png"
                        alt="IgLogo"
                        class="sm-logo"
                    />
                    <span>@strefastrzalu</span>
                </div>
                <div class="sm-item">
                    <img
                        src="../zdj/youtube.png"
                        alt="YtLogo"
                        class="sm-logo"
                    />
                    <span>Strefa Strzału Gdynia</span>
                </div>
                <div class="sm-item">
                    <img
                        src="../zdj/facebook.png"
                        alt="FbLogo"
                        class="sm-logo"
                    />
                    <span>Strefa Strzału Gdynia</span>
                </div>
            </div>

            <div class="logo-container">
                <img src="../zdj/logo.png" alt="Logo" class="logo" />
            </div>

            <div class="auth-links">
                <div id="authContainer" class="auth-container">
                    <?php if ($userData): ?>
                        <div class="user-header-info">
                            <span class="welcome-header-text">Witaj, <?php echo htmlspecialchars($userData['full_name']); ?>!</span>
                            <a href="../pages/my-account.php" style="color: white; margin-right: 10px; text-decoration: none;">Moje Konto</a>
                            <a href="index.php?logout=true" class="logout-header-btn">Wyloguj się</a>
                        </div>
                    <?php else: ?>
                        <?php echo $auth->getLoginButton(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- pasek nawigacyjny -->
        <nav class="main-nav">
            <a href="index.php" class="nav-link">Strona Główna</a>
            <a href="strzelnica.php" class="nav-link">Strzelnica</a>
            <a href="reservation.php" class="nav-link">Rezerwacje</a>
            <a href="store.php" class="nav-link">Sklep</a>
            <a href="kontakt.php" class="nav-link active">Kontakt</a>
            <?php if ($auth->isLoggedIn()): ?>
            <a href="../pages/my-account.php" class="nav-link">Moje Konto</a>
            <?php endif; ?>
            <?php if ($auth->isAdmin()): ?>
            <a href="../admin/admin-panel.php" class="nav-link">Panel Administratora</a>
            <?php endif; ?>
        </nav>

        <!-- Modal for welcome message -->
        <?php if ($userData): ?>
            <div id="welcomeModal" class="modal">
                <div class="modal-content welcome-modal">
                    <span class="close" onclick="closeWelcomeModal()">&times;</span>
                    <h3>Witaj w systemie, <?php echo htmlspecialchars($userData['full_name']); ?>!</h3>
                    <p>Zalogowany jako: <?php echo htmlspecialchars($userData['email']); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Modal for logout success -->
        <div id="logoutModal" class="modal">
            <div class="modal-content logout-modal">
                <span class="close" onclick="closeLogoutModal()">&times;</span>
                <h3>Wylogowano pomyślnie!</h3>
            </div>
        </div>

        <section class="contact-title">
            <h1 class="contact-heading">Kontakt</h1>
        </section>

        <section class="contact-info">
            <div class="left-column">
                <img
                    src="../zdj/kontakt-zdj.jpg"
                    alt="location"
                    class="location-img"
                />
            </div>
            <div class="right-column">
                <h2>Strefa Strzału Gdynia</h2>
                <p>ul. Strzelecka 10, 80-209 Gdynia</p>
                <p><strong>Telefon:</strong> +(48) 123 456 789</p>
                <p><strong>E-mail:</strong> kontakt@strzelnica.pl</p>
            </div>
        </section>

        <footer>
            <img src="../zdj/logo.png" alt="Logo" />
            <p>
                Wszelkie prawa zastrzeżone dla strefastrzalu.pl © 2025 |
                Projekt i wykonanie chrust
            </p>
        </footer>

        <script src="../js/buttons.js"></script>
        <script src="../js/navbar.js"></script>
        <script src="../js/user_auth.js"></script>
        <script>
                        // Modal functions
            function closeWelcomeModal() {
                document.getElementById('welcomeModal').style.display = 'none';
            }
            
            function closeLogoutModal() {
                document.getElementById('logoutModal').style.display = 'none';
            }
            
            // Initialize authentication on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Check if user is logged in and update UI accordingly
                if (window.userAuth) {
                    window.userAuth.checkSession();
                }
                
                // Show welcome modal if user just logged in
                <?php if ($userData && !isset($_SESSION['welcome_shown'])): ?>
                    document.getElementById('welcomeModal').style.display = 'block';
                    <?php $_SESSION['welcome_shown'] = true; ?>
                <?php endif; ?>
                
                // Show logout modal if just logged out
                <?php if (isset($_SESSION['show_logout_modal'])): ?>
                    document.getElementById('logoutModal').style.display = 'block';
                    <?php unset($_SESSION['show_logout_modal']); ?>
                <?php endif; ?>
                
                // Close modals when clicking outside
                window.onclick = function(event) {
                    const welcomeModal = document.getElementById('welcomeModal');
                    const logoutModal = document.getElementById('logoutModal');
                    if (event.target == welcomeModal) {
                        welcomeModal.style.display = 'none';
                    }
                    if (event.target == logoutModal) {
                        logoutModal.style.display = 'none';
                    }
                }
            });
            });
        </script>
    </body>
</html>
