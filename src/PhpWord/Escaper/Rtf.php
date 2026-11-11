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

namespace PhpOffice\PhpWord\Escaper;

/**
 * @since 0.13.0
 *
 * @codeCoverageIgnore
 */
class Rtf extends AbstractEscaper
{
    /** @return string */
    protected function escapeAsciiCharacter($code)
    {
        if ($code == 9) {
            return '{\\tab}';
        }
        if ($code === 10) {
            return ''; // or maybe '\par'
        }
        if (0x20 > $code || $code >= 0x80) {
            return '{\\u' . $code . '}';
        }
        if ($code == 123 || $code == 125 || $code == 92) { // open or close brace or backslash
            return '\\' . chr($code);
        }

        return chr($code);
    }

    protected function escapeMultibyteCharacter($code)
    {
        if ($code > 32767) {
            return '\\uc0\\u' . ((int) $code - 65536) . ' ';
        }

        return '\\uc0\\u' . $code . ' ';
    }

    /**
     * @param ?string $input
     */
    protected function escapeSingleValue($input)
    {
        $escapedValue = '';
        $utf16 = (string) mb_convert_encoding($input, 'UTF-16BE', 'UTF-8');
        $utf16len = strlen($utf16);
        for ($i = 0; $i < $utf16len; $i += 2) {
            $code = (ord($utf16[$i]) << 8) | ord($utf16[$i + 1]);
            if ($code <= 0x7f) {
                $escapedValue .= $this->escapeAsciiCharacter($code);
            } else {
                $escapedValue .= $this->escapeMultibyteCharacter($code);
            }
        }

        return $escapedValue;
    }
}
