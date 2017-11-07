#!/bin/bash

## PHP_CodeSniffer
./vendor/bin/phpcs src/ tests/ --standard=PSR2 -n --ignore=src/PhpWord/Shared/PCLZip

## PHP-CS-Fixer
./vendor/bin/php-cs-fixer fix --diff --verbose --dry-run

## PHP Mess Detector
./vendor/bin/phpmd src/,tests/ text ./phpmd.xml.dist --exclude pclzip.lib.php

## PHPUnit
./vendor/bin/phpunit -c ./ --no-coverage

