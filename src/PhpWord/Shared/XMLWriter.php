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

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Settings;

if (!defined('DATE_W3C')) {
    define('DATE_W3C', 'Y-m-d\TH:i:sP');
}

/**
 * XMLWriter wrapper
 *
 * @method bool startElement(string $name)
 * @method bool writeAttribute(string $name, string $value)
 * @method bool endElement()
 */
class XMLWriter
{
    /** Temporary storage method */
    const STORAGE_MEMORY = 1;
    const STORAGE_DISK = 2;

    /**
     * Internal XMLWriter
     *
     * @var \XMLWriter
     */
    private $_xmlWriter;

    /**
     * Temporary filename
     *
     * @var string
     */
    private $_tempFileName = '';

    /**
     * Create new XMLWriter
     *
     * @param int $pTemporaryStorage Temporary storage location
     * @param string $pTemporaryStorageFolder Temporary storage folder
     */
    public function __construct($pTemporaryStorage = self::STORAGE_MEMORY, $pTemporaryStorageFolder = './')
    {
        // Create internal XMLWriter
        $this->_xmlWriter = new \XMLWriter();

        // Open temporary storage
        if ($pTemporaryStorage == self::STORAGE_MEMORY) {
            $this->_xmlWriter->openMemory();
        } else {
            // Create temporary filename
            $this->_tempFileName = @tempnam($pTemporaryStorageFolder, 'xml');

            // Open storage
            if ($this->_xmlWriter->openUri($this->_tempFileName) === false) {
                // Fallback to memory...
                $this->_xmlWriter->openMemory();
            }
        }

        // Set xml Compatibility
        $compatibility = Settings::getCompatibility();
        if ($compatibility) {
            $this->_xmlWriter->setIndent(false);
            $this->_xmlWriter->setIndentString('');
        } else {
            $this->_xmlWriter->setIndent(true);
            $this->_xmlWriter->setIndentString('  ');
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        // Desctruct XMLWriter
        unset($this->_xmlWriter);

        // Unlink temporary files
        if ($this->_tempFileName != '') {
            @unlink($this->_tempFileName);
        }
    }

    /**
     * Get written data
     *
     * @return string XML data
     */
    public function getData()
    {
        if ($this->_tempFileName == '') {
            return $this->_xmlWriter->outputMemory(true);
        } else {
            $this->_xmlWriter->flush();
            return file_get_contents($this->_tempFileName);
        }
    }

    /**
     * Catch function calls (and pass them to internal XMLWriter)
     *
     * @param mixed $function
     * @param mixed $args
     */
    public function __call($function, $args)
    {
        try {
            @call_user_func_array(array($this->_xmlWriter, $function), $args);
        } catch (\Exception $ex) {
            // Do nothing!
        }
    }

    /**
     * Fallback method for writeRaw, introduced in PHP 5.2
     *
     * @param string $text
     * @return string
     */
    public function writeRaw($text)
    {
        if (isset($this->_xmlWriter) && is_object($this->_xmlWriter) && (method_exists($this->_xmlWriter, 'writeRaw'))) {
            return $this->_xmlWriter->writeRaw($text);
        }

        return $this->text($text);
    }
}
