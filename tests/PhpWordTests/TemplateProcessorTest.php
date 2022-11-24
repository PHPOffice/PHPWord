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

use DOMDocument;
use Exception;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

/**
 * @covers \PhpOffice\PhpWord\TemplateProcessor
 *
 * @coversDefaultClass \PhpOffice\PhpWord\TemplateProcessor
 *
 * @runTestsInSeparateProcesses
 */
final class TemplateProcessorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Construct test.
     *
     * @covers ::__construct
     */
    public function testTheConstruct(): void
    {
        $object = new TemplateProcessor(__DIR__ . '/_files/templates/blank.docx');
        self::assertInstanceOf('PhpOffice\\PhpWord\\TemplateProcessor', $object);
        self::assertEquals([], $object->getVariables());
    }

    /**
     * Template can be saved in temporary location.
     *
     * @covers ::save
     * @covers ::zip
     */
    public function testTemplateCanBeSavedInTemporaryLocation()
    {
        $templateFqfn = __DIR__ . '/_files/templates/with_table_macros.docx';

        $templateProcessor = new TemplateProcessor($templateFqfn);
        $xslDomDocument = new DOMDocument();
        $xslDomDocument->load(__DIR__ . '/_files/xsl/remove_tables_by_needle.xsl');
        foreach (['${employee.', '${scoreboard.', '${reference.'] as $needle) {
            $templateProcessor->applyXslStyleSheet($xslDomDocument, ['needle' => $needle]);
        }

        $embeddingText = 'The quick Brown Fox jumped over the lazy^H^H^H^Htired unitTester';
        $templateProcessor->zip()->AddFromString('word/embeddings/fox.bin', $embeddingText);
        $documentFqfn = $templateProcessor->save();

        self::assertNotEmpty($documentFqfn, 'FQFN of the saved document is empty.');
        self::assertFileExists($documentFqfn, "The saved document \"{$documentFqfn}\" doesn't exist.");

        $templateZip = new ZipArchive();
        $templateZip->open($templateFqfn);
        $templateHeaderXml = $templateZip->getFromName('word/header1.xml');
        $templateMainPartXml = $templateZip->getFromName('word/document.xml');
        $templateFooterXml = $templateZip->getFromName('word/footer1.xml');
        if (false === $templateZip->close()) {
            throw new Exception("Could not close zip file \"{$templateZip}\".");
        }

        $documentZip = new ZipArchive();
        $documentZip->open($documentFqfn);
        $documentHeaderXml = $documentZip->getFromName('word/header1.xml');
        $documentMainPartXml = $documentZip->getFromName('word/document.xml');
        $documentFooterXml = $documentZip->getFromName('word/footer1.xml');
        $documentEmbedding = $documentZip->getFromName('word/embeddings/fox.bin');
        if (false === $documentZip->close()) {
            throw new Exception("Could not close zip file \"{$documentZip}\".");
        }

        self::assertNotEquals($templateHeaderXml, $documentHeaderXml);
        self::assertNotEquals($templateMainPartXml, $documentMainPartXml);
        self::assertNotEquals($templateFooterXml, $documentFooterXml);
        self::assertEquals($embeddingText, $documentEmbedding);

        return $documentFqfn;
    }

    /**
     * XSL stylesheet can be applied.
     *
     * @covers ::applyXslStyleSheet
     *
     * @depends testTemplateCanBeSavedInTemporaryLocation
     *
     * @param string $actualDocumentFqfn
     */
    public function testXslStyleSheetCanBeApplied($actualDocumentFqfn): void
    {
        $expectedDocumentFqfn = __DIR__ . '/_files/documents/without_table_macros.docx';

        $actualDocumentZip = new ZipArchive();
        $actualDocumentZip->open($actualDocumentFqfn);
        $actualHeaderXml = $actualDocumentZip->getFromName('word/header1.xml');
        $actualMainPartXml = $actualDocumentZip->getFromName('word/document.xml');
        $actualFooterXml = $actualDocumentZip->getFromName('word/footer1.xml');
        if (false === $actualDocumentZip->close()) {
            throw new Exception("Could not close zip file \"{$actualDocumentFqfn}\".");
        }

        $expectedDocumentZip = new ZipArchive();
        $expectedDocumentZip->open($expectedDocumentFqfn);
        $expectedHeaderXml = $expectedDocumentZip->getFromName('word/header1.xml');
        $expectedMainPartXml = $expectedDocumentZip->getFromName('word/document.xml');
        $expectedFooterXml = $expectedDocumentZip->getFromName('word/footer1.xml');
        if (false === $expectedDocumentZip->close()) {
            throw new Exception("Could not close zip file \"{$expectedDocumentFqfn}\".");
        }

        self::assertXmlStringEqualsXmlString($expectedHeaderXml, $actualHeaderXml);
        self::assertXmlStringEqualsXmlString($expectedMainPartXml, $actualMainPartXml);
        self::assertXmlStringEqualsXmlString($expectedFooterXml, $actualFooterXml);
    }

    /**
     * XSL stylesheet cannot be applied on failure in setting parameter value.
     *
     * @covers                   ::applyXslStyleSheet
     */
    public function testXslStyleSheetCanNotBeAppliedOnFailureOfSettingParameterValue(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $this->expectExceptionMessage('Could not set values for the given XSL style sheet parameters.');
        // Test is not needed for PHP 8.0, because internally validation throws TypeError exception.
        if (\PHP_VERSION_ID >= 80000) {
            self::markTestSkipped('not needed for PHP 8.0');
        }

        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/blank.docx');

        $xslDomDocument = new DOMDocument();
        $xslDomDocument->load(__DIR__ . '/_files/xsl/passthrough.xsl');

        /*
         * We have to use error control below, because \XSLTProcessor::setParameter omits warning on failure.
         * This warning fails the test.
         */
        @$templateProcessor->applyXslStyleSheet($xslDomDocument, [1 => 'somevalue']);
    }

    /**
     * XSL stylesheet can be applied on failure of loading XML from template.
     *
     * @covers                   ::applyXslStyleSheet
     */
    public function testXslStyleSheetCanNotBeAppliedOnFailureOfLoadingXmlFromTemplate(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $this->expectExceptionMessage('Could not load the given XML document.');
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/corrupted_main_document_part.docx');

        $xslDomDocument = new DOMDocument();
        $xslDomDocument->load(__DIR__ . '/_files/xsl/passthrough.xsl');

        /*
         * We have to use error control below, because \DOMDocument::loadXML omits warning on failure.
         * This warning fails the test.
         */
        @$templateProcessor->applyXslStyleSheet($xslDomDocument);
    }

    /**
     * @covers ::deleteRow
     * @covers ::getVariables
     * @covers ::saveAs
     */
    public function testDeleteRow(): void
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/delete-row.docx');

        self::assertEquals(
            ['deleteMe', 'deleteMeToo'],
            $templateProcessor->getVariables()
        );

        $docName = 'delete-row-test-result.docx';
        $templateProcessor->deleteRow('deleteMe');
        self::assertEquals(
            [],
            $templateProcessor->getVariables()
        );
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);
        self::assertTrue($docFound);
    }

    /**
     * @covers ::cloneRow
     * @covers ::saveAs
     * @covers ::setValue
     */
    public function testCloneRow(): void
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-merge.docx');

        self::assertEquals(
            ['tableHeader', 'userId', 'userName', 'userLocation'],
            $templateProcessor->getVariables()
        );

        $docName = 'clone-test-result.docx';
        $templateProcessor->setValue('tableHeader', utf8_decode('ééé'));
        $templateProcessor->cloneRow('userId', 1);
        $templateProcessor->setValue('userId#1', 'Test');
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);
        self::assertTrue($docFound);
    }

    /**
     * @covers ::cloneRow
     * @covers ::saveAs
     * @covers ::setValue
     */
    public function testCloneRowAndSetValues(): void
    {
        $mainPart = '<w:tbl>
            <w:tr>
                <w:tc>
                    <w:tcPr>
                        <w:vMerge w:val="restart"/>
                    </w:tcPr>
                    <w:p>
                        <w:r>
                            <w:t>${userId}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:p>
                        <w:r>
                            <w:t>${userName}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
            </w:tr>
            <w:tr>
                <w:tc>
                    <w:tcPr>
                        <w:vMerge/>
                    </w:tcPr>
                    <w:p/>
                </w:tc>
                <w:tc>
                    <w:p>
                        <w:r>
                            <w:t>${userLocation}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>
            </w:tr>
        </w:tbl>';
        $templateProcessor = new TestableTemplateProcesor($mainPart);

        self::assertEquals(
            ['userId', 'userName', 'userLocation'],
            $templateProcessor->getVariables()
        );

        $values = [
            ['userId' => 1, 'userName' => 'Batman', 'userLocation' => 'Gotham City'],
            ['userId' => 2, 'userName' => 'Superman', 'userLocation' => 'Metropolis'],
        ];
        $templateProcessor->setValue('tableHeader', 'My clonable table');
        $templateProcessor->cloneRowAndSetValues('userId', $values);
        self::assertStringContainsString('<w:t>Superman</w:t>', $templateProcessor->getMainPart());
        self::assertStringContainsString('<w:t>Metropolis</w:t>', $templateProcessor->getMainPart());
    }

    public function testCloneNotExistingRowShouldThrowException(): void
    {
        $this->expectException(Exception::class);
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?><w:p><w:r><w:rPr></w:rPr><w:t>text</w:t></w:r></w:p>';
        $templateProcessor = new TestableTemplateProcesor($mainPart);

        $templateProcessor->cloneRow('fake_search', 2);
    }

    /**
     * @covers ::saveAs
     * @covers ::setValue
     */
    public function testMacrosCanBeReplacedInHeaderAndFooter(): void
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/header-footer.docx');

        self::assertEquals(['documentContent', 'headerValue:100:100', 'footerValue'], $templateProcessor->getVariables());

        $macroNames = ['headerValue', 'documentContent', 'footerValue'];
        $macroValues = ['Header Value', 'Document text.', 'Footer Value'];
        $templateProcessor->setValue($macroNames, $macroValues);

        $docName = 'header-footer-test-result.docx';
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);
        self::assertTrue($docFound);
    }

    /**
     * @covers ::setValue
     */
    public function testSetValue(): void
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-merge.docx');
        Settings::setOutputEscapingEnabled(true);
        $helloworld = "hello\nworld";
        $templateProcessor->setValue('userName', $helloworld);
        self::assertEquals(
            ['tableHeader', 'userId', 'userLocation'],
            $templateProcessor->getVariables()
        );
    }

    public function testSetComplexValue(): void
    {
        $title = new TextRun();
        $title->addText('This is my title');

        $firstname = new Text('Donald');
        $lastname = new Text('Duck');

        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <w:p>
            <w:r>
                <w:t xml:space="preserve">Hello ${document-title}</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:r>
                <w:t xml:space="preserve">Hello ${firstname} ${lastname}</w:t>
            </w:r>
        </w:p>';

        $result = '<?xml version="1.0" encoding="UTF-8"?>
        <w:p>
            <w:pPr/>
            <w:r>
                <w:rPr/>
                <w:t xml:space="preserve">This is my title</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:r>
                <w:t xml:space="preserve">Hello </w:t>
            </w:r>
            <w:r>
                <w:rPr/>
                <w:t xml:space="preserve">Donald</w:t>
            </w:r>
            <w:r>
                <w:t xml:space="preserve"> </w:t>
            </w:r>
            <w:r>
                <w:rPr/>
                <w:t xml:space="preserve">Duck</w:t>
            </w:r>
        </w:p>';

        $templateProcessor = new TestableTemplateProcesor($mainPart);
        $templateProcessor->setComplexBlock('document-title', $title);
        $templateProcessor->setComplexValue('firstname', $firstname);
        $templateProcessor->setComplexValue('lastname', $lastname);

        self::assertEquals(preg_replace('/>\s+</', '><', $result), preg_replace('/>\s+</', '><', $templateProcessor->getMainPart()));
    }

    /**
     * @covers ::setValues
     */
    public function testSetValues(): void
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <w:p>
            <w:r>
                <w:t xml:space="preserve">Hello ${firstname} ${lastname}</w:t>
            </w:r>
        </w:p>';

        $templateProcessor = new TestableTemplateProcesor($mainPart);
        $templateProcessor->setValues(['firstname' => 'John', 'lastname' => 'Doe']);

        self::assertStringContainsString('Hello John Doe', $templateProcessor->getMainPart());
    }

    /**
     * @covers ::setImageValue
     */
    public function testSetImageValue(): void
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/header-footer.docx');
        $imagePath = __DIR__ . '/_files/images/earth.jpg';

        $variablesReplace = [
            'headerValue' => function () use ($imagePath) {
                return $imagePath;
            },
            'documentContent' => ['path' => $imagePath, 'width' => 500, 'height' => 500],
            'footerValue' => ['path' => $imagePath, 'width' => 100, 'height' => 50, 'ratio' => false],
        ];
        $templateProcessor->setImageValue(array_keys($variablesReplace), $variablesReplace);

        $docName = 'header-footer-images-test-result.docx';
        $templateProcessor->saveAs($docName);

        self::assertFileExists($docName, "Generated file '{$docName}' not found!");

        $expectedDocumentZip = new ZipArchive();
        $expectedDocumentZip->open($docName);
        $expectedContentTypesXml = $expectedDocumentZip->getFromName('[Content_Types].xml');
        $expectedDocumentRelationsXml = $expectedDocumentZip->getFromName('word/_rels/document.xml.rels');
        $expectedHeaderRelationsXml = $expectedDocumentZip->getFromName('word/_rels/header1.xml.rels');
        $expectedFooterRelationsXml = $expectedDocumentZip->getFromName('word/_rels/footer1.xml.rels');
        $expectedMainPartXml = $expectedDocumentZip->getFromName('word/document.xml');
        $expectedHeaderPartXml = $expectedDocumentZip->getFromName('word/header1.xml');
        $expectedFooterPartXml = $expectedDocumentZip->getFromName('word/footer1.xml');
        $expectedImage = $expectedDocumentZip->getFromName('word/media/image_rId11_document.jpeg');
        if (false === $expectedDocumentZip->close()) {
            throw new Exception("Could not close zip file \"{$docName}\".");
        }

        self::assertNotEmpty($expectedImage, 'Embed image doesn\'t found.');
        self::assertStringContainsString('/word/media/image_rId11_document.jpeg', $expectedContentTypesXml, '[Content_Types].xml missed "/word/media/image5_document.jpeg"');
        self::assertStringContainsString('/word/_rels/header1.xml.rels', $expectedContentTypesXml, '[Content_Types].xml missed "/word/_rels/header1.xml.rels"');
        self::assertStringContainsString('/word/_rels/footer1.xml.rels', $expectedContentTypesXml, '[Content_Types].xml missed "/word/_rels/footer1.xml.rels"');
        self::assertStringNotContainsString('${documentContent}', $expectedMainPartXml, 'word/document.xml has no image.');
        self::assertStringNotContainsString('${headerValue}', $expectedHeaderPartXml, 'word/header1.xml has no image.');
        self::assertStringNotContainsString('${footerValue}', $expectedFooterPartXml, 'word/footer1.xml has no image.');
        self::assertStringContainsString('media/image_rId11_document.jpeg', $expectedDocumentRelationsXml, 'word/_rels/document.xml.rels missed "media/image5_document.jpeg"');
        self::assertStringContainsString('media/image_rId11_document.jpeg', $expectedHeaderRelationsXml, 'word/_rels/header1.xml.rels missed "media/image5_document.jpeg"');
        self::assertStringContainsString('media/image_rId11_document.jpeg', $expectedFooterRelationsXml, 'word/_rels/footer1.xml.rels missed "media/image5_document.jpeg"');

        unlink($docName);

        // dynamic generated doc
        $testFileName = 'images-test-sample.docx';
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('${Test:width=100:ratio=true}');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($testFileName);
        self::assertFileExists($testFileName, "Generated file '{$testFileName}' not found!");

        $resultFileName = 'images-test-result.docx';
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($testFileName);
        unlink($testFileName);
        $templateProcessor->setImageValue('Test', $imagePath);
        $templateProcessor->setImageValue('Test1', $imagePath);
        $templateProcessor->setImageValue('Test2', $imagePath);
        $templateProcessor->saveAs($resultFileName);
        self::assertFileExists($resultFileName, "Generated file '{$resultFileName}' not found!");

        $expectedDocumentZip = new ZipArchive();
        $expectedDocumentZip->open($resultFileName);
        $expectedMainPartXml = $expectedDocumentZip->getFromName('word/document.xml');
        if (false === $expectedDocumentZip->close()) {
            throw new Exception("Could not close zip file \"{$resultFileName}\".");
        }
        unlink($resultFileName);

        self::assertStringNotContainsString('${Test}', $expectedMainPartXml, 'word/document.xml has no image.');
    }

    /**
     * @covers ::cloneBlock
     * @covers ::deleteBlock
     * @covers ::saveAs
     */
    public function testCloneDeleteBlock(): void
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-delete-block.docx');

        self::assertEquals(
            ['DELETEME', '/DELETEME', 'CLONEME', 'blockVariable', '/CLONEME'],
            $templateProcessor->getVariables()
        );

        $docName = 'clone-delete-block-result.docx';
        $templateProcessor->cloneBlock('CLONEME', 3);
        $templateProcessor->deleteBlock('DELETEME');
        $templateProcessor->setValue('blockVariable#3', 'Test');
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);
        self::assertTrue($docFound);
    }

    /**
     * @covers ::getVariableCount
     */
    public function testGetVariableCountCountsHowManyTimesEachPlaceholderIsPresent(): void
    {
        // create template with placeholders
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $header = $section->addHeader();
        $header->addText('${a_field_that_is_present_three_times}');
        $footer = $section->addFooter();
        $footer->addText('${a_field_that_is_present_twice}');
        $section2 = $phpWord->addSection();
        $section2->addText('
                ${a_field_that_is_present_one_time}
                  ${a_field_that_is_present_three_times}
              ${a_field_that_is_present_twice}
                   ${a_field_that_is_present_three_times}
        ');
        $objWriter = IOFactory::createWriter($phpWord);
        $templatePath = 'test.docx';
        $objWriter->save($templatePath);

        $templateProcessor = new TemplateProcessor($templatePath);
        $variableCount = $templateProcessor->getVariableCount();
        unlink($templatePath);

        self::assertEquals(
            [
                'a_field_that_is_present_three_times' => 3,
                'a_field_that_is_present_twice' => 2,
                'a_field_that_is_present_one_time' => 1,
            ],
            $variableCount
        );
    }

    /**
     * @covers ::cloneBlock
     */
    public function testCloneBlockCanCloneABlockTwice(): void
    {
        // create template with placeholders and block
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $documentElements = [
            'Title: ${title}',
            '${subreport}',
            '${subreport.id}: ${subreport.text}. ',
            '${/subreport}',
        ];
        foreach ($documentElements as $documentElement) {
            $section->addText($documentElement);
        }

        $objWriter = IOFactory::createWriter($phpWord);
        $templatePath = 'test.docx';
        $objWriter->save($templatePath);

        // replace placeholders and save the file
        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('title', 'Some title');
        $templateProcessor->cloneBlock('subreport', 2);
        $templateProcessor->setValue('subreport.id', '123', 1);
        $templateProcessor->setValue('subreport.text', 'Some text', 1);
        $templateProcessor->setValue('subreport.id', '456', 1);
        $templateProcessor->setValue('subreport.text', 'Some other text', 1);
        $templateProcessor->saveAs($templatePath);

        // assert the block has been cloned twice
        // and the placeholders have been replaced correctly
        $phpWord = IOFactory::load($templatePath);
        $sections = $phpWord->getSections();
        /** @var \PhpOffice\PhpWord\Element\TextRun[] $actualElements */
        $actualElements = $sections[0]->getElements();
        unlink($templatePath);
        $expectedElements = [
            'Title: Some title',
            '123: Some text. ',
            '456: Some other text. ',
        ];
        self::assertCount(count($expectedElements), $actualElements);
        foreach ($expectedElements as $i => $expectedElement) {
            self::assertEquals(
                $expectedElement,
                $actualElements[$i]->getElement(0)->getText()
            );
        }
    }

    /**
     * @covers ::cloneBlock
     */
    public function testCloneBlock(): void
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <w:p>
            <w:r>
                <w:rPr></w:rPr>
                <w:t>${CLONEME}</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:r>
                <w:t xml:space="preserve">This block will be cloned with ${variable}</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:r w:rsidRPr="00204FED">
                <w:t>${/CLONEME}</w:t>
            </w:r>
        </w:p>';

        $templateProcessor = new TestableTemplateProcesor($mainPart);
        $templateProcessor->cloneBlock('CLONEME', 3);

        self::assertEquals(3, substr_count($templateProcessor->getMainPart(), 'This block will be cloned with ${variable}'));
    }

    /**
     * @covers ::cloneBlock
     */
    public function testCloneBlockWithVariables(): void
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <w:p>
            <w:r>
                <w:rPr></w:rPr>
                <w:t>${CLONEME}</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:r>
                <w:t xml:space="preserve">Address ${address}, Street ${street}</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:r w:rsidRPr="00204FED">
                <w:t>${/CLONEME}</w:t>
            </w:r>
        </w:p>';

        $templateProcessor = new TestableTemplateProcesor($mainPart);
        $templateProcessor->cloneBlock('CLONEME', 3, true, true);

        self::assertStringContainsString('Address ${address#1}, Street ${street#1}', $templateProcessor->getMainPart());
        self::assertStringContainsString('Address ${address#2}, Street ${street#2}', $templateProcessor->getMainPart());
        self::assertStringContainsString('Address ${address#3}, Street ${street#3}', $templateProcessor->getMainPart());
    }

    public function testCloneBlockWithVariableReplacements(): void
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <w:p>
            <w:r>
                <w:rPr></w:rPr>
                <w:t>${CLONEME}</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:r>
                <w:t xml:space="preserve">City: ${city}, Street: ${street}</w:t>
            </w:r>
        </w:p>
        <w:p>
            <w:r w:rsidRPr="00204FED">
                <w:t>${/CLONEME}</w:t>
            </w:r>
        </w:p>';

        $replacements = [
            ['city' => 'London', 'street' => 'Baker Street'],
            ['city' => 'New York', 'street' => '5th Avenue'],
            ['city' => 'Rome', 'street' => 'Via della Conciliazione'],
        ];
        $templateProcessor = new TestableTemplateProcesor($mainPart);
        $templateProcessor->cloneBlock('CLONEME', 0, true, false, $replacements);

        self::assertStringContainsString('City: London, Street: Baker Street', $templateProcessor->getMainPart());
        self::assertStringContainsString('City: New York, Street: 5th Avenue', $templateProcessor->getMainPart());
        self::assertStringContainsString('City: Rome, Street: Via della Conciliazione', $templateProcessor->getMainPart());
    }

    /**
     * Template macros can be fixed.
     *
     * @covers ::fixBrokenMacros
     */
    public function testFixBrokenMacros(): void
    {
        $templateProcessor = new TestableTemplateProcesor();

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>normal text</w:t></w:r>');
        self::assertEquals('<w:r><w:t>normal text</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>${documentContent}</w:t></w:r>');
        self::assertEquals('<w:r><w:t>${documentContent}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>$</w:t><w:t>{documentContent}</w:t></w:r>');
        self::assertEquals('<w:r><w:t>${documentContent}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>$1500</w:t><w:t>${documentContent}</w:t></w:r>');
        self::assertEquals('<w:r><w:t>$1500</w:t><w:t>${documentContent}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>$1500</w:t><w:t>$</w:t><w:t>{documentContent}</w:t></w:r>');
        self::assertEquals('<w:r><w:t>$1500</w:t><w:t>${documentContent}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>25$ plus some info {hint}</w:t></w:r>');
        self::assertEquals('<w:r><w:t>25$ plus some info {hint}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:t>$</w:t></w:r><w:bookmarkStart w:id="0" w:name="_GoBack"/><w:bookmarkEnd w:id="0"/><w:r><w:t xml:space="preserve">15,000.00. </w:t></w:r><w:r w:rsidR="0056499B"><w:t>$</w:t></w:r><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>{</w:t></w:r><w:proofErr w:type="spellStart"/><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>variable_name</w:t></w:r><w:proofErr w:type="spellEnd"/><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>}</w:t></w:r>');
        self::assertEquals('<w:t>$</w:t></w:r><w:bookmarkStart w:id="0" w:name="_GoBack"/><w:bookmarkEnd w:id="0"/><w:r><w:t xml:space="preserve">15,000.00. </w:t></w:r><w:r w:rsidR="0056499B"><w:t>${variable_name}</w:t></w:r>', $fixed);
    }

    /**
     * @covers ::getMainPartName
     */
    public function testMainPartNameDetection(): void
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/document22-xml.docx');

        $variables = ['test'];

        self::assertEquals($variables, $templateProcessor->getVariables());
    }

    /**
     * @covers ::getVariables
     */
    public function testGetVariables(): void
    {
        $templateProcessor = new TestableTemplateProcesor();

        $variables = $templateProcessor->getVariablesForPart('<w:r><w:t>normal text</w:t></w:r>');
        self::assertEquals([], $variables);

        $variables = $templateProcessor->getVariablesForPart('<w:r><w:t>${documentContent}</w:t></w:r>');
        self::assertEquals(['documentContent'], $variables);

        $variables = $templateProcessor->getVariablesForPart('<w:t>$</w:t></w:r><w:bookmarkStart w:id="0" w:name="_GoBack"/><w:bookmarkEnd w:id="0"/><w:r><w:t xml:space="preserve">15,000.00. </w:t></w:r><w:r w:rsidR="0056499B"><w:t>$</w:t></w:r><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>{</w:t></w:r><w:proofErr w:type="spellStart"/><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>variable_name</w:t></w:r><w:proofErr w:type="spellEnd"/><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>}</w:t></w:r>');
        self::assertEquals(['variable_name'], $variables);
    }

    /**
     * @covers ::textNeedsSplitting
     */
    public function testTextNeedsSplitting(): void
    {
        $templateProcessor = new TestableTemplateProcesor();

        self::assertFalse($templateProcessor->textNeedsSplitting('<w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve">${nothing-to-replace}</w:t></w:r>'));

        $text = '<w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve">Hello ${firstname} ${lastname}</w:t></w:r>';
        self::assertTrue($templateProcessor->textNeedsSplitting($text));
        $splitText = $templateProcessor->splitTextIntoTexts($text);
        self::assertFalse($templateProcessor->textNeedsSplitting($splitText));
    }

    /**
     * @covers ::splitTextIntoTexts
     */
    public function testSplitTextIntoTexts(): void
    {
        $templateProcessor = new TestableTemplateProcesor();

        $splitText = $templateProcessor->splitTextIntoTexts('<w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve">${nothing-to-replace}</w:t></w:r>');
        self::assertEquals('<w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve">${nothing-to-replace}</w:t></w:r>', $splitText);

        $splitText = $templateProcessor->splitTextIntoTexts('<w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve">Hello ${firstname} ${lastname}</w:t></w:r>');
        self::assertEquals('<w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve">Hello </w:t></w:r><w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve">${firstname}</w:t></w:r><w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve"> </w:t></w:r><w:r><w:rPr><w:b/><w:i/></w:rPr><w:t xml:space="preserve">${lastname}</w:t></w:r>', $splitText);
    }

    public function testFindXmlBlockStart(): void
    {
        $toFind = '<w:r>
                    <w:rPr>
                        <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
                        <w:lang w:val="en-GB"/>
                    </w:rPr>
                    <w:t>This whole paragraph will be replaced with my ${title}</w:t>
                </w:r>';
        $mainPart = '<w:document xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex" xmlns:cx1="http://schemas.microsoft.com/office/drawing/2015/9/8/chartex" xmlns:cx2="http://schemas.microsoft.com/office/drawing/2015/10/21/chartex" xmlns:cx3="http://schemas.microsoft.com/office/drawing/2016/5/9/chartex" xmlns:cx4="http://schemas.microsoft.com/office/drawing/2016/5/10/chartex" xmlns:cx5="http://schemas.microsoft.com/office/drawing/2016/5/11/chartex" xmlns:cx6="http://schemas.microsoft.com/office/drawing/2016/5/12/chartex" xmlns:cx7="http://schemas.microsoft.com/office/drawing/2016/5/13/chartex" xmlns:cx8="http://schemas.microsoft.com/office/drawing/2016/5/14/chartex" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:aink="http://schemas.microsoft.com/office/drawing/2016/ink" xmlns:am3d="http://schemas.microsoft.com/office/drawing/2017/model3d" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml" xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml" xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid" xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex" xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup" xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk" xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 w15 w16se w16cid wp14">
            <w:p w14:paraId="165D45AF" w14:textId="7FEC9B41" w:rsidR="005B1098" w:rsidRDefault="005B1098">
                <w:r w:rsidR="00A045B2">
                    <w:rPr>
                        <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
                        <w:lang w:val="en-GB"/>
                    </w:rPr>
                    <w:t xml:space="preserve"> ${value1} ${value2}</w:t>
                </w:r>
                <w:r>
                    <w:rPr>
                        <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
                        <w:lang w:val="en-GB"/>
                    </w:rPr>
                    <w:t>.</w:t>
                </w:r>
            </w:p>
            <w:p w14:paraId="330D1954" w14:textId="0AB1D347" w:rsidR="00156568" w:rsidRDefault="00156568">
                <w:pPr>
                    <w:rPr>
                        <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
                        <w:lang w:val="en-GB"/>
                    </w:rPr>
                </w:pPr>
                ' . $toFind . '
            </w:p>
        </w:document>';

        $templateProcessor = new TestableTemplateProcesor($mainPart);
        $position = $templateProcessor->findContainingXmlBlockForMacro('${title}', 'w:r');

        self::assertEquals($toFind, $templateProcessor->getSlice($position['start'], $position['end']));
    }

    public function testShouldReturnFalseIfXmlBlockNotFound(): void
    {
        $mainPart = '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
            <w:p>
                <w:r>
                    <w:rPr>
                        <w:lang w:val="en-GB"/>
                    </w:rPr>
                    <w:t xml:space="preserve">this is my text containing a ${macro}</w:t>
                </w:r>
            </w:p>
        </w:document>';
        $templateProcessor = new TestableTemplateProcesor($mainPart);

        //non-existing macro
        $result = $templateProcessor->findContainingXmlBlockForMacro('${fake-macro}', 'w:p');
        self::assertFalse($result);

        //existing macro but not inside node looked for
        $result = $templateProcessor->findContainingXmlBlockForMacro('${macro}', 'w:fake-node');
        self::assertFalse($result);

        //existing macro but end tag not found after macro
        $result = $templateProcessor->findContainingXmlBlockForMacro('${macro}', 'w:rPr');
        self::assertFalse($result);
    }

    public function testShouldMakeFieldsUpdateOnOpen(): void
    {
        $settingsPart = '<w:settings xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
            <w:zoom w:percent="100"/>
        </w:settings>';
        $templateProcessor = new TestableTemplateProcesor(null, $settingsPart);

        $templateProcessor->setUpdateFields(true);
        self::assertStringContainsString('<w:updateFields w:val="true"/>', $templateProcessor->getSettingsPart());

        $templateProcessor->setUpdateFields(false);
        self::assertStringContainsString('<w:updateFields w:val="false"/>', $templateProcessor->getSettingsPart());
    }
}
