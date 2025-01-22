<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3;

use PhpOffice\PhpWord\Shared\XMLWriter;
use PHPUnit\Framework\TestCase;

class StyleTest extends TestCase
{
    public function testEmptyStyles(): void
    {
        $styles = ['Font', 'Paragraph', 'Table'];
        foreach ($styles as $style) {
            $objectClass = 'PhpOffice\\PhpWord\\Writer\\EPub3\\Style\\' . $style;
            $xmlWriter = new XMLWriter();
            $object = new $objectClass();
            $object->setXmlWriter($xmlWriter);
            $object->write();

            self::assertEquals('', $xmlWriter->getData());
        }
    }
}
