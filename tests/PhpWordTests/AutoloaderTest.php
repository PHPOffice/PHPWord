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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests;

use PhpOffice\PhpWord\Autoloader;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Autoloader.
 */
class AutoloaderTest extends TestCase
{
    public function testRegister(): void
    {
        Autoloader::register();
        $splFunctions = spl_autoload_functions();
        // @phpstan-ignore-next-line spl_autoload_functions return false < PHP 8.0
        if ($splFunctions === false) {
            $splFunctions = [];
        }

        self::assertContains(
            ['PhpOffice\\PhpWord\\Autoloader', 'autoload'],
            $splFunctions
        );
    }

    public function testAutoload(): void
    {
        $declared = get_declared_classes();
        $declaredCount = count($declared);
        Autoloader::autoload('Foo');
        self::assertCount(
            $declaredCount,
            get_declared_classes(),
            'PhpOffice\\PhpWord\\Autoloader::autoload() is trying to load ' .
            'classes outside of the PhpOffice\\PhpWord namespace'
        );
    }
}
