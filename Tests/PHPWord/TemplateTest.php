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
        // TODO: implement after merge of the issue https://github.com/PHPOffice/PHPWord/issues/56
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
            \join(\DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'blank.docx'))
        );

        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(
            \join(\DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'xsl', 'passthrough.xsl'))
        );

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
            \join(\DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'templates', 'corrupted_main_document_part.docx'))
        );

        $xslDOMDocument = new \DOMDocument();
        $xslDOMDocument->load(
            \join(\DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'xsl', 'passthrough.xsl'))
        );

        @$template->applyXslStyleSheet($xslDOMDocument);
    }
}