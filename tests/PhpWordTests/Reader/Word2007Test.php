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

namespace PhpOffice\PhpWordTests\Reader;

use DateTime;
use PhpOffice\Math\Element;
use PhpOffice\PhpWord\Element\Comment;
use PhpOffice\PhpWord\Element\Formula;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\Word2007;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Reader\Word2007
 *
 * @runTestsInSeparateProcesses
 */
class Word2007Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Tear down after each test.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test canRead() method.
     */
    public function testCanRead(): void
    {
        $object = new Word2007();
        self::assertTrue($object->canRead(dirname(__DIR__, 1) . '/_files/documents/reader.docx'));
    }

    /**
     * Can read exception.
     */
    public function testCanReadFailed(): void
    {
        $object = new Word2007();
        self::assertFalse($object->canRead(dirname(__DIR__, 1) . '/_files/documents/foo.docx'));
    }

    /**
     * Load.
     */
    public function testLoad(): void
    {
        $phpWord = IOFactory::load(dirname(__DIR__, 1) . '/_files/documents/reader.docx', 'Word2007');

        self::assertInstanceOf(PhpWord::class, $phpWord);
        self::assertTrue($phpWord->getSettings()->hasDoNotTrackMoves());
        self::assertFalse($phpWord->getSettings()->hasDoNotTrackFormatting());
        self::assertEquals(100, $phpWord->getSettings()->getZoom());

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertEquals('0', $doc->getElementAttribute('/w:document/w:body/w:p/w:r[w:t/node()="italics"]/w:rPr/w:b', 'w:val'));
    }

    public function testLoadStyles(): void
    {
        $phpWord = IOFactory::load(dirname(__DIR__, 1) . '/_files/documents/reader-styles.docx', 'Word2007');

        self::assertInstanceOf(PhpWord::class, $phpWord);

        $section2 = $phpWord->getSection(2);
        self::assertInstanceOf(Section::class, $section2);

        $element2_31 = $section2->getElement(31);
        self::assertInstanceOf(TextRun::class, $element2_31);
        self::assertEquals('This is a paragraph with border differents', $element2_31->getText());

        /** @var Paragraph $element2_31_pStyle */
        $element2_31_pStyle = $element2_31->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $element2_31_pStyle);

        // Top
        self::assertEquals('FFFF00', $element2_31_pStyle->getBorderTopColor());
        self::assertEquals('10', $element2_31_pStyle->getBorderTopSize());
        self::assertEquals('dotted', $element2_31_pStyle->getBorderTopStyle());
        // Right
        self::assertEquals('00A933', $element2_31_pStyle->getBorderRightColor());
        self::assertEquals('4', $element2_31_pStyle->getBorderRightSize());
        self::assertEquals('dashed', $element2_31_pStyle->getBorderRightStyle());
        // Bottom
        self::assertEquals('F10D0C', $element2_31_pStyle->getBorderBottomColor());
        self::assertEquals('8', $element2_31_pStyle->getBorderBottomSize());
        self::assertEquals('dashSmallGap', $element2_31_pStyle->getBorderBottomStyle());
        // Left
        self::assertEquals('3465A4', $element2_31_pStyle->getBorderLeftColor());
        self::assertEquals('8', $element2_31_pStyle->getBorderLeftSize());
        self::assertEquals('dashed', $element2_31_pStyle->getBorderLeftStyle());
    }

    /**
     * Load a Word 2011 file.
     */
    public function testLoadWord2011(): void
    {
        $reader = new Word2007();
        $phpWord = $reader->load(dirname(__DIR__, 1) . '/_files/documents/reader-2011.docx');

        self::assertInstanceOf(PhpWord::class, $phpWord);

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[3]/w:r/w:pict/v:shape/v:imagedata'));
    }

    /**
     * Load a Word without/withoutImages.
     *
     * @dataProvider providerSettingsImageLoading
     */
    public function testLoadWord2011SettingsImageLoading(bool $hasImageLoading): void
    {
        $reader = new Word2007();
        $reader->setImageLoading($hasImageLoading);
        $phpWord = $reader->load(dirname(__DIR__, 1) . '/_files/documents/reader-2011.docx');

        self::assertInstanceOf(PhpWord::class, $phpWord);

        $sections = $phpWord->getSections();
        self::assertCount(1, $sections);
        $section = $sections[0];
        $elements = $section->getElements();
        self::assertCount(3, $elements);
        $element = $elements[2];
        self::assertInstanceOf(TextRun::class, $element);
        $subElements = $element->getElements();
        if ($hasImageLoading) {
            self::assertCount(1, $subElements);
            $subElement = $subElements[0];
            self::assertInstanceOf(Image::class, $subElement);
        } else {
            self::assertCount(0, $subElements);
        }
    }

    public function providerSettingsImageLoading(): iterable
    {
        return [
            [true],
            [false],
        ];
    }

    public function testLoadComments(): void
    {
        $phpWord = IOFactory::load(dirname(__DIR__, 1) . '/_files/documents/reader-comments.docx');

        self::assertInstanceOf(PhpWord::class, $phpWord);

        self::assertEquals(2, $phpWord->getComments()->countItems());

        /** @var Comment $comment */
        $comment = $phpWord->getComments()->getItem(0);
        self::assertInstanceOf(Comment::class, $comment);
        self::assertEquals('shaedrich', $comment->getAuthor());
        self::assertEquals(new DateTime('2021-10-28T13:56:55Z'), $comment->getDate());
        self::assertEquals('SH', $comment->getInitials());
        self::assertCount(1, $comment->getElements());
        self::assertInstanceOf(Text::class, $comment->getElement(0));
        self::assertEquals('This this be lowercase', $comment->getElement(0)->getText());
        /** @var Font $fontStyle */
        $fontStyle = $comment->getElement(0)->getFontStyle();
        self::assertInstanceOf(Font::class, $fontStyle);
        self::assertEquals('de-DE', $fontStyle->getLang()->getLatin());

        /** @var Comment $comment */
        $comment = $phpWord->getComments()->getItem(1);
        self::assertInstanceOf(Comment::class, $comment);
        self::assertEquals('shaedrich', $comment->getAuthor());
        self::assertEquals(new DateTime('2021-11-02T19:10:00Z'), $comment->getDate());
        self::assertEquals('SH', $comment->getInitials());
        self::assertCount(1, $comment->getElements());
        self::assertInstanceOf(Text::class, $comment->getElement(0));
        self::assertEquals('But this should be uppercase', $comment->getElement(0)->getText());
        /** @var Font $fontStyle */
        $fontStyle = $comment->getElement(0)->getFontStyle();
        self::assertInstanceOf(Font::class, $fontStyle);
        self::assertEquals('de-DE', $fontStyle->getLang()->getLatin());
    }

    public function testLoadFormula(): void
    {
        $phpWord = IOFactory::load(dirname(__DIR__, 1) . '/_files/documents/reader-formula.docx');

        self::assertInstanceOf(PhpWord::class, $phpWord);

        $sections = $phpWord->getSections();
        self::assertCount(1, $sections);

        $section = $sections[0];
        self::assertInstanceOf(Section::class, $section);

        $elements = $section->getElements();
        self::assertCount(1, $elements);

        $element = $elements[0];
        self::assertInstanceOf(Formula::class, $element);

        $elements = $element->getMath()->getElements();
        self::assertCount(5, $elements);

        /** @var Element\Fraction $element */
        $element = $elements[0];
        self::assertInstanceOf(Element\Fraction::class, $element);
        /** @var Element\Identifier $numerator */
        $numerator = $element->getNumerator();
        self::assertInstanceOf(Element\Identifier::class, $numerator);
        self::assertEquals('π', $numerator->getValue());
        /** @var Element\Numeric $denominator */
        $denominator = $element->getDenominator();
        self::assertInstanceOf(Element\Numeric::class, $denominator);
        self::assertEquals(2, $denominator->getValue());

        /** @var Element\Operator $element */
        $element = $elements[1];
        self::assertInstanceOf(Element\Operator::class, $element);
        self::assertEquals('+', $element->getValue());

        /** @var Element\Identifier $element */
        $element = $elements[2];
        self::assertInstanceOf(Element\Identifier::class, $element);
        self::assertEquals('a', $element->getValue());

        /** @var Element\Operator $element */
        $element = $elements[3];
        self::assertInstanceOf(Element\Operator::class, $element);
        self::assertEquals('∗', $element->getValue());

        /** @var Element\Numeric $element */
        $element = $elements[4];
        self::assertInstanceOf(Element\Numeric::class, $element);
        self::assertEquals(2, $element->getValue());
    }
}
