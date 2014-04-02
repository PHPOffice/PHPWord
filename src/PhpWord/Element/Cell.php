<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Container\Container;
use PhpOffice\PhpWord\Style\Cell as CellStyle;

/**
 * Table cell element
 */
class Cell extends Container
{
    /**
     * Cell width
     *
     * @var int
     */
    private $width = null;

    /**
     * Cell style
     *
     * @var CellStyle
     */
    private $cellStyle;

    /**
     * Create new instance
     *
     * @param string $docPart section|header|footer
     * @param int $docPartId
     * @param int $width
     * @param array|CellStyle $style
     */
    public function __construct($docPart, $docPartId, $width = null, $style = null)
    {
        $this->container = 'cell';
        $this->docPart = $docPart;
        $this->docPartId = $docPartId;
        $this->width = $width;
        $this->cellStyle = new CellStyle();

        if (!is_null($style)) {
            if (is_array($style)) {
                foreach ($style as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    $this->cellStyle->setStyleValue($key, $value);
                }
            } else {
                $this->cellStyle = $style;
            }
        }
    }

    /**
     * Get cell style
     *
     * @return CellStyle
     */
    public function getStyle()
    {
        return $this->cellStyle;
    }

    /**
     * Get cell width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
}
