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
 *
 * This static class has been deprecated and replaced by Collection\Footnotes.
 * File maintained for backward compatibility and will be removed on 1.0.
 *
 * @deprecated 0.10.0
 * @codeCoverageIgnore
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
     */
    public static function getElements()
    {
        return self::$elements;
    }

    /**
     * Get element count
     *
     * @return integer
     */
    public static function countElements()
    {
        return count(self::$elements);
    }

    /**
     * Reset elements
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
     */
    public static function addFootnoteElement($element)
    {
        return self::addElement($element);
    }

    /**
     * Get Footnote Elements
     *
     * @return array
     */
    public static function getFootnoteElements()
    {
        return self::getElements();
    }

    /**
     * Get Footnote Elements Count
     *
     * @return integer
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
     */
    public static function addFootnoteLinkElement($linkSrc)
    {
        return Media::addElement('footnotes', 'link', $linkSrc);
    }

    /**
     * Get Footnote Link Elements
     *
     * @return array
     */
    public static function getFootnoteLinkElements()
    {
        return Media::getElements('footnotes', 'link');
    }
}
