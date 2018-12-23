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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\TextBox as TextBoxStyle;

/**
 * TextBox element
 *
 * @since 0.11.0
 */
class TextBox extends AbstractContainer
{
    /**
     * @var string Container type
     */
    protected $container = 'TextBox';

    /**
     * TextBox style
     *
     * @var \PhpOffice\PhpWord\Style\TextBox
     */
    private $style;

    /**
     * Create a new textbox
     *
     * @param mixed $style
     */
    public function __construct($style = null)
    {
        $this->style = $this->setNewStyle(new TextBoxStyle(), $style);
    }

    /**
     * Get textbox style
     *
     * @return \PhpOffice\PhpWord\Style\TextBox
     */
    public function getStyle()
    {
        return $this->style;
    }
}
