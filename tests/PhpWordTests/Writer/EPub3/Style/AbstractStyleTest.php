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
        if (method_exists($this, 'getMockForAbstractClass')) {
            $style = $this->getMockForAbstractClass(AbstractStyle::class);
        } else {
            /** @var AbstractStyle $style */
            $style = new class() extends AbstractStyle {
                public function write(): string
                {
                    return '';
                }
            };
        }

        $result = $style->setParentWriter($parentWriter);

        self::assertSame($style, $result);
        self::assertSame($parentWriter, $style->getParentWriter());
    }
}
