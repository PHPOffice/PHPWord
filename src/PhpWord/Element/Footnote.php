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
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Footnote element
 */
class Footnote extends Container
{
    /**
     * Paragraph style
     *
     * @var string|Paragraph
     */
    private $paragraphStyle;

    /**
     * Footnote Reference ID
     *
     * @var string
     */
    private $referenceId;

    /**
     * Create new instance
     *
     * @param string|array|Paragraph $paragraphStyle
     */
    public function __construct($paragraphStyle = null)
    {
        $this->container = 'footnote';
        // Set paragraph style
        if (is_array($paragraphStyle)) {
            $this->paragraphStyle = new Paragraph();
            foreach ($paragraphStyle as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->paragraphStyle->setStyleValue($key, $value);
            }
        } else {
            $this->paragraphStyle = $paragraphStyle;
        }
    }

    /**
     * Get paragraph style
     *
     * @return string|Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }

    /**
     * Get Footnote Reference ID
     *
     * @return int
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * Set Footnote Reference ID
     *
     * @param int $refId
     */
    public function setReferenceId($refId)
    {
        $this->referenceId = $refId;
    }
}
