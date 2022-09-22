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

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\TOC;
use PhpOffice\PhpWord\PhpWord;

/**
 * Test class for PhpOffice\PhpWord\Element\TOC.
 *
 * @runTestsInSeparateProcesses
 */
class TOCTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Construct with font and TOC style in array format.
     */
    public function testConstructWithStyleArray(): void
    {
        $expected = [
            'position' => 9062,
            'leader' => \PhpOffice\PhpWord\Style\Tab::TAB_LEADER_DOT,
            'indent' => 200,
        ];
        $object = new TOC(['size' => 11], ['position' => $expected['position']]);
        $tocStyle = $object->getStyleTOC();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\TOC', $tocStyle);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $object->getStyleFont());

        foreach ($expected as $key => $value) {
            $method = "get{$key}";
            self::assertEquals($value, $tocStyle->$method());
        }
    }

    /**
     * Construct with named font style.
     */
    public function testConstructWithStyleName(): void
    {
        $object = new TOC('Font Style');
        // $tocStyle = $object->getStyleTOC();

        self::assertEquals('Font Style', $object->getStyleFont());
    }

    /**
     * Test when no PHPWord object is assigned:.
     */
    public function testNoPhpWord(): void
    {
        $object = new TOC();

        self::assertEmpty($object->getTitles());
        self::assertNull($object->getPhpWord());
    }

    /**
     * Set/get minDepth and maxDepth.
     */
    public function testSetGetMinMaxDepth(): void
    {
        $titles = [
            'Heading 1' => 1,
            'Heading 2' => 2,
            'Heading 3' => 3,
            'Heading 4' => 4,
        ];

        $phpWord = new PhpWord();
        foreach ($titles as $text => $depth) {
            $phpWord->addTitle(new Title($text, $depth));
        }
        $toc = new TOC();
        $toc->setPhpWord($phpWord);
        self::assertEquals(1, $toc->getMinDepth());
        self::assertEquals(9, $toc->getMaxDepth());

        $toc->setMinDepth(2);
        $toc->setMaxDepth(3);
        $toc->getTitles();

        self::assertEquals(2, $toc->getMinDepth());
        self::assertEquals(3, $toc->getMaxDepth());
    }
}
