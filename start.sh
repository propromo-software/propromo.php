# install composer dependencies
composer install --ignore-platform-req=ext-fileinfo

# rename .env.example .env
mv .env.example .env

# install node dependencies
npm install

# generate application key
php artisan key:generate

# drop & create tables
php artisan migrate:fresh

# start vite dev-server
npm run dev &

# start redis
docker-compose -d -f redis.yaml up
