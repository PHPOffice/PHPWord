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

namespace PhpOffice\PhpWordTests\Reader\Word2007;

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Frame;
use PhpOffice\PhpWord\Style\Image as ImageStyle;
use PHPUnit\Framework\TestCase;
use ZipArchive;

class ImageStyleTest extends TestCase
{
    /** @var string */
    private $filename = '';

    protected function tearDown(): void
    {
        if ($this->filename !== '') {
            unlink($this->filename);
            $this->filename = '';
        }
    }

    /**
     * Save a document with one image, and replace its w:drawing with the given markup; %s is the relationship id of the image.
     */
    private function saveImage(array $style = [], ?string $markup = null): void
    {
        $phpWord = new PhpWord();
        $phpWord->addSection()->addImage(__DIR__ . '/../../_files/images/earth.jpg', $style);
        $this->filename = (string) tempnam(Settings::getTempDir(), 'PhpWord');
        IOFactory::createWriter($phpWord, 'Word2007')->save($this->filename);
        if ($markup === null) {
            return;
        }

        $zip = new ZipArchive();
        $zip->open($this->filename);
        $document = (string) $zip->getFromName('word/document.xml');
        self::assertSame(1, preg_match('/<a:blip r:embed="([^"]+)"/', $document, $matches));
        $zip->addFromString('word/document.xml', (string) preg_replace('#<w:drawing>.*</w:drawing>#s', sprintf($markup, $matches[1] ?? ''), $document));
        $zip->close();
    }

    private function readStyle(): ImageStyle
    {
        $textRun = IOFactory::load($this->filename)->getSection(0)->getElement(0);
        self::assertInstanceOf(TextRun::class, $textRun);
        $image = $textRun->getElement(0);
        self::assertInstanceOf(Image::class, $image);

        return $image->getStyle();
    }

    public function testReadWritten(): void
    {
        $this->saveImage([
            'width' => 120,
            'height' => 90,
            'positioning' => Frame::POS_ABSOLUTE,
            'posHorizontal' => Frame::POS_CENTER,
            'posHorizontalRel' => Frame::POS_RELTO_PAGE,
            'posVertical' => Frame::POS_ABSOLUTE,
            'posVerticalRel' => Frame::POS_RELTO_TEXT,
            'wrappingStyle' => Frame::WRAP_SQUARE,
            'marginTop' => 36,
            'wrapDistanceLeft' => 9,
        ]);

        $style = $this->readStyle();
        self::assertEquals(120, $style->getWidth());
        self::assertEquals(90, $style->getHeight());
        self::assertSame(Frame::POS_ABSOLUTE, $style->getPos());
        self::assertSame(Frame::POS_CENTER, $style->getHPos());
        self::assertSame(Frame::POS_RELTO_PAGE, $style->getHPosRelTo());
        self::assertSame(Frame::POS_ABSOLUTE, $style->getVPos());
        self::assertSame(Frame::POS_RELTO_TEXT, $style->getVPosRelTo());
        self::assertSame(Frame::WRAP_SQUARE, $style->getWrap());
        self::assertEquals(36, $style->getTop());
        self::assertEquals(9, $style->getWrapDistanceLeft());
    }

    public function testReadWrittenInline(): void
    {
        $this->saveImage(['width' => 120, 'height' => 90]);

        $style = $this->readStyle();
        self::assertEquals(120, $style->getWidth());
        self::assertEquals(90, $style->getHeight());
        self::assertSame(Frame::WRAP_INLINE, $style->getWrap());
    }

    public function testReadVml(): void
    {
        // As PHPWord 1.4 writes it
        $this->saveImage([], '<w:pict><v:shape type="#_x0000_t75" stroked="f" style="width:120pt; height:90pt; margin-left:0pt; margin-top:36pt; mso-wrap-distance-left:9pt; position:absolute; mso-position-horizontal:center; mso-position-vertical:top; mso-position-horizontal-relative:page; mso-position-vertical-relative:text;">'
            . '<w10:wrap type="square" anchorx="page" anchory="page"/><v:imagedata r:id="%s" o:title=""/></v:shape></w:pict>');

        $style = $this->readStyle();
        self::assertEquals(120, $style->getWidth());
        self::assertEquals(90, $style->getHeight());
        self::assertSame(Frame::POS_ABSOLUTE, $style->getPos());
        self::assertSame(Frame::POS_CENTER, $style->getHPos());
        self::assertSame(Frame::POS_RELTO_PAGE, $style->getHPosRelTo());
        self::assertSame(Frame::POS_TOP, $style->getVPos());
        self::assertSame(Frame::POS_RELTO_TEXT, $style->getVPosRelTo());
        self::assertSame(Frame::WRAP_SQUARE, $style->getWrap());
        self::assertEquals(36, $style->getTop());
        self::assertEquals(9, $style->getWrapDistanceLeft());
    }

    public function testReadVmlInline(): void
    {
        // As PHPWord 1.4 writes it
        $this->saveImage([], '<w:pict><v:shape type="#_x0000_t75" stroked="f" style="width:120pt; height:90pt; margin-left:0pt; margin-top:0pt; mso-position-horizontal:left; mso-position-vertical:top; mso-position-horizontal-relative:char; mso-position-vertical-relative:line;">'
            . '<w10:wrap type="inline"/><v:imagedata r:id="%s" o:title=""/></v:shape></w:pict>');

        $style = $this->readStyle();
        self::assertEquals(120, $style->getWidth());
        self::assertEquals(90, $style->getHeight());
        self::assertSame(Frame::WRAP_INLINE, $style->getWrap());
    }

