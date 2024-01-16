@echo off

REM Install composer dependencies
composer install

REM Rename .env.example to .env
ren .env.example .env

REM Install node dependencies
npm install

REM Generate application key
php artisan key:generate

REM Drop and create tables
php artisan migrate:fresh

REM Start Vite dev-server
npm run dev
