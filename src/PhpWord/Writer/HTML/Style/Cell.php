<?php

namespace PhpOffice\PhpWord\Writer\HTML\Style;

class Cell extends AbstractStyle
{
    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        /** @var \PhpOffice\PhpWord\Style\Cell $style */
        $style = $this->getStyle();
        $css = array();

        $shading = $style->getShading();
        if ($shading) {
            $css['background-color'] = $this->getValueIf($shading->getFill(), '#' . $shading->getFill());
        }

        $css['border-top-width'] = $this->getValueIf($style->getBorderTopSize() !== null, $style->getBorderTopSize().'px');
        $css['border-top-color'] = $this->getValueIf($style->getBorderTopColor() !== null, '#'.$style->getBorderTopColor());

        $css['border-right-width'] = $this->getValueIf($style->getBorderRightSize() !== null, $style->getBorderRightSize().'px');
        $css['border-right-color'] = $this->getValueIf($style->getBorderRightColor() !== null, '#'.$style->getBorderRightColor());

        $css['border-bottom-width'] = $this->getValueIf($style->getBorderBottomSize() !== null, $style->getBorderBottomSize().'px');
        $css['border-bottom-color'] = $this->getValueIf($style->getBorderBottomColor() !== null, '#'.$style->getBorderBottomColor());

        $css['border-left-width'] = $this->getValueIf($style->getBorderLeftSize() !== null, $style->getBorderLeftSize().'px');
        $css['border-left-color'] = $this->getValueIf($style->getBorderLeftColor() !== null, '#'.$style->getBorderLeftColor());

        $css['vertical-align'] = $this->getValueIf($style->getVAlign() !== null, $style->getVAlign());

        return $this->assembleCss($css);
    }
}
