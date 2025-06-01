<?php
require_once '../includes/auth_helper.php';
$auth = getAuthHelper();
$userData = $auth->getUserData();
?>
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strzelnica</title>
    <link rel="icon" href="../zdj/logo2.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/reservation.css">
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
        <img src="../zdj/instagram.png" alt="IgLogo" class="sm-logo">
        <span>@strefastrzalu</span>
      </div>
      <div class="sm-item">
        <img src="../zdj/youtube.png" alt="YtLogo" class="sm-logo">
        <span>Strefa Strzału Gdynia</span>
      </div>
      <div class="sm-item">
        <img src="../zdj/facebook.png" alt="FbLogo" class="sm-logo">
        <span>Strefa Strzału Gdynia</span>
      </div>
    </div>

    <div class="logo-container">
      <img src="../zdj/logo.png" alt="Logo" class="logo">
    </div>

    <div class="auth-links">
      <div id="authContainer" class="auth-container">
        <?php echo $auth->getLoginButton(); ?>
      </div>
    </div>
    </header>


  <!-- pasek nawigacyjny -->
  <nav class="main-nav">
    <a href="../pages/index.php" class="nav-link" id="home-link">Strona Główna</a>
    <a href="../pages/strzelnica.php" class="nav-link">Strzelnica</a>
    <a href="../pages/reservation.php" class="nav-link active">Rezerwacje</a>
    <a href="../pages/store.php" class="nav-link">Sklep</a>
    <a href="../pages/kontakt.php" class="nav-link">Kontakt</a>
  </nav>

  <?php if ($userData): ?>
    <div style="background-color: #e6ffe6; padding: 15px; margin: 20px; border-radius: 5px; border: 1px solid #28a745;">
      <h3 style="margin: 0 0 10px 0; color: #28a745;">Witaj w systemie, <?php echo htmlspecialchars($userData['full_name']); ?>!</h3>
      <p style="margin: 0; color: #333;">Zalogowany jako: <?php echo htmlspecialchars($userData['email']); ?></p>
    </div>
  <?php endif; ?>

  <div class="background-section"></div>

  <section class="contact-title">
    <h1 class="contact-heading">Rezerwacje</h1>
  </section>

  <div class="reservation-info">
    <p>
      <strong>Przed przyjazdem na strzelnicę należy dokonać potwierdzenia rezerwacji</strong> pod numerem telefonu: <br>
      <span class="highlight">+48 123 456 789</span><br>
      Następnie przelać <strong>BLIK</strong> na powyższy numer telefonu opłatę rezerwacyjną: <strong>50zł / osoba</strong> (zadatek).
    </p>
    <p>
      Prosimy zabrać ze sobą <strong>dokument tożsamości</strong> oraz zapoznać się z <strong>regulaminem strzelnicy</strong> przed strzelaniem.
    </p>

    <h3 class="pricing-title">Cennik</h3>
    <table class="pricing-table">
      <tr><td>Wynajem stanowiska</td><td>100 zł / h za osobę</td></tr>
      <tr><td>Wynajem dla osób z własną bronią</td><td>120 zł / h za osobę</td></tr>
      <tr><td>Wynajem dla klubowiczów</td><td>50 zł / h za osobę</td></tr>
      <tr><td>Rezerwacja całej strzelnicy (do 10 osób)</td><td>1000 zł / h</td></tr>
      <tr><td>Zajęcia z instruktorem</td><td>150 zł / h za osobę</td></tr>
    </table>
  </div>


  <section class="reservation">
    <h2 class="reservation-title">Formularz rezerwacji</h2>
    <form id="reservation-form">
      <label for="date" class="form-label">Wybierz datę</label>
      <input type="date" id="date" name="date" required>

      <label for="time" class="form-label">Wybierz godzinę</label>
      <select id="time" name="time" required>
        <option value="">-- wybierz godzinę --</option>
      </select>

      <label for="people" class="form-label">Liczba osób</label>
      <input type="number" id="people" name="people" min="1" max="10" required>

      <label for="instructor" class="form-label">Zajęcia z instruktorem?</label>
      <select id="group" name="group" required>
        <option value="nie">Nie</option>
        <option value="tak">Tak</option>
      </select>

      <label for="group" class="form-label">Grupa zorganizowana?</label>
      <select id="group" name="group" required>
        <option value="nie">Nie</option>
        <option value="tak">Tak</option>
      </select>



      <button type="submit">Zarezerwuj</button>
    </form>
  </section>


    <footer>
      <img src="../zdj/logo.png" alt="Logo">
      <p>Wszelkie prawa zastrzeżone dla strefastrzalu.pl © 2025 | Projekt i wykonanie chrust</p>
    </footer>

    <script src="../js/buttons.js"></script>
    <script src="../js/navbar.js"></script>
    <script src="../js/date.js"></script>
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
