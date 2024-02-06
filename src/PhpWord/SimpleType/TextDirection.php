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

namespace PhpOffice\PhpWord\SimpleType;

use PhpOffice\PhpWord\Shared\AbstractEnum;

/**
 * Text Direction Values.
 * ST_TextDirection in case link below breaks.
 * Note that TBRL seems clearly to be misnamed.
 * I define it and alias RLTB.
 *
 * @since 2.0.0
 * @see https://learn.microsoft.com/en-us/openspecs/office_standards/ms-oi29500/ad8ec7f9-73a3-41aa-b193-562caeda1103
 */
final class TextDirection extends AbstractEnum
{
    /** Bottom to top, then left to right. */
    const BTLR = 'btLr';

    /** Left to right, then top to bottom. */
    const LRTB = 'lrTb';

    /** Left to right, then top to bottom rotated. */
    const LRTBV = 'lrTbV';

    /** Top to bottom, then left to right rotated. */
    const TBLRV = 'tbLrV';

    /** Right to left, top to bottom, despite acronym. */
    const TBRL = 'tbRl';

    /** Right to left, top to bottom. Alias of misnamed TBRL. */
    const RLTB = 'tbRl';

    /** Top to bottom, right to left rotated. */
    const TBRLV = 'tbRlV';

    const NONE = '';
}
