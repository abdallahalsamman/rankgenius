sudo chmod -R o+w storage/ &
sudo chmod -R 775 storage/ &
php artisan optimize:clear &
php artisan migrate &
yarn dev