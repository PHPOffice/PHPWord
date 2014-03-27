<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Section;

use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Footnote element
 */
class Footnote
{
    /**
     * Paragraph style
     *
     * @var \PhpOffice\PhpWord\Style\Paragraph
     */
    private $_styleParagraph;

    /**
     * Footnote Reference ID
     *
     * @var string
     */
    private $_refId;

    /**
     * Text collection
     *
     * @var array
     */
    private $_elementCollection;

    /**
     * Create a new Footnote Element
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
     * @return \PhpOffice\PhpWord\Section\Text
     */
    public function addText($text = null, $styleFont = null)
    {
        $givenText                  = $text;
        $text                       = new Text($givenText, $styleFont);
        $this->_elementCollection[] = $text;
        return $text;
    }

    /**
     * Add a Link Element
     *
     * @param string $linkSrc
     * @param string $linkName
     * @param mixed $styleFont
     * @return \PhpOffice\PhpWord\Section\Link
     */
    public function addLink($linkSrc, $linkName = null, $styleFont = null)
    {

        $link = new Link($linkSrc, $linkName, $styleFont);
        $rID = \PhpOffice\PhpWord\Footnote::addFootnoteLinkElement($linkSrc);
        $link->setRelationId($rID);

        $this->_elementCollection[] = $link;
        return $link;
    }

    /**
     * Get Footnote content
     *
     * @return array
     */
    public function getElements()
    {
        return $this->_elementCollection;
    }

    /**
     * Get paragraph style
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->_styleParagraph;
    }

    /**
     * Get Footnote Reference ID
     *
     * @return int
     */
    public function getReferenceId()
    {
        return $this->_refId;
    }

    /**
     * Set Footnote Reference ID
     *
     * @param int $refId
     */
    public function setReferenceId($refId)
    {
        $this->_refId = $refId;
    }
}
