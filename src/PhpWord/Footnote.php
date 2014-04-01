<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Element\Footnote as FootnoteElement;

/**
 *  Footnote
 */
class Footnote
{
    /**
     * Footnote elements
     *
     * @var array
     */
    private static $elements = array();

    /**
     * Add new footnote
     *
     * @param FootnoteElement $footnote
     * @return int Reference ID
     */
    public static function addFootnoteElement(FootnoteElement $footnote)
    {
        $refID = self::countFootnoteElements() + 1;

        self::$elements[] = $footnote;

        return $refID;
    }

    /**
     * Get Footnote Elements
     *
     * @return array
     */
    public static function getFootnoteElements()
    {
        return self::$elements;
    }

    /**
     * Get Footnote Elements Count
     *
     * @return int
     */
    public static function countFootnoteElements()
    {
        return count(self::$elements);
    }

    /**
     * Add new Footnote Link Element
     *
     * @param string $linkSrc
     * @return int Reference ID
     * @deprecated 0.9.2
     */
    public static function addFootnoteLinkElement($linkSrc)
    {
        return Media::addMediaElement('footnotes', 'hyperlink', $linkSrc);
    }

    /**
     * Get Footnote Link Elements
     *
     * @return array
     * @deprecated 0.9.2
     */
    public static function getFootnoteLinkElements()
    {
        return Media::getMediaElements('footnotes', 'hyperlink');
    }
}
