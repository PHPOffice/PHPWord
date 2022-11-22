<?php

namespace PhpOffice\PhpWord\Writer\HTML\Style;

/**
 * Table style HTML writer.
 *
 * @since 1.0.1
 */
class Table extends AbstractStyle
{
    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Table) {
            return '';
        }
        $css = [];
        if ($style->getLayout() == \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED) {
            $css['table-layout'] = 'fixed';
        } elseif ($style->getLayout() == \PhpOffice\PhpWord\Style\Table::LAYOUT_AUTO) {
            $css['table-layout'] = 'auto';
        }
        $css = array_merge($css, $this->getBorderStyles());
        return $this->assembleCss($css);
    }
}