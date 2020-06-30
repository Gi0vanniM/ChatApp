# ChatApp
School group project

### Laravel
\
Om de server op te starten, zorg je ervoor dat je terminal in je juist locatie staat. In mijn geval is dat 
`C:\xampp\htdocs\laravelapps\ChatApp`. Wanneer je in de folder ChatApp zit moet je het volgende command uitvoeren.
```bash
php artisan serve
```
\
Om de dependencies te installeren voer je het volgende command uit.
```bash
composer install
```
\
Het default adres is [http://127.0.0.1:8000](http://127.0.0.1:8000).
\
\
Nu moet je de `.env` aanpassen naar jou instellingen (bijv. database connectie). Als je nog geen `.env` bestand in de `ChatApp` map hebt dan kopieëer je `.env.example` en hernoem je het bestand naar `.env`. Hierin pas je de instelling aan, voornamelijk je database connectie.
\
\
Om de databases op te zetten voer je het volgende command uit.
```bash
php artisan migrate
```
