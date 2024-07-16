if [[ -z $PROJECT_DIR ]]
then
  PROJECT_DIR="nanoyaki.space"
fi

cd /var/www/"$PROJECT_DIR" || exit 1
sudo rm -r var/cache/*
sudo rm -r public/assets/*
sudo chown -R caddy:caddy .
sudo -u caddy -s \
php8.3 bin/console asset-map:compile && \
php8.3 bin/console cache:clear && \
php8.3 bin/console assets:install && \
php8.3 bin/console importmap:install && \
exit
