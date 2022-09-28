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

#### Run test

- copy ```.env.testing.example``` to ```.env.testing``` add need settings like email, database connection
- run test with command ```php artisan test --env=testing```
