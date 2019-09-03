<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Cell as CellStyle;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Length;

/**
 * Table cell element
 */
class Cell extends AbstractContainer
{
    /**
     * @var string Container type
     */
    protected $container = 'Cell';

    /**
     * Cell width
     *
     * @var Length
     */
    private $width;

    /**
     * Cell style
     *
     * @var \PhpOffice\PhpWord\Style\Cell
     */
    private $style;

    /**
     * Create new instance
     *
     * @param Length $width Can be set here or via $style
     * @param array|\PhpOffice\PhpWord\Style\Cell $style
     */
    public function __construct(Length $width = null, $style = null)
    {
        $this->width = $width ?? new Absolute(null);
        $this->style = $this->setNewStyle(new CellStyle(), $style, true);
    }

    /**
     * Get cell style
     *
     * @return \PhpOffice\PhpWord\Style\Cell
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Get cell width
     */
    public function getWidth(): Length
    {
        return $this->width;
    }
}
