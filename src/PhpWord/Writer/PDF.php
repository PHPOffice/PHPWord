<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PhpWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

/**
 * PDF Writer
 */
class PDF
{
    /**
     * The wrapper for the requested PDF rendering engine
     *
     * @var \PhpOffice\PhpWord\Writer\PDF\AbstractRenderer
     */
    private $renderer = null;

    /**
     * Instantiate a new renderer of the configured type within this container class
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function __construct(PhpWord $phpWord)
    {
        $pdfLibraryName = Settings::getPdfRendererName();
        $pdfLibraryPath = Settings::getPdfRendererPath();
        if (is_null($pdfLibraryName) || is_null($pdfLibraryPath)) {
            throw new Exception("PDF rendering library or library path has not been defined.");
        }

        $includePath = str_replace('\\', '/', get_include_path());
        $rendererPath = str_replace('\\', '/', $pdfLibraryPath);
        if (strpos($rendererPath, $includePath) === false) {
            set_include_path(get_include_path() . PATH_SEPARATOR . $pdfLibraryPath);
        }

        $rendererName = 'PhpOffice\\PhpWord\\Writer\\PDF\\' . $pdfLibraryName;
        $this->renderer = new $rendererName($phpWord);
    }

    /**
     * Magic method to handle direct calls to the configured PDF renderer wrapper class.
     *
     * @param string $name Renderer library method name
     * @param mixed[] $arguments Array of arguments to pass to the renderer method
     * @return mixed Returned data from the PDF renderer wrapper method
     */
    public function __call($name, $arguments)
    {
        if ($this->renderer === null) {
            throw new Exception("PDF Rendering library has not been defined.");
        }

        return call_user_func_array(array($this->renderer, $name), $arguments);
    }
}
