<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Container;

use PhpOffice\PhpWord\Element\Image;

/**
 * Header element
 */
class Header extends Container
{
    /**
     * Header types constants
     *
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-wheaderType-4.html Header or Footer Type
     */
    const AUTO = 'default'; // Did not use DEFAULT because it is a PHP keyword
    const EVEN = 'even';
    const FIRST = 'first';

    /**
     * Header type
     *
     * @var string
     */
    private $headerType = self::AUTO;

    /**
     * Create new instance
     *
     * @param int $sectionId
     */
    public function __construct($sectionId)
    {
        $this->container = 'header';
        $this->containerId = $sectionId;
        $this->setDocPart($this->container, $this->containerId);
    }

    /**
     * Add a Watermark Element
     *
     * @param string $src
     * @param mixed $style
     * @return Image
     */
    public function addWatermark($src, $style = null)
    {
        return $this->addImage($src, $style, true);
    }

    /**
     * Get header type
     */
    public function getType()
    {
        return $this->headerType;
    }

    /**
     * Reset type to default
     */
    public function resetType()
    {
        return $this->headerType = self::AUTO;
    }

    /**
     * First page only header
     */
    public function firstPage()
    {
        return $this->headerType = self::FIRST;
    }

    /**
     * Even numbered pages only
     */
    public function evenPage()
    {
        return $this->headerType = self::EVEN;
    }
}
