<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\TOC;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\TOC
 * @runTestsInSeparateProcesses
 */
class TOCTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getStyleTOC
     * @covers ::getStyleFont
     */
    public function testConstruct()
    {
        $expected = array(
            'tabPos'    => 9062,
            'tabLeader' => \PhpOffice\PhpWord\Style\TOC::TABLEADER_DOT,
            'indent'    => 200,
        );
        $object = new TOC(array('size' => 11), array('tabPos' => $expected['tabPos']));
        $tocStyle = $object->getStyleTOC();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\TOC', $tocStyle);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $object->getStyleFont());

        foreach ($expected as $key => $value) {
            $method = "get{$key}";
            $this->assertEquals($value, $tocStyle->$method());
        }
    }

    /**
     * @covers ::addTitle
     * @covers ::getTitles
     */
    public function testAddAndGetTitle()
    {
        // Prepare variables
        $titleCount = 3;
        $anchor = '_Toc' . (252634154 + $titleCount);
        $bookmark = $titleCount - 1;
        $titles = array(
            'Heading 1' => 1,
            'Heading 2' => 2,
            'Heading 3' => 3,
        );

        // @covers ::addTitle
        foreach ($titles as $text => $depth) {
            $response = TOC::addTitle($text, $depth);
        }
        $this->assertEquals($anchor, $response[0]);
        $this->assertEquals($bookmark, $response[1]);

        // @covers ::getTitles
        $i = 0;
        $savedTitles = TOC::getTitles();
        foreach ($titles as $text => $depth) {
            $this->assertEquals($text, $savedTitles[$i]['text']);
            $this->assertEquals($depth, $savedTitles[$i]['depth']);
            $i++;
        }
    }
}
