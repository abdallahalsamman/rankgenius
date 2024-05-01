sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get install -y curl php8.2-cli php8.2-common php8.2-fpm php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath php8.2-sqlite3
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
sudo mkdir -p /etc/apt/keyrings

curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash
source ~/.nvm/nvm.sh
nvm install --lts
nvm use --lts

cat > ~/.bashrc <<- HERODOC
f="\$HOME/.nvm/nvm.sh"
if [ -r "\$f" ]; then
  . "\$f" &>'/dev/null'
  nvm use --lts &>'/dev/null'
fi
HERODOC

echo fs.inotify.max_user_watches=131070 | sudo tee -a /etc/sysctl.conf && sudo sysctl -p

sudo apt install -y nginx

echo -n "server {
    listen 80;
    listen [::]:80;
    root /home/user/dev/rankgenius/public/;
    add_header X-Frame-Options \"SAMEORIGIN\";
    add_header X-Content-Type-Options \"nosniff\";
    index index.php;
    charset utf-8;
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_page 404 /index.php;
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
    }
    location ~ /\.(?"'!'"well-known).* {
        deny all;
    }
}" | sudo tee /etc/nginx/sites-available/default

# ignoring platform reqs because of "alc/sitemap-crawler"
composer install --ignore-platform-reqs

npm install -g yarn
yarn
yarn build

sudo chown -R $USER:www-data ~
sudo chmod -R o+w storage/
sudo chmod -R 775 storage/
