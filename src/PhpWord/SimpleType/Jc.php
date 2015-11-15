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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\SimpleType;

use Zend\Validator\InArray;

/**
 * Horizontal Alignment Type.
 *
 * @since 0.13.0
 *
 * @codeCoverageIgnore
 */
final class Jc
{
    const START = 'start';
    const CENTER = 'center';
    const END = 'end';
    const BOTH = 'both';
    const MEDIUM_KASHIDA = 'mediumKashida';
    const DISTRIBUTE = 'distribute';
    const NUM_TAB = 'numTab';
    const HIGH_KASHIDA = 'highKashida';
    const LOW_KASHIDA = 'lowKashida';
    const THAI_DISTRIBUTE = 'thaiDistribute';

    /**
     * @deprecated 0.13.0 Use `START` instead.
     */
    const LEFT = 'left';
    /**
     * @deprecated 0.13.0 Use `END` instead.
     */
    const RIGHT = 'right';
    /**
     * @deprecated 0.13.0 Use `BOTH` instead.
     */
    const JUSTIFY = 'justify';

    /**
     * @since 0.13.0
     *
     * @return \Zend\Validator\InArray
     */
    final public static function getValidator()
    {
        // todo: consider caching validator instances.
        return new InArray(
            array (
                'haystack' => array(
                    self::START,
                    self::CENTER,
                    self::END,
                    self::BOTH,
                    self::MEDIUM_KASHIDA,
                    self::DISTRIBUTE,
                    self::NUM_TAB,
                    self::HIGH_KASHIDA,
                    self::LOW_KASHIDA,
                    self::THAI_DISTRIBUTE,
                    self::LEFT,
                    self::RIGHT,
                    self::JUSTIFY,
                ),
                'strict'   => InArray::COMPARE_STRICT,
            )
        );
    }
}
