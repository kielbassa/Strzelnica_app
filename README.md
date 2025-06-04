#  Strzelnica – Aplikacja do zarządzania strzelnicą

Projekt zawiera prostą aplikację webową służącą do zarządzania strzelnicą. System zawiera panel administratora, umożliwiający przegląd i zarządzanie danymi w bazie.

##  Struktura projektu

```
Strzelnica_app-main/
├── strzelnica.sql # Plik SQL do utworzenia bazy danych
├── admin/ # Panel administratora
│ ├── admin-panel-old.html # Interfejs administratora (old version)
│ └── panel.css # Stylizacja panelu
│ └── admin-panel.php # Interfejs administratora
│ └── make-admin.php # Panel do zmiany użytkownika w admina
├── api/ # Backendowe API (PHP)
│ ├── cancel.reservation.php # Anulowanie rezerwacji
│ ├── check_session.php # Sprawdzanie aktywnej sesji
│ ├── login.php # Logowanie użytkownika
│ ├── logout.php # Wylogowywanie użytkownika
│ ├── purchase_membership.php # Zakup członkostwa
│ ├── register.php # Rejestracja użytkownika
│ └── reserve.php # Rezerwacja strzelnicy
├── classes/ # Klasy PHP
│ ├── AdminPanel.php # Panel administratora
│ ├── Client.php # Obsługa klienta
│ ├── Session.php # Obsługa sesji
│ └── User.php # Obsługa użytkownika
├── config/ # Konfiguracja bazy danych
│ ├── add_client_integration.sql # Dodatkowe dane dla klienta
│ └── database.php # Połączenie z bazą danych
├── css/ # Style CSS
│ ├── auth-window.css # Stylizacja okien autoryzacji
│ ├── cart.css # Stylizacja koszyka
│ ├── kontakt.css # Stylizacja strony kontaktowej
│ ├── reservation.css # Stylizacja rezerwacji
│ ├── store.css # Stylizacja sklepu
│ ├── strzelnica.css # Stylizacja strony głównej
│ └── styles.css # Ogólny styl
├── includes/ # Pomocnicze funkcje PHP
│ └── auth_helper.php # Pomocnik do logiki autoryzacji
├── js/ # Skrypty JavaScript
│ ├── account.js # Obłusga konta
│ ├── auth.js # Obsługa logowania/rejestracji
│ ├── buttons.js # Obsługa przycisków
│ ├── cart.js # Obsługa koszyka
│ ├── date.js # Obsługa daty
│ ├── membership.js # Logika członkostwa
│ ├── navbar.js # Pasek nawigacji
│ ├── reservation.js # Obsługa rezerwacji
│ └── user_auth.js # Logika autoryzacji użytkownika
├── pages/ # Główne strony aplikacji
│ ├── cart.php # Koszyk
│ ├── index.php # Strona główna
│ ├── kontakt.html # Strona kontaktowa (statyczna)
│ ├── kontakt.php # Strona kontaktowa (dynamiczna)
│ ├── login.php # Logowanie
│ ├── my-account.php # Konto użytkownika
│ ├── register.php # Rejestracja
│ ├── reservation.php # Rezerwacja
│ ├── store.php # Sklep
│ └── strzelnica.php # Strona strzelnicy
├── test/ # Skrypty testowe
│ ├── auth_test.php # Test logowania
│ ├── create_test_user.php # Tworzenie testowego użytkownika
│ ├── debug_auth.php # Debugowanie autoryzacji
│ └── debug_test.php # Inne testy
├── zdj/ # Zasoby graficzne
└── strzelnica.sql # Baza danych projektu
```

##  Wymagania

- Serwer WWW (np. XAMPP, WAMP lub inny z obsługą PHP i MySQL)
- Przeglądarka internetowa
- MySQL lub MariaDB

##  Instalacja

1. **Sklonuj repozytorium lub pobierz ZIP**
   ```bash
   git clone https://github.com/twoja-nazwa/Strzelnica_app.git
   ```

2. **Wgraj pliki do katalogu serwera WWW**, np. `htdocs` w XAMPP.

3. **Import bazy danych**
   - Otwórz phpMyAdmin
   - Utwórz nową bazę danych, np. `strzelnica`
   - Zaimportuj plik `strzelnica.sql`

4. **Uruchom aplikację**
   - Wejdź na adres `http://localhost/Strzelnica_app-main/admin/admin-panel.html`
