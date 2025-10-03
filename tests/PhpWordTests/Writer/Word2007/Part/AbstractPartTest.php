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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Part;

use Exception;
use PhpOffice\PhpWord\Writer\Word2007;

class AbstractPartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * covers   ::setParentWriter
     * covers   ::getParentWriter.
     */
    /** @var string */
    protected static $mockAbstract = 'getMockForAbstractClass';

    /** @param string $method */
    private function methodFound($method): bool
    {
        return method_exists($this, $method);
    }

    public function testSetGetParentWriter(): void
    {
        $method = self::$mockAbstract;
        if (self::methodFound($method)) {
            $stub = $this->$method(Word2007\Part\AbstractPart::class);
        } else {
            /** @var Word2007\Part\AbstractPart $stub */
            $stub = new class() extends Word2007\Part\AbstractPart {
                public function write(): string
                {
                    return '';
                }
            };
        }
        $stub->setParentWriter(new Word2007());
        self::assertEquals(new Word2007(), $stub->getParentWriter());
    }

    /**
     * covers   ::getParentWriter.
     */
    public function testSetGetParentWriterNull(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No parent WriterInterface assigned.');
        $method = self::$mockAbstract;
        if (self::methodFound($method)) {
            $stub = $this->$method(Word2007\Part\AbstractPart::class);
        } else {
            /** @var Word2007\Part\AbstractPart $stub */
            $stub = new class() extends Word2007\Part\AbstractPart {
                public function write(): string
                {
                    return '';
                }
            };
        }
        $stub->getParentWriter();
    }
}
