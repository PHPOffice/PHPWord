<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Style;

/**
 * Paragraph style HTML writer
 *
 * @since 0.10.0
 */
class Image extends AbstractStyle
{
    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        if (!($this->style instanceof \PhpOffice\PhpWord\Style\Image)) {
            return;
        }

        $css = array();
        if ($this->style->getWidth()) {
            $css['width'] = $this->style->getWidth() . 'px';
        }
        if ($this->style->getWidth()) {
            $css['height'] = $this->style->getHeight() . 'px';
        }

        return $this->assembleCss($css);
    }
}
