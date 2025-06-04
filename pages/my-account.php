<?php
require_once "../includes/auth_helper.php";

$auth = getAuthHelper();
$auth->requireLogin(); // Redirect if not logged in

$userData = $auth->getUserData();
$userId = $userData["id"];
$client = null;
$reservations = [];
$transactions = [];

// Get client info if available
if (isset($userData["client"]) && $userData["client"]) {
    // Create database connection
    require_once "../config/database.php";
    $database = new Database();
    $db = $database->getConnection();

    // Load client model
    require_once "../classes/Client.php";

    // Get client object
    $client = new Client($db);
    $client->getByUserId($userId);

    // Get client's reservations
    $reservations = $client->getReservations();

    // Get client's transactions
    $transactions = $client->getTransactions();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje Konto - Strzelnica</title>
    <link rel="icon" href="../zdj/logo2.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            background-image: url('../zdj/main-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .account-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .profile-card {
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        .section-title {
            color: red;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid red;
            padding-bottom: 10px;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .profile-info {
            background-color: rgba(0, 0, 0, 0.4);
            padding: 15px;
            border-radius: 8px;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }

        .info-label {
            flex: 0 0 40%;
            font-weight: bold;
            color: #aaa;
        }

        .info-value {
            flex: 0 0 60%;
            color: white;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #28a745;
            color: white;
        }

        .status-inactive {
            background-color: #dc3545;
            color: white;
        }

        .status-pending {
            background-color: #ffc107;
            color: black;
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 5px;
            margin-top: 20px;
        }

        .data-table th {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 12px;
        }

        .data-table td {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px;
        }

        .data-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #aaa;
            font-style: italic;
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            margin-top: 20px;
        }

        .action-button {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
        }

        .action-button:hover {
            background-color: darkred;
            transform: scale(1.05);
        }

        .tab-container {
            margin-bottom: 20px;
        }

        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-button {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .tab-button.active {
            background-color: rgba(0, 0, 0, 0.8);
            border-bottom: 3px solid red;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
        <a href="../pages/index.php" class="nav-link">Strona Główna</a>
        <a href="../pages/strzelnica.php" class="nav-link">Strzelnica</a>
        <a href="../pages/reservation.php" class="nav-link">Rezerwacje</a>
        <a href="../pages/store.php" class="nav-link">Sklep</a>
        <a href="../pages/kontakt.php" class="nav-link">Kontakt</a>
        <a href="../pages/my-account.php" class="nav-link active">Moje Konto</a>
    </nav>

    <div class="account-container">
        <div class="profile-card">
            <h1 class="section-title">Moje Konto</h1>

            <div class="tab-container">
                <div class="tab-buttons">
                    <button class="tab-button active" data-tab="profile">Profil</button>
                    <button class="tab-button" data-tab="reservations">Rezerwacje</button>
                    <button class="tab-button" data-tab="transactions">Transakcje</button>
                    <button class="tab-button" data-tab="membership">Członkostwo</button>
                </div>

                <!-- Profile Tab -->
                <div id="profile-tab" class="tab-content active">
                    <div class="profile-grid">
                        <div class="profile-info">
                            <h3 class="section-title">Dane Osobowe</h3>
                            <div class="info-row">
                                <div class="info-label">Imię:</div>
                                <div class="info-value"><?php echo htmlspecialchars(
                                    $userData["first_name"]
                                ); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Nazwisko:</div>
                                <div class="info-value"><?php echo htmlspecialchars(
                                    $userData["last_name"]
                                ); ?></div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email:</div>
                                <div class="info-value"><?php echo htmlspecialchars(
                                    $userData["email"]
                                ); ?></div>
                            </div>
                            <?php if ($client): ?>
                            <div class="info-row">
                                <div class="info-label">ID Klienta:</div>
                                <div class="info-value"><?php echo htmlspecialchars(
                                    $client->ID_client
                                ); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="profile-info">
                            <h3 class="section-title">Status Konta</h3>
                            <div class="info-row">
                                <div class="info-label">Status:</div>
                                <div class="info-value">
                                    <span class="status-badge status-active">Aktywne</span>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Członkostwo:</div>
                                <div class="info-value">
                                    <?php if (
                                        isset($userData["client"]) &&
                                        $userData["client"] &&
                                        $userData["client"][
                                            "has_active_membership"
                                        ]
                                    ): ?>
                                        <span class="status-badge status-active"><?php echo htmlspecialchars(
                                            $userData["client"][
                                                "membership_type"
                                            ]
                                        ); ?></span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive">Brak aktywnego członkostwa</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (
                                isset($userData["client"]) &&
                                $userData["client"] &&
                                $userData["client"]["has_active_membership"]
                            ): ?>
                            <div class="info-row">
                                <div class="info-label">Data ważności:</div>
                                <div class="info-value format-date"><?php echo htmlspecialchars(
                                    $userData["client"]["expiration_date"]
                                ); ?></div>
                            </div>
                            <?php endif; ?>

                            <?php if (
                                !isset($userData["client"]) ||
                                !$userData["client"] ||
                                !$userData["client"]["has_active_membership"]
                            ): ?>
                            <a href="../pages/store.php" class="action-button">Kup Członkostwo</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reservations Tab -->
                <div id="reservations-tab" class="tab-content">
                    <h3 class="section-title">Moje Rezerwacje</h3>

                    <?php if (!empty($reservations)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Godzina</th>
                                <th>Stanowisko</th>
                                <th>Liczba osób</th>
                                <th>Instruktor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td class="format-date"><?php echo $reservation[
                                    "date"
                                ]; ?></td>
                                <td><?php echo $reservation["time"]; ?></td>
                                <td>Stanowisko #<?php echo $reservation[
                                    "ID_station"
                                ]; ?> (max. <?php echo $reservation[
     "station_slots"
 ]; ?> osób)</td>
                                <td><?php echo $reservation[
                                    "participants"
                                ]; ?></td>
                                <td><?php echo $reservation["instructor"]
                                    ? "Tak"
                                    : "Nie"; ?></td>
                                <td>
                                    <?php
                                    $resDate = strtotime(
                                        $reservation["date"] .
                                            " " .
                                            $reservation["time"]
                                    );
                                    $now = time();

                                    if ($resDate < $now) {
                                        echo '<span class="status-badge status-inactive">Zakończona</span>';
                                    } elseif ($resDate - $now < 86400) {
                                        // less than 24 hours
                                        echo '<span class="status-badge status-pending">Wkrótce</span>';
                                    } else {
                                        echo '<span class="status-badge status-active">Aktywna</span>';
                                        // Add cancel button for future reservations
                                        echo '<button class="cancel-reservation-btn" data-id="' .
                                            $reservation["ID_reservations"] .
                                            '" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; margin-left: 10px; cursor: pointer; font-size: 0.8rem;">Anuluj</button>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="empty-state">
                        <p>Nie masz jeszcze żadnych rezerwacji.</p>
                        <a href="../pages/reservation.php" class="action-button">Zarezerwuj teraz</a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Transactions Tab -->
                <div id="transactions-tab" class="tab-content">
                    <h3 class="section-title">Historia Transakcji</h3>

                    <?php if (!empty($transactions)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Typ amunicji</th>
                                <th>Ilość</th>
                                <th>Cena jednostkowa</th>
                                <th>Łączna kwota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td>#<?php echo $transaction[
                                    "ID_transaction"
                                ]; ?></td>
                                <td><?php echo htmlspecialchars(
                                    $transaction["ammo_name"]
                                ); ?></td>
                                <td><?php echo $transaction[
                                    "count"
                                ]; ?> szt.</td>
                                <td><?php echo number_format(
                                    $transaction["ammo_price"],
                                    2
                                ); ?> zł</td>
                                <td><?php echo number_format(
                                    $transaction["count"] *
                                        $transaction["ammo_price"],
                                    2
                                ); ?> zł</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="empty-state">
                        <p>Nie masz jeszcze żadnych transakcji.</p>
                        <a href="../pages/store.php" class="action-button">Przejdź do sklepu</a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Membership Tab -->
                <div id="membership-tab" class="tab-content">
                    <h3 class="section-title">Moje Członkostwo</h3>

                    <?php if (
                        isset($userData["client"]) &&
                        $userData["client"] &&
                        $userData["client"]["has_active_membership"]
                    ): ?>
                    <div class="profile-info">
                        <div class="info-row">
                            <div class="info-label">Typ członkostwa:</div>
                            <div class="info-value"><?php echo htmlspecialchars(
                                $userData["client"]["membership_type"]
                            ); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Data aktywacji:</div>
                            <div class="info-value format-date"><?php echo htmlspecialchars(
                                $userData["client"]["activation_date"]
                            ); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Data ważności:</div>
                            <div class="info-value membership-expiry-date format-date"><?php echo htmlspecialchars(
                                $userData["client"]["expiration_date"]
                            ); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status:</div>
                            <div class="info-value">
                                <?php
                                $today = strtotime("today");
                                $expiry = strtotime(
                                    $userData["client"]["expiration_date"]
                                );

                                if ($expiry >= $today) {
                                    echo '<span class="status-badge status-active">Aktywne</span>';
                                    echo ' <span class="days-remaining"></span>';
                                } else {
                                    echo '<span class="status-badge status-inactive">Wygasło</span>';
                                }
                                ?>
                            </div>
                        </div>

                        <h4 style="margin-top: 20px; font-weight: bold; font-size: 1.1rem;">Korzyści członkostwa:</h4>
                        <ul style="list-style-type: disc; margin-left: 20px; margin-top: 10px;">
                            <li>Zniżki na wynajem stanowisk strzeleckich</li>
                            <li>Priorytet przy rezerwacjach</li>
                            <li>Dostęp do specjalnych wydarzeń klubowych</li>
                            <li>Zniżki na zakup amunicji</li>
                        </ul>

                        <?php if ($expiry < $today): ?>
                        <a href="../pages/store.php" class="action-button renew-membership-btn">Odnów Członkostwo</a>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="empty-state">
                        <p>Nie posiadasz aktywnego członkostwa w klubie.</p>
                        <p>Kupując członkostwo otrzymasz dostęp do zniżek, specjalnych wydarzeń i priorytetowych rezerwacji.</p>
                        <a href="../pages/store.php" class="action-button">Kup Członkostwo</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <img src="../zdj/logo.png" alt="Logo">
        <p>Wszelkie prawa zastrzeżone dla strefastrzalu.pl © 2025 | Projekt i wykonanie chrust</p>
    </footer>

    <script src="../js/navbar.js"></script>
    <script src="../js/user_auth.js"></script>
    <script src="../js/account.js"></script>
</body>
</html>
