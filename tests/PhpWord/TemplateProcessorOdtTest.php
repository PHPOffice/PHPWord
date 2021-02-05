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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;

/**
 * @covers \PhpOffice\PhpWord\TemplateProcessor
 * @coversDefaultClass \PhpOffice\PhpWord\TemplateProcessor
 * @runTestsInSeparateProcesses
 */
final class TemplateProcessorOdtTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Construct test
     *
     * @covers ::__construct
     * @test
     */
    public function testTheConstructOdt()
    {
        $object = new TemplateProcessorOdt(__DIR__ . '/_files/templates/blank.odt');
        $this->assertInstanceOf('PhpOffice\\PhpWord\\TemplateProcessorOdt', $object);
        $this->assertEquals(array(), $object->getVariables());
    }
    
    /**
     * @covers ::setValue
     * @covers ::cloneRow
     * @covers ::saveAs
     * @test
     */
    public function testCloneRowOdt()
    {
        $templateProcessor = new TemplateProcessorOdt(__DIR__ . '/_files/templates/clone-merge.odt');

        $this->assertEquals(
            array('tableHeader', 'userId', 'userName', 'userLocation'),
            $templateProcessor->getVariables()
        );

        $docName = 'clone-test-result.odt';
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
     * @covers ::cloneRow
     * @covers ::saveAs
     * @test
     */
    public function testCloneRowAndSetValuesOdt()
    {
        $mainPart = '<table:table table:name="Tableau1" table:style-name="Tableau1"><table:table-column table:style-name="Tableau1.A" table:number-columns-repeated="2"/><table:table-row table:style-name="TableLine104066144"><table:table-cell table:style-name="Tableau1.A1" table:number-rows-spanned="2" office:value-type="string"><text:p text:style-name="Standard">${userId}</text:p></table:table-cell><table:table-cell table:style-name="Tableau1.A1" office:value-type="string"><text:p text:style-name="Standard">${userName}</text:p></table:table-cell></table:table-row><table:table-row table:style-name="TableLine104066144"><table:covered-table-cell/><table:table-cell table:style-name="Tableau1.A1" office:value-type="string"><text:p text:style-name="Standard">${userLocation}</text:p></table:table-cell></table:table-row></table:table>';
        $templateProcessor = new TestableTemplateProcesorOdt($mainPart);

        $this->assertEquals(
            array('userId', 'userName', 'userLocation'),
            $templateProcessor->getVariables()
        );

        $values = array(
            array('userId' => 1, 'userName' => 'Batman', 'userLocation' => 'Gotham City'),
            array('userId' => 2, 'userName' => 'Superman', 'userLocation' => 'Metropolis'),
        );
        $templateProcessor->setValue('tableHeader', 'My clonable table');
        $templateProcessor->cloneRowAndSetValues('userId', $values);
        $this->assertContains('Superman</text:p>', $templateProcessor->getMainPart());
        $this->assertContains('Metropolis</text:p>', $templateProcessor->getMainPart());
    }
  

    /**
     * @expectedException \Exception
     * @test
     */
    public function testCloneNotExistingRowShouldThrowException()
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?><office:document-content><office:body><text:p>Text</text:p></office:text></office:body></office:document-content>';
        $templateProcessor = new TestableTemplateProcesorOdt($mainPart);

        $templateProcessor->cloneRow('fake_search', 2);
    }

    /**
     * @covers ::setValue
     * @covers ::saveAs
     * @test
     */
    public function testMacrosCanBeReplacedInHeaderAndFooterOdt()
    {
        $templateProcessor = new TemplateProcessorOdt(__DIR__ . '/_files/templates/header-footer.odt');

        $this->assertEquals(array('documentContent', 'headerValue:100:100', 'footerValue'), $templateProcessor->getVariables());

        $macroNames = array('headerValue', 'documentContent', 'footerValue');
        $macroValues = array('Header Value', 'Document text.', 'Footer Value');
        $templateProcessor->setValue($macroNames, $macroValues);

        $docName = 'header-footer-test-result.odt';
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);
        $this->assertTrue($docFound);
    }

    public function testSetComplexValueOdt()
    {
        $title = new TextRun();
        $title->addText('This is my title');

        $firstname = new Text('Donald');
        $lastname = new Text('Duck');

        $mainPart = '<?xml version="1.0" encoding="UTF-8"?><office:document-content><office:body><text:p>Hello ${document-title}</text:p><text:p>Hello  ${firstname} ${lastname}</text:p></office:text></office:body></office:document-content>';

        $result = '<?xml version="1.0" encoding="UTF-8"?><office:document-content><office:body><text:p text:style-name="Normal"><text:span>This is my title</text:span></text:p><text:p><text:span>Hello  </text:span><text:span>Donald</text:span><text:span> </text:span><text:span>Duck</text:span></text:p></office:text></office:body></office:document-content>';

        $templateProcessor = new TestableTemplateProcesorOdt($mainPart);
        $templateProcessor->setComplexBlock('document-title', $title);
        $templateProcessor->setComplexValue('firstname', $firstname);
        $templateProcessor->setComplexValue('lastname', $lastname);

        $this->assertEquals(preg_replace('/>\s+</', '><', $result), preg_replace('/>\s+</', '><', $templateProcessor->getMainPart()));
    }

    /**
     * @covers ::setValues
     * @test
     */
    public function testSetValues()
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <text:p>Hello ${firstname} ${lastname}</text:p>';

        $templateProcessor = new TestableTemplateProcesorOdt($mainPart);
        $templateProcessor->setValues(array('firstname' => 'John', 'lastname' => 'Doe'));

        $this->assertContains('Hello John Doe', $templateProcessor->getMainPart());
    }

    /**
     * @covers ::setImageValue
     * @test
     */
    public function testSetImageValueOdt()
    {
        // revoir pour la structure spécifique des ODT
        $templateProcessor = new TemplateProcessorOdt(__DIR__ . '/_files/templates/header-footer.odt');
        $imagePath = __DIR__ . '/_files/images/earth.jpg';

        $variablesReplace = array(
                                'headerValue'       => $imagePath,
                                'documentContent'   => array('path' => $imagePath, 'width' => 500, 'height' => 500),
                                'footerValue'       => array('path' => $imagePath, 'width' => 100, 'height' => 50, 'ratio' => false),
        );
        $templateProcessor->setImageValue(array_keys($variablesReplace), $variablesReplace);

        $docName = 'header-footer-images-test-result.odt';
        $templateProcessor->saveAs($docName);

        $this->assertFileExists($docName, "Generated file '{$docName}' not found!");

        $expectedDocumentZip = new \ZipArchive();
        $expectedDocumentZip->open($docName);
        $expectedMainPartXml = $expectedDocumentZip->getFromName('content.xml');
        $expectedStylePartXml = $expectedDocumentZip->getFromName('styles.xml');
        $expectedImage = $expectedDocumentZip->getFromName('Pictures/image_rId0_content.jpeg');
        if (false === $expectedDocumentZip->close()) {
            throw new \Exception("Could not close zip file \"{$docName}\".");
        }

        $this->assertNotEmpty($expectedImage, 'Embed image doesn\'t found.');
        $this->assertContains('Pictures/image_rId0_content.jpeg', $expectedMainPartXml, 'content.xml missed "Pictures/image5_document.jpeg"');
        $this->assertNotContains('${documentContent}', $expectedMainPartXml, 'content.xml has no image.');
        $this->assertNotContains('${headerValue}', $expectedStylePartXml, 'styles.xml header has no image.');
        $this->assertNotContains('${footerValue}', $expectedStylePartXml, 'styles.xml footer has no image.');

        unlink($docName);

        // dynamic generated doc
        $testFileName = 'images-test-sample.odt';
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('${Test:width=100:ratio=true}');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
        $objWriter->save($testFileName);
        $this->assertFileExists($testFileName, "Generated file '{$testFileName}' not found!");

        $resultFileName = 'images-test-result.odt';
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessorOdt($testFileName);
        unlink($testFileName);
        $templateProcessor->setImageValue('Test', $imagePath);
        $templateProcessor->setImageValue('Test1', $imagePath);
        $templateProcessor->setImageValue('Test2', $imagePath);
        $templateProcessor->saveAs($resultFileName);
        $this->assertFileExists($resultFileName, "Generated file '{$resultFileName}' not found!");

        $expectedDocumentZip = new \ZipArchive();
        $expectedDocumentZip->open($resultFileName);
        $expectedMainPartXml = $expectedDocumentZip->getFromName('content.xml');
        if (false === $expectedDocumentZip->close()) {
            throw new \Exception("Could not close zip file \"{$resultFileName}\".");
        }
        unlink($resultFileName);

        $this->assertNotContains('${Test}', $expectedMainPartXml, 'content.xml has no image.');
    }

    /**
     * @covers ::cloneBlock
     * @covers ::deleteBlock
     * @covers ::saveAs
     * @test
     */
    public function testCloneDeleteBlockOdt()
    {
        $templateProcessor = new TemplateProcessorOdt(__DIR__ . '/_files/templates/clone-delete-block.odt');

        $this->assertEquals(
            array('DELETEME', '/DELETEME', 'CLONEME', 'blockVariable', '/CLONEME'),
            $templateProcessor->getVariables()
        );

        $docName = 'clone-delete-block-result.odt';
        $templateProcessor->cloneBlock('CLONEME', 3);
        $templateProcessor->deleteBlock('DELETEME');
        $templateProcessor->setValue('blockVariable#3', 'Test');
        $templateProcessor->saveAs($docName);
        $docFound = file_exists($docName);
        unlink($docName);
        $this->assertTrue($docFound);
    }

    /**
     * @covers ::getVariableCount
     * @test
     */
    public function getVariableCountCountsHowManyTimesEachPlaceholderIsPresentOdt()
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
        $objWriter = IOFactory::createWriter($phpWord, 'ODText');
        $templatePath = 'test.odt';
        $objWriter->save($templatePath);

        $templateProcessor = new TemplateProcessorOdt($templatePath);
        $variableCount = $templateProcessor->getVariableCount();
        unlink($templatePath);

        $this->assertEquals(
            array(
                'a_field_that_is_present_three_times' => 3,
                'a_field_that_is_present_twice'       => 2,
                'a_field_that_is_present_one_time'    => 1,
            ),
            $variableCount
        );
    }

    /**
     * @covers ::cloneBlock
     * @test
     */
    public function cloneBlockCanCloneABlockTwiceOdt()
    {
        // create template with placeholders and block
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $documentElements = array(
            'Title: ${title}',
            '${subreport}',
            '${subreport.id}: ${subreport.text}. ',
            '${/subreport}',
        );
        foreach ($documentElements as $documentElement) {
            $section->addText($documentElement);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'ODText');
        $templatePath = 'test.odt';
        $objWriter->save($templatePath);
        // replace placeholders and save the file
        $templateProcessor = new TemplateProcessorOdt($templatePath);
        $templateProcessor->setValue('title', 'Some title');
        $templateProcessor->cloneBlock('subreport', 2);
        $templateProcessor->setValue('subreport.id', '123', 1);
        $templateProcessor->setValue('subreport.text', 'Some text', 1);
        $templateProcessor->setValue('subreport.id', '456', 1);
        $templateProcessor->setValue('subreport.text', 'Some other text', 1);
        $templateProcessor->saveAs($templatePath);

        $expectedDocumentZip = new \ZipArchive();
        $expectedDocumentZip->open($templatePath);
        $expectedMainPartXml = $expectedDocumentZip->getFromName('content.xml');
        unlink($templatePath);

        // assert the block has been cloned twice
        // and the placeholders have been replaced correctly
        $this->assertContains('Title: Some title', $expectedMainPartXml, 'Title not present');
        $this->assertContains('123: Some text.', $expectedMainPartXml, 'Some text not present');
        $this->assertContains('456: Some other text.', $expectedMainPartXml, 'Some other text not present');
    }

    /**
     * @covers ::cloneBlock
     * @test
     */
    public function testCloneBlock()
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <text:p text:style-name="P1">${CLONEME}</text:p><text:p text:style-name="Standard">This block will be cloned with ${variable}</text:p><text:p text:style-name="Standard">${/CLONEME}</text:p>';

        $templateProcessor = new TestableTemplateProcesorOdt($mainPart);
        $templateProcessor->cloneBlock('CLONEME', 3);
        $this->assertEquals(3, substr_count($templateProcessor->getMainPart(), 'This block will be cloned with ${variable}'));
    }

    /**
     * @covers ::cloneBlock
     * @test
     */
    public function testCloneBlockWithVariablesOdt()
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <text:p text:style-name="P1">${CLONEME}</text:p><text:p text:style-name="Standard">Address ${address}, Street ${street}</text:p><text:p text:style-name="Standard">${/CLONEME}</text:p>';

        $templateProcessor = new TestableTemplateProcesorOdt($mainPart);
        $templateProcessor->cloneBlock('CLONEME', 3, true, true);

        $this->assertContains('Address ${address#1}, Street ${street#1}', $templateProcessor->getMainPart());
        $this->assertContains('Address ${address#2}, Street ${street#2}', $templateProcessor->getMainPart());
        $this->assertContains('Address ${address#3}, Street ${street#3}', $templateProcessor->getMainPart());
    }

    public function testCloneBlockWithVariableReplacementsOdt()
    {
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
        <text:p text:style-name="P1">${CLONEME}</text:p><text:p text:style-name="Standard">City: ${city}, Street: ${street}</text:p><text:p text:style-name="Standard">${/CLONEME}</text:p>';

        $replacements = array(
            array('city' => 'London', 'street' => 'Baker Street'),
            array('city' => 'New York', 'street' => '5th Avenue'),
            array('city' => 'Rome', 'street' => 'Via della Conciliazione'),
        );
        $templateProcessor = new TestableTemplateProcesorOdt($mainPart);
        $templateProcessor->cloneBlock('CLONEME', 0, true, false, $replacements);

        $this->assertContains('City: London, Street: Baker Street', $templateProcessor->getMainPart());
        $this->assertContains('City: New York, Street: 5th Avenue', $templateProcessor->getMainPart());
        $this->assertContains('City: Rome, Street: Via della Conciliazione', $templateProcessor->getMainPart());
    }

    /**
     * Template macros can be fixed.
     *
     * @covers ::fixBrokenMacros
     * @test
     */
