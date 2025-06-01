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

<div id="registerWindow" class="okno-rejestracji">
    <div class="okno-tresc">
      <span id="closeRegister" class="zamknij">&times;</span>
      <h2 class="okno-tytul">Rejestracja</h2>
      <div id="errorMessage" class="error-message" style="display: none; color: red; margin-bottom: 15px; padding: 10px; background-color: #ffe6e6; border: 1px solid #ff0000; border-radius: 4px;"></div>
      <div id="successMessage" class="success-message" style="display: none; color: green; margin-bottom: 15px; padding: 10px; background-color: #e6ffe6; border: 1px solid #00ff00; border-radius: 4px;"></div>
      <form id="registerForm">
        <input type="text" name="firstName" placeholder="Imię" required minlength="2" maxlength="50">
        <input type="text" name="lastName" placeholder="Nazwisko" required minlength="2" maxlength="50">
        <input type="email" id="email" name="email" placeholder="E-mail" required maxlength="100">
        <input type="password" name="password" placeholder="Hasło (min. 8 znaków)" required minlength="8" maxlength="255">
        <input type="password" name="confirmPassword" placeholder="Potwierdź hasło" required minlength="8" maxlength="255">
        <button type="submit" id="submitBtn">Zarejestruj się</button>
      </form>
    </div>
  </div>

  <script src="../js/auth.js"></script>
</body>
</html>
