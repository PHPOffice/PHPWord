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

namespace PhpOffice\PhpWord\Writer\RTF\Style;

use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\LineSpacingRule;

/**
 * RTF paragraph style writer.
 *
 * @since 0.11.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * Depth of table container nested level; Primarily used for RTF writer/reader.
     *
     * 0 = Not in a table; 1 = in a table; 2 = in a table inside another table, etc.
     *
     * @var int
     */
    private $nestedLevel = 0;

    /**
     * Write style.
     *
     * @return string
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Paragraph) {
            return '';
        }

        $alignments = [
            Jc::START => '\ql',
            Jc::END => '\qr',
            Jc::CENTER => '\qc',
            Jc::BOTH => '\qj',
            Jc::LEFT => '\ql',
            Jc::RIGHT => '\qr',
            Jc::JUSTIFY => '\qj',
            Jc::DISTRIBUTE => '\qd',
            Jc::THAI_DISTRIBUTE => '\qt',
            Jc::HIGH_KASHIDA => '\qk20',
            Jc::MEDIUM_KASHIDA => '\qk10',
            Jc::LOW_KASHIDA => '\qk0',
        ];
        $bidiAlignments = [
            Jc::START => '\qr',
            Jc::END => '\ql',
            Jc::CENTER => '\qc',
            Jc::BOTH => '\qj',
            Jc::LEFT => '\ql',
            Jc::RIGHT => '\qr',
            Jc::JUSTIFY => '\qj',
            Jc::DISTRIBUTE => '\qd',
            Jc::THAI_DISTRIBUTE => '\qt',
            Jc::HIGH_KASHIDA => '\qk20',
            Jc::MEDIUM_KASHIDA => '\qk10',
            Jc::LOW_KASHIDA => '\qk0',
        ];
        $spacingRules = [
            LineSpacingRule::AUTO => '\slmult1',
            LineSpacingRule::EXACT => '\slmult0',
            LineSpacingRule::AT_LEAST => '\slmult0',
        ];

        $spaceAfter = $style->getSpaceAfter();
        $spaceBefore = $style->getSpaceBefore();

        $content = '';
        if ($this->nestedLevel == 0) {
            $content .= '\pard';
        }
        $alignment = $style->getAlignment();
        $bidi = $style->isBidi();
        if ($alignment === '' && $bidi !== null) {
            $alignment = Jc::START;
        }
        if (isset($alignments[$alignment])) {
            $content .= $bidi ? $bidiAlignments[$alignment] : $alignments[$alignment];
        }
        $content .= $this->writeIndentation($style->getIndentation());
        $content .= $this->getValueIf($spaceBefore !== null, '\sb' . round($spaceBefore ?? 0));
        $content .= $this->getValueIf($spaceAfter !== null, '\sa' . round($spaceAfter ?? 0));
        $lineHeight = $style->getLineHeight();
        if ($lineHeight) {
            $lineHeightAdjusted = (int) ($lineHeight * 240);
            $content .= "\\sl$lineHeightAdjusted";
        } else {
            $content .= $this->getValueIf($style->getSpacing() !== null, '\sl' . round($style->getSpacing() ?? 0));
            $spacingRule = $style->getSpacingLineRule();
            if (isset($spacingRules[$spacingRule])) {
                $content .= $this->getValueIf($style->getSpacing() !== null$spacingRules[$spacingRule]);
            }
        }

        $content .= $this->getValueIf($style->hasContextualSpacing(), '\contextualspace');

        // Pagination
        $content .= $this->getValueIf($style->hasWidowControl(), '\widctlpar');
        $content .= $this->getValueIf(!$style->hasWidowControl(), '\nowidctlpar');
        $content .= $this->getValueIf($style->isKeepNext(), '\keepn');
        $content .= $this->getValueIf($style->isKeepLines(), '\keep');
        $content .= $this->getValueIf($style->hasPageBreakBefore(), '\pagebb');
        $content .= $this->getValueIf($style->hasSuppressAutoHyphens(), '\hyphpar0');

        $styles = $style->getStyleValues();
        $content .= $this->writeTabs($styles['tabs']);

        return $content . ' ';
    }

    /**
     * Writes an \PhpOffice\PhpWord\Style\Indentation.
     *
     * @param null|\PhpOffice\PhpWord\Style\Indentation $indent
     *
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
     * Writes tabs.
     *
     * @param \PhpOffice\PhpWord\Style\Tab[] $tabs
     *
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
    public function setNestedLevel($value): void
    {
        $this->nestedLevel = $value;
    }
}
