cd /var/www/nanoyaki.space
php8.3 bin/console asset-map:compile
php8.3 bin/console cache:clear
php8.3 bin/console assets:install
php8.3 bin/console importmap:install