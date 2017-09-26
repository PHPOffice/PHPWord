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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

require_once 'OpenTemplateProcessor.php';

/**
 * @covers \PhpOffice\PhpWord\TemplateProcessor
 * @coversDefaultClass \PhpOffice\PhpWord\TemplateProcessor
 * @runTestsInSeparateProcesses
 */
final class TemplateProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Template can be saved in temporary location.
     *
     * @covers ::save
     * @test
     */
    final public function testTemplateCanBeSavedInTemporaryLocation()
    {
        $templateFqfn = __DIR__ . '/_files/templates/with_table_macros.docx';

        $templateProcessor = new TemplateProcessor($templateFqfn);
        $xslDomDocument = new \DOMDocument();
        $xslDomDocument->load(__DIR__ . "/_files/xsl/remove_tables_by_needle.xsl");
        foreach (array('${employee.', '${scoreboard.', '${reference.') as $needle) {
            $templateProcessor->applyXslStyleSheet($xslDomDocument, array('needle' => $needle));
        }

        $documentFqfn = $templateProcessor->save();

        $this->assertNotEmpty($documentFqfn, 'FQFN of the saved document is empty.');
        $this->assertFileExists($documentFqfn, "The saved document \"{$documentFqfn}\" doesn't exist.");

        $templateZip = new \ZipArchive();
        $templateZip->open($templateFqfn);
        $templateHeaderXml = $templateZip->getFromName('word/header1.xml');
        $templateMainPartXml = $templateZip->getFromName('word/document.xml');
        $templateFooterXml = $templateZip->getFromName('word/footer1.xml');
        if (false === $templateZip->close()) {
            throw new \Exception("Could not close zip file \"{$templateZip}\".");
        }

        $documentZip = new \ZipArchive();
        $documentZip->open($documentFqfn);
        $documentHeaderXml = $documentZip->getFromName('word/header1.xml');
        $documentMainPartXml = $documentZip->getFromName('word/document.xml');
        $documentFooterXml = $documentZip->getFromName('word/footer1.xml');
        if (false === $documentZip->close()) {
            throw new \Exception("Could not close zip file \"{$documentZip}\".");
        }

        $this->assertNotEquals($templateHeaderXml, $documentHeaderXml);
        $this->assertNotEquals($templateMainPartXml, $documentMainPartXml);
        $this->assertNotEquals($templateFooterXml, $documentFooterXml);

        return $documentFqfn;
    }

    /**
     * XSL stylesheet can be applied.
     *
     * @test
     * @covers ::applyXslStyleSheet
     * @depends testTemplateCanBeSavedInTemporaryLocation
     *
     * @param string $actualDocumentFqfn
     *
     * @throws \Exception
     */
    final public function testXslStyleSheetCanBeApplied($actualDocumentFqfn)
    {
        $expectedDocumentFqfn = __DIR__ . '/_files/documents/without_table_macros.docx';

        $actualDocumentZip = new \ZipArchive();
        $actualDocumentZip->open($actualDocumentFqfn);
        $actualHeaderXml = $actualDocumentZip->getFromName('word/header1.xml');
        $actualMainPartXml = $actualDocumentZip->getFromName('word/document.xml');
        $actualFooterXml = $actualDocumentZip->getFromName('word/footer1.xml');
        if (false === $actualDocumentZip->close()) {
            throw new \Exception("Could not close zip file \"{$actualDocumentFqfn}\".");
        }

        $expectedDocumentZip = new \ZipArchive();
        $expectedDocumentZip->open($expectedDocumentFqfn);
        $expectedHeaderXml = $expectedDocumentZip->getFromName('word/header1.xml');
        $expectedMainPartXml = $expectedDocumentZip->getFromName('word/document.xml');
        $expectedFooterXml = $expectedDocumentZip->getFromName('word/footer1.xml');
        if (false === $expectedDocumentZip->close()) {
            throw new \Exception("Could not close zip file \"{$expectedDocumentFqfn}\".");
        }

        $this->assertXmlStringEqualsXmlString($expectedHeaderXml, $actualHeaderXml);
        $this->assertXmlStringEqualsXmlString($expectedMainPartXml, $actualMainPartXml);
        $this->assertXmlStringEqualsXmlString($expectedFooterXml, $actualFooterXml);
    }

    /**
     * XSL stylesheet cannot be applied on failure in setting parameter value.
     *
     * @covers                   ::applyXslStyleSheet
     * @expectedException        \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Could not set values for the given XSL style sheet parameters.
     * @test
     */
    final public function testXslStyleSheetCanNotBeAppliedOnFailureOfSettingParameterValue()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/blank.docx');

        $xslDomDocument = new \DOMDocument();
        $xslDomDocument->load(__DIR__ . '/_files/xsl/passthrough.xsl');

        /*
         * We have to use error control below, because \XSLTProcessor::setParameter omits warning on failure.
         * This warning fails the test.
         */
        @$templateProcessor->applyXslStyleSheet($xslDomDocument, array(1 => 'somevalue'));
    }

    /**
     * XSL stylesheet can be applied on failure of loading XML from template.
     *
     * @covers                   ::applyXslStyleSheet
     * @expectedException        \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Could not load the given XML document.
     * @test
     */
    final public function testXslStyleSheetCanNotBeAppliedOnFailureOfLoadingXmlFromTemplate()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/corrupted_main_document_part.docx');

        $xslDomDocument = new \DOMDocument();
        $xslDomDocument->load(__DIR__ . '/_files/xsl/passthrough.xsl');

        /*
         * We have to use error control below, because \DOMDocument::loadXML omits warning on failure.
         * This warning fails the test.
         */
        @$templateProcessor->applyXslStyleSheet($xslDomDocument);
    }

    /**
     * @covers ::setValue
     * @covers ::cloneRow
     * @covers ::saveAs
     * @test
     */
    public function testCloneRow()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-merge.docx');

        $this->assertEquals(
            array('tableHeader', 'userId', 'userName', 'userLocation'),
            $templateProcessor->getVariables()
        );

        $docName = 'clone-test-result.docx';
        $templateProcessor->setValue('tableHeader', utf8_decode('ééé'));
        $templateProcessor->cloneRow('userId', 1);
        $templateProcessor->setValue('userId#1', 'Test');
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);
        $this->assertTrue($docFound);
    }

    /**
     * @covers ::setValue
     * @covers ::saveAs
     * @test
     */
    public function testMacrosCanBeReplacedInHeaderAndFooter()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/header-footer.docx');

        $this->assertEquals(array('documentContent', 'headerValue', 'footerValue'), $templateProcessor->getVariables());

        $macroNames = array('headerValue', 'documentContent', 'footerValue');
        $macroValues = array('Header Value', 'Document text.', 'Footer Value');
        $templateProcessor->setValue($macroNames, $macroValues);

        $docName = 'header-footer-test-result.docx';
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);
        $this->assertTrue($docFound);
    }

    /**
     * @covers ::cloneBlock
     * @covers ::deleteBlock
     * @covers ::getBlock
     * @covers ::saveAs
     * @test
     */
    public function testCloneDeleteBlock()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-delete-block.docx');

        $this->assertEquals(
            array('DELETEME', '/DELETEME', 'CLONEME', '/CLONEME'),
            $templateProcessor->getVariables()
        );
        $clone_times = 3;
        $docName = 'clone-delete-block-result.docx';
        $xmlblock = $templateProcessor->getBlock('CLONEME');
        $this->assertNotEmpty($xmlblock);
        $templateProcessor->cloneBlock('CLONEME', $clone_times);
        $templateProcessor->deleteBlock('DELETEME');
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        if ($docFound) {
            # Great, so we saved the replaced document, so we open that new document
            # note that we need to access private variables, so we use a sub-class
            $templateProcessorNEWFILE = new OpenTemplateProcessor($docName);
            # We test that all Block variables have been replaced (thus, getVariables() is empty)
            $this->assertEquals(
                [],
                $templateProcessorNEWFILE->getVariables(),
                "All block variables should have been replaced"
            );
            # we cloned block CLONEME $clone_times times, so let's count to $clone_times
            $this->assertEquals(
                $clone_times,
                substr_count($templateProcessorNEWFILE->tempDocumentMainPart, $xmlblock),
                "Block should be present $clone_times in the document"
            );
            unlink($docName); # delete generated file
        }

        $this->assertTrue($docFound);
    }

    /**
     * @covers ::cloneBlock
     * @covers ::getVariables
     * @covers ::getBlock
     * @covers ::setValue
     * @test
     */
    public function testCloneIndexedBlock()
    {
        $templateProcessor = new OpenTemplateProcessor(__DIR__ . '/_files/templates/blank.docx');
        # we will fake a block with a variable inside it, as there is no template document yet.
        $XMLTXT = '<w:p>This ${repeats} a few times</w:p>';
        $XMLSTR = '<?xml><w:p>${MYBLOCK}</w:p>' . $XMLTXT . '<w:p>${/MYBLOCK}</w:p>';
        $templateProcessor->tempDocumentMainPart = $XMLSTR;

        $this->assertEquals(
            $XMLTXT,
            $templateProcessor->getBlock('MYBLOCK'),
            "Block should be cut at the right place (using findBlockStart/findBlockEnd)"
        );

        # detects variables
        $this->assertEquals(
            array('MYBLOCK', 'repeats', '/MYBLOCK'),
            $templateProcessor->getVariables(),
            "Injected document should contain the right initial variables, in the right order"
        );
        
        $templateProcessor->cloneBlock('MYBLOCK', 4);
        # detects new variables
        $this->assertEquals(
            array('repeats#1', 'repeats#2', 'repeats#3', 'repeats#4'),
            $templateProcessor->getVariables(),
            "Injected document should contain the right cloned variables, in the right order"
        );

        $ARR = [
            'repeats#1' => 'ONE',
            'repeats#2' => 'TWO',
            'repeats#3' => 'THREE',
            'repeats#4' => 'FOUR'
        ];
        $templateProcessor->setValue(array_keys($ARR), array_values($ARR));
        $this->assertEquals(
            [],
            $templateProcessor->getVariables(),
            "Variables have been replaced and should not be present anymore"
        );

        # now we test the order of replacement: ONE,TWO,THREE then FOUR
        $STR = "";
        foreach ($ARR as $k => $v) {
            $STR .= str_replace('${repeats}', $v, $XMLTXT);
        }
        $this->assertEquals(
            1,
            substr_count($templateProcessor->tempDocumentMainPart, $STR),
            "order of replacement should be: ONE,TWO,THREE then FOUR"
        );
        
        # Now we try again, but without variable incrementals (old behavior)
        $templateProcessor->tempDocumentMainPart = $XMLSTR;
        $templateProcessor->cloneBlock('MYBLOCK', 4, true, false);

        # detects new variable
        $this->assertEquals(
            array('repeats'),
            $templateProcessor->getVariables(),
            'new variable $repeats should be present'
        );

        # we cloned block CLONEME 4 times, so let's count
        $this->assertEquals(
            4,
            substr_count($templateProcessor->tempDocumentMainPart, $XMLTXT),
            'detects new variable $repeats to be present 4 times'
        );

        # we cloned block CLONEME 4 times, so let's see that there is no space between these blocks
        $this->assertEquals(
            1,
            substr_count($templateProcessor->tempDocumentMainPart, $XMLTXT.$XMLTXT.$XMLTXT.$XMLTXT),
            "The four times cloned block should be the same as four times the block"
        );
    }
}
