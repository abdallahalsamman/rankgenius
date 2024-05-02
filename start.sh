# ignoring platform reqs because of "alc/sitemap-crawler"
just cmpsr install
sudo chmod -R o+w storage/
sudo chmod -R 775 storage/
yarn dev &
php artisan migrate
php artisan optimize:clear
while true; do
	php artisan queue:work database --timeout=999999
done;
