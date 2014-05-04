<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Shading style
 *
 * @link http://www.schemacentral.com/sc/ooxml/t-w_CT_Shd.html
 * @since 0.10.0
 */
class Shading extends AbstractStyle
{
    /**
     * Shading pattern
     *
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/t-w_ST_Shd.html
     */
    private $pattern = 'clear';

    /**
     * Shading pattern color
     *
     * @var string
     */
    private $color = 'auto';

    /**
     * Shading background color
     *
     * @var string
     */
    private $fill;

    /**
     * Create a new instance
     *
     * @param array $style
     */
    public function __construct($style = array())
    {
        $this->setStyleByArray($style);
    }

    /**
     * Get pattern
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Set pattern
     *
     * @param string $value
     * @return self
     */
    public function setPattern($value = null)
    {
        $this->pattern = $value;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set pattern
     *
     * @param string $value
     * @return self
     */
    public function setColor($value = null)
    {
        $this->color = $value;

        return $this;
    }

    /**
     * Get fill
     *
     * @return string
     */
    public function getFill()
    {
        return $this->fill;
    }

    /**
     * Set fill
     *
     * @param string $value
     * @return self
     */
    public function setFill($value = null)
    {
        $this->fill = $value;

        return $this;
    }
}
