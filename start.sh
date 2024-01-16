# install composer dependencies
composer install

# rename .env.example .env
mv .env.example .env

# install node dependencies
npm install

# generate application key
php artisan key:generate

# drop & create tables
php artisan migrate:fresh

# start vite dev-server
npm run dev
