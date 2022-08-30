## Installation

- git clone https://git.kolsanovafit.ru/kolsanovafit/back-users/
- cd back-users
- composer install
- cp .env.example .env
- create empty DB and set DB settings in .env
- php artisan key:generate
- php artisan migrate
- php artisan passport:install
