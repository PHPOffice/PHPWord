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

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Textrun/paragraph element.
 */
class TextRun extends AbstractContainer
{
    /**
     * @var string Container type
     */
    protected $container = 'TextRun';

    /**
     * Paragraph style.
     *
     * @var \PhpOffice\PhpWord\Style\Paragraph|string
     */
    protected $paragraphStyle;

    /**
     * Create new instance.
     *
     * @param array|\PhpOffice\PhpWord\Style\Paragraph|string $paragraphStyle
     */
    public function __construct($paragraphStyle = null)
    {
        $this->paragraphStyle = $this->setParagraphStyle($paragraphStyle);
    }

    /**
     * Get Paragraph style.
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph|string
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }

    /**
     * Set Paragraph style.
     *
     * @param array|\PhpOffice\PhpWord\Style\Paragraph|string $style
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph|string
     */
    public function setParagraphStyle($style = null)
    {
        if (is_array($style)) {
            $this->paragraphStyle = new Paragraph();
            $this->paragraphStyle->setStyleByArray($style);
        } elseif ($style instanceof Paragraph) {
            $this->paragraphStyle = $style;
        } elseif (null === $style) {
            $this->paragraphStyle = new Paragraph();
        } else {
            $this->paragraphStyle = $style;
        }

        return $this->paragraphStyle;
    }
}
