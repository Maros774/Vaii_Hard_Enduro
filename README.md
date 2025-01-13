NÁVOD NA INŠTALÁCIU A SPUSTENIE PROJEKTU

1. KLONOVANIE PROJEKTU
   V termináli alebo príkazovom riadku zadaj:
   
   git clone https://github.com/moj-github/moj-projekt.git
   cd moj-projekt

2. NASTAVENIE SÚBORU .env
   Skopíruj .env.example na .env:

   cp .env.example .env

   Otvor .env a uprav podľa potreby premenné (najmä pripojenie k databáze):

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=moja_db
   DB_USERNAME=moje_meno
   DB_PASSWORD=moje_heslo

3. INŠTALÁCIA BALÍČKOV
   Spusť:

   composer install
   npm install

   Potom vygeneruj front-end balíčky:

   npm run dev

   (alebo npm run build pre produkčné nasadenie)

4. MIGRÁCIE DATABÁZY
   Vytvor (alebo updatni) tabuľky v databáze:

   php artisan migrate

   Ak máš seedery s testovacími dátami, môžeš ich spustiť:

   php artisan db:seed

5. STORAGE LINK (ABY FUNGOVALI NAHRANÉ SÚBORY)
   php artisan storage:link

   Týmto sa vytvorí symbolický odkaz public/storage, ktorý smeruje na storage/app/public.

6. SPUSTENIE VÝVOJOVÉHO SERVERA
   php artisan serve

   Aplikácia bude bežať na adrese http://127.0.0.1:8000 alebo http://localhost:8000.

7. PRIHLASOVACIE ÚDAJE
   Ak si nastavil v seederoch nejakého testovacieho používateľa (napr. admin@example.com / heslo: admin), môžeš ho použiť.
   Ak nie, registruj sa na /register.

8. DOCKER (VOLITEĽNÉ)
   Ak projekt používa Docker, stačí:

   docker-compose up -d
   docker-compose exec php-fpm composer install
   docker-compose exec php-fpm npm install
   docker-compose exec php-fpm npm run dev
   docker-compose exec php-fpm php artisan migrate

   Prípadné testovacie dáta:

   docker-compose exec php-fpm php artisan db:seed

   Aplikácia bude na http://localhost:8000 (podľa nastavení docker-compose.yml).

