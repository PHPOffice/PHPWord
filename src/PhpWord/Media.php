<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Exception\Exception;

/**
 * Media collection
 */
class Media
{
    /**
     * Media elements
     *
     * @var array
     */
    private static $elements = array();

    /**
     * Add new media element
     *
     * @since 0.10.0
     * @since 0.9.2
     *
     * @param string $container section|headerx|footerx|footnote|endnote
     * @param string $mediaType image|object|link
     * @param string $source
     * @param \PhpOffice\PhpWord\Element\Image $image
     *
     * @return integer
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public static function addElement($container, $mediaType, $source, Image $image = null)
    {
        // Assign unique media Id and initiate media container if none exists
        $mediaId = md5($container . $source);
        if (!isset(self::$elements[$container])) {
            self::$elements[$container] = array();
        }

        // Add media if not exists or point to existing media
        if (!isset(self::$elements[$container][$mediaId])) {
            $mediaCount = self::countElements($container);
            $mediaTypeCount = self::countElements($container, $mediaType);
            $mediaTypeCount++;
            $rId = ++$mediaCount;
            $target = null;
            $mediaData = array('mediaIndex' => $mediaTypeCount);

            switch ($mediaType) {
                // Images
                case 'image':
                    if (is_null($image)) {
                        throw new Exception('Image object not assigned.');
                    }
                    $isMemImage = $image->isMemImage();
                    $extension = $image->getImageExtension();
                    $mediaData['imageExtension'] = $extension;
                    $mediaData['imageType'] = $image->getImageType();
                    if ($isMemImage) {
                        $mediaData['isMemImage'] = true;
                        $mediaData['createFunction'] = $image->getImageCreateFunction();
                        $mediaData['imageFunction'] = $image->getImageFunction();
                    }
                    $target = "{$container}_image{$mediaTypeCount}.{$extension}";
                    $image->setTarget($target);
                    $image->setMediaIndex($mediaTypeCount);
                    break;

                // Objects
                case 'object':
                    $target = "{$container}_oleObject{$mediaTypeCount}.bin";
                    break;

                // Links
                case 'link':
                    $target = $source;
                    break;
            }

            $mediaData['source'] = $source;
            $mediaData['target'] = $target;
            $mediaData['type'] = $mediaType;
            $mediaData['rID'] = $rId;
            self::$elements[$container][$mediaId] = $mediaData;
            return $rId;
        } else {
            $mediaData = self::$elements[$container][$mediaId];
            if (!is_null($image)) {
                $image->setTarget($mediaData['target']);
                $image->setMediaIndex($mediaData['mediaIndex']);
            }
            return $mediaData['rID'];
        }
    }

    /**
     * Get media elements count
     *
     * @param string $container section|headerx|footerx|footnote|endnote
     * @param string $mediaType image|object|link
     * @return integer
     * @since 0.10.0
     */
    public static function countElements($container, $mediaType = null)
    {
        $mediaCount = 0;

        if (isset(self::$elements[$container])) {
            foreach (self::$elements[$container] as $mediaData) {
                if (!is_null($mediaType)) {
                    if ($mediaType == $mediaData['type']) {
                        $mediaCount++;
                    }
                } else {
                    $mediaCount++;
                }
            }
        }

        return $mediaCount;
    }

    /**
     * Get media elements
     *
     * @param string $container section|headerx|footerx|footnote|endnote
     * @param string $type image|object|link
     * @return array
     * @since 0.10.0
     */
    public static function getElements($container, $type = null)
    {
        $elements = array();

        // If header/footer, search for headerx and footerx where x is number
        if ($container == 'header' || $container == 'footer') {
            foreach (self::$elements as $key => $val) {
                if (substr($key, 0, 6) == $container) {
                    $elements[$key] = $val;
                }
            }
            return $elements;
        } else {
            if (!isset(self::$elements[$container])) {
                return $elements;
            }
            return self::getElementsByType($container, $type);
        }
    }

    /**
     * Get elements by media type
     *
     * @param string $container section|footnote|endnote
     * @param string $type image|object|link
     * @return array
     * @since 0.11.0 Splitted from `getElements` to reduce complexity
     */
    private static function getElementsByType($container, $type = null)
    {
        $elements = array();

        foreach (self::$elements[$container] as $key => $data) {
            if ($type !== null) {
                if ($type == $data['type']) {
                    $elements[$key] = $data;
                }
            } else {
                $elements[$key] = $data;
            }
        }

        return $elements;
    }

    /**
     * Reset media elements
     */
    public static function resetElements()
    {
        self::$elements = array();
    }

    /**
     * Add new Section Media Element
     *
     * @deprecated 0.10.0
     *
     * @param  string $src
     * @param  string $type
     * @param  \PhpOffice\PhpWord\Element\Image $image
     *
     * @return integer
     *
     * @codeCoverageIgnore
     */
    public static function addSectionMediaElement($src, $type, Image $image = null)
    {
        return self::addElement('section', $type, $src, $image);
    }

    /**
     * Add new Section Link Element
     *
     * @deprecated 0.10.0
     *
     * @param string $linkSrc
     *
     * @return integer
     *
     * @codeCoverageIgnore
     */
    public static function addSectionLinkElement($linkSrc)
    {
        return self::addElement('section', 'link', $linkSrc);
    }

    /**
     * Get Section Media Elements
     *
     * @deprecated 0.10.0
     *
     * @param string $key
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public static function getSectionMediaElements($key = null)
    {
        return self::getElements('section', $key);
    }

    /**
     * Get Section Media Elements Count
     *
     * @deprecated 0.10.0
     *
     * @param string $key
     *
     * @return integer
     *
     * @codeCoverageIgnore
     */
    public static function countSectionMediaElements($key = null)
    {
        return self::countElements('section', $key);
    }

    /**
     * Add new Header Media Element
     *
     * @deprecated 0.10.0
     *
     * @param  integer $headerCount
     * @param  string $src
     * @param  \PhpOffice\PhpWord\Element\Image $image
     *
     * @return integer
     *
     * @codeCoverageIgnore
     */
    public static function addHeaderMediaElement($headerCount, $src, Image $image = null)
    {
        return self::addElement("header{$headerCount}", 'image', $src, $image);
    }

    /**
     * Get Header Media Elements Count
     *
     * @deprecated 0.10.0
     *
     * @param string $key
     *
     * @return integer
     *
     * @codeCoverageIgnore
     */
    public static function countHeaderMediaElements($key)
    {
        return self::countElements($key);
    }

    /**
     * Get Header Media Elements
     *
     * @deprecated 0.10.0
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public static function getHeaderMediaElements()
    {
        return self::getElements('header');
    }

    /**
     * Add new Footer Media Element
     *
     * @deprecated 0.10.0
     *
     * @param  integer $footerCount
     * @param  string $src
     * @param  \PhpOffice\PhpWord\Element\Image $image
     *
     * @return integer
     *
     * @codeCoverageIgnore
     */
    public static function addFooterMediaElement($footerCount, $src, Image $image = null)
    {
        return self::addElement("footer{$footerCount}", 'image', $src, $image);
    }

    /**
     * Get Footer Media Elements Count
     *
     * @deprecated 0.10.0
     *
     * @param string $key
     *
     * @return integer
     *
     * @codeCoverageIgnore
     */
    public static function countFooterMediaElements($key)
    {
        return self::countElements($key);
    }

    /**
     * Get Footer Media Elements
     *
     * @deprecated 0.10.0
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public static function getFooterMediaElements()
    {
        return self::getElements('footer');
    }
}
