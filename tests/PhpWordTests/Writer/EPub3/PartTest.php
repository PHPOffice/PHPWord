<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3;

use PhpOffice\PhpWord\Writer\EPub3\Part;
use PHPUnit\Framework\TestCase;

class PartTest extends TestCase
{
    public function testGetPartClass(): void
    {
        $types = ['Content', 'Manifest', 'Meta', 'Mimetype'];

        foreach ($types as $type) {
            $class = Part::getPartClass($type);
            $expectedClass = 'PhpOffice\\PhpWord\\Writer\\EPub3\\Part\\' . $type;

            self::assertEquals($expectedClass, $class);
        }
    }

    public function testGetPartClassWithInvalidType(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);

        Part::getPartClass('InvalidType');
    }
}
