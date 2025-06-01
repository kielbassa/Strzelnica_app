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
        <link rel="stylesheet" href="../css/kontakt.css" />
        <style>
            .auth-container {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .user-info {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .welcome-text {
                color: #333;
                font-weight: bold;
            }
            .logout-btn {
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 8px 16px;
                border-radius: 4px;
                cursor: pointer;
                text-decoration: none;
                font-size: 14px;
            }
            .logout-btn:hover {
                background-color: #c82333;
            }
            .auth-buttons {
                display: flex;
                gap: 10px;
            }
            .login-btn, .register-btn {
                padding: 8px 16px;
                text-decoration: none;
                border-radius: 4px;
                font-size: 14px;
            }
            .login-btn {
                background-color: #007bff;
                color: white;
            }
            .login-btn:hover {
                background-color: #0056b3;
            }
            .register-btn {
                background-color: #28a745;
                color: white;
            }
            .register-btn:hover {
                background-color: #1e7e34;
            }
        </style>
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
                    <?php echo $auth->getLoginButton(); ?>
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
        </nav>

        <?php if ($userData): ?>
            <div style="background-color: #e6ffe6; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #28a745;">
                <h3 style="margin: 0 0 10px 0; color: #28a745;">Witaj w systemie, <?php echo htmlspecialchars($userData['full_name']); ?>!</h3>
                <p style="margin: 0; color: #333;">Zalogowany jako: <?php echo htmlspecialchars($userData['email']); ?></p>
            </div>
        <?php endif; ?>

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
            // Initialize authentication on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Check if user is logged in and update UI accordingly
                if (window.userAuth) {
                    window.userAuth.checkSession();
                }
            });
        </script>
    </body>
</html>
