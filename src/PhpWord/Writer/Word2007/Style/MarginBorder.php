<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Margin border style writer
 *
 * @since 0.10.0
 */
class MarginBorder extends AbstractStyle
{
    /**
     * Sizes
     *
     * @var int[]
     */
    private $sizes;

    /**
     * Colors
     *
     * @var string[]
     */
    private $colors;

    /**
     * Other attributes
     *
     * @var array
     */
    private $attributes = array();

    /**
     * Write style
     */
    public function write()
    {
        $sides = array('top', 'left', 'right', 'bottom', 'insideH', 'insideV');
        $sizeCount = count($this->sizes) - 1;

        for ($i = 0; $i < $sizeCount; $i++) {
            if (!is_null($this->sizes[$i])) {
                $this->xmlWriter->startElement('w:' . $sides[$i]);
                if (!empty($this->colors)) {
                    if (is_null($this->colors[$i]) && !empty($this->attributes)) {
                        if (array_key_exists('defaultColor', $this->attributes)) {
                            $this->colors[$i] = $this->attributes['defaultColor'];
                        }
                    }
                    $this->xmlWriter->writeAttribute('w:val', 'single');
                    $this->xmlWriter->writeAttribute('w:sz', $this->sizes[$i]);
                    $this->xmlWriter->writeAttribute('w:color', $this->colors[$i]);
                    if (!empty($this->attributes)) {
                        if (array_key_exists('space', $this->attributes)) {
                            $this->xmlWriter->writeAttribute('w:space', $this->attributes['space']);
                        }
                    }
                } else {
                    $this->xmlWriter->writeAttribute('w:w', $this->sizes[$i]);
                    $this->xmlWriter->writeAttribute('w:type', 'dxa');
                }
                $this->xmlWriter->endElement();
            }
        }
    }

    /**
     * Set sizes
     *
     * @param int[] $value
     */
    public function setSizes($value)
    {
        $this->sizes = $value;
    }

    /**
     * Set colors
     *
     * @param string[] $value
     */
    public function setColors($value)
    {
        $this->colors = $value;
    }

    /**
     * Set attributes
     *
     * @param array $value
     */
    public function setAttributes($value)
    {
        $this->attributes = $value;
    }
}
