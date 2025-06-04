<?php
require_once "../includes/auth_helper.php";
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
  <link rel="stylesheet" href="../css/store.css">
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
    <a href="../pages/reservation.php" class="nav-link">Rezerwacje</a>
    <a href="../pages/store.php" class="nav-link active">Sklep</a>
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
              <h3>Witaj w systemie, <?php echo htmlspecialchars(
                  $userData["full_name"]
              ); ?>!</h3>
              <p>Zalogowany jako: <?php echo htmlspecialchars(
                  $userData["email"]
              ); ?></p>
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

  <section class="store">
    <div class="store-container">
      <table class="club-info-table">
        <thead>
          <tr>
            <th colspan="5">Klub Strzelecki</th>
          </tr>
        </thead>
        <tbody>
          <?php if (
              $userData &&
              isset($userData["client"]) &&
              $userData["client"] &&
              $userData["client"]["has_active_membership"]
          ): ?>
          <tr>
            <td colspan="5">
              <strong>🎉 Jesteś członkiem klubu!</strong><br>
              Typ członkostwa: <strong><?php echo htmlspecialchars(
                  $userData["client"]["membership_type"]
              ); ?></strong><br>
              Ważne do: <strong><?php echo htmlspecialchars(
                  $userData["client"]["expiration_date"]
              ); ?></strong><br>
              Korzystasz ze zniżek klubowych i priorytetowych rezerwacji.
            </td>
          </tr>
          <?php elseif ($userData): ?>
          <tr>
            <td colspan="5">
              Nie masz aktywnego członkostwa. Dołącz do naszego Klubu Strzeleckiego!<br>
              Uzyskaj dostęp do zniżek, specjalnych wydarzeń i priorytetowych rezerwacji. Członkostwo już od <strong>50 zł miesięcznie</strong>.
            </td>
          </tr>
          <tr>
            <td colspan="5" class="text-center">
              <button id="join-btn" class="join-btn">Kup członkostwo</button>
            </td>
          </tr>
          <?php else: ?>
          <tr>
            <td colspan="5">
              Dołącz do naszego Klubu Strzeleckiego!<br>
              Uzyskaj dostęp do zniżek, specjalnych wydarzeń i priorytetowych rezerwacji. Członkostwo już od <strong>50 zł miesięcznie</strong>.<br>
              <small><em>Musisz być zalogowany, aby kupić członkostwo.</em></small>
            </td>
          </tr>
          <tr>
            <td colspan="5" class="text-center">
              <a href="../pages/login.php" class="join-btn">Zaloguj się</a>
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <h2>Lista dostępnej amunicji</h2>
      <div id="loading-ammunition" style="text-align: center; padding: 40px; color: white;">
        Ładowanie amunicji...
      </div>
      <table id="ammunition-table" style="display: none;">
        <thead>
          <tr>
            <th>Broń</th>
            <th>Amunicja</th>
            <th>Cena za 1 nabój</th>
            <th>Dostępna ilość</th>
            <th>Ilość</th>
            <th>Akcja</th>
          </tr>
        </thead>
        <tbody id="ammunition-tbody">
          <!-- Dynamic content will be loaded here -->
        </tbody>
      </table>

      <div class="go-to-cart-wrapper">
        <a href="cart.php" class="go-to-cart-button">Przejdź do koszyka</a>
      </div>

    </div>
  </section>

  <!-- formularz klubowicz -->
  <div id="membership-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Kup Członkostwo w Klubie Strzeleckim</h2>

      <form id="membership-form">
        <?php if ($userData): ?>
        <div class="form-group">
          <div class="info" style="background: #000000; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <strong>Kupujesz członkostwo dla:</strong><br>
            <?php echo htmlspecialchars(
                $userData["full_name"]
            ); ?> (<?php echo htmlspecialchars($userData["email"]); ?>)
          </div>
        </div>
        <?php else: ?>
        <div class="form-group">
          <label for="firstname">Imię</label>
          <input type="text" id="firstname" name="firstname" required>
        </div>

        <div class="form-group">
          <label for="lastname">Nazwisko</label>
          <input type="text" id="lastname" name="lastname" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
          <label for="idNumber">Numer dowodu osobistego</label>
          <input type="text" id="idNumber" name="idNumber" required>
        </div>
        <?php endif; ?>
</edits>

        <div class="form-group membership-type-section">
          <label>Rodzaj członkostwa</label>
          <div class="radio-group">
            <input type="radio" id="standard" name="membershipType" value="standard" checked>
            <label for="standard">Standard (50 zł/miesiąc)</label>
          </div>
          <div class="radio-group">
            <input type="radio" id="premium" name="membershipType" value="premium">
            <label for="premium">Premium (100 zł/miesiąc)</label>
          </div>
          <div class="radio-group">
            <input type="radio" id="vip" name="membershipType" value="vip">
            <label for="vip">VIP (200 zł/miesiąc)</label>
          </div>
        </div>

        <div id="transfer-details" class="payment-details">
          <p class="bank-info">
            Dane do przelewu:<br>
            Strefa Strzału Gdynia<br>
            Nr konta: 12 3456 7890 1234 5678 9012 3456<br>
            Tytuł: Członkostwo - [Imię i Nazwisko]
          </p>
        </div>

        <div class="form-group terms-group">
          <div class="terms-text">
            Akceptuję <a href="#">regulamin klubu</a> i wyrażam zgodę na przetwarzanie moich danych osobowych
          </div>
          <div class="terms-checkbox">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">Akceptuję</label>
          </div>
        </div>

        <div class="form-group submit-group">
          <button type="submit" class="submit-btn">Zapisz się</button>
        </div>
      </form>
    </div>
  </div>

    <footer>
      <img src="../zdj/logo.png" alt="Logo">
      <p>Wszelkie prawa zastrzeżone dla strefastrzalu.pl © 2025 | Projekt i wykonanie chrust</p>
    </footer>

    <script src="../js/buttons.js"></script>
    <script src="../js/navbar.js"></script>
    <script src="../js/auth.js"></script>
    <script src="../js/cart.js"></script>
    <script src="../js/membership.js"></script>
    <script src="../js/user_auth.js"></script>
    <script src="../js/store.js"></script>
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
          <?php if ($userData && !isset($_SESSION["welcome_shown"])): ?>
              document.getElementById('welcomeModal').style.display = 'block';
              <?php $_SESSION["welcome_shown"] = true; ?>
          <?php endif; ?>

          // Show logout modal if just logged out
          <?php if (isset($_SESSION["show_logout_modal"])): ?>
              document.getElementById('logoutModal').style.display = 'block';
              <?php unset($_SESSION["show_logout_modal"]); ?>
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
