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

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\Element\Bookmark;

/**
 * Test class for PhpOffice\PhpWord\Element\Footer.
 *
 * @runTestsInSeparateProcesses
 */
class BookmarkTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance.
     */
    public function testConstruct(): void
    {
        $bookmarkName = 'test';
        $oBookmark = new Bookmark($bookmarkName);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Bookmark', $oBookmark);
        self::assertEquals($bookmarkName, $oBookmark->getName());
    }
}
