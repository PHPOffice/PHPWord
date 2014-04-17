<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

/**
 * PHPWord settings class
 *
 * @since 0.8.0
 */
class Settings
{
    /** Available Zip library classes */
    const PCLZIP     = 'PhpOffice\\PhpWord\\Shared\\ZipArchive';
    const ZIPARCHIVE = 'ZipArchive';

    /** Optional PDF Rendering libraries */
    const PDF_RENDERER_DOMPDF = 'DomPDF';

    /**
     * Compatibility option for XMLWriter
     *
     * @var boolean
     */
    private static $xmlWriterCompatibility = true;

    /**
     * Name of the class used for Zip file management
     *
     * @var string
     */
    private static $zipClass = self::ZIPARCHIVE;

    /**
     * Name of the classes used for PDF renderer
     *
     * @var array
     */
    private static $pdfRenderers = array(self::PDF_RENDERER_DOMPDF);

    /**
     * Name of the external Library used for rendering PDF files
     *
     * @var string
     */
    private static $pdfRendererName = null;

    /**
     * Directory Path to the external Library used for rendering PDF files
     *
     * @var string
     */
    private static $pdfRendererPath = null;

    /**
     * Set the compatibility option used by the XMLWriter
     *
     * @param boolean $compatibility  This sets the setIndent and setIndentString for better compatibility
     * @return  boolean Success or failure
     */
    public static function setCompatibility($compatibility)
    {
        if (is_bool($compatibility)) {
            self::$xmlWriterCompatibility = $compatibility;
            return true;
        }
        return false;
    }

    /**
     * Return the compatibility option used by the XMLWriter
     *
     * @return boolean Compatibility
     */
    public static function getCompatibility()
    {
        return self::$xmlWriterCompatibility;
    }

    /**
     * Set the Zip handler Class that PHPWord should use for Zip file management (PCLZip or ZipArchive)
     *
     * @param  string $zipClass  The Zip handler class that PHPWord should use for Zip file management
     *   e.g. Settings::PCLZip or Settings::ZipArchive
     * @return boolean Success or failure
     */
    public static function setZipClass($zipClass)
    {
        if (($zipClass === self::PCLZIP) ||
            ($zipClass === self::ZIPARCHIVE)) {
            self::$zipClass = $zipClass;
            return true;
        }
        return false;
    }

    /**
     * Return the name of the Zip handler Class that PHPWord is configured to use (PCLZip or ZipArchive)
     *  or Zip file management
     *
     * @return string Name of the Zip handler Class that PHPWord is configured to use
     *  for Zip file management
     *  e.g. Settings::PCLZip or Settings::ZipArchive
     */
    public static function getZipClass()
    {
        return self::$zipClass;
    }

    /**
     * Set details of the external library for rendering PDF files
     *
     * @param string $libraryName
     * @param string $libraryBaseDir
     * @return boolean Success or failure
     */
    public static function setPdfRenderer($libraryName, $libraryBaseDir)
    {
        if (!self::setPdfRendererName($libraryName)) {
            return false;
        }

        return self::setPdfRendererPath($libraryBaseDir);
    }

    /**
     * Return the PDF Rendering Library
     */
    public static function getPdfRendererName()
    {
        return self::$pdfRendererName;
    }

    /**
     * Identify the external library to use for rendering PDF files
     *
     * @param string $libraryName
     * @return boolean Success or failure
     */
    public static function setPdfRendererName($libraryName)
    {
        if (!in_array($libraryName, self::$pdfRenderers)) {
            return false;
        }

        self::$pdfRendererName = $libraryName;

        return true;
    }


    /**
     * Return the directory path to the PDF Rendering Library
     */
    public static function getPdfRendererPath()
    {
        return self::$pdfRendererPath;
    }

    /**
     * Location of external library to use for rendering PDF files
     *
     * @param string $libraryBaseDir Directory path to the library's base folder
     * @return boolean Success or failure
     */
    public static function setPdfRendererPath($libraryBaseDir)
    {
        if ((file_exists($libraryBaseDir) === false) || (is_readable($libraryBaseDir) === false)) {
            return false;
        }
        self::$pdfRendererPath = $libraryBaseDir;

        return true;
    }
}
