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
     * @covers ::zipAddFromString
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

        $embeddingText = "The quick Brown Fox jumped over the lazy^H^H^H^Htired unitTester";
        $templateProcessor->zipAddFromString('word/embeddings/fox.bin', $embeddingText);
        $documentFqfn = $templateProcessor->save();

        $this->assertNotEmpty($documentFqfn, 'FQFN of the saved document is empty.');
        $this->assertFileExists($documentFqfn, "The saved document \"{$documentFqfn}\" doesn't exist.");

        $templateZip = new \ZipArchive();
        $templateZip->open($templateFqfn);
        $templateHeaderXml = $templateZip->getFromName('word/header1.xml');
        $templateMainPartXml = $templateZip->getFromName('word/document.xml');
        $templateFooterXml = $templateZip->getFromName('word/footer1.xml');
        $templateFooterXml = $templateZip->getFromName('word/footer1.xml');
        if (false === $templateZip->close()) {
            throw new \Exception("Could not close zip file \"{$templateZip}\".");
        }

        $documentZip = new \ZipArchive();
        $documentZip->open($documentFqfn);
        $documentHeaderXml = $documentZip->getFromName('word/header1.xml');
        $documentMainPartXml = $documentZip->getFromName('word/document.xml');
        $documentFooterXml = $documentZip->getFromName('word/footer1.xml');
        $documentEmbedding = $documentZip->getFromName('word/embeddings/fox.bin');
        if (false === $documentZip->close()) {
            throw new \Exception("Could not close zip file \"{$documentZip}\".");
        }

        $this->assertNotEquals($templateHeaderXml, $documentHeaderXml);
        $this->assertNotEquals($templateMainPartXml, $documentMainPartXml);
        $this->assertNotEquals($templateFooterXml, $documentFooterXml);

        $this->assertEquals($embeddingText, $documentEmbedding);

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

        $this->assertxmlStringEqualsxmlString($expectedHeaderXml, $actualHeaderXml);
        $this->assertxmlStringEqualsxmlString($expectedMainPartXml, $actualMainPartXml);
        $this->assertxmlStringEqualsxmlString($expectedFooterXml, $actualFooterXml);
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
     * @covers ::findTagLeft
     * @covers ::findTagRight
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
     * @covers ::getRow
     * @covers ::saveAs
     * @covers ::findTagLeft
     * @covers ::findTagRight
     * @test
     */
    public function testGetRow()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-merge.docx');
        $initialArray = array('tableHeader', 'userId', 'userName', 'userLocation');
        $finalArray = array(
            'tableHeader',
            'userId#1', 'userName#1', 'userLocation#1',
            'userId#2', 'userName#2', 'userLocation#2'
        );
        $row = $templateProcessor->getRow('userId');
        $this->assertNotEmpty($row);
        $this->assertEquals(
            $initialArray,
            $templateProcessor->getVariables()
        );
        $row = $templateProcessor->cloneRow('userId', 2);
        $this->assertStringStartsWith('<w:tr', $row);
        $this->assertStringEndsWith('</w:tr>', $row);
        $this->assertNotEmpty($row);
        $this->assertEquals(
            $finalArray,
            $templateProcessor->getVariables()
        );

        $docName = 'test-getRow-result.docx';
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        $this->assertTrue($docFound);
        if ($docFound) {
            $templateProcessorNEWFILE = $this->getOpenTemplateProcessor($docName);
            $this->assertEquals(
                $finalArray,
                $templateProcessorNEWFILE->getVariables()
            );
            unlink($docName);
        }
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
     * Convoluted way to get a helper class, without using include_once (not allowed by phpcs)
     * or inline (not allowed by phpcs) or touching the autoload.php (and make it accessible to users)
     * this helper class returns a TemplateProcessor that allows access to private variables
     * like tempDocumentMainPart, see the functions that use it for usage.
     * eval is evil, but phpcs and phpunit made me do it!
     */
    private function getOpenTemplateProcessor($name)
    {
        if (!file_exists($name) || !is_readable($name)) {
            return null;
        }
        $evalString =
            'class OpenTemplateProcessor extends \PhpOffice\PhpWord\TemplateProcessor {'
            . 'public function __construct($instance){return parent::__construct($instance);}'
            . 'public function __call($method, $args) {return call_user_func_array(array($this, $method), $args);}'
            . 'public function __get($key){return $this->$key;}'
            . 'public function __set($key, $val){return $this->$key = $val;} };'
            . 'return new OpenTemplateProcessor("'.$name.'");';
        return eval($evalString);
    }
    /**
     * @covers ::cloneBlock
     * @covers ::deleteBlock
     * @covers ::getBlock
     * @covers ::saveAs
     * @covers ::findTagLeft
     * @covers ::findTagRight
     * @test
     */
    public function testCloneDeleteBlock()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-delete-block.docx');

        $this->assertEquals(
            array('DELETEME', '/DELETEME', 'CLONEME', '/CLONEME'),
            $templateProcessor->getVariables()
        );
        $cloneTimes = 3;
        $docName = 'clone-delete-block-result.docx';
        $xmlblock = $templateProcessor->getBlock('CLONEME');
        $this->assertNotEmpty($xmlblock);
        $templateProcessor->cloneBlock('CLONEME', $cloneTimes);
        $templateProcessor->deleteBlock('DELETEME');
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        if ($docFound) {
            # Great, so we saved the replaced document, so we open that new document
            # note that we need to access private variables, so we use a sub-class
            $templateProcessorNEWFILE = $this->getOpenTemplateProcessor($docName);
            # We test that all Block variables have been replaced (thus, getVariables() is empty)
            $this->assertEquals(
                array(),
                $templateProcessorNEWFILE->getVariables(),
                "All block variables should have been replaced"
            );
            # we cloned block CLONEME $cloneTimes times, so let's count to $cloneTimes
            $this->assertEquals(
                $cloneTimes,
                substr_count($templateProcessorNEWFILE->tempDocumentMainPart, $xmlblock),
                "Block should be present $cloneTimes in the document"
            );
            unlink($docName); # delete generated file
        }

        $this->assertTrue($docFound);
    }

    /**
     * @covers ::cloneBlock
     * @covers ::getVariables
     * @covers ::getBlock
     * @covers ::findTagLeft
     * @covers ::findTagRight
     * @covers ::setValue
     * @test
     */
    public function testCloneIndexedBlock()
    {
        $templateProcessor = $this->getOpenTemplateProcessor(__DIR__ . '/_files/templates/blank.docx');
        # we will fake a block with a variable inside it, as there is no template document yet.
        $xmlTxt = '<w:p>This ${repeats} a few times</w:p>';
        $xmlStr = '<?xml><w:p>${MYBLOCK}</w:p>' . $xmlTxt . '<w:p>${/MYBLOCK}</w:p>';
        $templateProcessor->tempDocumentMainPart = $xmlStr;

        $this->assertEquals(
            $xmlTxt,
            $templateProcessor->getBlock('MYBLOCK'),
            "Block should be cut at the right place (using findTagLeft/findTagRight)"
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

        $variablesArray = [
            'repeats#1' => 'ONE',
            'repeats#2' => 'TWO',
            'repeats#3' => 'THREE',
            'repeats#4' => 'FOUR'
        ];
        $templateProcessor->setValue(array_keys($variablesArray), array_values($variablesArray));
        $this->assertEquals(
            array(),
            $templateProcessor->getVariables(),
            "Variables have been replaced and should not be present anymore"
        );

        # now we test the order of replacement: ONE,TWO,THREE then FOUR
        $tmpStr = "";
        foreach ($variablesArray as $variable) {
            $tmpStr .= str_replace('${repeats}', $variable, $xmlTxt);
        }
        $this->assertEquals(
            1,
            substr_count($templateProcessor->tempDocumentMainPart, $tmpStr),
            "order of replacement should be: ONE,TWO,THREE then FOUR"
        );

        # Now we try again, but without variable incrementals (old behavior)
        $templateProcessor->tempDocumentMainPart = $xmlStr;
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
            substr_count($templateProcessor->tempDocumentMainPart, $xmlTxt),
            'detects new variable $repeats to be present 4 times'
        );

        # we cloned block CLONEME 4 times, so let's see that there is no space between these blocks
        $this->assertEquals(
            1,
            substr_count($templateProcessor->tempDocumentMainPart, $xmlTxt.$xmlTxt.$xmlTxt.$xmlTxt),
            "The four times cloned block should be the same as four times the block"
        );
    }

    /**
     * @covers ::cloneBlock
     * @covers ::getVariables
     * @covers ::getBlock
     * @covers ::setValue
     * @covers ::findTagLeft
     * @covers ::findTagRight
     * @test
     */
    public function testClosedBlock()
    {
        $templateProcessor = $this->getOpenTemplateProcessor(__DIR__ . '/_files/templates/blank.docx');
        $xmlTxt = '<w:p>This ${BLOCKCLOSE/} is here.</w:p>';
        $xmlStr = '<?xml><w:p>${BEFORE}</w:p>' . $xmlTxt . '<w:p>${AFTER}</w:p>';
        $templateProcessor->tempDocumentMainPart = $xmlStr;

        $this->assertEquals(
            $xmlTxt,
            $templateProcessor->getBlock('BLOCKCLOSE/'),
            "Block should be cut at the right place (using findTagLeft/findTagRight)"
        );

        # detects variables
        $this->assertEquals(
            array('BEFORE', 'BLOCKCLOSE/', 'AFTER'),
            $templateProcessor->getVariables(),
            "Injected document should contain the right initial variables, in the right order"
        );

        # inserting itself should result in no change
        $oldvalue = $templateProcessor->tempDocumentMainPart;
        $block = $templateProcessor->getBlock('BLOCKCLOSE/');
        $templateProcessor->replaceBlock('BLOCKCLOSE/', $block);
        $this->assertEquals(
            $oldvalue,
            $templateProcessor->tempDocumentMainPart,
            "ReplaceBlock should replace at the right position"
        );

        $templateProcessor->cloneBlock('BLOCKCLOSE/', 4);
        # detects new variables
        $this->assertEquals(
            array('BEFORE', 'BLOCKCLOSE/#1', 'BLOCKCLOSE/#2', 'BLOCKCLOSE/#3', 'BLOCKCLOSE/#4', 'AFTER'),
            $templateProcessor->getVariables(),
            "Injected document should contain the right cloned variables, in the right order"
        );

        $templateProcessor->tempDocumentMainPart = $xmlStr;
        $templateProcessor->deleteBlock('BLOCKCLOSE/');
        $this->assertEquals(
            '<?xml><w:p>${BEFORE}</w:p><w:p>${AFTER}</w:p>',
            $templateProcessor->tempDocumentMainPart,
            'closedblock should delete properly'
        );
    }

    /**
     * @covers ::setValue
     * @covers ::saveAs
     * @covers ::findTagLeft
     * @covers ::findTagRight
     * @test
     */
    public function testSetValueMultiline()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-merge.docx');

        $this->assertEquals(
            array('tableHeader', 'userId', 'userName', 'userLocation'),
            $templateProcessor->getVariables()
        );

        $docName = 'multiline-test-result.docx';
        $helloworld = "hello\nworld";
        $templateProcessor->setValue('userName', $helloworld);
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        $this->assertTrue($docFound);
        if ($docFound) {
            # We open that new document (and use the OpenTemplateProcessor to access private variables)
            $templateProcessorNEWFILE = $this->getOpenTemplateProcessor($docName);
            # We test that all Block variables have been replaced (thus, getVariables() is empty)
            $this->assertEquals(
                0,
                substr_count($templateProcessorNEWFILE->tempDocumentMainPart, $helloworld),
                "there should be a multiline"
            );
            # The block it should be turned into:
            $xmlblock = '<w:t>hello</w:t><w:br/><w:t>world</w:t>';
            $this->assertEquals(
                1,
                substr_count($templateProcessorNEWFILE->tempDocumentMainPart, $xmlblock),
                "multiline should be present 1 in the document"
            );
            unlink($docName); # delete generated file
        }
    }

    /**
     * @covers ::replaceBlock
     * @covers ::getBlock
     * @covers ::findTagLeft
     * @covers ::findTagRight
     * @test
     */
    public function testInlineBlock()
    {
        $templateProcessor = $this->getOpenTemplateProcessor(__DIR__ . '/_files/templates/blank.docx');
        $xmlStr = '<?xml><w:p><w:pPr><w:pStyle w:val="Normal"/><w:spacing w:after="160" w:before="0"/>'.
            '<w:rPr/></w:pPr><w:r><w:rPr><w:lang w:val="en-US"/></w:rPr><w:t>This</w:t></w:r>'.
            '<w:r><w:rPr><w:lang w:val="en-US"/></w:rPr><w:t>${inline}</w:t></w:r><w:r><w:rPr><w:b/>'.
            '<w:bCs/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve"> has been'.
            '${/inline}</w:t></w:r><w:r><w:rPr><w:lang w:val="en-US"/></w:rPr>'.
            '<w:t xml:space="preserve"> block</w:t></w:r></w:p>';

        $templateProcessor->tempDocumentMainPart = $xmlStr;

        $this->assertEquals(
            $templateProcessor->getBlock('inline'),
            '</w:t></w:r><w:r><w:rPr><w:b/>'.
            '<w:bCs/><w:lang w:val="en-US"/></w:rPr><w:t xml:space="preserve"> has been',
            "When inside the same <w:p>, cut inside the paragraph"
        );

        $templateProcessor->replaceBlock('inline', 'shows');

        $this->assertEquals(
            $templateProcessor->tempDocumentMainPart,
            '<?xml><w:p><w:pPr><w:pStyle w:val="Normal"/><w:spacing w:after="160" w:before="0"/>'.
            '<w:rPr/></w:pPr><w:r><w:rPr><w:lang w:val="en-US"/></w:rPr>'.
            '<w:t>This</w:t></w:r><w:r><w:rPr><w:lang w:val="en-US"/></w:rPr><w:t>'.
            'shows</w:t></w:r><w:r><w:rPr><w:lang w:val="en-US"/></w:rPr>'.
            '<w:t xml:space="preserve"> block</w:t></w:r></w:p>',
            "InlineBlock replace is malformed"
        );
    }

    /**
     * @covers ::replaceBlock
     * @covers ::cloneBlock
     * @covers ::setValue
     * @test
     */
    public function testSetBlock()
    {
        $templateProcessor = $this->getOpenTemplateProcessor(__DIR__ . '/_files/templates/blank.docx');
        $xmlStr = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
        '<w:document><w:body><w:p></w:p>'.
        '<w:p><w:pPr/><w:r><w:rPr/>'.
        '<w:t xml:space="preserve">BEFORE</w:t>'.
        '<w:t xml:space="preserve">${inline/}</w:t>'.
        '<w:t xml:space="preserve">AFTER</w:t>'.
        '</w:r></w:p>'.
        '<w:p></w:p></w:body></w:document>';

        $templateProcessor->tempDocumentMainPart = $xmlStr;
        $templateProcessor->setBlock('inline/', "one\ntwo");

        // XMLReader::xml($templateProcessor->tempDocumentMainPart)->isValid()

        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<w:document><w:body><w:p></w:p>'.
            '<w:p><w:pPr/><w:r><w:rPr/>'.
            '<w:t xml:space="preserve">BEFORE</w:t>'.
            '<w:t xml:space="preserve">one</w:t>'.
            '<w:t xml:space="preserve">AFTER</w:t>'.
            '</w:r></w:p>'.
            '<w:p><w:pPr/><w:r><w:rPr/>'.
            '<w:t xml:space="preserve">BEFORE</w:t>'.
            '<w:t xml:space="preserve">two</w:t>'.
            '<w:t xml:space="preserve">AFTER</w:t>'.
            '</w:r></w:p>'.
            '<w:p></w:p></w:body></w:document>',
            $templateProcessor->tempDocumentMainPart
        );

        $templateProcessor->tempDocumentMainPart = $xmlStr;
        $templateProcessor->setBlock('inline/', "simplé`");
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<w:document><w:body><w:p></w:p>'.
            '<w:p><w:pPr/><w:r><w:rPr/>'.
            '<w:t xml:space="preserve">BEFORE</w:t>'.
            '<w:t xml:space="preserve">simplé`</w:t>'.
            '<w:t xml:space="preserve">AFTER</w:t>'.
            '</w:r></w:p>'.
            '<w:p></w:p></w:body></w:document>',
            $templateProcessor->tempDocumentMainPart
        );
    }

    /**
     * @covers ::fixBrokenMacros
     * @covers ::ensureMacroCompleted
     * @covers ::getVariables
     * @test
     */
    public function testFixBrokenMacros()
    {
        $templateProcessor = $this->getOpenTemplateProcessor(__DIR__ . '/_files/templates/bad-tags.docx');

        // the only tag that is a real tag
        $this->assertEquals(
            ['tag'],
            $templateProcessor->getVariables()
        );

        $xmlStr = '<w:r><w:t>${</w:t></w:r><w:proofErr w:type="spellStart"/><w:r><w:t>aaaaa</w:t></w:r>'.
            '<w:proofErr w:type="spellEnd"/><w:r><w:t>}</w:t></w:r>';
        $macro = $templateProcessor->ensureMacroCompleted('aaaaa');
        $this->assertEquals(
            $macro,
            '${aaaaa}'
        );

        TemplateProcessor::$ensureMacroCompletion = false;
        $this->assertEquals(
            $templateProcessor->ensureMacroCompleted('aaaaa'),
            'aaaaa'
        );
        TemplateProcessor::$ensureMacroCompletion = true;

        $xmlFixed = $templateProcessor->fixBrokenMacros($xmlStr);
        $this->assertEquals(
            1,
            substr_count($xmlFixed, $macro),
            "could not find '$macro' in: $xmlFixed"
        );
    }

    /**
     * @covers ::failGraciously
     * @covers ::cloneSegment
     * @covers ::replaceSegment
     * @covers ::deleteSegment
     * @test
     */
    public function testFailGraciously()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-merge.docx');

        $this->assertEquals(
            null,
            $templateProcessor->cloneSegment('I-DO-NOT-EXIST', 'w:p', 'MainPart', 1, true, true, false)
        );

        $this->assertEquals(
            false,
            $templateProcessor->cloneSegment('tableHeader', 'DESPACITO', 'MainPart', 1, true, true, false)
        );

        $this->assertEquals(
            null,
            $templateProcessor->replaceSegment('I-DO-NOT-EXIST', 'w:p', 'IOU', 'MainPart', false)
        );

        $this->assertEquals(
            false,
            $templateProcessor->replaceSegment('tableHeader', 'we:be', 'BodyMoving', 'MainPart', false)
        );

        $this->assertEquals(
            false,
            $templateProcessor->deleteSegment('tableHeader', '>sabotage<', 'MainPart', 1, true, true, false)
        );
    }

    /**
     * @covers ::cloneSegment
     * @covers ::getVariables
     * @covers ::setBlock
     * @covers ::saveAs
     * @test
     */
    public function testCloneSegment()
    {
        $testDocument = __DIR__ . '/_files/templates/header-footer.docx';
        $templateProcessor = new TemplateProcessor($testDocument);

        $this->assertEquals(
            ['documentContent', 'headerValue', 'footerValue'],
            $templateProcessor->getVariables()
        );

        $zipFile = new \ZipArchive();
        $zipFile->open($testDocument);
        $originalFooterXml = $zipFile->getFromName('word/footer1.xml');
        if (false === $zipFile->close()) {
            throw new \Exception("Could not close zip file");
        }

        $segment = $templateProcessor->cloneSegment('${footerValue}', 'w:p', 'Footers:1', 2);
        $this->assertNotNull($segment);
        $segment = $templateProcessor->cloneSegment('${headerValue}', 'w:p', 'Headers:1', 2);
        $this->assertNotNull($segment);
        $segment = $templateProcessor->cloneSegment('${documentContent}', 'w:p', 'MainPart', 1);
        $this->assertNotNull($segment);
        $templateProcessor->setBlock('headerValue#1', "In the end, it doesn't even matter.");

        $docName = 'header-footer-test-result.docx';
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        if ($docFound) {
            $zipFile->open($docName);
            $updatedFooterXml = $zipFile->getFromName('word/footer1.xml');
            if (false === $zipFile->close()) {
                throw new \Exception("Could not close zip file");
            }

            $this->assertNotEquals(
                $originalFooterXml,
                $updatedFooterXml
            );

            $templateProcessor2 = new TemplateProcessor($docName);
            $this->assertEquals(
                ['documentContent#1', 'headerValue#2', 'footerValue#1', 'footerValue#2'],
                $templateProcessor2->getVariables()
            );
            unlink($docName);
        }
        $this->assertTrue($docFound);
    }

    /**
     * @covers                   ::failGraciously
     * @covers                   ::cloneSegment
     * @expectedException        \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Can not find segment 'I-DO-NOT-EXIST', text not found or text contains markup
     * @test
     */
    final public function testThrowFailGraciously()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-merge.docx');
        $this->assertEquals(
            null,
            $templateProcessor->cloneSegment('I-DO-NOT-EXIST', 'w:p', 'MainPart', 1, true, true, true)
        );
    }

    /**
     * @covers                   ::failGraciously
     * @covers                   ::replaceSegment
     * @expectedException        \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Can not find segment 'I-DO-NOT-EXIST', text not found or text contains markup
     * @test
     */
    final public function testAnotherThrowFailGraciously()
    {
        $templateProcessor = new TemplateProcessor(__DIR__ . '/_files/templates/clone-merge.docx');
        $this->assertEquals(
            null,
            $templateProcessor->replaceSegment('I-DO-NOT-EXIST', 'w:p', 'IOU', 'MainPart', true)
        );
    }
}
