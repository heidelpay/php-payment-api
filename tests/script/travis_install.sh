#!/usr/bin/env bash

set -e
trap '>&2 echo Error: Command \`$BASH_COMMAND\` on line $LINENO failed with exit code $?' ERR

PHPVER=$(phpenv version-name)
INSTALL_COMMAND="composer install --no-interaction --prefer-dist --verbose"
UPDATE_COMMAND="composer update --no-interaction --prefer-source --verbose"

if [ "$deps" == "no" ]; then
  echo ${INSTALL_COMMAND}
  ${INSTALL_COMMAND}
fi

if [ "$deps" == "high" ]; then
  echo ${UPDATE_COMMAND}
  ${UPDATE_COMMAND}
fi
