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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * PageBreak element HTML writer
 *
 * @since 0.10.0
 */
class PageBreak extends TextBreak
{
    /**
     * Write page break
     *
     * @since 0.12.0
     *
     * @return string
     */
    public function write()
    {
        /** @var \PhpOffice\PhpWord\Writer\HTML $parentWriter Type hint */
        $parentWriter = $this->parentWriter;
        if ($parentWriter->isPdf()) {
            return '<pagebreak style="page-break-before: always;" pagebreak="true"></pagebreak>';
        }

        return '';
    }
}
