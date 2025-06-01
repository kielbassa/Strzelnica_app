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
    <title>Test Systemu Autentykacji - Strzelnica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .status-panel {
            border: 2px solid;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .logged-in {
            border-color: #28a745;
            background-color: #d4edda;
            color: #155724;
        }
        .logged-out {
            border-color: #dc3545;
            background-color: #f8d7da;
            color: #721c24;
        }
        .test-section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fafafa;
        }
        .test-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .test-button:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            background-color: #dc3545;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .auth-btn {
            background-color: #28a745;
        }
        .auth-btn:hover {
            background-color: #1e7e34;
        }
        .result-box {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            font-family: monospace;
            white-space: pre-wrap;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .info-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        h1, h2, h3 {
            color: #333;
        }
        .auth-required {
            display: none;
        }
        .guest-only {
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Test Systemu Autentykacji - Strzelnica</h1>

        <!-- Flash messages -->
        <?php $auth->displayFlashMessage(); ?>

        <!-- Status Panel -->
        <div class="status-panel <?php echo $auth->isLoggedIn()
            ? "logged-in"
            : "logged-out"; ?>">
            <h2>Status Logowania</h2>
            <?php if ($auth->isLoggedIn()): ?>
                <p><strong>✅ Jesteś zalogowany!</strong></p>
                <p><strong>Użytkownik:</strong> <?php echo htmlspecialchars(
                    $userData["full_name"]
                ); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars(
                    $userData["email"]
                ); ?></p>
                <p><strong>ID:</strong> <?php echo htmlspecialchars(
                    $userData["id"]
                ); ?></p>

            <?php else: ?>
                <p><strong>❌ Nie jesteś zalogowany</strong></p>
                <p>Przejdź do <a href="../pages/login.php">strony logowania</a> lub <a href="../pages/register.php">zarejestruj się</a></p>
            <?php endif; ?>
        </div>

        <!-- Navigation Links -->
        <div class="test-section">
            <h3>🔗 Linki Nawigacyjne</h3>
            <a href="../pages/index.php" class="test-button">Strona Główna</a>
            <a href="../pages/login.php" class="test-button guest-only">Logowanie</a>
            <a href="../pages/register.php" class="test-button guest-only">Rejestracja</a>
            <a href="../pages/reservation.php" class="test-button auth-required">Rezerwacje</a>
            <a href="../pages/store.php" class="test-button auth-required">Sklep</a>
            <button class="test-button logout-btn auth-required" onclick="logout()">Wyloguj się</button>
        </div>

        <!-- API Tests -->
        <div class="test-section">
            <h3>🧪 Testy API</h3>
            <button class="test-button" onclick="testCheckSession()">Sprawdź Sesję</button>
            <button class="test-button" onclick="testLoginForm()">Test Logowania</button>
            <button class="test-button logout-btn" onclick="testLogout()">Test Wylogowania</button>

            <div id="apiResults" class="result-box" style="display: none;">
                <strong>Wyniki testów API:</strong><br>
                <span id="apiOutput"></span>
            </div>
        </div>

        <!-- User Information Display -->
        <?php if ($auth->isLoggedIn()): ?>
        <div class="test-section auth-required">
            <h3>👤 Informacje o Użytkowniku</h3>
            <div class="info-grid">
                <div class="info-card">
                    <h4>Dane Podstawowe</h4>
                    <p><strong>ID:</strong> <?php echo htmlspecialchars(
                        $userData["id"]
                    ); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars(
                        $userData["email"]
                    ); ?></p>
                    <p><strong>Imię:</strong> <?php echo htmlspecialchars(
                        $userData["first_name"]
                    ); ?></p>
                    <p><strong>Nazwisko:</strong> <?php echo htmlspecialchars(
                        $userData["last_name"]
                    ); ?></p>
                </div>
                <div class="info-card">
                    <h4>Dane Dodatkowe</h4>
                    <p><strong>Pełne imię:</strong> <?php echo htmlspecialchars(
                        $userData["full_name"]
                    ); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Session Information -->
        <div class="test-section">
            <h3>🕒 Informacje o Sesji</h3>
            <table>
                <tr>
                    <th>Parametr</th>
                    <th>Wartość</th>
                </tr>
                <tr>
                    <td>Status sesji</td>
                    <td><?php echo session_status() === PHP_SESSION_ACTIVE
                        ? "Aktywna"
                        : "Nieaktywna"; ?></td>
                </tr>
                <tr>
                    <td>ID sesji</td>
                    <td><?php echo session_id(); ?></td>
                </tr>
                <tr>
                    <td>Nazwa sesji</td>
                    <td><?php echo session_name(); ?></td>
                </tr>
                <tr>
                    <td>Użytkownik zalogowany</td>
                    <td><?php echo $auth->isLoggedIn() ? "Tak" : "Nie"; ?></td>
                </tr>
                <?php if ($auth->isLoggedIn()): ?>
                <tr>
                    <td>ID użytkownika w sesji</td>
                    <td><?php echo isset($_SESSION["user_id"])
                        ? $_SESSION["user_id"]
                        : "Brak"; ?></td>
                </tr>
                <tr>
                    <td>Czas logowania</td>
                    <td><?php echo isset($_SESSION["login_time"])
                        ? date("Y-m-d H:i:s", $_SESSION["login_time"])
                        : "Brak"; ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Authentication Components Test -->
        <div class="test-section">
            <h3>🎛️ Test Komponentów Autentykacji</h3>
            <p>Poniżej znajdują się komponenty, które powinny automatycznie aktualizować się w zależności od statusu logowania:</p>

            <div id="authContainer" class="auth-container">
                <!-- This will be populated by JavaScript -->
            </div>

            <div class="user-display" style="margin: 10px 0; padding: 10px; background: #e3f2fd; border-radius: 5px;">
                Nazwa użytkownika pojawi się tutaj gdy będziesz zalogowany
            </div>

            <div class="auth-required" style="padding: 10px; background: #f3e5f5; border-radius: 5px; margin: 5px 0;">
                Ten tekst jest widoczny tylko dla zalogowanych użytkowników
            </div>

            <div class="guest-only" style="padding: 10px; background: #fff3e0; border-radius: 5px; margin: 5px 0;">
                Ten tekst jest widoczny tylko dla niezalogowanych użytkowników
            </div>
        </div>

        <!-- Security Test -->
        <div class="test-section">
            <h3>🔒 Test Bezpieczeństwa</h3>
            <p>Funkcje bezpieczeństwa:</p>
            <ul>
                <li><strong>Regeneracja ID sesji:</strong> <?php echo isset(
                    $_SESSION["user_id"]
                )
                    ? "Wykonano przy logowaniu"
                    : "Oczekuje na logowanie"; ?></li>
                <li><strong>Timeout sesji:</strong> Sesja wygasa po 1 godzinie braku aktywności</li>
                <li><strong>Walidacja danych:</strong> Wszystkie dane wejściowe są walidowane i sanityzowane</li>
                <li><strong>Hashowanie haseł:</strong> Hasła są przechowywane jako hash (password_hash/password_verify)</li>
            </ul>
        </div>

        <!-- Debug Information -->
        <div class="test-section">
            <h3>🐛 Informacje Debugowania</h3>
            <button class="test-button" onclick="toggleDebugInfo()">Pokaż/Ukryj Debug</button>
            <div id="debugInfo" class="result-box" style="display: none;">
                <strong>Zmienne sesji:</strong><br>
                <?php if (!empty($_SESSION)) {
                    foreach ($_SESSION as $key => $value) {
                        echo htmlspecialchars($key) .
                            ": " .
                            htmlspecialchars(
                                is_array($value) ? json_encode($value) : $value
                            ) .
                            "\n";
                    }
                } else {
                    echo "Brak zmiennych sesji\n";
                } ?>

                <br><strong>Informacje PHP:</strong><br>
                PHP Version: <?php echo PHP_VERSION; ?><br>
                Session Save Path: <?php echo session_save_path(); ?><br>
                Session Cookie Params: <?php echo json_encode(
                    session_get_cookie_params()
                ); ?><br>
            </div>
        </div>
    </div>

    <script src="../js/user_auth.js"></script>
    <script>
        // Test functions
        async function testCheckSession() {
            try {
                const response = await fetch('../api/check_session.php');
                const result = await response.json();
                displayApiResult('Check Session', result);
            } catch (error) {
                displayApiResult('Check Session Error', {error: error.message});
            }
        }

        async function testLoginForm() {
            const email = prompt('Wprowadź email testowy:');
            const password = prompt('Wprowadź hasło testowe:');

            if (!email || !password) {
                alert('Anulowano test logowania');
                return;
            }

            try {
                const response = await fetch('../api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({email, password})
                });
                const result = await response.json();
                displayApiResult('Login Test', result);

                if (result.success) {
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            } catch (error) {
                displayApiResult('Login Test Error', {error: error.message});
            }
        }

        async function testLogout() {
            try {
                const response = await fetch('../api/logout.php', {
                    method: 'POST'
                });
                const result = await response.json();
                displayApiResult('Logout Test', result);

                if (result.success) {
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            } catch (error) {
                displayApiResult('Logout Test Error', {error: error.message});
            }
        }

        function displayApiResult(testName, result) {
            const resultsDiv = document.getElementById('apiResults');
            const outputSpan = document.getElementById('apiOutput');

            outputSpan.innerHTML = `<strong>${testName}:</strong>\n${JSON.stringify(result, null, 2)}`;
            resultsDiv.style.display = 'block';
        }

        function toggleDebugInfo() {
            const debugDiv = document.getElementById('debugInfo');
            debugDiv.style.display = debugDiv.style.display === 'none' ? 'block' : 'none';
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // The user_auth.js will automatically handle UI updates
            console.log('Authentication test page loaded');

            // Test automatic session checking
            setTimeout(() => {
                if (window.userAuth) {
                    console.log('UserAuth initialized:', window.userAuth.isUserLoggedIn());
                }
            }, 1000);
        });
    </script>
</body>
</html>
