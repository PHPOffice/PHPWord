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

namespace PhpOffice\PhpWord\Writer\WPS\Part;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\WriterPartInterface;
use PhpOffice\PhpWord\Writer\WPS;

/**
 * Abstract writer part class
 */
abstract class AbstractPart implements WriterPartInterface
{
    /**
     * Parent writer
     *
     * @var WPS
     */
    protected $parentWriter;
    
    /**
     * @var XMLWriter
     */
    protected $xmlWriter;

    /**
     * Set parent writer.
     */
    public function setParentWriter(\PhpOffice\PhpWord\Writer\AbstractWriter $parentWriter): void
    {
        $this->parentWriter = $parentWriter;
    }

    /**
     * Get parent writer
     */
    public function getParentWriter(): WPS
    {
        return $this->parentWriter;
    }

    /**
     * Get XML Writer
     */
    protected function getXmlWriter(): XMLWriter
    {
        if (!$this->xmlWriter instanceof XMLWriter) {
            $this->xmlWriter = new XMLWriter(Settings::hasCompatibility());
        }

        return $this->xmlWriter;
    }

    /**
     * Write part
     */
    abstract public function write(): string;
}
