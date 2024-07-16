#!/usr/bin/env bash
PROJECT_DIR="nanoyaki.space"

ssh ubuntu@nanoyaki.space sudo chown -R ubuntu:caddy /var/www/"$PROJECT_DIR"/
rsync --exclude=.env* --exclude=var/ -r --info=progress2 --info=name0 . ubuntu@nanoyaki.space:/var/www/"$PROJECT_DIR"/
ssh ubuntu@nanoyaki.space < ./remote-deploy-script.sh