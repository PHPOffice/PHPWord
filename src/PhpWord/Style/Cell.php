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
    /**
     * Text direction constants
     *
     * @const string
     */
    const TEXT_DIR_BTLR = 'btLr';
    const TEXT_DIR_TBRL = 'tbRl';

    /**
     * Default border color
     *
     * @const string
     */
    const DEFAULT_BORDER_COLOR = '000000';

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
     * Get vertical align
     */
    public function getVAlign()
    {
        return $this->valign;
    }

    /**
     * Set vertical align
     *
     * @param string $value
     */
    public function setVAlign($value = null)
    {
        $this->valign = $value;
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
     * @param string $value
     */
    public function setTextDirection($value = null)
    {
        $this->textDirection = $value;
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
     * @return self
     */
    public function setBgColor($value = null)
    {
        return $this->setShading(array('fill' => $value));
    }

    /**
     * Set grid span (colspan)
     *
     * @param int $value
     */
    public function setGridSpan($value = null)
    {
        $this->gridSpan = $value;
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
     * @param string $value
     */
    public function setVMerge($value = null)
    {
        $this->vMerge = $value;
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

    /**
     * Get default border color
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getDefaultBorderColor()
    {
        return self::DEFAULT_BORDER_COLOR;
    }
}
