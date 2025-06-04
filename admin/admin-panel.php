<?php
require_once '../includes/auth_helper.php';
require_once '../classes/AdminPanel.php';

$auth = getAuthHelper();
$auth->requireAdmin(); // Only admins can access this page

// Get database connection
require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Create admin panel instance
$adminPanel = new AdminPanel($db);

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'toggle_gun_availability':
            $result = $adminPanel->toggleGunAvailability($_POST['gun_id']);
            echo json_encode(['success' => $result]);
            exit;
            
        case 'toggle_gun_in_use':
            $result = $adminPanel->toggleGunInUse($_POST['gun_id']);
            echo json_encode(['success' => $result]);
            exit;
            
        case 'update_ammo_amount':
            $result = $adminPanel->updateAmmoAmount($_POST['ammo_id'], $_POST['new_amount']);
            echo json_encode(['success' => $result]);
            exit;
    }
}

// Get all data for display
$guns = $adminPanel->getGuns();
$ammo = $adminPanel->getAmmo();
$reservations = $adminPanel->getReservations();
$clients = $adminPanel->getClients();
$memberships = $adminPanel->getMemberships();
$transactions = $adminPanel->getTransactions();
$stations = $adminPanel->getStations();
$users = $adminPanel->getUsers();
$stats = $adminPanel->getDashboardStats();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Panel Administratora</title>
  <link rel="stylesheet" href="panel.css">
  <style>
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    
    .stat-card {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .stat-number {
      font-size: 2em;
      font-weight: bold;
      color: #000;
      margin-bottom: 5px;
    }
    
    .stat-label {
      color: #666;
      font-size: 0.9em;
    }
    
    .action-btn {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 3px;
      cursor: pointer;
      font-size: 12px;
      margin: 2px;
    }
    
    .action-btn:hover {
      background-color: #0056b3;
    }
    
    .action-btn.danger {
      background-color: #dc3545;
    }
    
    .action-btn.danger:hover {
      background-color: #c82333;
    }
    
    .action-btn.success {
      background-color: #28a745;
    }
    
    .action-btn.success:hover {
      background-color: #1e7e34;
    }
    
    .status-active {
      color: #28a745;
      font-weight: bold;
    }
    
    .status-inactive {
      color: #dc3545;
      font-weight: bold;
    }
    
    .admin-badge {
      background-color: #ffc107;
      color: #000;
      padding: 2px 6px;
      border-radius: 3px;
      font-size: 10px;
      font-weight: bold;
    }
    
    .table-controls {
      margin-bottom: 10px;
      text-align: right;
    }
    
    .search-box {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      margin-left: 10px;
    }
  </style>
</head>

<body>
  <!-- Pasek u góry -->
  <header class="header">
    <h1 class="header-title">Panel Administratora</h1>
    <div>
      <span style="color: white; margin-right: 20px;">Witaj, <?php echo htmlspecialchars($auth->getUserData()['full_name']); ?>!</span>
      <a href="../pages/index.php" class="back-link">Powrót na stronę</a>
    </div>
  </header>

  <main class="main-content">

    <!-- Dashboard Statistics -->
    <section>
      <h2 class="section-title">Statystyki systemu</h2>
      <div class="stats-container">
        <div class="stat-card">
          <div class="stat-number"><?php echo $stats['total_users'] ?? 0; ?></div>
          <div class="stat-label">Użytkowników</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $stats['total_clients'] ?? 0; ?></div>
          <div class="stat-label">Klientów</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $stats['active_memberships'] ?? 0; ?></div>
          <div class="stat-label">Aktywnych członkostw</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $stats['todays_reservations'] ?? 0; ?></div>
          <div class="stat-label">Dzisiejszych rezerwacji</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $stats['future_reservations'] ?? 0; ?></div>
          <div class="stat-label">Przyszłych rezerwacji</div>
        </div>
        <div class="stat-card">
          <div class="stat-number"><?php echo $stats['available_guns'] ?? 0; ?></div>
          <div class="stat-label">Dostępnych broni</div>
        </div>
      </div>
    </section>

    <!-- Użytkownicy -->
    <section>
      <h2 class="section-title">Użytkownicy systemu</h2>
      <div class="table-controls">
        <input type="text" class="search-box" placeholder="Szukaj użytkowników..." onkeyup="searchTable(this, 'usersTable')">
      </div>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Imię</th>
              <th>Nazwisko</th>
              <th>Email</th>
              <th>Status</th>
              <th>Uprawnienia</th>
              <th>Data rejestracji</th>
            </tr>
          </thead>
          <tbody id="usersTable">
            <?php foreach ($users as $user): ?>
            <tr>
              <td><?php echo $user['id']; ?></td>
              <td><?php echo htmlspecialchars($user['first_name']); ?></td>
              <td><?php echo htmlspecialchars($user['last_name']); ?></td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
              <td>
                <span class="<?php echo $user['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                  <?php echo $user['is_active'] ? 'Aktywny' : 'Nieaktywny'; ?>
                </span>
              </td>
              <td>
                <?php if ($user['is_admin']): ?>
                  <span class="admin-badge">ADMIN</span>
                <?php else: ?>
                  Użytkownik
                <?php endif; ?>
              </td>
              <td><?php echo date('d.m.Y', strtotime($user['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Dostępne bronie -->
    <section>
      <h2 class="section-title">Lista dostępnych broni palnych</h2>
      <div class="table-controls">
        <input type="text" class="search-box" placeholder="Szukaj broni..." onkeyup="searchTable(this, 'bronieTable')">
      </div>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nazwa</th>
              <th>Typ amunicji</th>
              <th>Gotowość</th>
              <th>W użyciu</th>
              <th>Akcje</th>
            </tr>
          </thead>
          <tbody id="bronieTable">
            <?php foreach ($guns as $gun): ?>
            <tr>
              <td><?php echo $gun['ID_guns']; ?></td>
              <td><?php echo htmlspecialchars($gun['name']); ?></td>
              <td><?php echo htmlspecialchars($gun['ammo_name'] ?? 'Brak'); ?></td>
              <td>
                <span class="<?php echo $gun['availability'] ? 'status-active' : 'status-inactive'; ?>">
                  <?php echo $gun['availability'] ? 'Dostępna' : 'Niedostępna'; ?>
                </span>
              </td>
              <td>
                <span class="<?php echo $gun['in_use'] ? 'status-inactive' : 'status-active'; ?>">
                  <?php echo $gun['in_use'] ? 'Tak' : 'Nie'; ?>
                </span>
              </td>
              <td>
                <button class="action-btn" onclick="toggleGunAvailability(<?php echo $gun['ID_guns']; ?>)">
                  <?php echo $gun['availability'] ? 'Wyłącz' : 'Włącz'; ?>
                </button>
                <button class="action-btn <?php echo $gun['in_use'] ? 'success' : 'danger'; ?>" onclick="toggleGunInUse(<?php echo $gun['ID_guns']; ?>)">
                  <?php echo $gun['in_use'] ? 'Zwolnij' : 'Zajmij'; ?>
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Typy amunicji -->
    <section>
      <h2 class="section-title">Lista typów amunicji</h2>
      <div class="table-controls">
        <input type="text" class="search-box" placeholder="Szukaj amunicji..." onkeyup="searchTable(this, 'amunicjaTable')">
      </div>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nazwa</th>
              <th>Ilość</th>
              <th>Cena</th>
              <th>Akcje</th>
            </tr>
          </thead>
          <tbody id="amunicjaTable">
            <?php foreach ($ammo as $ammunition): ?>
            <tr>
              <td><?php echo $ammunition['ID_ammo']; ?></td>
              <td><?php echo htmlspecialchars($ammunition['name']); ?></td>
              <td>
                <input type="number" value="<?php echo $ammunition['amount']; ?>" 
                       style="width: 80px; padding: 2px;" 
                       onchange="updateAmmoAmount(<?php echo $ammunition['ID_ammo']; ?>, this.value)">
              </td>
              <td><?php echo number_format($ammunition['price'], 2); ?> zł</td>
              <td>
                <button class="action-btn" onclick="updateAmmoAmount(<?php echo $ammunition['ID_ammo']; ?>, prompt('Nowa ilość:', <?php echo $ammunition['amount']; ?>))">
                  Aktualizuj
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Rezerwacje -->
    <section>
      <h2 class="section-title">Rezerwacje</h2>
      <div class="table-controls">
        <input type="text" class="search-box" placeholder="Szukaj rezerwacji..." onkeyup="searchTable(this, 'rezerwacjeTable')">
      </div>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Dzień</th>
              <th>Godzina</th>
              <th>Klient</th>
              <th>Liczba osób</th>
              <th>Instruktor</th>
              <th>Stanowisko</th>
            </tr>
          </thead>
          <tbody id="rezerwacjeTable">
            <?php foreach ($reservations as $reservation): ?>
            <tr>
              <td><?php echo $reservation['ID_reservations']; ?></td>
              <td><?php echo date('d.m.Y', strtotime($reservation['date'])); ?></td>
              <td><?php echo date('H:i', strtotime($reservation['time'])); ?></td>
              <td><?php echo htmlspecialchars($reservation['client_name'] ?? 'Brak danych'); ?></td>
              <td><?php echo $reservation['participants']; ?></td>
              <td><?php echo $reservation['instructor'] ? 'Tak' : 'Nie'; ?></td>
              <td><?php echo $reservation['ID_station']; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Klienci -->
    <section>
      <h2 class="section-title">Klienci</h2>
      <div class="table-controls">
        <input type="text" class="search-box" placeholder="Szukaj klientów..." onkeyup="searchTable(this, 'klienciTable')">
      </div>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Imię</th>
              <th>Nazwisko</th>
              <th>Typ członkostwa</th>
            </tr>
          </thead>
          <tbody id="klienciTable">
            <?php foreach ($clients as $client): ?>
            <tr>
              <td><?php echo $client['ID_client']; ?></td>
              <td><?php echo htmlspecialchars($client['name']); ?></td>
              <td><?php echo htmlspecialchars($client['surname']); ?></td>
              <td><?php echo htmlspecialchars($client['membership_type']); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Członkostwo -->
    <section>
      <h2 class="section-title">Członkostwo</h2>
      <div class="table-controls">
        <input type="text" class="search-box" placeholder="Szukaj członkostw..." onkeyup="searchTable(this, 'czlonkowieTable')">
      </div>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Typ</th>
              <th>Klient</th>
              <th>Data rozpoczęcia</th>
              <th>Data kolejnej opłaty</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="czlonkowieTable">
            <?php foreach ($memberships as $membership): ?>
            <tr>
              <td><?php echo $membership['ID_membership']; ?></td>
              <td><?php echo htmlspecialchars($membership['type']); ?></td>
              <td><?php echo htmlspecialchars($membership['client_name'] ?? 'Nie przypisano'); ?></td>
              <td><?php echo date('d.m.Y', strtotime($membership['activation_date'])); ?></td>
              <td><?php echo date('d.m.Y', strtotime($membership['expiration_date'])); ?></td>
              <td>
                <span class="<?php echo (strtotime($membership['expiration_date']) >= time()) ? 'status-active' : 'status-inactive'; ?>">
                  <?php echo (strtotime($membership['expiration_date']) >= time()) ? 'Aktywne' : 'Wygasłe'; ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Transakcje -->
    <section>
      <h2 class="section-title">Transakcje</h2>
      <div class="table-controls">
        <input type="text" class="search-box" placeholder="Szukaj transakcji..." onkeyup="searchTable(this, 'transakcjeTable')">
      </div>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Typ amunicji</th>
              <th>Ilość</th>
              <th>Klient</th>
            </tr>
          </thead>
          <tbody id="transakcjeTable">
            <?php foreach (array_slice($transactions, 0, 100) as $transaction): ?>
            <tr>
              <td><?php echo $transaction['ID_transaction']; ?></td>
              <td><?php echo htmlspecialchars($transaction['ammo_name'] ?? 'Brak danych'); ?></td>
              <td><?php echo $transaction['count']; ?></td>
              <td><?php echo htmlspecialchars($transaction['client_name'] ?? 'Brak danych'); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php if (count($transactions) > 100): ?>
        <p style="text-align: center; color: white; margin-top: 10px;">
          Pokazano pierwsze 100 transakcji z <?php echo count($transactions); ?>
        </p>
        <?php endif; ?>
      </div>
    </section>

    <!-- Stanowiska strzeleckie -->
    <section>
      <h2 class="section-title">Stanowiska strzeleckie</h2>
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Maksymalna ilość osób</th>
            </tr>
          </thead>
          <tbody id="stanowiskaTable">
            <?php foreach ($stations as $station): ?>
            <tr>
              <td><?php echo $station['ID_station']; ?></td>
              <td><?php echo $station['slots']; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <script>
    function searchTable(input, tableId) {
      const filter = input.value.toLowerCase();
      const table = document.getElementById(tableId);
      const rows = table.getElementsByTagName('tr');
      
      for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < cells.length; j++) {
          if (cells[j].textContent.toLowerCase().includes(filter)) {
            found = true;
            break;
          }
        }
        
        rows[i].style.display = found ? '' : 'none';
      }
    }

    function toggleGunAvailability(gunId) {
      if (confirm('Czy na pewno chcesz zmienić status dostępności tej broni?')) {
        const formData = new FormData();
        formData.append('action', 'toggle_gun_availability');
        formData.append('gun_id', gunId);
        
        fetch('', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Błąd podczas zmiany statusu');
          }
        });
      }
    }

    function toggleGunInUse(gunId) {
      if (confirm('Czy na pewno chcesz zmienić status użycia tej broni?')) {
        const formData = new FormData();
        formData.append('action', 'toggle_gun_in_use');
        formData.append('gun_id', gunId);
        
        fetch('', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Błąd podczas zmiany statusu');
          }
        });
      }
    }

    function updateAmmoAmount(ammoId, newAmount) {
      if (newAmount === null || newAmount === '') return;
      
      const formData = new FormData();
      formData.append('action', 'update_ammo_amount');
      formData.append('ammo_id', ammoId);
      formData.append('new_amount', newAmount);
      
      fetch('', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Ilość amunicji została zaktualizowana');
          location.reload();
        } else {
          alert('Błąd podczas aktualizacji');
        }
      });
    }

    // Auto-refresh every 30 seconds
    setInterval(() => {
      const currentTime = new Date();
      console.log('Panel last updated:', currentTime.toLocaleTimeString());
    }, 30000);
  </script>

</body>
</html>