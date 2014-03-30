<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Exception\InvalidImageException;
use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Shared\String;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Textrun/paragraph element
 */
class TextRun
{
    /**
     * Paragraph style
     *
     * @var Paragraph
     */
    private $_styleParagraph;

    /**
     * Text collection
     *
     * @var array
     */
    private $_elementCollection;


    /**
     * Create a new TextRun Element
     *
     * @param mixed $styleParagraph
     */
    public function __construct($styleParagraph = null)
    {
        $this->_elementCollection = array();

        // Set paragraph style
        if (is_array($styleParagraph)) {
            $this->_styleParagraph = new Paragraph();

            foreach ($styleParagraph as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_styleParagraph->setStyleValue($key, $value);
            }
        } else {
            $this->_styleParagraph = $styleParagraph;
        }
    }


    /**
     * Add a Text Element
     *
     * @param string $text
     * @param mixed $styleFont
     * @return \PhpOffice\PhpWord\Element\Text
     */
    public function addText($text = null, $styleFont = null)
    {
        if (!String::isUTF8($text)) {
            $text = utf8_encode($text);
        }
        $text = new Text($text, $styleFont);
        $this->_elementCollection[] = $text;
        return $text;
    }

    /**
     * Add a Link Element
     *
     * @param string $linkSrc
     * @param string $linkName
     * @param mixed $styleFont
     * @return \PhpOffice\PhpWord\Element\Link
     */
    public function addLink($linkSrc, $linkName = null, $styleFont = null)
    {
        $linkSrc = utf8_encode($linkSrc);
        if (!is_null($linkName)) {
            $linkName = utf8_encode($linkName);
        }

        $link = new Link($linkSrc, $linkName, $styleFont);
        $rID = Media::addSectionLinkElement($linkSrc);
        $link->setRelationId($rID);

        $this->_elementCollection[] = $link;
        return $link;
    }

    /**
     * Add a Image Element
     *
     * @param string $imageSrc
     * @param mixed $style
     * @return \PhpOffice\PhpWord\Element\Image
     */
    public function addImage($imageSrc, $style = null)
    {
        $image = new Image($imageSrc, $style);
        if (!is_null($image->getSource())) {
            $rID = Media::addSectionMediaElement($imageSrc, 'image', $image);
            $image->setRelationId($rID);
            $this->_elementCollection[] = $image;
            return $image;
        } else {
            throw new InvalidImageException;
        }
    }

    /**
     * Add TextBreak
     *
     * @param int $count
     * @param mixed $fontStyle
     * @param mixed $paragraphStyle
     */
    public function addTextBreak($count = 1, $fontStyle = null, $paragraphStyle = null)
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->_elementCollection[] = new TextBreak($fontStyle, $paragraphStyle);
        }
    }

    /**
     * Create a new Footnote Element
     *
     * @param mixed $styleParagraph
     * @return \PhpOffice\PhpWord\Element\Footnote
     */
    public function createFootnote($styleParagraph = null)
    {
        $footnote = new \PhpOffice\PhpWord\Element\Footnote($styleParagraph);
        $refID = \PhpOffice\PhpWord\Footnote::addFootnoteElement($footnote);
        $footnote->setReferenceId($refID);
        $this->_elementCollection[] = $footnote;
        return $footnote;
    }

    /**
     * Get TextRun content
     *
     * @return string
     */
    public function getElements()
    {
        return $this->_elementCollection;
    }

    /**
     * Get Paragraph style
     *
     * @return string|Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->_styleParagraph;
    }
}
