<?php

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TestHelperDOCX;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Field
 */
class FieldTest extends TestCase
{
    /**
     * Executed before each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test Field write
     */
    public function testWriteWithRefType()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addField(
            'REF',
            array(
                'name' => 'my-bookmark',
            ),
            array(
                'InsertParagraphNumberRelativeContext',
                'CreateHyperLink',
            )
        );

        $section->addListItem('line one item');
        $section->addListItem('line two item');
        $section->addBookmark('my-bookmark');
        $section->addListItem('line three item');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $refFieldPath = '/w:document/w:body/w:p[1]/w:r[2]/w:instrText';

        $this->assertTrue($doc->elementExists($refFieldPath));

        $bookMarkElement = $doc->getElement($refFieldPath);

        $this->assertNotNull($bookMarkElement);

        $this->assertEquals(' REF my-bookmark \r \h ', $bookMarkElement->textContent);

        $bookmarkPath = '/w:document/w:body/w:bookmarkStart';

        $this->assertTrue($doc->elementExists($bookmarkPath));
        $this->assertEquals('my-bookmark', $doc->getElementAttribute("$bookmarkPath", 'w:name'));
    }
}
