<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font as FontStyle;

/**
 * Font style HTML writer
 *
 * @since 0.10.0
 */
class Font extends AbstractStyle
{
    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Font)) {
            return;
        }

        $css = array();
        if (PhpWord::DEFAULT_FONT_NAME != $this->style->getName()) {
            $css['font-family'] = "'" . $this->style->getName() . "'";
        }
        if (PhpWord::DEFAULT_FONT_SIZE != $this->style->getSize()) {
            $css['font-size'] = $this->style->getSize() . 'pt';
        }
        if (PhpWord::DEFAULT_FONT_COLOR != $this->style->getColor()) {
            $css['color'] = '#' . $this->style->getColor();
        }
        $css['background'] = $this->style->getFgColor();
        if ($this->style->getBold()) {
            $css['font-weight'] = 'bold';
        }
        if ($this->style->getItalic()) {
            $css['font-style'] = 'italic';
        }
        if ($this->style->getSuperScript()) {
            $css['vertical-align'] = 'super';
        } elseif ($this->style->getSubScript()) {
            $css['vertical-align'] = 'sub';
        }
        $css['text-decoration'] = '';
        if ($this->style->getUnderline() != FontStyle::UNDERLINE_NONE) {
            $css['text-decoration'] .= 'underline ';
        }
        if ($this->style->getStrikethrough()) {
            $css['text-decoration'] .= 'line-through ';
        }

        return $this->assembleCss($css);
    }
}
