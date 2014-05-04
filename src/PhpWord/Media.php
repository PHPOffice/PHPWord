<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
     * @param string $container section|headerx|footerx|footnote|endnote
     * @param string $mediaType image|object|link
     * @param string $source
     * @param \PhpOffice\PhpWord\Element\Image $image
     * @return integer
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @since 0.9.2
     * @since 0.10.0
     */
    public static function addElement($container, $mediaType, $source, Image &$image = null)
    {
        // Assign unique media Id and initiate media container if none exists
        $mediaId = md5($container . $source);
        if (!array_key_exists($container, self::$elements)) {
            self::$elements[$container] = array();
        }

        // Add media if not exists or point to existing media
        if (!array_key_exists($mediaId, self::$elements[$container])) {
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
                    $isMemImage = $image->getIsMemImage();
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

        if (array_key_exists($container, self::$elements)) {
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
     * @param string $mediaType image|object|link
     * @return array
     * @since 0.10.0
     */
    public static function getElements($container, $mediaType = null)
    {
        $mediaElements = array();

        // If header/footer, search for headerx and footerx where x is number
        if ($container == 'header' || $container == 'footer') {
            foreach (self::$elements as $key => $val) {
                if (substr($key, 0, 6) == $container) {
                    $mediaElements[$key] = $val;
                }
            }
        } else {
            if (!array_key_exists($container, self::$elements)) {
                return $mediaElements;
            }
            foreach (self::$elements[$container] as $mediaKey => $mediaData) {
                if (!is_null($mediaType)) {
                    if ($mediaType == $mediaData['type']) {
                        $mediaElements[$mediaKey] = $mediaData;
                    }
                } else {
                    $mediaElements[$mediaKey] = $mediaData;
                }
            }
        }

        return $mediaElements;
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
     * @param  string $src
     * @param  string $type
     * @param  \PhpOffice\PhpWord\Element\Image $image
     * @return integer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function addSectionMediaElement($src, $type, Image $image = null)
    {
        return self::addElement('section', $type, $src, $image);
    }

    /**
     * Add new Section Link Element
     *
     * @param string $linkSrc
     * @return integer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function addSectionLinkElement($linkSrc)
    {
        return self::addElement('section', 'link', $linkSrc);
    }

    /**
     * Get Section Media Elements
     *
     * @param string $key
     * @return array
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function getSectionMediaElements($key = null)
    {
        return self::getElements('section', $key);
    }

    /**
     * Get Section Media Elements Count
     *
     * @param string $key
     * @return integer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function countSectionMediaElements($key = null)
    {
        return self::countElements('section', $key);
    }

    /**
     * Add new Header Media Element
     *
     * @param  integer $headerCount
     * @param  string $src
     * @param  \PhpOffice\PhpWord\Element\Image $image
     * @return integer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function addHeaderMediaElement($headerCount, $src, Image $image = null)
    {
        return self::addElement("header{$headerCount}", 'image', $src, $image);
    }

    /**
     * Get Header Media Elements Count
     *
     * @param string $key
     * @return integer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function countHeaderMediaElements($key)
    {
        return self::countElements($key);
    }

    /**
     * Get Header Media Elements
     *
     * @return array
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function getHeaderMediaElements()
    {
        return self::getElements('header');
    }

    /**
     * Add new Footer Media Element
     *
     * @param  integer $footerCount
     * @param  string $src
     * @param  \PhpOffice\PhpWord\Element\Image $image
     * @return integer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function addFooterMediaElement($footerCount, $src, Image $image = null)
    {
        return self::addElement("footer{$footerCount}", 'image', $src, $image);
    }

    /**
     * Get Footer Media Elements Count
     *
     * @param string $key
     * @return integer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function countFooterMediaElements($key)
    {
        return self::countElements($key);
    }

    /**
     * Get Footer Media Elements
     *
     * @return array
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public static function getFooterMediaElements()
    {
        return self::getElements('footer');
    }
}
