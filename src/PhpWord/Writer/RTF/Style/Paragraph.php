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

namespace PhpOffice\PhpWord\Writer\RTF\Style;

use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * RTF paragraph style writer
 *
 * @since 0.11.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * Depth of table container nested level; Primarily used for RTF writer/reader
     *
     * 0 = Not in a table; 1 = in a table; 2 = in a table inside another table, etc.
     *
     * @var int
     */
    private $nestedLevel = 0;

    /**
     * Write style
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Paragraph) {
            return '';
        }

        $alignments = array(
            Jc::START  => '\ql',
            Jc::END    => '\qr',
            Jc::CENTER => '\qc',
            Jc::BOTH   => '\qj',
        );

        $spaceAfter = $style->getSpaceAfter();
        $spaceBefore = $style->getSpaceBefore();

        $content = '';
        if ($this->nestedLevel == 0) {
            $content .= '\pard\nowidctlpar ';
        }
        if (isset($alignments[$style->getAlignment()])) {
            $content .= $alignments[$style->getAlignment()];
        }
        $content .= $this->writeIndentation($style->getIndentation());
        $content .= $this->getValueIf($spaceBefore !== null, '\sb' . round($spaceBefore));
        $content .= $this->getValueIf($spaceAfter !== null, '\sa' . round($spaceAfter));
        $lineHeight = $style->getLineHeight();
        if ($lineHeight) {
            $lineHeightAdjusted = (int) ($lineHeight * 240);
            $content .= "\\sl$lineHeightAdjusted\\slmult1";
        }
        if ($style->hasPageBreakBefore()) {
            $content .= '\\page';
        }

        $styles = $style->getStyleValues();
        $content .= $this->writeTabs($styles['tabs']);

        return $content;
    }

    /**
     * Writes an \PhpOffice\PhpWord\Style\Indentation
     *
     * @param null|\PhpOffice\PhpWord\Style\Indentation $indent
     * @return string
     */
    private function writeIndentation($indent = null)
    {
        if (isset($indent) && $indent instanceof \PhpOffice\PhpWord\Style\Indentation) {
            $writer = new Indentation($indent);

            return $writer->write();
        }

        return '';
    }

    /**
     * Writes tabs
     *
     * @param \PhpOffice\PhpWord\Style\Tab[] $tabs
     * @return string
     */
    private function writeTabs($tabs = null)
    {
        $content = '';
        if (!empty($tabs)) {
            foreach ($tabs as $tab) {
                $styleWriter = new Tab($tab);
                $content .= $styleWriter->write();
            }
        }

        return $content;
    }

    /**
     * Set nested level.
     *
     * @param int $value
     */
    public function setNestedLevel($value)
    {
        $this->nestedLevel = $value;
    }
}
