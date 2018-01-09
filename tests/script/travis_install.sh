#!/usr/bin/env bash

set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

PHPVER=$(phpenv version-name)
INSTALL_COMMAND="composer install --no-interaction --prefer-dist"
UPDATE_COMMAND="composer update --no-interaction --prefer-source"

if [ "$deps" == "no" ]; then
    ${INSTALL_COMMAND}
fi

if [ "$deps" == "high" ]; then
    ${UPDATE_COMMAND}
fi
