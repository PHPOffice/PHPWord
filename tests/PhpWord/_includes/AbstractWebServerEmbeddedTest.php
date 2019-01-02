<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

use Symfony\Component\Process\Process;

abstract class AbstractWebServerEmbeddedTest extends \PHPUnit\Framework\TestCase
{
    private static $httpServer;

    public static function setUpBeforeClass()
    {
        if (self::isBuiltinServerSupported()) {
            self::$httpServer = new Process('php -S localhost:8080 -t tests/PhpWord/_files');
            self::$httpServer->start();
            while (!self::$httpServer->isRunning()) {
                usleep(1000);
            }
        }
    }

    public static function tearDownAfterClass()
    {
        if (self::isBuiltinServerSupported()) {
            self::$httpServer->stop();
        }
    }

    protected static function getBaseUrl()
    {
        return 'http://localhost:8080';
    }

    protected static function getRemoteImageUrl()
    {
        if (self::$httpServer) {
            return self::getBaseUrl() . '/images/new-php-logo.png';
        }

        return 'http://php.net/images/logos/new-php-logo.png';
    }

    protected static function getRemoteGifImageUrl()
    {
        if (self::$httpServer) {
            return self::getBaseUrl() . '/images/mario.gif';
        }

        return 'http://php.net/images/logos/php-med-trans-light.gif';
    }

    protected static function getRemoteBmpImageUrl()
    {
        if (self::$httpServer) {
            return self::getBaseUrl() . '/images/duke_nukem.bmp';
        }

        return 'https://samples.libav.org/image-samples/RACECAR.BMP';
    }

    private static function isBuiltinServerSupported()
    {
        return version_compare(PHP_VERSION, '5.4.0', '>=');
    }
}
