#!/usr/bin/env bash

set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

PHPVER=$(phpenv version-name)
INSTALL_COMMAND="composer install --no-interaction --prefer-dist"
UPDATE_COMMAND="composer update --no-interaction --prefer-source"

echo "PHPVER: ${PHPVER}"

if [ "$deps" == "no" ]; then

    # if PHP version is 5, use travis_wait for up to 20 minutes waiting time.
    if [[ "$PHPVER" =~ ^5\.[0-9]+$ ]]; then
        echo "Executing ${INSTALL_COMMAND} with travis_wait..."
        travis_wait ${INSTALL_COMMAND}
    fi

    if [[ "$PHPVER" =~ ^7\.[0-9]+$ ]]; then
        echo "Executing ${INSTALL_COMMAND} ..."
        ${INSTALL_COMMAND}
    fi
fi

if [ "$deps" == "high" ]; then
    echo "Executing ${UPDATE_COMMAND} ..."
    ${UPDATE_COMMAND}
fi
