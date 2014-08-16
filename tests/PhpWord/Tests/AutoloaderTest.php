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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Autoloader;

/**
 * Test class for PhpOffice\PhpWord\Autoloader
 *
 * @runTestsInSeparateProcesses
 */
class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Register
     */
    public function testRegister()
    {
        Autoloader::register();
        $this->assertContains(
            array('PhpOffice\\PhpWord\\Autoloader', 'autoload'),
            spl_autoload_functions()
        );
    }

    /**
     * Autoload
     */
    public function testAutoload()
    {
        $declaredCount = count(get_declared_classes());
        Autoloader::autoload('Foo');
        $this->assertCount(
            $declaredCount,
            get_declared_classes(),
            'PhpOffice\\PhpWord\\Autoloader::autoload() is trying to load ' .
            'classes outside of the PhpOffice\\PhpWord namespace'
        );
        // TODO change this class to the main PhpWord class when it is namespaced
        Autoloader::autoload('PhpOffice\\PhpWord\\Exception\\InvalidStyleException');
        $this->assertTrue(
            in_array('PhpOffice\\PhpWord\\Exception\\InvalidStyleException', get_declared_classes()),
            'PhpOffice\\PhpWord\\Autoloader::autoload() failed to autoload the ' .
            'PhpOffice\\PhpWord\\Exception\\InvalidStyleException class'
        );
    }
}
