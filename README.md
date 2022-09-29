## Installation

- git clone https://git.kolsanovafit.ru/kolsanovafit/back-users/
- cd back-users
- composer install
- cp .env.example .env
- chmod -R 777 storage
- create empty DB and set DB settings in .env
- php artisan key:generate
- php artisan migrate
- php artisan passport:install

- php artisan db:seed --class=RolesTableSeeder
- php artisan l5-swagger:generate (docs URL: https://back.test.kolsanovafit.ru/api/documentation)

#### Run import from external CRM

- settings queue to channel ```php artisan queue --queue=crmimport```
- run or add cron job for command ```php artisan kfit:import:crm``` for example runs every day, also here you can use ```--except=clients,products``` params to except methods used only for testing
