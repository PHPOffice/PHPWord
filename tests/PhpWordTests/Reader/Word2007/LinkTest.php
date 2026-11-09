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

namespace PhpOffice\PhpWordTests\Reader\Word2007;

use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    /** @var string */
    private $filename = '';

    protected function tearDown(): void
    {
        if ($this->filename !== '') {
            unlink($this->filename);
            $this->filename = '';
        }
    }

    private function roundTrip(?string $tooltip): Link
    {
        $phpWord = new PhpWord();
        $phpWord->addSection()->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord')->setTooltip($tooltip);
        $this->filename = (string) tempnam(Settings::getTempDir(), 'PhpWord');
        IOFactory::createWriter($phpWord, 'Word2007')->save($this->filename);

        $textRun = IOFactory::load($this->filename)->getSection(0)->getElement(0);
        self::assertInstanceOf(TextRun::class, $textRun);
        $link = $textRun->getElement(0);
        self::assertInstanceOf(Link::class, $link);
        self::assertSame('https://github.com/PHPOffice/PHPWord', $link->getSource());

        return $link;
    }

    public function testReadTooltip(): void
    {
        self::assertSame('The PHPWord repository', $this->roundTrip('The PHPWord repository')->getTooltip());
    }

    public function testReadNoTooltip(): void
    {
        self::assertNull($this->roundTrip(null)->getTooltip());
    }
}
