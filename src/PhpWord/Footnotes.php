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
 * Footnote collection
 */
class Footnotes
{
    /**
     * Elements
     *
     * @var array
     */
    private static $elements = array();

    /**
     * Add new element
     *
     * @param \PhpOffice\PhpWord\Element\Footnote $element
     * @return integer Reference ID
     * @since 0.10.0
     */
    public static function addElement($element)
    {
        $rId = self::countElements() + 1;
        self::$elements[$rId] = $element;

        return $rId;
    }

    /**
     * Set element
     *
     * @param integer $index
     * @param \PhpOffice\PhpWord\Element\Footnote $element
     * @since 0.10.0
     */
    public static function setElement($index, $element)
    {
        if (array_key_exists($index, self::$elements)) {
            self::$elements[$index] = $element;
        }
    }

    /**
     * Get element by index
     *
     * @param integer $index
     * @return \PhpOffice\PhpWord\Element\Footnote
     * @since 0.10.0
     */
    public static function getElement($index)
    {
        if (array_key_exists($index, self::$elements)) {
            return self::$elements[$index];
        } else {
            return null;
        }
    }

    /**
     * Get elements
     *
     * @return array
     * @since 0.10.0
     */
    public static function getElements()
    {
        return self::$elements;
    }

    /**
     * Get element count
     *
     * @return integer
     * @since 0.10.0
     */
    public static function countElements()
    {
        return count(self::$elements);
    }

    /**
     * Reset elements
     *
     * @since 0.10.0
     */
    public static function resetElements()
    {
        self::$elements = array();
    }

    /**
     * Add new footnote
     *
     * @param \PhpOffice\PhpWord\Element\Footnote $element
     * @return integer Reference ID
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function addFootnoteElement($element)
    {
        return self::addElement($element);
    }

    /**
     * Get Footnote Elements
     *
     * @return array
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function getFootnoteElements()
    {
        return self::getElements();
    }

    /**
     * Get Footnote Elements Count
     *
     * @return integer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function countFootnoteElements()
    {
        return self::countElements();
    }

    /**
     * Add new Footnote Link Element
     *
     * @param string $linkSrc
     * @return integer Reference ID
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function addFootnoteLinkElement($linkSrc)
    {
        return Media::addElement('footnotes', 'link', $linkSrc);
    }

    /**
     * Get Footnote Link Elements
     *
     * @return array
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function getFootnoteLinkElements()
    {
        return Media::getElements('footnotes', 'link');
    }
}
