@echo off

rem install composer dependencies
call composer install

rem rename .env.example .env
copy .env.example .env

rem generate application key
php artisan key:generate

rem start redis
docker-compose -f redis.yml up -d

rem start postgres
docker-compose -f postgres.yml up -d

rem drop create tables
php artisan migrate:fresh

rem install node dependencies
call npm install

rem start vite dev-server
start npm run dev

rem start laravel dev-server
start php artisan serve
