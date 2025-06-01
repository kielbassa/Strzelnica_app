<?php
require_once "../includes/auth_helper.php";
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
        <script src="../js/auth.js"></script>
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
            <a href="../pages/index.php" class="nav-link active" id="home-link"
                >Strona Główna</a
            >
            <a href="../pages/strzelnica.php" class="nav-link">Strzelnica</a>
            <a href="../pages/reservation.php" class="nav-link">Rezerwacje</a>
            <a href="../pages/store.php" class="nav-link">Sklep</a>
            <a href="../pages/kontakt.php" class="nav-link">Kontakt</a>
        </nav>

        <?php if ($userData): ?>
            <div style="background-color: #e6ffe6; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #28a745;">
                <h3 style="margin: 0 0 10px 0; color: #28a745;">Witaj w systemie, <?php echo htmlspecialchars($userData['full_name']); ?>!</h3>
                <p style="margin: 0; color: #333;">Zalogowany jako: <?php echo htmlspecialchars($userData['email']); ?></p>
            </div>
        <?php endif; ?>

        <section class="banner-section">
            <img
                src="../zdj/banner.jpg"
                alt="Zdjęcie główne"
                class="banner-img"
            />
        </section>

        <div class="background-section"></div>

        <section class="gallery">
            <div class="img-container">
                <img src="../zdj/main-zdj1.jpg" alt="Zdjęcie 1" />
                <div class="overlay"></div>
                <div class="btn-wrapper">
                    <a href="../pages/strzelnica.php" class="hover-btn"
                        >STRZELNICA</a
                    >>
                </div>
            </div>
            <div class="img-container">
                <img src="../zdj/main-zdj2.jpg" alt="Zdjęcie 2" />
                <div class="overlay"></div>
                <div class="btn-wrapper">
                    <a href="../pages/reservation.php" class="hover-btn"
                        >REZERWACJE</a
                    >
                </div>
            </div>
            <div class="img-container">
                <img src="../zdj/main-zdj3.jpg" alt="Zdjęcie 3" />
                <div class="overlay"></div>
                <div class="btn-wrapper">
                    <a href="../pages/kontakt.php" class="hover-btn"
                        >KONTAKT</a
                    >
                </div>
            </div>
            <div class="img-container">
                <img src="../zdj/main-zdj4.jpg" alt="Zdjęcie 4" />
                <div class="overlay"></div>
                <div class="btn-wrapper">
                    <a href="../pages/store.php" class="hover-btn">SKLEP</a>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <h2 class="contact-heading">Jak do nas trafić?</h2>
            <div class="contact-grid">
                <div class="contact-item">
                    <img
                        src="../zdj/mail.png"
                        alt="Email Icon"
                        class="contact-icon"
                    />
                    <p class="contact-text">kontakt@strzelnica.pl</p>
                </div>
                <div class="contact-item">
                    <img
                        src="../zdj/location.png"
                        alt="Location Icon"
                        class="contact-icon"
                    />
                    <p class="contact-text">ul. Strzelecka 10, 80-209 Gdynia</p>
                </div>
                <div class="contact-item">
                    <img
                        src="../zdj/phone.png"
                        alt="Phone Icon"
                        class="contact-icon"
                    />
                    <p class="contact-text">+48 123 456 789</p>
                </div>
            </div>
        </section>

        <div class="background-section"></div>

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
