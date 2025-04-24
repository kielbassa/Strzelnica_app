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

dostęp do bazy i wpisywanie danych zachodzi poprzez `bin/rails console`

#### tworzenie użytkownika poprzez konsolę:
<code>User.create! email_address: "you@example.org", password: "s3cr3t", password_confirmation: "s3cr3t" </code>