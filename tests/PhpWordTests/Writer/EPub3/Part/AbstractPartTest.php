<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Part;

use PhpOffice\PhpWord\Writer\EPub3;
use PhpOffice\PhpWord\Writer\EPub3\Part\AbstractPart;
use PHPUnit\Framework\TestCase;

class AbstractPartTest extends TestCase
{
    /**
     * @var AbstractPart
     */
    private $part;

    protected function setUp(): void
    {
        if (method_exists($this, 'getMockForAbstractClass')) {
            $this->part = $this->getMockForAbstractClass(AbstractPart::class);
        } else {
            $this->part = new class() extends AbstractPart {
                public function write(): string
                {
                    return '';
                }
            };
        }
    }

    public function testParentWriter(): void
    {
        $writer = new EPub3();
        $this->part->setParentWriter($writer);

        self::assertInstanceOf(EPub3::class, $this->part->getParentWriter());
    }
}
