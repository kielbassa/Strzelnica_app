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
  <link rel="stylesheet" href="../css/strzelnica.css">
</head>
<body>
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
    
  <!-- Flash messages -->
  <?php $auth->displayFlashMessage(); ?>
  
  <!-- pasek nawigacyjny -->
  <nav class="main-nav">
    <a href="index.php" class="nav-link" id="home-link">Strona Główna</a>
    <a href="strzelnica.php" class="nav-link active">Strzelnica</a>
    <a href="reservation.php" class="nav-link">Rezerwacje</a>
    <a href="store.php" class="nav-link">Sklep</a>
    <a href="kontakt.php" class="nav-link">Kontakt</a>
  </nav>
</edits>

  <div class="background-section"></div>

  <main class="shooting-range-section">
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
    
    <section class="weapons-intro">
      <h1 class="main-weapons-title">Do twojej dyspozycji jest:</h1>
      <p class="weapon-category-title">Broń Krótka</p>
      <p class="weapon-category-title">Broń Długa</p>
      <p class="weapon-category-title">Pistolety Maszynowe</p>

    <!-- bronie --> 
    <section class="weapons-section">
      <h2 class="category-title-gun">Broń Krótka</h2>
      <div class="weapon-category">
        <div class="weapon-item">
          <img src="../zdj/guns/beretta92FS.png" alt="Beretta 92FS" class="weapon-img">
          <p>Beretta 92 FS</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/colt1911.png" alt="Colt 1911" class="weapon-img">
          <p>Colt 1911</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/glock17gen4.png" alt="Glock 17 Gen.4" class="weapon-img">
          <p>Glock 17 Gen.4</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/HKzTlumikiem.png" alt="HK z tłumikiem" class="weapon-img">
          <p>HK z tłumikiem</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/vis100.png" alt="Vis 100" class="weapon-img">
          <p>Vis 100</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/357magnum.png" alt=".357 Magnum" class="weapon-img">
          <p>.357 Magnum</p>
        </div>
      </div>
    </section>
  
    <section class="weapons-section">
      <h2 class="category-title-gun">Broń Długa</h2>
      <div class="weapon-category">
        <div class="weapon-item">
          <img src="../zdj/guns/ar15.png" alt="AR-15" class="weapon-img">
          <p>AR-15</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/beryl.png" alt="beryl" class="weapon-img">
          <p>kbk wz. 96D „Beryl”</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/galil.png" alt="galil" class="weapon-img">
          <p>IWI Galil SAR</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/grot.png" alt="grot" class="weapon-img">
          <p>MSBS „Grot”</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/kalashnikow.png" alt="kalasznikow" class="weapon-img">
          <p>kbk AKMS „Kałasznikow”</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/m4.png" alt="m4" class="weapon-img">
          <p>M4</p>
        </div>
      </div>
    </section>
  
    <section class="weapons-section">
      <h2 class="category-title-gun">Pistolety Maszynowe</h2>
      <div class="weapon-category">
        <div class="weapon-item">
          <img src="../zdj/guns/pm.png" alt="pm" class="weapon-img">
          <p>PM-84P „Glauberyt” (Full – Auto)</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/uzi.png" alt="Uzi" class="weapon-img">
          <p>IWI UZI (Full – Auto)</p>
        </div>
        <div class="weapon-item">
          <img src="../zdj/guns/mp40.png" alt="mp40" class="weapon-img">
          <p>MP 40 (Full – Auto)</p>
        </div>
      </div>
    </section>

    <section class="cennik">
      <h1>Cennik</h1>
      <table class="price-table">
        <tr class="first-row">
          <td>
            <strong>Wybierz tyle sztuk broni ile chcesz:</strong><br> 
            Pakiet startowy: 3 sztuki broni do wyboru – 250 zł<br>
            Każda kolejna broń kosztuje 80 zł
          </td>
          <td>
            <strong>Wraz z bronią dostaniesz następującą ilość amunicji:</strong>
          </td>
        </tr>
        <tr>
          <td>Opłata rezerwacyjna</td>
          <td>50 zł / osoba</td>
        </tr>
        <tr>
          <td>Pistolet</td>
          <td>15 sztuk</td>
        </tr>
        <tr>
          <td>Broń długa</td>
          <td>15 sztuk</td>
        </tr>
        <tr>
          <td>Pistolety maszynowe</td>
          <td>10 sztuk</td>
        </tr>
        <tr>
          <td colspan="2"><strong>Pozostałe usługi</strong></td>
        </tr>
        <tr>
          <td>Wynajem stanowiska</td>
          <td>100 zł / h za osobę</td>
        </tr>
        <tr>
          <td>Wynajem stanowiska dla osób z własną bronią</td>
          <td>120 zł / h za osobę</td>
        </tr>
        <tr>
          <td>Wynajem stanowiska (klubowicz)</td>
          <td>50 zł / h za osobę</td>
        </tr>
        <tr>
          <td>Wynajem strzelnicy (rezerwacja całego obiektu do 10 osób)</td>
          <td>1000 zł / h</td>
        </tr>
        <tr>
          <td>Dodatkowe zajęcia z instruktorem</td>
          <td>150 zł / h za osobę</td>
        </tr>
      </table>
    </section>

    <section class="hours">
      <h1>Godziny otwarcia</h1>
      <div class="hours-grid-row">
        <div class="day"><span>Poniedziałek</span><span>14-22</span></div>
        <div class="day"><span>Wtorek</span><span>14-22</span></div>
        <div class="day"><span>Środa</span><span>14-22</span></div>
        <div class="day"><span>Czwartek</span><span>14-22</span></div>
      </div>
      <div class="hours-grid-row center-row">
        <div class="day"><span>Piątek</span><span>14-23</span></div>
        <div class="day"><span>Sobota</span><span>12-20</span></div>
        <div class="day"><span>Niedziela</span><span>13-17</span></div>
      </div>
    </section>

    <section class="contact-section2">
      <h2 class="contact-heading">Jak do nas trafić?</h2>
      <div class="contact-grid">
        <div class="contact-item">
          <img src="../zdj/mail.png" alt="Email Icon" class="contact-icon">
          <p class="contact-text">kontakt@strzelnica.pl</p>
        </div>
        <div class="contact-item">
          <img src="../zdj/location.png" alt="Location Icon" class="contact-icon">
          <p class="contact-text">ul. Strzelecka 10, 80-209 Gdynia</p>
        </div>
        <div class="contact-item">
          <img src="../zdj/phone.png" alt="Phone Icon" class="contact-icon">
          <p class="contact-text">+48 123 456 789</p>
        </div>
      </div>
    </section>
    
  </main>

  <footer>
    <img src="../zdj/logo.png" alt="Logo">
    <p>Wszelkie prawa zastrzeżone dla strefastrzalu.pl © 2025 | Projekt i wykonanie chrust</p>
  </footer>
  
  <script src="../js/buttons.js"></script>
  <script src="../js/navbar.js"></script>
  <script src="../js/user_auth.js"></script>
  <script>
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


