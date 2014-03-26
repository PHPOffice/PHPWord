<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Template;

/**
 * @covers \PhpOffice\PhpWord\Template
 * @coversDefaultClass \PhpOffice\PhpWord\Template
 */
final class TemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::save
     * @test
     */
    final public function testTemplateCanBeSavedInTemporaryLocation()
    {
        $templateFqfn = __DIR__ . "/_files/templates/with_table_macros.docx";

        $document = new Template($templateFqfn);
        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(__DIR__ . "/_files/xsl/remove_tables_by_needle.xsl");
        foreach (array('${employee.', '${scoreboard.') as $needle) {
            $document->applyXslStyleSheet($xslDOMDocument, array('needle' => $needle));
        }

        $documentFqfn = $document->save();

        $this->assertNotEmpty($documentFqfn, 'FQFN of the saved document is empty.');
        $this->assertFileExists($documentFqfn, "The saved document \"{$documentFqfn}\" doesn't exist.");

        $templateZip = new \ZipArchive();
        $templateZip->open($templateFqfn);
        $templateXml = $templateZip->getFromName('word/document.xml');
        if ($templateZip->close() === false) {
            throw new \Exception("Could not close zip file \"{$templateZip}\".");
        }

        $documentZip = new \ZipArchive();
        $documentZip->open($documentFqfn);
        $documentXml = $documentZip->getFromName('word/document.xml');
        if ($documentZip->close() === false) {
            throw new \Exception("Could not close zip file \"{$documentZip}\".");
        }

        $this->assertNotEquals($documentXml, $templateXml);

        return $documentFqfn;
    }

    /**
     * @covers ::applyXslStyleSheet
     * @depends testTemplateCanBeSavedInTemporaryLocation
     * @test
     */
    final public function testXslStyleSheetCanBeApplied($actualDocumentFqfn)
    {
        $expectedDocumentFqfn = __DIR__ . "/_files/documents/without_table_macros.docx";

        $actualDocumentZip = new \ZipArchive();
        $actualDocumentZip->open($actualDocumentFqfn);
        $actualDocumentXml = $actualDocumentZip->getFromName('word/document.xml');
        if ($actualDocumentZip->close() === false) {
            throw new \Exception("Could not close zip file \"{$actualDocumentFqfn}\".");
        }

        $expectedDocumentZip = new \ZipArchive();
        $expectedDocumentZip->open($expectedDocumentFqfn);
        $expectedDocumentXml = $expectedDocumentZip->getFromName('word/document.xml');
        if ($expectedDocumentZip->close() === false) {
            throw new \Exception("Could not close zip file \"{$expectedDocumentFqfn}\".");
        }

        $this->assertXmlStringEqualsXmlString($expectedDocumentXml, $actualDocumentXml);
    }

    /**
     * @covers                   ::applyXslStyleSheet
     * @expectedException        \PhpOffice\PhpWord\Exceptions\Exception
     * @expectedExceptionMessage Could not set values for the given XSL style sheet parameters.
     * @test
     */
    final public function testXslStyleSheetCanNotBeAppliedOnFailureOfSettingParameterValue()
    {
        $template = new Template(__DIR__ . "/_files/templates/blank.docx");

        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(__DIR__ . "/_files/xsl/passthrough.xsl");

        /*
         * We have to use error control below, because \XSLTProcessor::setParameter omits warning on failure.
         * This warning fails the test.
         */
        @$template->applyXslStyleSheet($xslDOMDocument, array(1 => 'somevalue'));
    }

    /**
     * @covers                   ::applyXslStyleSheet
     * @expectedException        \PhpOffice\PhpWord\Exceptions\Exception
     * @expectedExceptionMessage Could not load XML from the given template.
     * @test
     */
    final public function testXslStyleSheetCanNotBeAppliedOnFailureOfLoadingXmlFromTemplate()
    {
        $template = new Template(__DIR__ . "/_files/templates/corrupted_main_document_part.docx");

        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(__DIR__ . "/_files/xsl/passthrough.xsl");

        /*
         * We have to use error control below, because \DOMDocument::loadXML omits warning on failure.
         * This warning fails the test.
         */
        @$template->applyXslStyleSheet($xslDOMDocument);
    }

    /**
     * @covers  ::getVariables
     * @covers  ::setValue
     * @covers  ::cloneRow
     * @covers  ::saveAs
     */
    public function testCloneRow()
    {
        $template = __DIR__ . "/_files/templates/clone-merge.docx";
        $expectedVar = array('tableHeader', 'userId', 'userName', 'userLocation');
        $docName = 'clone-test-result.docx';

        $document = new Template($template);
        $actualVar = $document->getVariables();
        $document->setValue('tableHeader', utf8_decode('ééé'));
        $document->cloneRow('userId', 1);
        $document->setValue('userId#1', 'Test');
        $document->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);

        $this->assertEquals($expectedVar, $actualVar);
        $this->assertTrue($docFound);
    }
}
