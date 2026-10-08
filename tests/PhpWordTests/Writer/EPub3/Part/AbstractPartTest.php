<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Part;

use PhpOffice\PhpWord\Writer\EPub3;
use PHPUnit\Framework\TestCase;

class AbstractPartTest extends TestCase
{
    public function testParentWriter(): void
    {
        $part = new AbstractPartClass();
        $writer = new EPub3();
        $part->setParentWriter($writer);

        self::assertInstanceOf(EPub3::class, $part->getParentWriter());
    }
}
