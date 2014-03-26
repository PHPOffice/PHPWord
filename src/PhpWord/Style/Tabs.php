<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Tabs style
 */
class Tabs
{
    /**
     * Tabs
     *
     * @var array
     */
    private $_tabs;

    /**
     * Create new tab collection style
     *
     * @param array $tabs
     */
    public function __construct(array $tabs)
    {
        $this->_tabs = $tabs;
    }

    /**
     * Return XML
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter &$xmlWriter
     */
    public function toXml(XMLWriter &$xmlWriter = null)
    {
        if (isset($xmlWriter)) {
            $xmlWriter->startElement("w:tabs");
            foreach ($this->_tabs as &$tab) {
                $tab->toXml($xmlWriter);
            }
            $xmlWriter->endElement();
        }
    }
}
