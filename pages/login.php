<?php
require_once '../includes/auth_helper.php';
$auth = getAuthHelper();
$auth->preventLoginAccess(); // Redirect if already logged in
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Strzelnica - Logowanie</title>
  <link rel="icon" href="../zdj/logo2.png" type="image/png">
  <link rel="stylesheet" href="../css/auth-window.css">
</head>
<body>
  <!-- Flash messages -->
  <?php $auth->displayFlashMessage(); ?>
  
  <div id="loginWindow" class="okno-rejestracji">
    <div class="okno-tresc">
      <span id="closeLogin" class="zamknij">&times;</span>
      <h2 class="okno-tytul">Zaloguj się</h2>
      <div id="errorMessage" class="error-message" style="display: none; color: red; margin-bottom: 15px; padding: 10px; background-color: #ffe6e6; border: 1px solid #ff0000; border-radius: 4px;"></div>
      <div id="successMessage" class="success-message" style="display: none; color: green; margin-bottom: 15px; padding: 10px; background-color: #e6ffe6; border: 1px solid #00ff00; border-radius: 4px;"></div>
      <form id="loginForm">
        <input type="email" name="email" placeholder="Email" required maxlength="100">
        <input type="password" name="password" placeholder="Hasło" required minlength="8" maxlength="255">
        <button type="submit" id="loginBtn">Zaloguj się</button>
      </form>
      <div style="margin-top: 15px; text-align: center;">
        <p style="color: white;">Nie masz konta? 
        <a href="../pages/register.php" style="color: red; text-decoration: none;">Zarejestruj się</a>
        </p>
      </div>
    </div>
  </div>
  <script src="../js/auth.js"></script>
  <script src="../js/user_auth.js"></script>
  <script>
    // Prevent access for already logged in users
    document.addEventListener('DOMContentLoaded', function() {
      if (preventAuthAccess) {
        preventAuthAccess();
      }
    });
  </script>
</body>
</html>