<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

/**
 *  Footnote
 */
class Footnote
{
    /**
     * Footnote Elements
     *
     * @var array
     */
    private static $_footnoteCollection = array();

    /**
     * Footnote Link Elements
     *
     * @var array
     */
    private static $_footnoteLink = array();

    /**
     * Add new Footnote Element
     *
     * @return int Reference ID
     */
    public static function addFootnoteElement(\PhpOffice\PhpWord\Element\Footnote $footnote)
    {
        $refID = self::countFootnoteElements() + 2;

        self::$_footnoteCollection[] = $footnote;

        return $refID;
    }

    /**
     * Get Footnote Elements
     *
     * @return array
     */
    public static function getFootnoteElements()
    {
        return self::$_footnoteCollection;
    }

    /**
     * Get Footnote Elements Count
     *
     * @return int
     */
    public static function countFootnoteElements()
    {
        return count(self::$_footnoteCollection);
    }

    /**
     * Add new Footnote Link Element
     *
     * @param string $linkSrc
     *
     * @return int Reference ID
     */
    public static function addFootnoteLinkElement($linkSrc)
    {
        $rID = self::countFootnoteLinkElements() + 1;

        $link = array();
        $link['target'] = $linkSrc;
        $link['rID'] = $rID;
        $link['type'] = 'hyperlink';

        self::$_footnoteLink[] = $link;

        return $rID;
    }

    /**
     * Get Footnote Link Elements
     *
     * @return array
     */
    public static function getFootnoteLinkElements()
    {
        return self::$_footnoteLink;
    }

    /**
     * Get Footnote Link Elements Count
     *
     * @return int
     */
    public static function countFootnoteLinkElements()
    {
        return count(self::$_footnoteLink);
    }
}
