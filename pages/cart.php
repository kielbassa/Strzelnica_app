<?php
require_once '../includes/auth_helper.php';
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
        <link rel="stylesheet" href="../css/cart.css" />
        <script src="../js/auth.js"></script>
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
            <a href="../pages/index.php" class="nav-link" id="home-link"
                >Strona Główna</a
            >
            <a href="../pages/strzelnica.php" class="nav-link">Strzelnica</a>
            <a href="../pages/reservation.php" class="nav-link">Rezerwacje</a>
            <a href="../pages/store.php" class="nav-link">Sklep</a>
            <a href="../pages/kontakt.php" class="nav-link">Kontakt</a>
            <?php if ($auth->isLoggedIn()): ?>
            <a href="../pages/my-account.php" class="nav-link">Moje Konto</a>
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

        <main class="cart-page">
            <h1>Twój koszyk</h1>

            <section id="cart-items"></section>

            <section class="cart-summary">
                <div class="total-line">
                    <strong>Razem: </strong>
                    <span id="cart-total">0.00 zł</span>
                </div>
                <button class="checkout-btn">Kup teraz</button>
            </section>
        </main>

        <footer>
            <img src="../zdj/logo.png" alt="Logo" />
            <p>
                Wszelkie prawa zastrzeżone dla strefastrzalu.pl © 2025 |
                Projekt i wykonanie chrust
            </p>
        </footer>

        <script src="../js/buttons.js"></script>
        <script src="../js/navbar.js"></script>
        <script src="../js/cart.js"></script>
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
        </script>
    </body>
</html>
