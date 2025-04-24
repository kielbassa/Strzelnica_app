# README

## Instalacja Ruby ond Rails 
https://guides.rubyonrails.org/install_ruby_on_rails.html#install-ruby-on-windows

Na systemie Windows użyć **Windows Subsystem for Linux (WSL)**

## Wstępna konfiguracja i uruchomienie aplikacji
Zainstaluj potrzebne dependencje komendą `bundle update`

Aby włączyć serwer aplikacji, będąc w katalogu **/Strzelnica_app** uruchom serwer komendą `bin/rails server`

Otwórz drugie okno terminala WSL, po czym w katologu projektu skonfiguruj bazę danych komendą `bin/rails db:migrate`

## Otwarcie klienta webowego
W dowolnej przeglądarcę wpisz adres http://127.0.0.1:3000/