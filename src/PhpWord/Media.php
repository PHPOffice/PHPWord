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

/**
 * Media
 */
class Media
{
    /**
     * Media elements
     *
     * @var array
     */
    private static $media = array();

    /**
     * Add new media element
     *
     * @param string $container section|headerx|footerx|footnote
     * @param string $mediaType image|object|link
     * @param string $source
     * @param Image $image
     * @return integer
     */
    public static function addMediaElement($container, $mediaType, $source, Image $image = null)
    {
        // Assign unique media Id and initiate media container if none exists
        $mediaId = md5($container . $source);
        if (!array_key_exists($container, self::$media)) {
            self::$media[$container]= array();
        }

        // Add media if not exists or point to existing media
        if (!array_key_exists($mediaId, self::$media[$container])) {
            $mediaCount = self::countMediaElements($container);
            $mediaTypeCount = self::countMediaElements($container, $mediaType);
            $mediaData = array();
            $relId = ++$mediaCount;
            $target = null;
            $mediaTypeCount++;

            // Images
            if ($mediaType == 'image') {
                $isMemImage = false;
                if (!is_null($image)) {
                    $isMemImage = $image->getIsMemImage();
                    $ext = $image->getImageExtension();
                    $ext = strtolower($ext);
                }
                if ($isMemImage) {
                    $mediaData['isMemImage'] = true;
                    $mediaData['createfunction'] = $image->getImageCreateFunction();
                    $mediaData['imagefunction'] = $image->getImageFunction();
                }
                $target = "media/{$container}_image{$mediaTypeCount}.{$ext}";
            // Objects
            } elseif ($mediaType == 'object') {
                $file = "oleObject{$mediaTypeCount}.bin";
                $target = "embeddings/{$container}_oleObject{$mediaTypeCount}.bin";
            // Links
            } elseif ($mediaType == 'link') {
                $target = $source;
            }

            $mediaData['source'] = $source;
            $mediaData['target'] = $target;
            $mediaData['type'] = $mediaType;
            $mediaData['rID'] = $relId;
            self::$media[$container][$mediaId] = $mediaData;
            return $relId;
        } else {
            return self::$media[$container][$mediaId]['rID'];
        }
    }

    /**
     * Get media elements count
     *
     * @param string $container section|headerx|footerx|footnote
     * @param string $mediaType image|object|link
     * @return integer
     */
    public static function countMediaElements($container, $mediaType = null)
    {
        $mediaCount = 0;

        if (array_key_exists($container, self::$media)) {
            foreach (self::$media[$container] as $mediaKey => $mediaData) {
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
     * @param string $container section|headerx|footerx|footnote
     * @param string $mediaType image|object|link
     * @return array
     */
    public static function getMediaElements($container, $mediaType = null)
    {
        $mediaElements = array();

        // If header/footer, search for headerx and footerx where x is number
        if ($container == 'header' || $container == 'footer') {
            foreach (self::$media as $key => $val) {
                if (substr($key, 0, 6) == $container) {
                    $mediaElements[$key] = $val;
                }
            }
        } else {
            if (!array_key_exists($container, self::$media)) {
                return $mediaElements;
            }
            foreach (self::$media[$container] as $mediaKey => $mediaData) {
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
     * Add new Section Media Element
     *
     * @param  string $src
     * @param  string $type
     * @param  Image $image
     * @return integer|array
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function addSectionMediaElement($src, $type, Image $image = null)
    {
        return self::addMediaElement("section", $type, $src, $image);
    }

    /**
     * Add new Section Link Element
     *
     * @param string $linkSrc
     * @return integer
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function addSectionLinkElement($linkSrc)
    {
        return self::addMediaElement('section', 'link', $linkSrc);
    }

    /**
     * Get Section Media Elements
     *
     * @param string $key
     * @return array
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function getSectionMediaElements($key = null)
    {
        return self::getMediaElements('section', $key);
    }

    /**
     * Get Section Media Elements Count
     *
     * @param string $key
     * @return integer
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function countSectionMediaElements($key = null)
    {
        return self::countMediaElements('section', $key);
    }

    /**
     * Add new Header Media Element
     *
     * @param  integer $headerCount
     * @param  string $src
     * @param  Image $image
     * @return integer
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function addHeaderMediaElement($headerCount, $src, Image $image = null)
    {
        return self::addMediaElement("header{$headerCount}", 'image', $src, $image);
    }

    /**
     * Get Header Media Elements Count
     *
     * @param string $key
     * @return integer
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function countHeaderMediaElements($key)
    {
        return self::countMediaElements($key);
    }

    /**
     * Get Header Media Elements
     *
     * @return array
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function getHeaderMediaElements()
    {
        return self::getMediaElements('header');
    }

    /**
     * Add new Footer Media Element
     *
     * @param  integer $footerCount
     * @param  string $src
     * @param  Image $image
     * @return integer
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function addFooterMediaElement($footerCount, $src, Image $image = null)
    {
        return self::addMediaElement("footer{$footerCount}", 'image', $src, $image);
    }

    /**
     * Get Footer Media Elements Count
     *
     * @param string $key
     * @return integer
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function countFooterMediaElements($key)
    {
        return self::countMediaElements($key);
    }

    /**
     * Get Footer Media Elements
     *
     * @return array
     * @deprecated 0.9.2
     * @codeCoverageIgnore
     */
    public static function getFooterMediaElements()
    {
        return self::getMediaElements('footer');
    }
}
