if [[ -z $PROJECT_DIR ]]
then
  PROJECT_DIR="dev.nanoyaki.space"
fi

cd /var/www/"$PROJECT_DIR" || exit 1
sudo chown -R caddy:caddy .
sudo -u caddy -s
rm -r var/cache/*
rm -r public/assets/*
php8.3 bin/console asset-map:compile
php8.3 bin/console cache:clear
php8.3 bin/console assets:install
php8.3 bin/console importmap:install
exit