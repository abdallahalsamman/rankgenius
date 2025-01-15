# apt install immediately, don't ask for confirmation
echo 'APT::Get::Assume-Yes "true";' | sudo tee -a /etc/apt/apt.conf.d/90forceyes

# put private key in ~/.ssh/id_rsa for github
echo -n "-----BEGIN OPENSSH PRIVATE KEY-----
-----END OPENSSH PRIVATE KEY-----
" | sudo tee ~/.ssh/id_rsa
sudo chmod 0400 ~/.ssh/id_rsa
sudo apt install -y git
git config --global user.name "abdallah alsamman"
git config --global user.email "sammanabdallah@gmail.com"

# NGINX
sudo apt install -y nginx
echo -n "server {
    listen 80;
    listen [::]:80;

    # server_name needed for let's encrypt certbot to work
    server_name seoyousoon.com;
    
    root /var/www/html/rankgenius/public/;
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
sudo systemctl restart nginx

cd /var/www/html
git clone git@github.com:abdallahalsamman/rankgenius.git
cd rankgenius
git config core.fileMode false

# Supervisor
sudo apt install -y supervisor
echo -n "[program:laravel-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/rankgenius/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/html/rankgenius/storage/logs/queue-worker.log" | sudo tee /etc/supervisor/conf.d/laravel-queue-worker.conf
sudo supervisorctl reread
sudo supervisorctl update

# install just
wget -qO - 'https://proget.makedeb.org/debian-feeds/prebuilt-mpr.pub' | gpg --dearmor | sudo tee /usr/share/keyrings/prebuilt-mpr-archive-keyring.gpg 1> /dev/null
echo "deb [arch=all,$(dpkg --print-architecture) signed-by=/usr/share/keyrings/prebuilt-mpr-archive-keyring.gpg] https://proget.makedeb.org prebuilt-mpr $(lsb_release -cs)" | sudo tee /etc/apt/sources.list.d/prebuilt-mpr.list
sudo apt update
sudo apt install just

# install postgres
sudo apt install -y postgresql-common
sudo /usr/share/postgresql-common/pgdg/apt.postgresql.org.sh
sudo apt install postgresql-16 postgresql-contrib
sudo systemctl start postgresql.service
sudo -u postgres createdb rankgeniusdb
sudo -u postgres psql -c "ALTER ROLE postgres WITH PASSWORD 'postgres'";

# install pgvector
postgres_version=$(psql --version | grep -oP 'PostgreSQL\) \K\d+' | head -n1)
sudo apt install postgresql-$postgres_version-pgvector
sudo -u postgres psql rankgeniusdb -c "CREATE EXTENSION vector;"

# PHP, Composer
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get install -y php8.2-{curl,cli,common,fpm,mysql,zip,gd,mbstring,curl,xml,bcmath,pgsql,redis}
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
sudo mkdir -p /etc/apt/keyrings
sudo sed -i 's/memory_limit = 128M/memory_limit = 512M/g' /etc/php/8.2/fpm/php.ini
sudo systemctl restart php8.2-fpm

# Node, NPM, NVM
curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash
source ~/.nvm/nvm.sh
nvm install --lts
nvm use --lts

# Yarn dev puts so many watchers so need this to increase the limit 
echo fs.inotify.max_user_watches=131070 | sudo tee -a /etc/sysctl.conf && sudo sysctl -p

sudo rm -rf vendor
sudo mkdir vendor
sudo chown -R www-data vendor
# using just for ignoring platform reqs because of "alc/sitemap-crawler"
sudo -u www-data just composer install

# Todo: remove yarn from prod
npm install -g yarn
rm -rf node_modules
yarn
yarn build

# only on prod env
sudo find /var/www/html/rankgenius/ -type d -exec chmod 755 {} \;
sudo find /var/www/html/rankgenius/ -type f -exec chmod 644 {} \;
sudo chown -R root:root /var/www/html/rankgenius/
sudo chown -R www-data /var/www/html/rankgenius/storage
sudo chown -R www-data /var/www/html/rankgenius/bootstrap/cache

# only on dev env
sudo chmod -R 777 /var/www/html/rankgenius/
