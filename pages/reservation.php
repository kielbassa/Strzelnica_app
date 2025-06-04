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
  </head>

<body class="<?php echo $auth->isLoggedIn() ? 'user-logged-in' : ''; ?>">
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
    <a href="../pages/index.php" class="nav-link" id="home-link">Strona Główna</a>
    <a href="../pages/strzelnica.php" class="nav-link">Strzelnica</a>
    <a href="../pages/reservation.php" class="nav-link active">Rezerwacje</a>
    <a href="../pages/store.php" class="nav-link">Sklep</a>
    <a href="../pages/kontakt.php" class="nav-link">Kontakt</a>
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

  <?php if ($auth->isLoggedIn()): ?>
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
      <input type="number" id="people" name="people" min="1" max="8" required>

      <label for="instructor" class="form-label">Zajęcia z instruktorem?</label>
      <select id="instructor" name="instructor" required>
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
  <?php else: ?>
  <section class="reservation">
    <div class="login-required-message">
      <h2 class="reservation-title">Aby dokonać rezerwacji zaloguj się</h2>
      <p>Rezerwacja dostępna jest tylko dla zalogowanych użytkowników.</p>
      <a href="../pages/login.php">Zaloguj się</a>
    </div>
  </section>
  <?php endif; ?>


    <footer>
      <img src="../zdj/logo.png" alt="Logo">
      <p>Wszelkie prawa zastrzeżone dla strefastrzalu.pl © 2025 | Projekt i wykonanie chrust</p>
    </footer>

    <script src="../js/buttons.js"></script>
    <script src="../js/navbar.js"></script>
    <script src="../js/date.js"></script>
    <script src="../js/user_auth.js"></script>
    <script src="../js/reservation.js"></script>
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
