<?php
namespace PHPWord\Tests;

use PHPWord_Template;

/**
 * @coversDefaultClass PHPWord_Template
 */
final class TemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::save
     * @test
     */
    final public function testTemplateCanBeSavedInTemporaryLocation()
    {
        $templateFqfn = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'with_table_macros.docx')
        );

        $document = new PHPWord_Template($templateFqfn);
        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(
            \join(
                \DIRECTORY_SEPARATOR,
                array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'xsl', 'remove_tables_by_needle.xsl')
            )
        );
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

        return $document;
    }

    /**
     * @covers ::applyXslStyleSheet
     * @depends testTemplateCanBeSavedInTemporaryLocation
     * @test
     */
    final public function testXslStyleSheetCanBeApplied(PHPWord_Template $actualDocument)
    {
        $expectedDocument = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'without_table_macros.docx')
        );

        $actualZip = new \ZipArchive();
        $actualZip->open($actualDocument);
        $actualXml = $actualZip->getFromName('word/document.xml');
        if ($actualZip->close() === false) {
            throw new \Exception("Could not close zip file \"{$actualDocument}\".");
        }

        $expectedZip = new \ZipArchive();
        $expectedZip->open($expectedDocument);
        $expectedXml = $expectedZip->getFromName('word/document.xml');
        if ($expectedZip->close() === false) {
            throw new \Exception("Could not close zip file \"{$expectedDocument}\".");
        }

        $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml);
    }

    /**
     * @covers                   ::applyXslStyleSheet
     * @expectedException        Exception
     * @expectedExceptionMessage Could not set values for the given XSL style sheet parameters.
     * @test
     */
    final public function testXslStyleSheetCanNotBeAppliedOnFailureOfSettingParameterValue()
    {
        $template = new PHPWord_Template(
            \join(
                \DIRECTORY_SEPARATOR,
                array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'blank.docx')
            )
        );

        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(
            \join(
                \DIRECTORY_SEPARATOR,
                array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'xsl', 'passthrough.xsl')
            )
        );

        /*
         * We have to use error control below, because XSLTProcessor::setParameter omits warning on failure.
         * This warning fails the test.
         */
        @$template->applyXslStyleSheet($xslDOMDocument, array(1 => 'somevalue'));
    }

    /**
     * @covers                   ::applyXslStyleSheet
     * @expectedException        Exception
     * @expectedExceptionMessage Could not load XML from the given template.
     * @test
     */
    final public function testXslStyleSheetCanNotBeAppliedOnFailureOfLoadingXmlFromTemplate()
    {
        $template = new PHPWord_Template(
            \join(
                \DIRECTORY_SEPARATOR,
                array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'corrupted_main_document_part.docx')
            )
        );

        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(
            \join(
                \DIRECTORY_SEPARATOR,
                array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'xsl', 'passthrough.xsl')
            )
        );

        /*
         * We have to use error control below, because DOMDocument::loadXML omits warning on failure.
         * This warning fails the test.
         */
        @$template->applyXslStyleSheet($xslDOMDocument);
    }
}
