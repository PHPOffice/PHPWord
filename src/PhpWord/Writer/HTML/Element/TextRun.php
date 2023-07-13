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

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * TextRun element HTML writer.
 *
 * @since 0.10.0
 */
class TextRun extends Text
{

    /**
     * 是否包含 $tabs
     *
     * @var bool
     */
    protected $tabs = false;

    /**
     * 文本宽度
     *
     * @var int
     */
    protected $textWidth = 0;

    /**
     * 是否遇到空w:text
     *
     * @var int
     */
    protected $isEmptyText = 0;

    /**
     * Write text run.
     *
     * @return string
     */
    public function write()
    {
        $content = '';

        $content .= $this->writeOpening();
        $writer = new Container($this->parentWriter, $this->element, false, $this);
        $content .= $writer->write();
        $content .= $this->writeClosing();

        return $content;
    }

    /**
     *
     * @param boolean $value
     * @author <presleylee@qq.com>
     * @since 2023/7/13 10:33 上午
     */
    public function setTabs($value) {
        $this->tabs = $value;
    }

    /**
     * 获取text是否包含　tabs
     *
     * @return bool
     * @author <presleylee@qq.com>
     * @since 2023/7/13 10:53 上午
     */
    public function getTabs() {
        return $this->tabs;
    }

    public function getTextWidth()
    {
        return $this->textWidth;
    }

    public function setTextWidth($value)
    {
        $this->textWidth = $value;
    }

    public function getIsEmptyText()
    {
        return $this->isEmptyText;
    }

    public function setIsEmptyText($value)
    {
        $this->isEmptyText = $value;
    }
}
