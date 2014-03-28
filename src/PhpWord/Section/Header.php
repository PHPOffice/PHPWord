<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Section;

use PhpOffice\PhpWord\Exceptions\InvalidImageException;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Section\Footer\PreserveText;
use PhpOffice\PhpWord\Shared\String;

/**
 * Header element
 */
class Header
{
    /**
     * Header Count
     *
     * @var int
     */
    private $_headerCount;

    /**
     * Header Relation ID
     *
     * @var int
     */
    private $_rId;

    /**
     * Header type
     *
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-w_type-4.html Header or Footer Type
     */
    private $_type = self::AUTO;

    /**
     * Even Numbered Pages Only
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-w_type-4.html Header or Footer Type
     */
    const EVEN = 'even';

    /**
     * Default Header or Footer
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-w_type-4.html Header or Footer Type
     */
    const AUTO = 'default'; // Did not use DEFAULT because it is a PHP keyword

    /**
     * First Page Only
     * @var string
     * @link http://www.schemacentral.com/sc/ooxml/a-w_type-4.html Header or Footer Type
     */
    const FIRST = 'first';

    /**
     * Header Element Collection
     *
     * @var int
     */
    private $_elementCollection = array();

    /**
     * Create a new Header
     *
     * @param int $sectionCount
     */
    public function __construct($sectionCount)
    {
        $this->_headerCount = $sectionCount;
    }

    /**
     * Add a Text Element
     *
     * @param string $text
     * @param mixed $styleFont
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\Text
     */
    public function addText($text, $styleFont = null, $styleParagraph = null)
    {
        if (!String::isUTF8($text)) {
            $text = utf8_encode($text);
        }
        $text = new Text($text, $styleFont, $styleParagraph);
        $this->_elementCollection[] = $text;
        return $text;
    }

    /**
     * Add TextBreak
     *
     * @param int $count
     * @param null|string|array|\PhpOffice\PhpWord\Style\Font $fontStyle
     * @param null|string|array|\PhpOffice\PhpWord\Style\Paragraph $paragraphStyle
     */
    public function addTextBreak($count = 1, $fontStyle = null, $paragraphStyle = null)
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->_elementCollection[] = new TextBreak($fontStyle, $paragraphStyle);
        }
    }

    /**
     * Create a new TextRun
     *
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\TextRun
     */
    public function createTextRun($styleParagraph = null)
    {
        $textRun = new TextRun($styleParagraph);
        $this->_elementCollection[] = $textRun;
        return $textRun;
    }

    /**
     * Add a Table Element
     *
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Table
     */
    public function addTable($style = null)
    {
        $table = new Table('header', $this->_headerCount, $style);
        $this->_elementCollection[] = $table;
        return $table;
    }

    /**
     * Add a Image Element
     *
     * @param string $src
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Image
     */
    public function addImage($src, $style = null)
    {
        $image = new Image($src, $style);
        if (!is_null($image->getSource())) {
            $rID = Media::addHeaderMediaElement($this->_headerCount, $src, $image);
            $image->setRelationId($rID);
            $this->_elementCollection[] = $image;
            return $image;
        } else {
            throw new InvalidImageException;
        }
    }

    /**
     * Add a by PHP created Image Element
     *
     * @param string $src
     * @param mixed $style
     * @deprecated
     */
    public function addMemoryImage($src, $style = null)
    {
        return $this->addImage($src, $style);
    }

    /**
     * Add a PreserveText Element
     *
     * @param string $text
     * @param mixed $styleFont
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Section\Footer\PreserveText
     */
    public function addPreserveText($text, $styleFont = null, $styleParagraph = null)
    {
        if (!String::isUTF8($text)) {
            $text = utf8_encode($text);
        }
        $ptext = new PreserveText($text, $styleFont, $styleParagraph);
        $this->_elementCollection[] = $ptext;
        return $ptext;
    }

    /**
     * Add a Watermark Element
     *
     * @param string $src
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Section\Image
     */
    public function addWatermark($src, $style = null)
    {
        $image = new Image($src, $style, true);
        if (!is_null($image->getSource())) {
            $rID = Media::addHeaderMediaElement($this->_headerCount, $src, $image);
            $image->setRelationId($rID);
            $this->_elementCollection[] = $image;
            return $image;
        } else {
            throw new InvalidImageException;
        }
    }

    /**
     * Get Header Relation ID
     */
    public function getRelationId()
    {
        return $this->_rId;
    }

    /**
     * Set Header Relation ID
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        $this->_rId = $rId;
    }

    /**
     * Get all Header Elements
     */
    public function getElements()
    {
        return $this->_elementCollection;
    }

    /**
     * Get Header Count
     */
    public function getHeaderCount()
    {
        return $this->_headerCount;
    }

    /**
     * Get Header Type
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Reset back to default
     */
    public function resetType()
    {
        return $this->_type = self::AUTO;
    }

    /**
     * First page only header
     */
    public function firstPage()
    {
        return $this->_type = self::FIRST;
    }

    /**
     * Even numbered Pages only
     */
    public function evenPage()
    {
        return $this->_type = self::EVEN;
    }
}
