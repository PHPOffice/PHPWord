<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Style;

use PhpOffice\PhpWord\Writer\EPub3;
use PhpOffice\PhpWord\Writer\EPub3\Style\AbstractStyle;
use PHPUnit\Framework\TestCase;

class AbstractStyleTest extends TestCase
{
    /** @var string */
    protected static $mockAbstract = 'getMockForAbstractClass';

    /** @param string $method */
    private function methodFound($method): bool
    {
        return method_exists($this, $method);
    }

    /**
     * Test setParentWriter and getParentWriter methods.
     */
    public function testParentWriter(): void
    {
        $parentWriter = new EPub3();
        $mockAbstract = self::$mockAbstract;
        if ($this->methodFound($mockAbstract)) {
            $style = $this->$mockAbstract(AbstractStyle::class);
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
