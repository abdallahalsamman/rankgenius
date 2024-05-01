# REDIS
lsb-release curl gpg
curl -fsSL https://packages.redis.io/gpg | sudo gpg --dearmor -o /usr/share/keyrings/redis-archive-keyring.gpg

echo "deb [signed-by=/usr/share/keyrings/redis-archive-keyring.gpg] https://packages.redis.io/deb $(lsb_release -cs) main" | sudo tee /etc/apt/sources.list.d/redis.list

sudo apt-get update
sudo apt-get install redis

# Supervisor
sudo apt install supervisor
echo -n "[program:laravel-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/$USER/dev/rankgenius/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/home/$USER/dev/rankgenius/storage/logs/queue-worker.log" | sudo tee /etc/supervisor/conf.d/laravel-queue-worker.conf
sudo supervisorctl reread
sudo supervisorctl update

# install just
wget -qO - 'https://proget.makedeb.org/debian-feeds/prebuilt-mpr.pub' | gpg --dearmor | sudo tee /usr/share/keyrings/prebuilt-mpr-archive-keyring.gpg 1> /dev/null
echo "deb [arch=all,$(dpkg --print-architecture) signed-by=/usr/share/keyrings/prebuilt-mpr-archive-keyring.gpg] https://proget.makedeb.org prebuilt-mpr $(lsb_release -cs)" | sudo tee /etc/apt/sources.list.d/prebuilt-mpr.list
sudo apt update
sudo apt install just

# install postgres
sudo apt install postgresql postgresql-contrib
sudo systemctl start postgresql.service
sudo -u postgres createdb rankgeniusdb
sudo -u postgres psql "ALTER ROLE postgres WITH PASSWORD 'postgres'";

# PHP, Composer
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get install -y php8.2-{curl,cli,common,fpm,mysql,zip,gd,mbstring,curl,xml,bcmath,pgsql,redis}
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
sudo mkdir -p /etc/apt/keyrings

// Node, NPM, NVM
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

# NGINX
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
