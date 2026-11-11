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

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Exception\Exception as WordException;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Element\Image.
 */
class ImageInvalidProtocolTest extends TestCase
{
    /**
     * Valid image types.
     *
     * @dataProvider providerInvalidProtocol
     */
    public function testInvalidProtocol(string $url): void
    {
        $this->expectException(WordException::class);
        $this->expectExceptionMessage('Invalid protocol');
        $object = new Image($url);
        $source = $object->getSource();
    }

    public static function providerInvalidProtocol(): array
    {
        return [
            'normal phar' => ['phar://anything'],
            'mixed case phar' => ['PHAR://anything'],
            'phar with 3 slashes' => ['phar:///anything'],
            'leading space' => [' phar:///anything'],
            'embedded space' => ['ph ar:///anything'],
            'control character' => ["ph\x14ar:///anything"],
            'filter with phar' => ['php://filter/read=convert.base64-encode/resource=phar:///tmp/x.Phar'],
            'filter with phar and newline' => ["php://filter/read=convert.base64-encode/\nresource=phar:///tmp/x.Phar"],
            'protocol with period followed by phar' => ['compress.zlib://phar:///x.phar'],
            'protocol with period and embedded space' => ['comp ress.zlib://anything'],
        ];
    }
}
