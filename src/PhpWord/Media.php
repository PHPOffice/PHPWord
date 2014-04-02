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
     * Section Media Elements
     *
     * @var array
     */
    private static $sectionMedia = array(
        'images'     => array(),
        'embeddings' => array(),
        'links'      => array()
    );

    /**
     * Header Media Elements
     *
     * @var array
     */
    private static $headerMedia = array();

    /**
     * Footer Media Elements
     *
     * @var array
     */
    private static $footerMedia = array();

    /**
     * Media elements
     *
     * @var array
     */
    private static $media = array();

    /**
     * ObjectID Counter
     *
     * @var int
     */
    private static $objectId = 1325353440;

    /**
     * Add new Section Media Element
     *
     * @param  string $src
     * @param  string $type
     * @param  Image $image
     * @return mixed
     */
    public static function addSectionMediaElement($src, $type, Image $image = null)
    {
        $mediaId = md5($src);
        $key = ($type === 'image') ? 'images' : 'embeddings';
        if (!array_key_exists($mediaId, self::$sectionMedia[$key])) {
            $cImg = self::countSectionMediaElements('images');
            $cObj = self::countSectionMediaElements('embeddings');
            $rID = self::countSectionMediaElements() + 7;
            $media = array();
            $folder = null;
            $file = null;
            if ($type === 'image') {
                $cImg++;
                $isMemImage = false;
                if (!is_null($image)) {
                    $isMemImage = $image->getIsMemImage();
                    $ext = $image->getImageExtension();
                }
                if ($isMemImage) {
                    $media['isMemImage'] = true;
                    $media['createfunction'] = $image->getImageCreateFunction();
                    $media['imagefunction'] = $image->getImageFunction();
                }
                $folder = 'media';
                $file = $type . $cImg . '.' . strtolower($ext);
            } elseif ($type === 'oleObject') {
                $cObj++;
                $folder = 'embeddings';
                $file = $type . $cObj . '.bin';
            }
            $media['source'] = $src;
            $media['target'] = "$folder/section_$file";
            $media['type'] = $type;
            $media['rID'] = $rID;
            self::$sectionMedia[$key][$mediaId] = $media;
            if ($type === 'oleObject') {
                return array($rID, ++self::$objectId);
            }
            return $rID;
        } else {
            if ($type === 'oleObject') {
                $rID = self::$sectionMedia[$key][$mediaId]['rID'];
                return array($rID, ++self::$objectId);
            }
            return self::$sectionMedia[$key][$mediaId]['rID'];
        }
    }

    /**
     * Add new Section Link Element
     *
     * @param string $linkSrc
     * @return mixed
     */
    public static function addSectionLinkElement($linkSrc)
    {
        $rID = self::countSectionMediaElements() + 7;

        $link = array();
        $link['target'] = $linkSrc;
        $link['rID'] = $rID;
        $link['type'] = 'hyperlink';

        self::$sectionMedia['links'][] = $link;

        return $rID;
    }

    /**
     * Get Section Media Elements
     *
     * @param string $key
     * @return array
     */
    public static function getSectionMediaElements($key = null)
    {
        if (!is_null($key)) {
            return self::$sectionMedia[$key];
        }

        $arrImages = self::$sectionMedia['images'];
        $arrObjects = self::$sectionMedia['embeddings'];
        $arrLinks = self::$sectionMedia['links'];
        return array_merge($arrImages, $arrObjects, $arrLinks);
    }

    /**
     * Get Section Media Elements Count
     *
     * @param string $key
     * @return int
     */
    public static function countSectionMediaElements($key = null)
    {
        if (!is_null($key)) {
            return count(self::$sectionMedia[$key]);
        }

        $cImages = count(self::$sectionMedia['images']);
        $cObjects = count(self::$sectionMedia['embeddings']);
        $cLinks = count(self::$sectionMedia['links']);
        return ($cImages + $cObjects + $cLinks);
    }

    /**
     * Add new Header Media Element
     *
     * @param  int $headerCount
     * @param  string $src
     * @param  Image $image
     * @return int
     */
    public static function addHeaderMediaElement($headerCount, $src, Image $image = null)
    {
        return self::addMediaElement("header{$headerCount}", 'image', $src, $image);
    }

    /**
     * Get Header Media Elements Count
     *
     * @param string $key
     * @return int
     */
    public static function countHeaderMediaElements($key)
    {
        return self::countMediaElements($key);
    }

    /**
     * Get Header Media Elements
     *
     * @return array
     */
    public static function getHeaderMediaElements($prefix = 'header')
    {
        $mediaCollection = array();
        if (!empty(self::$media)) {
            foreach (self::$media as $key => $val) {
                if (substr($key, 0, 6) == $prefix) {
                    $mediaCollection[$key] = $val;
                }
            }
        }

        return $mediaCollection;
    }

    /**
     * Add new Footer Media Element
     *
     * @param  int $footerCount
     * @param  string $src
     * @param  Image $image
     * @return int
     */
    public static function addFooterMediaElement($footerCount, $src, Image $image = null)
    {
        return self::addMediaElement("footer{$footerCount}", 'image', $src, $image);
    }

    /**
     * Get Footer Media Elements Count
     *
     * @param string $key
     * @return int
     */
    public static function countFooterMediaElements($key)
    {
        return self::countMediaElements($key);
    }

    /**
     * Get Footer Media Elements
     *
     * @return array
     */
    public static function getFooterMediaElements()
    {
        return self::getHeaderMediaElements('footer');
    }

    /**
     * Add new media element
     *
     * @param string $container section|headerx|footerx|footnote
     * @param string $mediaType image|embeddings|hyperlink
     * @param string $source
     * @param Image $image
     * @return int
     */
    public static function addMediaElement($container, $mediaType, $source, Image $image = null)
    {
        // Assign media Id and initiate media container if none exists
        $mediaId = md5($source);
        if (!array_key_exists($container, self::$media)) {
            self::$media[$container]= array();
        }

        // Add media if not exists or point to existing media
        if (!array_key_exists($mediaId, self::$media[$container])) {
            $mediaCount = self::countMediaElements($container);
            $mediaTypeCount = self::countMediaElements($container, $mediaType);
            $mediaData = array();
            $relId = $mediaCount + 1;
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
            } elseif ($mediaType == 'embeddings') {
                $file = "oleObject{$mediaTypeCount}.bin";
                $target = "embeddings/{$container}_oleObject{$mediaTypeCount}.bin";
            // Links
            } elseif ($mediaType == 'hyperlink') {
                $target = $source;
            }

            $mediaData['source'] = $source;
            $mediaData['target'] = $target;
            $mediaData['type'] = $mediaType;
            $mediaData['rID'] = $relId;
            self::$media[$container][$mediaId] = $mediaData;
            if ($mediaType === 'embeddings') {
                return array($relId, ++self::$objectId);
            } else {
                return $relId;
            }
        } else {
            if ($mediaType === 'embeddings') {
                $relId = self::$media[$container][$mediaId]['rID'];
                return array($relId, ++self::$objectId);
            } else {
                return self::$media[$container][$mediaId]['rID'];
            }
        }
    }

    /**
     * Get media elements count
     *
     * @param string $container
     * @param string $mediaType
     * @return int
     */
    public static function countMediaElements($container, $mediaType = null)
    {
        $mediaCount = 0;
        foreach (self::$media[$container] as $mediaKey => $mediaData) {
            if (!is_null($mediaType)) {
                if ($mediaType == $mediaData['type']) {
                    $mediaCount++;
                }
            } else {
                $mediaCount++;
            }
        }

        return $mediaCount;
    }

    /**
     * Get media elements
     *
     * @param string $container
     * @return int
     */
    public static function getMediaElements($container, $mediaType = null)
    {
        if (!array_key_exists($container, self::$media)) {
            return false;
        }

        $mediaElements = array();
        foreach (self::$media[$container] as $mediaKey => $mediaData) {
            if (!is_null($mediaType)) {
                if ($mediaType == $mediaData['type']) {
                    $mediaElements[$mediaKey] = $mediaData;
                }
            } else {
                $mediaElements[$mediaKey] = $mediaData;
            }
        }

        return $mediaElements;
    }
}
