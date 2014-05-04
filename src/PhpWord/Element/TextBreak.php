<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Text break element
 */
class TextBreak extends AbstractElement
{
    /**
     * Paragraph style
     *
     * @var string|\PhpOffice\PhpWord\Style\Paragraph
     */
    private $paragraphStyle = null;

    /**
     * Text style
     *
     * @var string|\PhpOffice\PhpWord\Style\Font
     */
    private $fontStyle = null;

    /**
     * Create a new TextBreak Element
     *
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     */
    public function __construct($fontStyle = null, $paragraphStyle = null)
    {
        if (!is_null($paragraphStyle)) {
            $paragraphStyle = $this->setParagraphStyle($paragraphStyle);
        }
        if (!is_null($fontStyle)) {
            $this->setFontStyle($fontStyle, $paragraphStyle);
        }
    }

    /**
     * Set Text style
     *
     * @param mixed $style
     * @param mixed $paragraphStyle
     * @return string|\PhpOffice\PhpWord\Style\Font
     */
    public function setFontStyle($style = null, $paragraphStyle = null)
    {
        if ($style instanceof Font) {
            $this->fontStyle = $style;
            $this->setParagraphStyle($paragraphStyle);
        } elseif (is_array($style)) {
            $this->fontStyle = new Font('text', $paragraphStyle);
            $this->fontStyle->setArrayStyle($style);
        } else {
            $this->fontStyle = $style;
            $this->setParagraphStyle($paragraphStyle);
        }
        return $this->fontStyle;
    }

    /**
     * Get Text style
     *
     * @return string|\PhpOffice\PhpWord\Style\Font
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * Set Paragraph style
     *
     * @param   string|array|\PhpOffice\PhpWord\Style\Paragraph $style
     * @return  string|\PhpOffice\PhpWord\Style\Paragraph
     */
    public function setParagraphStyle($style = null)
    {
        if (is_array($style)) {
            $this->paragraphStyle = new Paragraph;
            $this->paragraphStyle->setArrayStyle($style);
        } elseif ($style instanceof Paragraph) {
            $this->paragraphStyle = $style;
        } else {
            $this->paragraphStyle = $style;
        }
        return $this->paragraphStyle;
    }

    /**
     * Get Paragraph style
     *
     * @return string|\PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }
}
