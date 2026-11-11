<?php

namespace PhpOffice\PhpWordTests\Writer\EPub3\Part;

use PhpOffice\PhpWord\Exception\Exception as WordException;
use PhpOffice\PhpWord\Writer\EPub3\Part\ContentXhtml;
use PHPUnit\Framework\TestCase;

class ContentXhtmlTest extends TestCase
{
    public function testExceptionIfNoParentDoc(): void
    {
        $this->expectException(WordException::class);
        $this->expectExceptionMessage('No PhpWord assigned.');
        $object = new ContentXhtml();
        $object->write();
    }
}
