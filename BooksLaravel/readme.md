PHP Version: 7.x
Installation:
composer install

ENV Vars:
Update .env with your enviroment

APP_URL=http://127.0.0.1:8000/
BASE_URL=http://127.0.0.1:8000/
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<Database Name>
DB_USERNAME=<User Name>
DB_PASSWORD=<Password>


For the ease of use please import mysql file from:
Project Folder/db

Migration:
php artisan migrate

To Serve:
From Project Directory Execute: php artisan serve

To Run Testcases:
From Project Directory Execute: ./vendor/bin/phpunit