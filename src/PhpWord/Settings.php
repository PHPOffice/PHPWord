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
    const ZIPARCHIVE = 'ZipArchive';
    const PCLZIP = 'PclZip';
    const OLD_LIB = 'PhpOffice\\PhpWord\\Shared\\ZipArchive'; // @deprecated 0.11

    /**
     * PDF rendering libraries
     *
     * @const string
     */
    const PDF_RENDERER_DOMPDF = 'DomPDF';
    const PDF_RENDERER_TCPDF = 'TCPDF';
    const PDF_RENDERER_MPDF = 'MPDF';

    /**
     * Measurement units multiplication factor
     *
     * Applied to:
     * - Section: margins, header/footer height, gutter, column spacing
     * - Tab: position
     * - Indentation: left, right, firstLine, hanging
     * - Spacing: before, after
     *
     * @const string
     */
    const UNIT_TWIP = 'twip'; // = 1/20 point
    const UNIT_CM = 'cm';
    const UNIT_MM = 'mm';
    const UNIT_INCH = 'inch';
    const UNIT_POINT = 'point'; // = 1/72 inch
    const UNIT_PICA = 'pica'; // = 1/6 inch = 12 points

    /**
     * Default font settings
     *
     * OOXML defined font size values in halfpoints, i.e. twice of what PhpWord
     * use, and the conversion will be conducted during XML writing.
     */
    const DEFAULT_FONT_NAME = 'Arial';
    const DEFAULT_FONT_SIZE = 10;
    const DEFAULT_FONT_COLOR = '000000';
    const DEFAULT_FONT_CONTENT_TYPE = 'default'; // default|eastAsia|cs
    const DEFAULT_PAPER = 'A4';

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
     * @var int|float
     */
    private static $measurementUnit = self::UNIT_TWIP;

    /**
     * Default font name
     *
     * @var string
     */
    private static $defaultFontName = self::DEFAULT_FONT_NAME;

    /**
     * Default font size
     * @var int
     */
    private static $defaultFontSize = self::DEFAULT_FONT_SIZE;

    /**
     * Default paper
     * @var string
     */
    private static $defaultPaper = self::DEFAULT_PAPER;

    /**
     * The user defined temporary directory.
     *
     * @var string
     */
    private static $tempDir = '';

    /**
     * Enables built-in output escaping mechanism.
     * Default value is `false` for backward compatibility with versions below 0.13.0.
     *
     * @var bool
     */
    private static $outputEscapingEnabled = false;

    /**
     * Return the compatibility option used by the XMLWriter
     *
     * @return bool Compatibility
     */
    public static function hasCompatibility()
    {
        return self::$xmlWriterCompatibility;
    }

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
        $compatibility = (bool) $compatibility;
        self::$xmlWriterCompatibility = $compatibility;

        return true;
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
     * Set zip handler class
     *
     * @param  string $zipClass
     * @return bool
     */
    public static function setZipClass($zipClass)
    {
        if (in_array($zipClass, array(self::PCLZIP, self::ZIPARCHIVE, self::OLD_LIB))) {
            self::$zipClass = $zipClass;

            return true;
        }

        return false;
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
     * Return the PDF Rendering Library.
     *
     * @return string
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
        $pdfRenderers = array(self::PDF_RENDERER_DOMPDF, self::PDF_RENDERER_TCPDF, self::PDF_RENDERER_MPDF);
        if (!in_array($libraryName, $pdfRenderers)) {
            return false;
        }
        self::$pdfRendererName = $libraryName;

        return true;
    }

    /**
     * Return the directory path to the PDF Rendering Library.
     *
     * @return string
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
        if (false === file_exists($libraryBaseDir) || false === is_readable($libraryBaseDir)) {
            return false;
        }
        self::$pdfRendererPath = $libraryBaseDir;

        return true;
    }

    /**
     * Get measurement unit
     *
     * @return string
     */
    public static function getMeasurementUnit()
    {
        return self::$measurementUnit;
    }

    /**
     * Set measurement unit
     *
     * @param string $value
     * @return bool
     */
    public static function setMeasurementUnit($value)
    {
        $units = array(self::UNIT_TWIP, self::UNIT_CM, self::UNIT_MM, self::UNIT_INCH,
            self::UNIT_POINT, self::UNIT_PICA, );
        if (!in_array($value, $units)) {
            return false;
        }
        self::$measurementUnit = $value;

        return true;
    }

    /**
     * Sets the user defined path to temporary directory.
     *
     * @since 0.12.0
     *
     * @param string $tempDir The user defined path to temporary directory
     */
    public static function setTempDir($tempDir)
    {
        self::$tempDir = $tempDir;
    }

    /**
     * Returns path to temporary directory.
     *
     * @since 0.12.0
     *
     * @return string
     */
    public static function getTempDir()
    {
        if (!empty(self::$tempDir)) {
            $tempDir = self::$tempDir;
        } else {
            $tempDir = sys_get_temp_dir();
        }

        return $tempDir;
    }

    /**
     * @since 0.13.0
     *
     * @return bool
     */
    public static function isOutputEscapingEnabled()
    {
        return self::$outputEscapingEnabled;
    }

    /**
     * @since 0.13.0
     *
     * @param bool $outputEscapingEnabled
     */
    public static function setOutputEscapingEnabled($outputEscapingEnabled)
    {
        self::$outputEscapingEnabled = $outputEscapingEnabled;
    }

    /**
     * Get default font name
     *
     * @return string
     */
    public static function getDefaultFontName()
    {
        return self::$defaultFontName;
    }

    /**
     * Set default font name
     *
     * @param string $value
     * @return bool
     */
    public static function setDefaultFontName($value)
    {
        if (is_string($value) && trim($value) !== '') {
            self::$defaultFontName = $value;

            return true;
        }

        return false;
    }

    /**
     * Get default font size
     *
     * @return int
     */
    public static function getDefaultFontSize()
    {
        return self::$defaultFontSize;
    }

    /**
     * Set default font size
     *
     * @param int $value
     * @return bool
     */
    public static function setDefaultFontSize($value)
    {
        $value = (int) $value;
        if ($value > 0) {
            self::$defaultFontSize = $value;

            return true;
        }

        return false;
    }

    /**
     * Load setting from phpword.yml or phpword.yml.dist
     *
     * @param string $filename
     * @return array
     */
    public static function loadConfig($filename = null)
    {
        // Get config file
        $configFile = null;
        $configPath = __DIR__ . '/../../';
        if ($filename !== null) {
            $files = array($filename);
        } else {
            $files = array("{$configPath}phpword.ini", "{$configPath}phpword.ini.dist");
        }
        foreach ($files as $file) {
            if (file_exists($file)) {
                $configFile = realpath($file);
                break;
            }
        }

        // Parse config file
        $config = array();
        if ($configFile !== null) {
            $config = @parse_ini_file($configFile);
            if ($config === false) {
                return $config;
            }
        }

        // Set config value
        foreach ($config as $key => $value) {
            $method = "set{$key}";
            if (method_exists(__CLASS__, $method)) {
                self::$method($value);
            }
        }

        return $config;
    }

    /**
     * Get default paper
     *
     * @return string
     */
    public static function getDefaultPaper()
    {
        return self::$defaultPaper;
    }

    /**
     * Set default paper
     *
     * @param string $value
     * @return bool
     */
    public static function setDefaultPaper($value)
    {
        if (is_string($value) && trim($value) !== '') {
            self::$defaultPaper = $value;

            return true;
        }

        return false;
    }

    /**
     * Return the compatibility option used by the XMLWriter
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public static function getCompatibility()
    {
        return self::hasCompatibility();
    }
}
