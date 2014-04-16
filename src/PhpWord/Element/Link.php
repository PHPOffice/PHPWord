<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Link element
 */
class Link extends AbstractElement
{
    /**
     * Link source
     *
     * @var string
     */
    private $source;

    /**
     * Link name
     *
     * @var string
     */
    private $name;

    /**
     * Font style
     *
     * @var string|\PhpOffice\PhpWord\Style\Font
     */
    private $fontStyle;

    /**
     * Paragraph style
     *
     * @var string|\PhpOffice\PhpWord\Style\Paragraph
     */
    private $paragraphStyle;


    /**
     * Create a new Link Element
     *
     * @param string $linkSrc
     * @param string $linkName
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     */
    public function __construct($linkSrc, $linkName = null, $fontStyle = null, $paragraphStyle = null)
    {
        $this->source = $linkSrc;
        $this->name = $linkName;
        $this->fontStyle = $this->setStyle(new Font('text'), $fontStyle);
        $this->paragraphStyle = $this->setStyle(new Paragraph(), $paragraphStyle);

        return $this;
    }

    /**
     * Get Link source
     *
     * @return string
     */
    public function getLinkSrc()
    {
        return $this->source;
    }

    /**
     * Get Link name
     *
     * @return string
     */
    public function getLinkName()
    {
        return $this->name;
    }

    /**
     * Get Text style
     *
     * @return string|\PhpOffice\PhpWord\Style\Font
     */
    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    /**
     * Get Paragraph style
     *
     * @return string|\PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }
}
