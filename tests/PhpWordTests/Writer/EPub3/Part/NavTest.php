<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Part;

use DOMDocument;
use PhpOffice\PhpWord\Writer\EPub3\Part\Nav;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Writer\EPub3\Part\Nav.
 */
class NavTest extends TestCase
{
    public function testWrite(): void
    {
        $nav = new Nav();
        $xml = $nav->write();

        // Test that valid XML is generated
        $dom = new DOMDocument();
        $dom->loadXML($xml);

        // Test required XML elements and attributes exist
        self::assertEquals('html', $dom->documentElement->nodeName);
        self::assertEquals('http://www.w3.org/1999/xhtml', $dom->documentElement->getAttribute('xmlns'));
        self::assertEquals('http://www.idpf.org/2007/ops', $dom->documentElement->getAttribute('xmlns:epub'));

        // Test nav element
        $navElements = $dom->getElementsByTagName('nav');
        self::assertEquals(1, $navElements->length);
        $navElement = $navElements->item(0);
        self::assertEquals('toc', $navElement->getAttribute('epub:type'));
        self::assertEquals('toc', $navElement->getAttribute('id'));

        // Test title exists
        $titleElements = $dom->getElementsByTagName('title');
        self::assertEquals(1, $titleElements->length);
        self::assertEquals('Navigation', $titleElements->item(0)->nodeValue);

        // Test TOC header exists
        $h1Elements = $dom->getElementsByTagName('h1');
        self::assertEquals(1, $h1Elements->length);
        self::assertEquals('Table of Contents', $h1Elements->item(0)->nodeValue);

        // Test TOC list structure exists
        $olElements = $dom->getElementsByTagName('ol');
        self::assertEquals(1, $olElements->length);
    }
}
