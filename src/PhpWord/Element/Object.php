<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Image as ImageStyle;

/**
 * Object element
 */
class Object extends Element
{
    /**
     * Ole-Object Src
     *
     * @var string
     */
    private $source;

    /**
     * Image Style
     *
     * @var \PhpOffice\PhpWord\Style\Image
     */
    private $style;

    /**
     * Object Relation ID
     *
     * @var int
     */
    private $relationId;

    /**
     * Image Relation ID
     *
     * @var int
     */
    private $imageRelationId;

    /**
     * Object ID
     *
     * @var int
     */
    private $objectId;

    /**
     * Create a new Ole-Object Element
     *
     * @param string $src
     * @param mixed $style
     */
    public function __construct($src, $style = null)
    {
        $supportedTypes = array('xls', 'doc', 'ppt', 'xlsx', 'docx', 'pptx');
        $inf = pathinfo($src);

        if (\file_exists($src) && in_array($inf['extension'], $supportedTypes)) {
            $this->source = $src;
            $this->style = $this->setStyle(new ImageStyle(), $style, true);
            return $this;
        } else {
            return false;
        }
    }

    /**
     * Get Image style
     *
     * @return \PhpOffice\PhpWord\Style\Image
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Get Source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get Object Relation ID
     *
     * @return int
     */
    public function getRelationId()
    {
        return $this->relationId;
    }

    /**
     * Set Object Relation ID
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        $this->relationId = $rId;
    }

    /**
     * Get Image Relation ID
     *
     * @return int
     */
    public function getImageRelationId()
    {
        return $this->imageRelationId;
    }

    /**
     * Set Image Relation ID
     *
     * @param int $rId
     */
    public function setImageRelationId($rId)
    {
        $this->imageRelationId = $rId;
    }

    /**
     * Get Object ID
     *
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set Object ID
     *
     * @param int $objId
     */
    public function setObjectId($objId)
    {
        $this->objectId = $objId;
    }
}
