composer install
yarn dev &
php artisan migrate
php artisan optimize:clear
while true; do
	php artisan queue:work database --timeout=999999
done;
