#!/bin/sh

composer install

bin/console doctrine:migrations:migrate --no-interaction

if [[ "$(bin/console dbal:run-sql 'select id from user limit 1' --no-ansi | grep 'empty result set')" != "" ]];
then
    bin/console doctrine:fixtures:load --no-interaction
fi

if [[ "$APP_ENV" == "prod" ]]; then
    rr serve -c .rr.yaml
else
    rr serve -c .rr.dev.yaml
fi
