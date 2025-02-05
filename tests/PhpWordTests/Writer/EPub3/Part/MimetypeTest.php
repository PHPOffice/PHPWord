<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\EPub3;
use PhpOffice\PhpWord\Writer\EPub3\Part\Mimetype;
use PHPUnit\Framework\TestCase;

class MimetypeTest extends TestCase
{
    /**
     * @var Mimetype
     */
    private $mimetype;

    protected function setUp(): void
    {
        $this->mimetype = new Mimetype();
        $phpWord = new PhpWord();
        $writer = new EPub3($phpWord);
        $this->mimetype->setParentWriter($writer);
    }

    public function testWrite(): void
    {
        $result = $this->mimetype->write();

        self::assertIsString($result);
        self::assertEquals('application/epub+zip', $result);
    }
}
