docker-compose up -d

docker-compose exec php-fpm composer install

docker-compose exec php-fpm chmod 777 -R storage/
docker-compose exec php-fpm chmod 777 -R pg_data/

docker-compose exec php-fpm cp .env.example .env
docker-compose exec php-fpm php artisan key:generate
docker-compose exec php-fpm php artisan migrate
docker-compose exec php-fpm php artisan config:cache

npm i
npm run prod
