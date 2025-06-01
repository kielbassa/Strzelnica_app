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
        <?php echo $auth->getLoginButton(); ?>
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

  <section class="store">
    <div class="store-container">
      <table class="club-info-table">
        <thead>
          <tr>
            <th colspan="5">Dołącz do naszego Klubu Strzeleckiego!</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="5">
              Uzyskaj dostęp do zniżek, specjalnych wydarzeń i priorytetowych rezerwacji. Członkostwo już od <strong>50 zł miesięcznie</strong>.
            </td>
          </tr>
          <tr>
            <td colspan="5" class="text-center">
              <button id="join-btn" class="join-btn">Zapisz się teraz</button>
            </td>
          </tr>
        </tbody>
      </table>

      <h2>Lista dostępnej amunicji</h2>
      <table>
        <thead>
          <tr>
            <th>Broń</th>
            <th>Amunicja</th>
            <th>Cena za 1 nabój</th>
            <th>Dostępna ilość</th>
            <th>Akcja</th>
          </tr>
        </thead>
        <tbody>
          <tr class="category-row"><td colspan="5">Broń Krótka</td></tr>
          <tr><td>Beretta 92 FS</td><td>9x19 mm</td><td>2.00 zł</td><td>500</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>Colt 1911</td><td>.45 ACP</td><td>3.60 zł</td><td>300</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>Glock 17 Gen.4</td><td>9x19 mm</td><td>2.00 zł</td><td>450</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>HK z tłumikiem</td><td>9x19 mm</td><td>2.80 zł</td><td>250</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>Vis 100</td><td>9x19 mm</td><td>2.40 zł</td><td>400</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>.357 Magnum</td><td>9x32Rmm</td><td>5.00 zł</td><td>100</td><td><button>Dodaj do koszyka</button></td></tr>
  
          <tr class="category-row"><td colspan="5">Broń Długa</td></tr>
          <tr><td>AR-15</td><td>.223 Rem</td><td>6.00 zł</td><td>200</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>kbk wz. 96D „Beryl”</td><td>5.56 NATO</td><td>4.00 zł</td><td>180</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>IWI Galil SAR</td><td>7.62x39 mm</td><td>6.50 zł</td><td>160</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>MSBS „Grot”</td><td>5.56 NATO</td><td>6.00 zł</td><td>150</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>AKMS „Kałasznikow”</td><td>7.62x39 mm</td><td>6.50 zł</td><td>170</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>M4</td><td>5.56 NATO</td><td>4.00 zł</td><td>200</td><td><button>Dodaj do koszyka</button></td></tr>

          <tr class="category-row"><td colspan="5">Pistolety Maszynowe</td></tr>
          <tr><td>PM-84P „Glauberyt”</td><td>9x19 mm</td><td>2.40 zł</td><td>300</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>IWI UZI</td><td>9x19 mm</td><td>2.40 zł</td><td>250</td><td><button>Dodaj do koszyka</button></td></tr>
          <tr><td>MP 40</td><td>9x19 mm</td><td>1.60 zł</td><td>180</td><td><button>Dodaj do koszyka</button></td></tr>
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
      <h2>Zapisz się do Klubu Strzeleckiego</h2>
      
      <form id="membership-form">
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