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

declare(strict_types=1);

namespace PhpOffice\PhpWordTests\Writer\ODText\Part;

use PhpOffice\Math\Element;
use PhpOffice\Math\Math;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\ODText\Part\Manifest.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\ODText\Part\Manifest
 */
class ManifestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWriteBasic(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertFalse($doc->elementExists(
            '/manifest:manifest/manifest:file-entry[@manifest:full-path="Formula0/content.xml"]',
            'META-INF/manifest.xml'
        ));
        self::assertFalse($doc->elementExists(
            '/manifest:manifest/manifest:file-entry[@manifest:full-path="Formula0/"]',
            'META-INF/manifest.xml'
        ));
    }

    public function testWriteFormula(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $math = new Math();
        $math->add(
            new Element\Fraction(
                new Element\Numeric(2),
                new Element\Identifier('π')
            )
        );
        $section->addFormula($math);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        self::assertTrue($doc->elementExists(
            '/manifest:manifest/manifest:file-entry[@manifest:full-path="Formula0/content.xml"]',
            'META-INF/manifest.xml'
        ));
        self::assertEquals('text/xml', $doc->getElementAttribute(
            '/manifest:manifest/manifest:file-entry[@manifest:full-path="Formula0/content.xml"]',
            'manifest:media-type',
            'META-INF/manifest.xml'
        ));

        self::assertTrue($doc->elementExists(
            '/manifest:manifest/manifest:file-entry[@manifest:full-path="Formula0/"]',
            'META-INF/manifest.xml'
        ));
        self::assertEquals('1.2', $doc->getElementAttribute(
            '/manifest:manifest/manifest:file-entry[@manifest:full-path="Formula0/"]',
            'manifest:version',
            'META-INF/manifest.xml'
        ));
        self::assertEquals('application/vnd.oasis.opendocument.formula', $doc->getElementAttribute(
            '/manifest:manifest/manifest:file-entry[@manifest:full-path="Formula0/"]',
            'manifest:media-type',
            'META-INF/manifest.xml'
        ));
    }
}
