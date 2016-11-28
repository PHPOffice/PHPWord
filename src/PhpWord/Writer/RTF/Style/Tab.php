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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\CustomRTF\Style;

/**
 * Line numbering style writer
 *
 * @since 0.10.0
 */
class Tab extends AbstractStyle
{
    /**
     * Write style.
     *
     * @return void
     */
    public function write()
    {
        $style = $this->getStyle();
        if (!$style instanceof \PhpOffice\PhpWord\Style\Tab) {
            return;
        }
        $content = '';
        $type = $style->getType();
        if($type == \PhpOffice\PhpWord\Style\Tab::TAB_STOP_RIGHT){
        	$content .= '\tqr';
        }else if($type == \PhpOffice\PhpWord\Style\Tab::TAB_STOP_CENTER){
        	$content .= '\tqc';
        }
        $pos = $style->getPosition();
        if(isset($pos)){
        	$content .= '\tx'.$pos;
        }else{
        	$content .= '\tx';
        }
		
        return $content;
    }
}
