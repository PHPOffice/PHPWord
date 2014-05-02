<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Style\Shading;

/**
 * Table cell style
 */
class Cell extends Border
{
    const TEXT_DIR_BTLR = 'btLr';
    const TEXT_DIR_TBRL = 'tbRl';

    /**
     * Vertical align (top, center, both, bottom)
     *
     * @var string
     */
    private $valign;

    /**
     * Text Direction
     *
     * @var string
     */
    private $textDirection;

    /**
     * Border Default Color
     *
     * @var string
     */
    private $defaultBorderColor;

    /**
     * colspan
     *
     * @var integer
     */
    private $gridSpan = null;

    /**
     * rowspan (restart, continue)
     *
     * - restart: Start/restart merged region
     * - continue: Continue merged region
     *
     * @var string
     */
    private $vMerge = null;

    /**
     * Shading
     *
     * @var \PhpOffice\PhpWord\Style\Shading
     */
    private $shading;

    /**
     * Create a new Cell Style
     */
    public function __construct()
    {
        $this->valign = null;
        $this->textDirection = null;
        $this->borderTopSize = null;
        $this->borderTopColor = null;
        $this->borderLeftSize = null;
        $this->borderLeftColor = null;
        $this->borderRightSize = null;
        $this->borderRightColor = null;
        $this->borderBottomSize = null;
        $this->borderBottomColor = null;
        $this->defaultBorderColor = '000000';
    }

    /**
     * Get vertical align
     */
    public function getVAlign()
    {
        return $this->valign;
    }

    /**
     * Set vertical align
     *
     * @param string $pValue
     */
    public function setVAlign($pValue = null)
    {
        $this->valign = $pValue;
    }

    /**
     * Get text direction
     */
    public function getTextDirection()
    {
        return $this->textDirection;
    }

    /**
     * Set text direction
     *
     * @param string $pValue
     */
    public function setTextDirection($pValue = null)
    {
        $this->textDirection = $pValue;
    }

    /**
     * Get background
     *
     * @return string
     */
    public function getBgColor()
    {
        if (!is_null($this->shading)) {
            return $this->shading->getFill();
        }
    }

    /**
     * Set background
     *
     * @param string $value
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function setBgColor($value = null)
    {
        $this->setShading(array('fill' => $value));
    }

    /**
     * Get default border color
     */
    public function getDefaultBorderColor()
    {
        return $this->defaultBorderColor;
    }

    /**
     * Set grid span (colspan)
     *
     * @param int $pValue
     */
    public function setGridSpan($pValue = null)
    {
        $this->gridSpan = $pValue;
    }

    /**
     * Get grid span (colspan)
     */
    public function getGridSpan()
    {
        return $this->gridSpan;
    }

    /**
     * Set vertical merge (rowspan)
     *
     * @param string $pValue
     */
    public function setVMerge($pValue = null)
    {
        $this->vMerge = $pValue;
    }

    /**
     * Get vertical merge (rowspan)
     */
    public function getVMerge()
    {
        return $this->vMerge;
    }

    /**
     * Get shading
     *
     * @return \PhpOffice\PhpWord\Style\Shading
     */
    public function getShading()
    {
        return $this->shading;
    }

    /**
     * Set shading
     *
     * @param array $value
     * @return self
     */
    public function setShading($value = null)
    {
        if (is_array($value)) {
            if (!$this->shading instanceof Shading) {
                $this->shading = new Shading();
            }
            $this->shading->setStyleByArray($value);
        } else {
            $this->shading = null;
        }

        return $this;
    }
}
