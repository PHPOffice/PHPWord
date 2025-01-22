<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Style;

use PhpOffice\PhpWord\Writer\EPub3;
use PhpOffice\PhpWord\Writer\EPub3\Style\AbstractStyle;
use PHPUnit\Framework\TestCase;

class AbstractStyleTest extends TestCase
{
    /**
     * Test setParentWriter and getParentWriter methods.
     */
    public function testParentWriter(): void
    {
        $parentWriter = new EPub3();
        $style = $this->getMockForAbstractClass(AbstractStyle::class);

        $result = $style->setParentWriter($parentWriter);

        $this->assertSame($style, $result);
        $this->assertSame($parentWriter, $style->getParentWriter());
    }
}
