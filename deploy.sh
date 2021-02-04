#!/bin/bash

git pull
composer install
./yii migrate
./yii rbac/migrate
./permissions.sh
