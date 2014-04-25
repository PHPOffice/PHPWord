<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Settings;

// @codeCoverageIgnoreStart
if (!defined('DATE_W3C')) {
    define('DATE_W3C', 'Y-m-d\TH:i:sP');
}
// @codeCoverageIgnoreEnd

/**
 * XMLWriter wrapper
 *
 * @method bool writeElement(string $name, string $content = null)
 * @method bool startElement(string $name)
 * @method bool writeAttribute(string $name, string $value)
 * @method bool endElement()
 * @method bool startDocument(string $version = 1.0, string $encoding = null, string $standalone = null)
 * @method bool text(string $content)
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
            $this->tempFile = @tempnam($tempFolder, 'xml');

            // Open storage
            if ($this->xmlWriter->openUri($this->tempFile) === false) {
                // Fallback to memory...
                $this->xmlWriter->openMemory();
            }
        }

        // Set xml Compatibility
        $compatibility = Settings::getCompatibility();
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
     */
    public function __call($function, $args)
    {
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
     * Fallback method for writeRaw, introduced in PHP 5.2
     *
     * @param string $text
     * @return bool
     */
    public function writeRaw($text)
    {
        if (isset($this->xmlWriter) && is_object($this->xmlWriter) && (method_exists($this->xmlWriter, 'writeRaw'))) {
            return $this->xmlWriter->writeRaw($text);
        }

        return $this->text($text);
    }
}
