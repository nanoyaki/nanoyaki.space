rsync --exclude=.env -r --info=progress2 --info=name0 . ubuntu@nanoyaki.space:/var/www/nanoyaki.space/
cat ./remote-deploy-script.sh | ssh otherhost