    public function testReadVmlBehindText(): void
    {
        // As Word writes a picture behind the text: no w10:wrap, a negative z-index, lengths in other units
        $this->saveImage([], '<w:pict><v:shape id="Picture 1" type="#_x0000_t75" style="position:absolute;margin-left:-0.5in;margin-top:1cm;width:2in;height:108pt;z-index:-251658240;mso-position-horizontal-relative:text;mso-position-vertical-relative:paragraph"><v:imagedata r:id="%s" o:title=""/></v:shape></w:pict>');

        $style = $this->readStyle();
        self::assertEquals(144, $style->getWidth());
        self::assertEquals(108, $style->getHeight());
        self::assertEqualsWithDelta(-36, $style->getLeft(), 0.01);
        self::assertEqualsWithDelta(28.35, $style->getTop(), 0.01);
        self::assertSame(Frame::WRAP_BEHIND, $style->getWrap());
        self::assertSame(Frame::POS_RELTO_COLUMN, $style->getHPosRelTo());
    }

    private const GRAPHIC = '<a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"><a:graphicData uri="http://schemas.openxmlformats.org/drawingml/2006/picture">'
        . '<pic:pic xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture"><pic:nvPicPr><pic:cNvPr id="0" name="earth.jpg"/><pic:cNvPicPr/></pic:nvPicPr>'
        . '<pic:blipFill><a:blip r:embed="%s"/></pic:blipFill></pic:pic></a:graphicData></a:graphic>';

    public function testReadDrawingInline(): void
    {
        $this->saveImage([], '<w:drawing><wp:inline distT="12700" distB="0" distL="0" distR="0"><wp:extent cx="1524000" cy="1143000"/><wp:docPr id="1" name="Picture 1"/>' . self::GRAPHIC . '</wp:inline></w:drawing>');

        $style = $this->readStyle();
        self::assertEquals(120, $style->getWidth());
        self::assertEquals(90, $style->getHeight());
        self::assertEquals(1, $style->getWrapDistanceTop());
        self::assertSame(Frame::WRAP_INLINE, $style->getWrap());
    }

    public function testReadDrawingAnchor(): void
    {
        $this->saveImage([], '<w:drawing><wp:anchor behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1" relativeHeight="1" simplePos="0" distT="0" distB="0" distL="114300" distR="114300">'
            . '<wp:simplePos x="0" y="0"/><wp:positionH relativeFrom="page"><wp:align>center</wp:align></wp:positionH>'
            . '<wp:positionV relativeFrom="paragraph"><wp:posOffset>457200</wp:posOffset></wp:positionV>'
            . '<wp:extent cx="1524000" cy="1143000"/><wp:wrapTopAndBottom/><wp:docPr id="1" name="Picture 1"/>' . self::GRAPHIC . '</wp:anchor></w:drawing>');

        $style = $this->readStyle();
        self::assertEquals(120, $style->getWidth());
        self::assertEquals(90, $style->getHeight());
        self::assertSame(Frame::POS_ABSOLUTE, $style->getPos());
        self::assertSame(Frame::POS_CENTER, $style->getHPos());
        self::assertSame(Frame::POS_RELTO_PAGE, $style->getHPosRelTo());
        self::assertSame(Frame::POS_ABSOLUTE, $style->getVPos());
        self::assertSame(Frame::POS_RELTO_TEXT, $style->getVPosRelTo());
        self::assertEquals(36, $style->getTop());
        self::assertEquals(9, $style->getWrapDistanceLeft());
        self::assertSame(Frame::WRAP_TOPBOTTOM, $style->getWrap());
    }

    public function testReadDrawingBehindText(): void
    {
        $this->saveImage([], '<w:drawing><wp:anchor behindDoc="1" locked="0" layoutInCell="1" allowOverlap="1" relativeHeight="1" simplePos="0" distT="0" distB="0" distL="0" distR="0">'
            . '<wp:simplePos x="0" y="0"/><wp:positionH relativeFrom="character"><wp:posOffset>-127000</wp:posOffset></wp:positionH>'
            . '<wp:positionV relativeFrom="line"><wp:align>top</wp:align></wp:positionV>'
            . '<wp:extent cx="1524000" cy="1143000"/><wp:wrapNone/><wp:docPr id="1" name="Picture 1"/>' . self::GRAPHIC . '</wp:anchor></w:drawing>');

        $style = $this->readStyle();
        self::assertSame(Frame::POS_ABSOLUTE, $style->getHPos());
        self::assertEquals(-10, $style->getLeft());
        self::assertSame(Frame::POS_RELTO_CHAR, $style->getHPosRelTo());
        self::assertSame(Frame::POS_TOP, $style->getVPos());
        self::assertSame(Frame::POS_RELTO_LINE, $style->getVPosRelTo());
        self::assertSame(Frame::WRAP_BEHIND, $style->getWrap());
    }
}
