<?php
namespace PHPWord\Tests;

use PHPWord_Template;

/**
 * @coversDefaultClass PHPWord_Template
 */
class PHPWord_TemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::applyXslStyleSheet
     * @test
     */
    final public function testXslStyleSheetCanBeApplied()
    {
        $template = new PHPWord_Template(
            \join(
                \DIRECTORY_SEPARATOR,
                array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'with_table_macros.docx')
            )
        );
        
        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(
            \join(
                \DIRECTORY_SEPARATOR,
                array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'xsl', 'remove_tables_by_needle.xsl')
            )
        );
        
        foreach (array('${employee.', '${scoreboard.') as $needle) {
            $template->applyXslStyleSheet($xslDOMDocument, array('needle' => $needle));
        }
        
        $actualDocument = $template->save();
        $expectedDocument = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'without_table_macros.docx')
        );
        
        $actualZip = new \ZipArchive();
        $actualZip->open($actualDocument);
        $actualXml = $zip->getFromName('word/document.xml');
        if ($actualZip->close() === false) {
            throw new \Exception('Could not close zip file "' . $actualDocument . '".');
        }
                
        $expectedZip = new \ZipArchive();
        $expectedZip->open($expectedDocument);
        $expectedXml = $zip->getFromName('word/document.xml');
        if ($expectedZip->close() === false) {
            throw new \Exception('Could not close zip file "' . $expectedDocument . '".');
        }
        
        $this->assertXmlStringEqualsXmlString(expectedXml, actualXml);
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