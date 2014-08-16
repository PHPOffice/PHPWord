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

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Settings;

/**
 * XMLWriter wrapper
 *
 * @method bool endElement()
 * @method bool startDocument(string $version = 1.0, string $encoding = null, string $standalone = null)
 * @method bool startElement(string $name)
 * @method bool text(string $content)
 * @method bool writeAttribute(string $name, mixed $value)
 * @method bool writeElement(string $name, string $content = null)
 * @method bool writeRaw(string $content)
 */
class XMLWriter
{
    /** Temporary storage location */
    const STORAGE_MEMORY = 1;
    const STORAGE_DISK = 2;

    /**
     * Internal XMLWriter
     *
     * @var \XMLWriter
     */
    private $xmlWriter;

    /**
     * Temporary filename
     *
     * @var string
     */
    private $tempFile = '';

    /**
     * Create new XMLWriter
     *
     * @param int $tempLocation Temporary storage location
     * @param string $tempFolder Temporary storage folder
     */
    public function __construct($tempLocation = self::STORAGE_MEMORY, $tempFolder = './')
    {
        // Create internal XMLWriter
        $this->xmlWriter = new \XMLWriter();

        // Open temporary storage
        if ($tempLocation == self::STORAGE_MEMORY) {
            $this->xmlWriter->openMemory();
        } else {
            // Create temporary filename
            $this->tempFile = tempnam($tempFolder, 'xml');

            // Fallback to memory when temporary file cannot be used
            // @codeCoverageIgnoreStart
            // Can't find any test case. Uncomment when found.
            if (false === $this->tempFile || false === $this->xmlWriter->openUri($this->tempFile)) {
                $this->xmlWriter->openMemory();
            }
            // @codeCoverageIgnoreEnd
        }

        // Set xml Compatibility
        $compatibility = Settings::hasCompatibility();
        if ($compatibility) {
            $this->xmlWriter->setIndent(false);
            $this->xmlWriter->setIndentString('');
        } else {
            $this->xmlWriter->setIndent(true);
            $this->xmlWriter->setIndentString('  ');
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        // Destruct XMLWriter
        unset($this->xmlWriter);

        // Unlink temporary files
        if ($this->tempFile != '') {
            @unlink($this->tempFile);
        }
    }

    /**
     * Catch function calls (and pass them to internal XMLWriter)
     *
     * @param mixed $function
     * @param mixed $args
     * @throws \BadMethodCallException
     */
    public function __call($function, $args)
    {
        // Catch exception
        if (method_exists($this->xmlWriter, $function) === false) {
            throw new \BadMethodCallException("Method '{$function}' does not exists.");
        }

        // Run method
        try {
            @call_user_func_array(array($this->xmlWriter, $function), $args);
        } catch (\Exception $ex) {
            // Do nothing!
        }
    }

    /**
     * Get written data
     *
     * @return string XML data
     */
    public function getData()
    {
        if ($this->tempFile == '') {
            return $this->xmlWriter->outputMemory(true);
        } else {
            $this->xmlWriter->flush();
            return file_get_contents($this->tempFile);
        }
    }

    /**
     * Write simple element and attribute(s) block
     *
     * There are two options:
     * 1. If the `$attributes` is an array, then it's an associative array of attributes
     * 2. If not, then it's a simple attribute-value pair
     *
     * @param string $element
     * @param string|array $attributes
     * @param string $value
     * @return void
     */
    public function writeElementBlock($element, $attributes, $value = null)
    {
        $this->xmlWriter->startElement($element);
        if (!is_array($attributes)) {
            $attributes = array($attributes => $value);
        }
        foreach ($attributes as $attribute => $value) {
            $this->xmlWriter->writeAttribute($attribute, $value);
        }
        $this->xmlWriter->endElement();
    }

    /**
     * Write element if ...
     *
     * @param bool $condition
     * @param string $element
     * @param string $attribute
     * @param mixed $value
     * @return void
     */
    public function writeElementIf($condition, $element, $attribute = null, $value = null)
    {
        if ($condition == true) {
            if (is_null($attribute)) {
                $this->xmlWriter->writeElement($element, $value);
            } else {
                $this->xmlWriter->startElement($element);
                $this->xmlWriter->writeAttribute($attribute, $value);
                $this->xmlWriter->endElement();
            }
        }
    }

    /**
     * Write attribute if ...
     *
     * @param bool $condition
     * @param string $attribute
     * @param mixed $value
     * @return void
     */
    public function writeAttributeIf($condition, $attribute, $value)
    {
        if ($condition == true) {
            $this->xmlWriter->writeAttribute($attribute, $value);
        }
    }
}
