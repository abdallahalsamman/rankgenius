sudo chmod -R o+w storage/ &
sudo chmod -R 775 storage/ &
sudo chown $USER:www-data /tmp &
php artisan optimize:clear &
php artisan migrate &
yarn dev