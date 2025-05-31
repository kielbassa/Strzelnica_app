<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Strzelnica</title>
  <link rel="icon" href="../zdj/logo2.png" type="image/png">
  <link rel="stylesheet" href="../css/auth-window.css">
</head>
<body>
  <div id="loginWindow" class="okno-rejestracji">
    <div class="okno-tresc">
      <span id="closeLogin" class="zamknij">&times;</span>
      <h2 class="okno-tytul">Zaloguj się</h2>
      <form id="loginForm">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Hasło" required>
        <button type="submit">Zaloguj się</button>
      </form>
    </div>
  </div>
  <script src="../js/auth.js"></script>
</body>
</html>