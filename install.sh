# apt install immediately, don't ask for confirmation
echo 'APT::Get::Assume-Yes "true";' | sudo tee -a /etc/apt/apt.conf.d/90forceyes

# put private key in ~/.ssh/id_rsa for github
echo -n "-----BEGIN OPENSSH PRIVATE KEY-----
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
N+ipfjygvcbkVhAAAACXVzZXJAdXNlcgE=
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
sudo chown -R www-data storage
sudo chown -R www-data bootstrap/cache

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

# certbot
apt install certbot 
sudo apt install python-certbot-nginx

# using just for ignoring platform reqs because of "alc/sitemap-crawler"
just composer install

npm install -g yarn
yarn
yarn build
