<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Element\Image;

/**
 * Header element
 */
class Header extends AbstractContainer
{
    /**
     * Header types constants
     *
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-wtype-4.html Header or Footer Type
     */
    const AUTO  = 'default'; // default and odd pages
    const FIRST = 'first';
    const EVEN  = 'even';

    /**
     * Header type
     *
     * @var string
     */
    private $type = self::AUTO;

    /**
     * Create new instance
     *
     * @param int $sectionId
     * @param int $headerId
     * @param string $type
     */
    public function __construct($sectionId, $headerId = 1, $type = self::AUTO)
    {
        $this->container = 'header';
        $this->sectionId = $sectionId;
        $this->setType($type);
        $this->setDocPart($this->container, ($sectionId - 1) * 3 + $headerId);
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
     * Set header type
     *
     * @param string $value
     * @since 0.10.0
     */
    public function setType($value = self::AUTO)
    {
        if (!in_array($value, array(self::AUTO, self::FIRST, self::EVEN))) {
            $value = self::AUTO;
        }
        $this->type = $value;
    }

    /**
     * Get header type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Reset type to default
     *
     * @return string
     */
    public function resetType()
    {
        return $this->type = self::AUTO;
    }

    /**
     * First page only header
     *
     * @return string
     */
    public function firstPage()
    {
        return $this->type = self::FIRST;
    }

    /**
     * Even numbered pages only
     *
     * @return string
     */
    public function evenPage()
    {
        return $this->type = self::EVEN;
    }
}
