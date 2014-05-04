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
     * Link target
     *
     * @var string
     */
    private $target;

    /**
     * Link text
     *
     * @var string
     */
    private $text;

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
     * @param string $target
     * @param string $text
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     */
    public function __construct($target, $text = null, $fontStyle = null, $paragraphStyle = null)
    {
        $this->target = $target;
        $this->text = is_null($text) ? $target : $text;
        $this->fontStyle = $this->setStyle(new Font('text'), $fontStyle);
        $this->paragraphStyle = $this->setStyle(new Paragraph(), $paragraphStyle);

        return $this;
    }

    /**
     * Get link target
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Get link text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
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

    /**
     * Get Link source
     *
     * @return string
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getLinkSrc()
    {
        return $this->getTarget();
    }

    /**
     * Get Link name
     *
     * @return string
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getLinkName()
    {
        return $this->getText();
    }
}
