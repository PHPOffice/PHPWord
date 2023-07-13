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

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Exception\InvalidStyleException;
use PhpOffice\PhpWord\Shared\Text;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TextAlignment;

/**
 * Latent style.
 *
 * OOXML:
 * - General: alignment, outline level
 * - Indentation: left, right, firstline, hanging
 * - Spacing: before, after, line spacing
 * - Pagination: widow control, keep next, keep line, page break before
 * - Formatting exception: suppress line numbers, don't hyphenate
 * - Textbox options
 * - Tabs
 * - Shading
 * - Borders
 *
 * OpenOffice:
 * - Indents & spacing
 * - Alignment
 * - Text flow
 * - Outline & numbering
 * - Tabs
 * - Dropcaps
 * - Tabs
 * - Borders
 * - Background
 *
 * @see  http://www.schemacentral.com/sc/ooxml/t-w_CT_PPr.html
 */
class Latent extends Border
{

    /**
     * 潜在样式的数量。
     *
     * @var integer
     */
    private $count;

    /**
     * 指定默认情况下是否应用 Quick Format（快速样式）
     *
     * @var
     */
    private $defQFormat;

    /**
     * 默认情况下是否在使用时显示潜在样式
     *
     * @var
     */
    private $defUnhideWhenUsed;

    /**
     * 默认情况下是否将潜在样式设置为半隐藏状态
     *
     * @var
     */
    private $defSemiHidden;

    /**
     * 默认情况下潜在样式的用户界面优先级
     *
     * @var
     */
    private $defUIPriority;

    /**
     * 默认情况下潜在样式的锁定状态
     *
     * @var
     */
    private $defLockedState;

    /**
     * 例外样式的定义。
     * qFormat
     * unhideWhenUsed
     * uiPriority
     * defSemiHidden
     * defUIPriority
     * defLockedState
     * semiHidden
     * name
     *
     * @var array
     */
    private $lsdExceptions = [];


    /**
     * Set Style value.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return self
     */
    public function setStyleValue($key, $value)
    {
        $key = Text::removeUnderscorePrefix($key);
        return parent::setStyleValue($key, $value);
    }

    /**
     * Get style values.
     *
     * An experiment to retrieve all style values in one function. This will
     * reduce function call and increase cohesion between functions. Should be
     * implemented in all styles.
     *
     * @ignoreScrutinizerPatch
     *
     * @return array
     */
    public function getStyleValues()
    {
        $styles = [
            'count' => $this->getCount(),
            'defQFormat' => $this->getDefQFormat(),
            'defUnhideWhenUsed' => $this->getDefUnhideWhenUsed(),
            'defSemiHidden' => $this->getDefSemiHidden(),
            'defUIPriority' => $this->getDefUIPriority(),
            'defLockedState' => $this->getDefLockedState(),
            'lsdExceptions' => $this->getLsdExceptions(),
        ];

        return $styles;
    }

    /**
     * @since 0.13.0
     *
     * @return string
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @since 0.13.0
     *
     * @param string $value
     *
     * @return self
     */
    public function setCount($value)
    {
        $this->count = $value;

        return $this;
    }

    /**
     * Get parent style ID.
     *
     * @return string
     */
    public function getDefQFormat()
    {
        return $this->defQFormat;
    }

    /**
     * Set parent style ID.
     *
     * @param string $value
     *
     * @return self
     */
    public function setDefQFormat($value = 'Normal')
    {
        $this->defQFormat = $value;

        return $this;
    }

    /**
     * Get style for next paragraph.
     *
     * @return string
     */
    public function getDefUnhideWhenUsed()
    {
        return $this->defUnhideWhenUsed;
    }

    /**
     * Set style for next paragraph.
     *
     * @param string $value
     *
     * @return self
     */
    public function setDefUnhideWhenUsed($value = null)
    {
        $this->defUnhideWhenUsed = $value;

        return $this;
    }

    /**
     * Get shading.
     *
     * @return \PhpOffice\PhpWord\Style\Indentation
     */
    public function getDefSemiHidden()
    {
        return $this->defSemiHidden;
    }

    /**
     * Get shading.
     *
     * @return \PhpOffice\PhpWord\Style\Indentation
     */
    public function setDefSemiHidden($value = NULL)
    {
        $this->defSemiHidden = $value;

        return $this;
    }

    /**
     * Get shading.
     *
     * @return \PhpOffice\PhpWord\Style\Indentation
     */
    public function setDefUIPriority($value = NULL)
    {
        $this->defUIPriority = $value;

        return $this;
    }

    /**
     * Get shading.
     *
     * @return \PhpOffice\PhpWord\Style\Indentation
     */
    public function getDefUIPriority(){
        return $this->defUIPriority;
    }

    /**
     * Set shading.
     *
     * @param mixed $value
     *
     * @return self
     */
    public function setDefLockedState($value = null)
    {
        $this->defLockedState = $value;

        return $this;
    }

    /**
     * Get indentation.
     *
     * @return int
     */
    public function getDefLockedState()
    {
        return $this->defLockedState;
    }

    public function setLsdExceptions($value = null) {
        if ($value != NULL && is_array($value)) {
            $this->lsdExceptions = $value;
        }
        return $this;
    }

    public function getLsdExceptions()
    {
        return $this->lsdExceptions;
    }
}
