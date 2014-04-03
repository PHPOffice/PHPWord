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
     * Create new instance
     *
     * @param string|array|Paragraph $paragraphStyle
     */
    public function __construct($paragraphStyle = null)
    {
        $this->container = 'footnote';
        $this->paragraphStyle = $this->setStyle(new Paragraph(), $paragraphStyle);
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
     * @deprecated 0.9.2
     */
    public function getReferenceId()
    {
        return $this->getRelationId();
    }

    /**
     * Set Footnote Reference ID
     *
     * @param int $refId
     * @deprecated 0.9.2
     */
    public function setReferenceId($refId)
    {
        $this->setRelationId($refId);
    }
}
