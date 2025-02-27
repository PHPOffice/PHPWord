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
        $style = $this->getMockBuilder(AbstractStyle::class)->getMock();

        $style->expects(static::once())
            ->method('setParentWriter')
            ->willReturn($style);
        $result = $style->setParentWriter($parentWriter);
        $style->expects(static::once())
            ->method('getParentWriter')
            ->willReturn($parentWriter);

        self::assertSame($style, $result);
        self::assertSame($parentWriter, $style->getParentWriter());
    }
}
