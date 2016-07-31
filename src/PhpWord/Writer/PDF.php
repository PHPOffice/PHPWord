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
 * @link        https://github.com/PHPOffice/PhpWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

/**
 * PDF Writer
 *
 * @since 0.10.0
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
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
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

        $rendererName = get_class($this) . '\\' . $pdfLibraryName;
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
        // Note: Commented because all exceptions should already be catched by `__construct`
        // if ($this->renderer === null) {
        //     throw new Exception("PDF Rendering library has not been defined.");
        // }

        return call_user_func_array(array($this->renderer, $name), $arguments);
    }
}
