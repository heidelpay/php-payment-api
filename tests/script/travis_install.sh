#!/usr/bin/env bash

set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

PHPVER=$(phpenv version-name)
INSTALL_COMMAND="composer install --no-interaction --prefer-dist"
UPDATE_COMMAND="composer update --no-interaction --prefer-source"

if [ "$deps" == "no" ]; then

    # if PHP version is 5, use travis_wait for up to 20 minutes waiting time.
    if [[ "$PHPVER" =~ ^5\.[0-9]+$ ]]; then
        travis_wait ${INSTALL_COMMAND}
    fi

    if [[ "$PHPVER" =~ ^7\.[0-9]+$ ]]; then
        ${INSTALL_COMMAND}
    fi
fi

if [ "$deps" == "high" ]; then
    ${UPDATE_COMMAND}
fi
