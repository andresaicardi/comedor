Una vez clonado el repositorio seguir los siguientes pasos:

En la consola de comando ejecutar los siguientes comandos:
1 -  composer install
2 -  En este caso debemos hacer una copia de .env.example a .env y configurar el archivo
3 -  php artisan cache:clear
4 -  composer dump-autoload
5 -  php artisan key:generate
6 -  php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"