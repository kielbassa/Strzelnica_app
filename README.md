# README

## Instalacja Ruby ond Rails
https://guides.rubyonrails.org/install_ruby_on_rails.html#install-ruby-on-windows

Na systemie Windows użyj **Windows Subsystem for Linux (WSL)**

## Wstępna konfiguracja i uruchomienie aplikacji
Zainstaluj potrzebne dependencje komendą `bundle update`

Aby włączyć serwer aplikacji, będąc w katalogu **/Strzelnica_app** uruchom serwer komendą `bin/rails server`

Serwer zatrzymuje się skrótem klawiszowym `ctrl + c`

Otwórz drugie okno terminala WSL, po czym w katologu projektu skonfiguruj bazę danych komendą `bin/rails db:migrate`

## Otwarcie klienta webowego
W dowolnej przeglądarcę wpisz adres http://localhost:3000/

bezspośredni dostęp do bazy i wpisywanie danych zachodzi poprzez `bin/rails console`

## Eksport bazy
Wejdź do katalogu **/Strzelnica_app** i uruchom komendę `bin/rails db` aby otworzyć interfejs konsolowy bazy danych.
Wpisz `.output filename.txt`, następnie `.dump`, a następnie `.quit`
Zawartość bazy zostanie zapisana w pliku filename.txt

## Import do bazy
Usuń poprzednią bazę danych `/Strzelnica_app/storage/development.sqlite3`
Wejdź do katalogu **/Strzelnica_app** i uruchom komendę `bin/rails db` aby otworzyć interfejs konsolowy bazy danych.
Wpisz `.read filename.txt`, a następnie `.quit`
Zawartość bazy zostanie zaimportowana z pliku filename.txt

#### tworzenie użytkownika poprzez konsolę:
<code>User.create! email_address: "you@example.org", password: "s3cr3t", password_confirmation: "s3cr3t" </code>

https://guides.rubyonrails.org/getting_started.html
