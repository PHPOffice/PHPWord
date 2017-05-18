<?php

namespace PhpOffice\PhpWord\Writer\HTML\Style;

class Table extends AbstractStyle
{
    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        /** @var \PhpOffice\PhpWord\Style\Table $style */
        $style = $this->getStyle();
        $css = array();


        $css['border-top-width'] = $this->getValueIf($style->getBorderTopSize() !== null, $style->getBorderTopSize().'px');
        $css['border-top-color'] = $this->getValueIf($style->getBorderTopColor() !== null, '#'.$style->getBorderTopColor());

        $css['border-right-width'] = $this->getValueIf($style->getBorderRightSize() !== null, $style->getBorderRightSize().'px');
        $css['border-right-color'] = $this->getValueIf($style->getBorderRightColor() !== null, '#'.$style->getBorderRightColor());

        $css['border-bottom-width'] = $this->getValueIf($style->getBorderBottomSize() !== null, $style->getBorderBottomSize().'px');
        $css['border-bottom-color'] = $this->getValueIf($style->getBorderBottomColor() !== null, '#'.$style->getBorderBottomColor());

        $css['border-left-width'] = $this->getValueIf($style->getBorderLeftSize() !== null, $style->getBorderLeftSize().'px');
        $css['border-left-color'] = $this->getValueIf($style->getBorderLeftColor() !== null, '#'.$style->getBorderLeftColor());

        return $this->assembleCss($css);
    }
}
