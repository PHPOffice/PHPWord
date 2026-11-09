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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\TestHelperDOCX;
use PHPUnit\Framework\TestCase;
use ZipArchive;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element\AltChunk.
 */
class AltChunkTest extends TestCase
{
    private const RELATIONSHIP = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/aFChunk';

    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testWriteAltChunk(): void
    {
        $src = __DIR__ . '/../../../_files/documents/reader.docx';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Before');
        $section->addAltChunk($src);
        $section->addText('After');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        // Block-level element, between the paragraphs, not inside one
        self::assertTrue($doc->elementExists('/w:document/w:body/w:altChunk'));
        self::assertEquals('Before', $doc->getElement('/w:document/w:body/w:altChunk/preceding-sibling::w:p[1]')->textContent);
        self::assertEquals('After', $doc->getElement('/w:document/w:body/w:altChunk/following-sibling::w:p[1]')->textContent);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:altChunk/following-sibling::w:sectPr'));

        // Relationship to the embedded part
        $rId = $doc->getElementAttribute('/w:document/w:body/w:altChunk', 'r:id');
        $relationship = $doc->getElement('/*[local-name()="Relationships"]/*[local-name()="Relationship"][@Id="' . $rId . '"]', 'word/_rels/document.xml.rels');
        self::assertNotNull($relationship);
        self::assertEquals(self::RELATIONSHIP, $relationship->getAttribute('Type'));
        self::assertEquals('section_altChunk1.docx', $relationship->getAttribute('Target'));

        // Content type of a whole WordprocessingML package
        $contentType = $doc->getElement('/*[local-name()="Types"]/*[local-name()="Default"][@Extension="docx"]', '[Content_Types].xml');
        self::assertNotNull($contentType);
        self::assertEquals(
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            $contentType->getAttribute('ContentType')
        );

        // The embedded part is the source file, unchanged
        $zip = new ZipArchive();
        self::assertTrue($zip->open(TestHelperDOCX::getFile()));
        self::assertSame(file_get_contents($src), $zip->getFromName('word/section_altChunk1.docx'));
        $zip->close();
    }

    public function testWriteSeveralAltChunksWithOtherRelations(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addLink('https://github.com/PHPOffice/PHPWord');
        $section->addAltChunk(__DIR__ . '/../../../_files/documents/reader.docx');
        $section->addPageBreak();
        $section->addImage(__DIR__ . '/../../../_files/images/PhpWord.png');
        $section->addAltChunk(__DIR__ . '/../../../_files/documents/reader-styles.docx');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertEquals(2, $doc->getNodeList('/w:document/w:body/w:altChunk')->length);

        $targets = [];
        for ($i = 1; $i <= 2; ++$i) {
            $rId = $doc->getElementAttribute('/w:document/w:body/w:altChunk[' . $i . ']', 'r:id');
            $relationship = $doc->getElement('/*[local-name()="Relationships"]/*[local-name()="Relationship"][@Id="' . $rId . '"]', 'word/_rels/document.xml.rels');
            self::assertNotNull($relationship);
            self::assertEquals(self::RELATIONSHIP, $relationship->getAttribute('Type'));
            $targets[] = $relationship->getAttribute('Target');
        }
        self::assertEquals(['section_altChunk1.docx', 'section_altChunk2.docx'], $targets);

        // Relationship ids stay unique across links, images and chunks
        $ids = [];
        foreach ($doc->getNodeList('/*[local-name()="Relationships"]/*[local-name()="Relationship"]/@Id', 'word/_rels/document.xml.rels') as $id) {
            $ids[] = $id->nodeValue;
        }
        self::assertEquals(count($ids), count(array_unique($ids)));
    }

    public function testSameDocumentAddedTwice(): void
    {
        $src = __DIR__ . '/../../../_files/documents/reader.docx';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addAltChunk($src);
        $section->addAltChunk($src);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertEquals(2, $doc->getNodeList('/w:document/w:body/w:altChunk')->length);
        self::assertEquals(
            $doc->getElementAttribute('/w:document/w:body/w:altChunk[1]', 'r:id'),
            $doc->getElementAttribute('/w:document/w:body/w:altChunk[2]', 'r:id')
        );
    }
}
