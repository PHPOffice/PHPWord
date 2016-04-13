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
 * @copyright   2010-2015 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Writer\Word2007;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Header
 *
 * @runTestsInSeparateProcesses
 */
class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Write header
     */
    public function testWriteHeader()
    {
        $imageSrc = __DIR__ . '/../../../_files/images/PhpWord.png';

        $container = new \PhpOffice\PhpWord\Element\Header(1);
        $container->addText(htmlspecialchars('Test', ENT_COMPAT, 'UTF-8'));
        $container->addPreserveText(htmlspecialchars('', ENT_COMPAT, 'UTF-8'));
        $container->addTextBreak();
        $container->addTextRun();
        $container->addTable()->addRow()->addCell()->addText(htmlspecialchars('', ENT_COMPAT, 'UTF-8'));
        $container->addImage($imageSrc);
        $container->addWatermark($imageSrc);

        $writer = new Word2007();
        $writer->setUseDiskCaching(true);
        $object = new Header();
        $object->setParentWriter($writer);
        $object->setElement($container);
        $xml = simplexml_load_string($object->write());

        $this->assertInstanceOf('SimpleXMLElement', $xml);
    }
}
