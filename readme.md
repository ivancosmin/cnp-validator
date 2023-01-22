# CNP Validation and Person Information Extraction

This project provides a PHP class for validating Romanian CNP (personal identification number) and extracting information about the person such as birthdate and sex.

## Configuration
1. Run composer install
2. Make sure that you have mysql up and running on localhost (127.0.0.1). If not, change localhost in .env file.
2. Run php bin/console doctrine:database:create
3. Run php bin/console doctrine:migration:migrate
4. Run php bin/console doctrine:fixtures:load
5. Run symfony serve
6. Access in browser http://127.0.0.1:8000/

## Unit testing
Run ./vendor/bin/phpunit
