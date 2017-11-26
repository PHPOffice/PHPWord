#!/bin/bash
echo "Running composer update"
composer update

## PHP_CodeSniffer
echo "Running CodeSniffer"
./vendor/bin/phpcs src/ tests/ --standard=PSR2 -n --ignore=src/PhpWord/Shared/PCLZip

## PHP-CS-Fixer
echo "Running CS Fixer"
./vendor/bin/php-cs-fixer fix --diff --verbose --dry-run

## PHP Mess Detector
echo "Running Mess Detector"
./vendor/bin/phpmd src/,tests/ text ./phpmd.xml.dist --exclude pclzip.lib.php

## PHPUnit
echo "Running PHPUnit"
./vendor/bin/phpunit -c ./

