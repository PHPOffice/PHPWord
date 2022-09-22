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

use Exception;
use PhpOffice\PhpWord\Element\Header;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Section as SectionStyle;

/**
 * @covers \PhpOffice\PhpWord\Element\Section
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Section
 *
 * @runTestsInSeparateProcesses
 */
class SectionTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructorWithDefaultStyle(): void
    {
        $section = new Section(0);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Section', $section->getStyle());
    }

    public function testConstructorWithArrayStyle(): void
    {
        $section = new Section(0, ['orientation' => 'landscape']);
        $style = $section->getStyle();
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Section', $style);
        self::assertEquals('landscape', $style->getOrientation());
    }

    public function testConstructorWithObjectStyle(): void
    {
        $style = new SectionStyle();
        $section = new Section(0, $style);
        self::assertSame($style, $section->getStyle());
    }

    /**
     * @covers ::setStyle
     */
    public function testSetStyle(): void
    {
        $expected = 'landscape';
        $object = new Section(0);
        $object->setStyle(['orientation' => $expected, 'foo' => null]);
        self::assertEquals($expected, $object->getStyle()->getOrientation());
    }

    /**
     * @coversNothing
     */
    public function testAddElements(): void
    {
        $objectSource = __DIR__ . '/../_files/documents/reader.docx';
        $imageSource = __DIR__ . '/../_files/images/PhpWord.png';

        $section = new Section(0);
        $section->setPhpWord(new PhpWord());
        $section->addText(utf8_decode('ä'));
        $section->addLink(utf8_decode('http://äää.com'), utf8_decode('ä'));
        $section->addTextBreak();
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem(utf8_decode('ä'));
        $section->addObject($objectSource);
        $section->addImage($imageSource);
        $section->addTitle(utf8_decode('ä'), 1);
        $section->addTextRun();
        $section->addFootnote();
        $section->addCheckBox(utf8_decode('chkä'), utf8_decode('Contentä'));
        $section->addTOC();

        $elementCollection = $section->getElements();
        $elementTypes = [
            'Text',
            'Link',
            'TextBreak',
            'PageBreak',
            'Table',
            'ListItem',
            'OLEObject',
            'Image',
            'Title',
            'TextRun',
            'Footnote',
            'CheckBox',
            'TOC',
        ];
        $elmCount = 0;
        foreach ($elementTypes as $elementType) {
            self::assertInstanceOf("PhpOffice\\PhpWord\\Element\\{$elementType}", $elementCollection[$elmCount]);
            ++$elmCount;
        }
    }

    /**
     * @coversNothing
     */
    public function testAddObjectException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidObjectException::class);
        $source = __DIR__ . '/_files/xsl/passthrough.xsl';
        $section = new Section(0);
        $section->addObject($source);
    }

    /**
     * Add title with predefined style.
     *
     * @coversNothing
     */
    public function testAddTitleWithStyle(): void
    {
        Style::addTitleStyle(1, ['size' => 14]);
        $section = new Section(0);
        $section->setPhpWord(new PhpWord());
        $section->addTitle('Test', 1);
        $elementCollection = $section->getElements();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Title', $elementCollection[0]);
    }

    /**
     * @covers ::addFooter
     * @covers ::addHeader
     * @covers ::hasDifferentFirstPage
     */
    public function testAddHeaderFooter(): void
    {
        $object = new Section(0);
        $elements = ['Header', 'Footer'];

        foreach ($elements as $element) {
            $method = "add{$element}";
            self::assertInstanceOf("PhpOffice\\PhpWord\\Element\\{$element}", $object->$method());
        }
        self::assertFalse($object->hasDifferentFirstPage());
    }

    /**
     * @covers ::addHeader
     * @covers ::hasDifferentFirstPage
     */
    public function testHasDifferentFirstPageFooter(): void
    {
        $object = new Section(1);
        $object->addFooter(Header::FIRST);
        self::assertTrue($object->hasDifferentFirstPage());
    }

    /**
     * @covers ::addHeader
     * @covers ::hasDifferentFirstPage
     */
    public function testHasDifferentFirstPage(): void
    {
        $object = new Section(1);
        $header = $object->addHeader();
        $header->setType(Header::FIRST);
        self::assertTrue($object->hasDifferentFirstPage());
    }

    /**
     * @covers ::addHeader
     */
    public function testAddHeaderException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid header/footer type.');
        $object = new Section(1);
        $object->addHeader('ODD');
    }

    /**
     * @covers \PhpOffice\PhpWord\Element\AbstractContainer::removeElement
     */
    public function testRemoveElementByIndex(): void
    {
        $section = new Section(1);
        $section->addText('firstText');
        $section->addText('secondText');

        self::assertEquals(2, $section->countElements());
        $section->removeElement(1);

        self::assertEquals(1, $section->countElements());
    }

    /**
     * @covers \PhpOffice\PhpWord\Element\AbstractContainer::removeElement
     */
    public function testRemoveElementByElement(): void
    {
        $section = new Section(1);
        $firstText = $section->addText('firstText');
        $secondText = $section->addText('secondText');

        self::assertEquals(2, $section->countElements());
        $section->removeElement($firstText);

        self::assertEquals(1, $section->countElements());
        self::assertEquals($secondText->getElementId(), $section->getElement(1)->getElementId());
    }
}
