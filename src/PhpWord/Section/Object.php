<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Section;

/**
 * Object element
 */
class Object
{
    /**
     * Ole-Object Src
     *
     * @var string
     */
    private $_src;

    /**
     * Image Style
     *
     * @var \PhpOffice\PhpWord\Style\Image
     */
    private $_style;

    /**
     * Object Relation ID
     *
     * @var int
     */
    private $_rId;

    /**
     * Image Relation ID
     *
     * @var int
     */
    private $_rIdImg;

    /**
     * Object ID
     *
     * @var int
     */
    private $_objId;


    /**
     * Create a new Ole-Object Element
     *
     * @param string $src
     * @param mixed $style
     */
    public function __construct($src, $style = null)
    {
        $_supportedObjectTypes = array('xls', 'doc', 'ppt', 'xlsx', 'docx', 'pptx');
        $inf = pathinfo($src);

        if (\file_exists($src) && in_array($inf['extension'], $_supportedObjectTypes)) {
            $this->_src = $src;
            $this->_style = new \PhpOffice\PhpWord\Style\Image();

            if (!is_null($style) && is_array($style)) {
                foreach ($style as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    $this->_style->setStyleValue($key, $value);
                }
            }

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
        return $this->_style;
    }

    /**
     * Get Source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->_src;
    }

    /**
     * Get Object Relation ID
     *
     * @return int
     */
    public function getRelationId()
    {
        return $this->_rId;
    }

    /**
     * Set Object Relation ID
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        $this->_rId = $rId;
    }

    /**
     * Get Image Relation ID
     *
     * @return int
     */
    public function getImageRelationId()
    {
        return $this->_rIdImg;
    }

    /**
     * Set Image Relation ID
     *
     * @param int $rId
     */
    public function setImageRelationId($rId)
    {
        $this->_rIdImg = $rId;
    }

    /**
     * Get Object ID
     *
     * @return int
     */
    public function getObjectId()
    {
        return $this->_objId;
    }

    /**
     * Set Object ID
     *
     * @param int $objId
     */
    public function setObjectId($objId)
    {
        $this->_objId = $objId;
    }
}
