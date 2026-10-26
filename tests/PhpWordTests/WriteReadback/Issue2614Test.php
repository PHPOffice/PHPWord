<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\WriteReadback;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Writer\Word2007 as Word2007Writer;

class Issue2614Test extends \PHPUnit\Framework\TestCase
{
    public function testIssue2614(): void
    {
        $infile = 'tests/PhpWordTests/_files/templates/word.2614.docx';
        $objReader = IOFactory::createReader('Word2007');
        $phpWordWriter = $objReader->load($infile);
        $writer = new Word2007Writer($phpWordWriter);
        $file = tempnam(sys_get_temp_dir(), 'PhpWord');
        self::assertNotFalse($file);
        $writer->save($file);
        self::assertFileExists($file);

        $phpWordReader = IOFactory::load($file, 'Word2007');
        unlink($file);

        $comments = $phpWordReader->getComments();
        self::assertSame(1, $comments->countItems());
        $comment = $comments->getItem(0);
        self::assertSame('E Turtle', $comment->getAuthor());
        $elements = $comment->getElements();
        self::assertCount(1, $elements);
        self::assertInstanceOf(Text::class, $elements[0]);
        self::assertSame('Test', $elements[0]->getText());
    }
}
