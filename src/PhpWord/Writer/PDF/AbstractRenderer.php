<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PhpWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\PDF;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;

/**
 * Abstract PDF renderer
 */
abstract class AbstractRenderer extends \PhpOffice\PhpWord\Writer\HTML
{
    /**
     * Temporary storage directory
     *
     * @var string
     */
    protected $tempDir = '';

    /**
     * Font
     *
     * @var string
     */
    protected $font;

    /**
     * Paper size
     *
     * @var int
     */
    protected $paperSize = null;

    /**
     * Orientation
     *
     * @var string
     */
    protected $orientation = null;

    /**
     * Paper Sizes xRef List
     *
     * @var array
     */
    protected static $paperSizes = array(
        9 => 'A4', // (210 mm by 297 mm)
    );

    /**
     * Create new instance
     *
     * @param PhpWord $phpWord PhpWord object
     */
    public function __construct(PhpWord $phpWord)
    {
        parent::__construct($phpWord);
    }

    /**
     * Get Font
     *
     * @return string
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * Set font. Examples:
     *      'arialunicid0-chinese-simplified'
     *      'arialunicid0-chinese-traditional'
     *      'arialunicid0-korean'
     *      'arialunicid0-japanese'
     *
     * @param string $fontName
     */
    public function setFont($fontName)
    {
        $this->font = $fontName;
        return $this;
    }

    /**
     * Get Paper Size
     *
     * @return int
     */
    public function getPaperSize()
    {
        return $this->paperSize;
    }

    /**
     * Set Paper Size
     *
     * @param int $pValue Paper size = PAPERSIZE_A4
     * @return self
     */
    public function setPaperSize($pValue = 9)
    {
        $this->paperSize = $pValue;
        return $this;
    }

    /**
     * Get Orientation
     *
     * @return string
     */
    public function getOrientation()
    {
        return $this->orientation;
    }

    /**
     * Set Orientation
     *
     * @param string $pValue Page orientation ORIENTATION_DEFAULT
     * @return self
     */
    public function setOrientation($pValue = 'default')
    {
        $this->orientation = $pValue;
        return $this;
    }

    /**
     * Save PhpWord to PDF file, pre-save
     *
     * @param string $pFilename Name of the file to save as
     * @return resource
     */
    protected function prepareForSave($pFilename = null)
    {
        $fileHandle = fopen($pFilename, 'w');
        if ($fileHandle === false) {
            throw new Exception("Could not open file $pFilename for writing.");
        }
        $this->isPdf = true;

        return $fileHandle;
    }

    /**
     * Save PhpWord to PDF file, post-save
     *
     * @param resource $fileHandle
     * @throws Exception
     */
    protected function restoreStateAfterSave($fileHandle)
    {
        fclose($fileHandle);
    }
}
