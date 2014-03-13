<?php
namespace PHPWord\Tests;

use PHPWord_TOC;
use PHPWord_Style_TOC;

/**
 * Class TOCTest
 *
 * @package PHPWord\Tests
 * @covers  PHPWord_TOC
 * @runTestsInSeparateProcesses
 */
class TOCTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PHPWord_TOC::__construct
     * @covers PHPWord_TOC::getStyleTOC
     * @covers PHPWord_TOC::getStyleFont
     */
    public function testConstruct()
    {
        $expected = array(
            'tabPos' => 9062,
            'tabLeader' => PHPWord_Style_TOC::TABLEADER_DOT,
            'indent' => 200,
        );
        $object = new PHPWord_TOC(
            array('size' => 11),
            array('tabPos' => $expected['tabPos'])
        );
        $tocStyle = $object->getStyleTOC();

        $this->assertInstanceOf('PHPWord_Style_TOC', $tocStyle);
        $this->assertInstanceOf('PHPWord_Style_Font', $object->getStyleFont());

        foreach ($expected as $key => $value) {
            $method = "get{$key}";
            $this->assertEquals($value, $tocStyle->$method());
        }
    }

    /**
     * @covers PHPWord_TOC::addTitle
     * @covers PHPWord_TOC::getTitles
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

        // @covers PHPWord_TOC::addTitle
        foreach ($titles as $text => $depth) {
            $response = PHPWord_TOC::addTitle($text, $depth);
        }
        $this->assertEquals($anchor, $response[0]);
        $this->assertEquals($bookmark, $response[1]);

        // @covers PHPWord_TOC::getTitles
        $i = 0;
        $savedTitles = PHPWord_TOC::getTitles();
        foreach ($titles as $text => $depth) {
            $this->assertEquals($text, $savedTitles[$i]['text']);
            $this->assertEquals($depth, $savedTitles[$i]['depth']);
            $i++;
        }
    }
}
