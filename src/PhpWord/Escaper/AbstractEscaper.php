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

namespace PhpOffice\PhpWord\Escaper;

use Laminas\Escaper\Escaper;
/**
 * @since 0.13.0
 *
 * @codeCoverageIgnore
 */
abstract class AbstractEscaper implements EscaperInterface
{
    /**
     * @param string $input
     *
     * @return string
     */
    abstract protected function escapeSingleValue($input);

    public function escape($input)
    {
        if (is_array($input)) {
            foreach ($input as &$item) {
                $item = $this->escapeSingleValue($item);
            }
        } else {
            $input = $this->escapeSingleValue($input);
        }

        return $input;
    }
    
    public function escapeHtml($input)
    {
        $escaper = new Escaper();
        
        if (is_array($input)) {
            foreach ($input as &$item) {
                $item = $escaper->escapeHtml($item);
            }
        } else {
            $input = $escaper->escapeHtml($input);
        }

        return $input;
    }
    
    public function escapeJs($input)
    {
        $escaper = new Escaper();
        
        if (is_array($input)) {
            foreach ($input as &$item) {
                $item = $escaper->escapeJs($item);
            }
        } else {
            $input = $escaper->escapeJs($input);
        }

        return $input;
    }
    
    public function escapeCss($input)
    {
        $escaper = new Escaper();
        
        if (is_array($input)) {
            foreach ($input as &$item) {
                $item = $escaper->escapeCss($item);
            }
        } else {
            $input = $escaper->escapeCss($input);
        }

        return $input;
    }
    
    public function escapeHtmlAttr($input)
    {
        $escaper = new Escaper();
        
        if (is_array($input)) {
            foreach ($input as &$item) {
                $item = $escaper->escapeHtmlAttr($item);
            }
        } else {
            $input = $escaper->escapeHtmlAttr($input);
        }

        return $input;
    }
    
    public function escapeUrl($input)
    {
        $escaper = new Escaper();
        
        if (is_array($input)) {
            foreach ($input as &$item) {
                $item = $escaper->escapeUrl($item);
            }
        } else {
            $input = $escaper->escapeUrl($input);
        }

        return $input;
    }
}
