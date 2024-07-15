if [[ -z $PROJECT_DIR ]]
then
  PROJECT_DIR="dev.nanoyaki.space"
fi

cd /var/www/"$PROJECT_DIR" || exit 1
sudo chown -R caddy:caddy .
sudo -u caddy rm -r var/*
sudo -u caddy php8.3 bin/console asset-map:compile
sudo -u caddy php8.3 bin/console cache:clear
sudo -u caddy php8.3 bin/console assets:install
sudo -u caddy php8.3 bin/console importmap:install