/*    public function testFixBrokenMacros()
    {
        $templateProcessor = new TestableTemplateProcesorOdt();
        // TODO

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>normal text</w:t></w:r>');
        $this->assertEquals('<w:r><w:t>normal text</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>${documentContent}</w:t></w:r>');
        $this->assertEquals('<w:r><w:t>${documentContent}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>$</w:t><w:t>{documentContent}</w:t></w:r>');
        $this->assertEquals('<w:r><w:t>${documentContent}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>$1500</w:t><w:t>${documentContent}</w:t></w:r>');
        $this->assertEquals('<w:r><w:t>$1500</w:t><w:t>${documentContent}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>$1500</w:t><w:t>$</w:t><w:t>{documentContent}</w:t></w:r>');
        $this->assertEquals('<w:r><w:t>$1500</w:t><w:t>${documentContent}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:r><w:t>25$ plus some info {hint}</w:t></w:r>');
        $this->assertEquals('<w:r><w:t>25$ plus some info {hint}</w:t></w:r>', $fixed);

        $fixed = $templateProcessor->fixBrokenMacros('<w:t>$</w:t></w:r><w:bookmarkStart w:id="0" w:name="_GoBack"/><w:bookmarkEnd w:id="0"/><w:r><w:t xml:space="preserve">15,000.00. </w:t></w:r><w:r w:rsidR="0056499B"><w:t>$</w:t></w:r><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>{</w:t></w:r><w:proofErr w:type="spellStart"/><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>variable_name</w:t></w:r><w:proofErr w:type="spellEnd"/><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>}</w:t></w:r>');
        $this->assertEquals('<w:t>$</w:t></w:r><w:bookmarkStart w:id="0" w:name="_GoBack"/><w:bookmarkEnd w:id="0"/><w:r><w:t xml:space="preserve">15,000.00. </w:t></w:r><w:r w:rsidR="0056499B"><w:t>${variable_name}</w:t></w:r>', $fixed); 
    }
        */

    /**
     * @covers ::getMainPartName
     */
    public function testMainPartNameDetectionOdt()
    {
        $templateProcessor = new TemplateProcessorOdt(__DIR__ . '/_files/templates/document22-xml.odt');

        $variables = array('test');

        $this->assertEquals($variables, $templateProcessor->getVariables());
    }

    /**
     * @covers ::getVariables
     */
    public function testGetVariablesOdt()
    {
        $templateProcessor = new TestableTemplateProcesorOdt();

        $variables = $templateProcessor->getVariablesForPart('<text:p>normal text</text:p>');
        $this->assertEquals(array(), $variables);

        $variables = $templateProcessor->getVariablesForPart('<text:p>${documentContent}</text:p>');
        $this->assertEquals(array('documentContent'), $variables);
        // TODO Find equivalent noise for ODT format and test it
        /*$variables = $templateProcessor->getVariablesForPart('<w:t>$</w:t></w:r><w:bookmarkStart w:id="0" w:name="_GoBack"/><w:bookmarkEnd w:id="0"/><w:r><w:t xml:space="preserve">15,000.00. </w:t></w:r><w:r w:rsidR="0056499B"><w:t>$</w:t></w:r><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>{</w:t></w:r><w:proofErr w:type="spellStart"/><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>variable_name</w:t></w:r><w:proofErr w:type="spellEnd"/><w:r w:rsidR="00573DFD" w:rsidRPr="00573DFD"><w:rPr><w:iCs/></w:rPr><w:t>}</w:t></w:r>');
        $this->assertEquals(array('variable_name'), $variables);*/
    }

    /**
     * @covers ::textNeedsSplitting
     */
    public function testTextNeedsSplittingOdt()
    {
        $templateProcessor = new TestableTemplateProcesorOdt();

        $this->assertFalse($templateProcessor->textNeedsSplitting('<text:p>${nothing-to-replace}</text:p>'));

        $text = '<text:p>Hello ${firstname} ${lastname}</text:p>';
        $this->assertTrue($templateProcessor->textNeedsSplitting($text));
        $splitText = $templateProcessor->splitTextIntoTexts($text);
        $this->assertFalse($templateProcessor->textNeedsSplitting($splitText));
    }

    /**
     * @covers ::splitTextIntoTexts
     */
    public function testSplitTextIntoTexts()
    {
        $templateProcessor = new TestableTemplateProcesorOdt();

        $splitText = $templateProcessor->splitTextIntoTexts('<text:span>${nothing-to-replace}</text:span>');
        $this->assertEquals('<text:span>${nothing-to-replace}</text:span>', $splitText);

        $splitText = $templateProcessor->splitTextIntoTexts('<text:span text:style-name="T1">Hello ${firstname} ${lastname}</text:span>');
        $this->assertEquals('<text:span text:style-name="T1">Hello </text:span><text:span text:style-name="T1">${firstname}</text:span><text:span text:style-name="T1"> </text:span><text:span text:style-name="T1">${lastname}</text:span>', $splitText);
    }

    public function testFindXmlBlockStart()
    {
        $toFind = '<text:p text:style-name="P1">${title}</text:p>';
        $mainPart = '<?xml version="1.0" encoding="UTF-8"?>
<office:document-content xmlns:grddl="http://www.w3.org/2003/g/data-view#" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:css3t="http://www.w3.org/TR/css3-text/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:formx="urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:dom="http://www.w3.org/2001/xml-events" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:field="urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0" xmlns:of="urn:oasis:names:tc:opendocument:xmlns:of:1.2" xmlns:oooc="http://openoffice.org/2004/calc" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:calcext="urn:org:documentfoundation:names:experimental:calc:xmlns:calcext:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:loext="urn:org:documentfoundation:names:experimental:office:xmlns:loext:1.0" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:ooo="http://openoffice.org/2004/office" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:officeooo="http://openoffice.org/2009/office" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:tableooo="http://openoffice.org/2009/table" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:drawooo="http://openoffice.org/2010/draw" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:ooow="http://openoffice.org/2004/writer" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:rpt="http://openoffice.org/2005/report" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" office:version="1.2"><office:scripts/><office:font-face-decls><style:font-face style:name="Lohit Hindi1" svg:font-family="&apos;Lohit Hindi&apos;"/><style:font-face style:name="Liberation Serif" svg:font-family="&apos;Liberation Serif&apos;" style:font-family-generic="roman" style:font-pitch="variable"/><style:font-face style:name="Liberation Sans" svg:font-family="&apos;Liberation Sans&apos;" style:font-family-generic="swiss" style:font-pitch="variable"/><style:font-face style:name="Lohit Hindi" svg:font-family="&apos;Lohit Hindi&apos;" style:font-family-generic="system" style:font-pitch="variable"/><style:font-face style:name="Source Han Sans" svg:font-family="&apos;Source Han Sans&apos;" style:font-family-generic="system" style:font-pitch="variable"/><style:font-face style:name="Source Han Sans SC" svg:font-family="&apos;Source Han Sans SC&apos;" style:font-family-generic="system" style:font-pitch="variable"/></office:font-face-decls><office:automatic-styles><style:style style:name="P1" style:family="paragraph" style:parent-style-name="Standard"><style:text-properties officeooo:rsid="000d9f48" officeooo:paragraph-rsid="000d9f48"/></style:style></office:automatic-styles><office:body><office:text><text:sequence-decls><text:sequence-decl text:display-outline-level="0" text:name="Illustration"/><text:sequence-decl text:display-outline-level="0" text:name="Table"/><text:sequence-decl text:display-outline-level="0" text:name="Text"/><text:sequence-decl text:display-outline-level="0" text:name="Drawing"/><text:sequence-decl text:display-outline-level="0" text:name="Figure"/></text:sequence-decls><text:p text:style-name="P1">${title}</text:p></office:text></office:body></office:document-content>';

        $templateProcessor = new TestableTemplateProcesorOdt($mainPart);
        $position = $templateProcessor->findContainingXmlBlockForMacro('${title}', 'text:p');

        $this->assertEquals($toFind, $templateProcessor->getSlice($position['start'], $position['end']));
    }

}
