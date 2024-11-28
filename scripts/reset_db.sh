#!/bin/bash

OPT_ENV="--env=dev"

##########################################
# Drop and recreate
php bin/console doctrine:database:drop --if-exists --force ${OPT_ENV}
php bin/console doctrine:database:create ${OPT_ENV}

##########################################
# Update schema
php bin/console doctrine:schema:update --force --complete ${OPT_ENV}
