<?php

namespace PhpOffice\PhpWord\Writer\HTML\Style;

/**
 * Cell style HTML writer.
 *
 * @since 1.0.1
 */
class Cell extends AbstractStyle
{

    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Cell) {
            return '';
        }
        $css = [];
        $css = array_merge($css, $this->getBorderStyles());
        return $this->assembleCss($css);
    }
}