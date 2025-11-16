<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Style;

use PhpOffice\PhpWord\Writer\EPub3;
use PHPUnit\Framework\TestCase;

class AbstractStyleTest extends TestCase
{
    /**
     * Test setParentWriter and getParentWriter methods.
     */
    public function testParentWriter(): void
    {
        $parentWriter = new EPub3();

        $style = new AbstractStyleClass();

        $result = $style->setParentWriter($parentWriter);

        self::assertSame($style, $result);
        self::assertSame($parentWriter, $style->getParentWriter());
    }
}
