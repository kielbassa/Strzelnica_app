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
      <a href="register.php" class="auth-link" id="register-link">Rejestracja</a>
      <a href="login.php" class="auth-link" id="login-link">Zaloguj się</a>
    </div>
    </header>


  <!-- pasek nawigacyjny -->
  <nav class="main-nav">
    <a href="../pages/index.html" class="nav-link" id="home-link">Strona Główna</a>
    <a href="../pages/strzelnica.php" class="nav-link">Strzelnica</a>
    <a href="../pages/reservation.php" class="nav-link">Rezerwacje</a>
    <a href="../pages/store.php" class="nav-link">Sklep</a>
    <a href="../pages/kontakt.html" class="nav-link">Kontakt</a>
  </nav>

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
  </body>
  </html>
