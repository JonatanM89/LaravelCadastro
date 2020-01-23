# LaravelCadastro
Cadastro básico de cliente em Laravel

# Install & run (Windows)
 
 - cd appCad

 - composer install

 - php artisan make:db_create

 - editar arquivo .env.example (criar novo de preferencia) com db_cadastro para DB e usuário e senha do MYSQL

 - php artisan migrate:install 
 
 - php artisan migrate

 - php artisan key:generate
 
 - php artisan storage:link

 - php artisan serve

# Tests

 - vendor/bin/phpunit

 # Send Email
 Set in .env file
 MAIL_GOOGLE
 PASS_GOOGLE