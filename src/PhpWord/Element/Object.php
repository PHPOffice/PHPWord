<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Image as ImageStyle;
use PhpOffice\PhpWord\Exception\InvalidObjectException;

/**
 * Object element
 */
class Object extends AbstractElement
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
     * Icon
     *
     * @var string
     */
    private $icon;

    /**
     * Image Relation ID
     *
     * @var int
     */
    private $imageRelationId;

    /**
     * Create a new Ole-Object Element
     *
     * @param string $source
     * @param mixed $style
     */
    public function __construct($source, $style = null)
    {
        $supportedTypes = array('xls', 'doc', 'ppt', 'xlsx', 'docx', 'pptx');
        $pathInfo = pathinfo($source);

        if (file_exists($source) && in_array($pathInfo['extension'], $supportedTypes)) {
            $ext = $pathInfo['extension'];
            if (strlen($ext) == 4 && strtolower(substr($ext, -1)) == 'x') {
                $ext = substr($ext, 0, -1);
            }

            $this->source = $source;
            $this->style = $this->setStyle(new ImageStyle(), $style, true);
            $this->icon = realpath(__DIR__ . "/../resources/{$ext}.png");

            return $this;
        } else {
            throw new InvalidObjectException();
        }
    }

    /**
     * Get object source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get object style
     *
     * @return \PhpOffice\PhpWord\Style\Image
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Get object icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Get image relation ID
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
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getObjectId()
    {
        return $this->relationId + 1325353440;
    }

    /**
     * Set Object ID
     *
     * @param int $objId
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function setObjectId($objId)
    {
        $this->relationId = $objId;
    }
}
