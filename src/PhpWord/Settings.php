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
    /**
     * Zip libraries
     *
     * @const string
     */
    const PCLZIP     = 'PhpOffice\\PhpWord\\Shared\\ZipArchive';
    const ZIPARCHIVE = 'ZipArchive';

    /**
     * PDF rendering libraries
     *
     * @const string
     */
    const PDF_RENDERER_DOMPDF = 'DomPDF';

    /**
     * Measurement units multiplication factor
     *
     * Applied to:
     * - Section: margins, header/footer height, gutter, column spacing
     * - Tab: position
     * - Indentation: left, right, firstLine, hanging
     * - Spacing: before, after
     *
     * @const int|float
     */
    const UNIT_TWIP  = 1; // = 1/20 point
    const UNIT_CM    = 567;
    const UNIT_MM    = 56.7;
    const UNIT_INCH  = 1440;
    const UNIT_POINT = 20; // = 1/72 inch
    const UNIT_PICA  = 240; // = 1/6 inch = 12 points

    /**
     * Compatibility option for XMLWriter
     *
     * @var bool
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
     * Measurement unit
     *
     * @var string
     */
    private static $measurementUnit = self::UNIT_TWIP;

    /**
     * Set the compatibility option used by the XMLWriter
     *
     * This sets the setIndent and setIndentString for better compatibility
     *
     * @param bool $compatibility
     * @return bool
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
     * @return bool Compatibility
     */
    public static function getCompatibility()
    {
        return self::$xmlWriterCompatibility;
    }

    /**
     * Set zip handler class
     *
     * @param  string $zipClass
     * @return bool
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
     * Get zip handler class
     *
     * @return string
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
     * @return bool Success or failure
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
     * @return bool
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
     * @return bool Success or failure
     */
    public static function setPdfRendererPath($libraryBaseDir)
    {
        if ((file_exists($libraryBaseDir) === false) || (is_readable($libraryBaseDir) === false)) {
            return false;
        }
        self::$pdfRendererPath = $libraryBaseDir;

        return true;
    }

    /**
     * Get measurement unit
     *
     * @return int|float
     */
    public static function getMeasurementUnit()
    {
        return self::$measurementUnit;
    }

    /**
     * Set measurement unit
     *
     * @param int|float $value
     * @return bool
     */
    public static function setMeasurementUnit($value)
    {
        $units = array(self::UNIT_TWIP, self::UNIT_CM, self::UNIT_MM, self::UNIT_INCH, self::UNIT_POINT, self::UNIT_PICA);
        if (!in_array($value, $units)) {
            return false;
        }
        self::$measurementUnit = $value;

        return true;
    }
}
