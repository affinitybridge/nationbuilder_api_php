#!/bin/bash
BASEDIR=`php -r "echo dirname(realpath('$0'));"`
OLDDIR=`pwd`
cd $BASEDIR

./vendor/bin/phpunit ./tests/

# ./vendor/bin/phpunit --verbose --debug ./tests/
#./vendor/bin/phpunit --verbose --debug ./tests/PeopleTest

# Go back where we came from.
cd $OLDDIR
