<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

/**
 * Class PHPWord_Media
 */
class PHPWord_Media
{
    /**
     * Section Media Elements
     *
     * @var array
     */
    private static $_sectionMedia = array(
        'images' => array(),
        'embeddings' => array(),
        'links' => array()
    );

    /**
     * Header Media Elements
     *
     * @var array
     */
    private static $_headerMedia = array();

    /**
     * Footer Media Elements
     *
     * @var array
     */
    private static $_footerMedia = array();

    /**
     * ObjectID Counter
     *
     * @var int
     */
    private static $_objectId = 1325353440;

    /**
     * Add new Section Media Element
     *
     * @param string $src
     * @param string $type
     * @param PHPWord_Section_MemoryImage|null $memoryImage
     * @return mixed
     */
    public static function addSectionMediaElement($src, $type, PHPWord_Section_MemoryImage $memoryImage = null)
    {
        $mediaId = md5($src);
        $key = ($type === 'image') ? 'images' : 'embeddings';

        if (!array_key_exists($mediaId, self::$_sectionMedia[$key])) {
            $cImg = self::countSectionMediaElements('images');
            $cObj = self::countSectionMediaElements('embeddings');
            $rID = self::countSectionMediaElements() + 7;

            $media = array();

            $folder = null;
            $file = null;
            if ($type === 'image') {
                $cImg++;
                //Detect if it's a memory image first by php ext and second by regex
                $isMemImage = false;
                if (stripos(strrev($src), strrev('.php')) === 0) {
                    $isMemImage = true;
                }
                if (!$isMemImage) {
                    $isMemImage = (filter_var($src, FILTER_VALIDATE_URL) !== false);
                }
                if ($isMemImage) {
                    $extension = $memoryImage->getImageExtension();
                    $media['isMemImage'] = true;
                    $media['createfunction'] = $memoryImage->getImageCreateFunction();
                    $media['imagefunction'] = $memoryImage->getImageFunction();
                } else {
                    $extension = PHPWord_Shared_File::imagetype($src);
                }

                $folder = 'media';
                $file = $type . $cImg . '.' . strtolower($extension);
            } elseif ($type === 'oleObject') {
                $cObj++;
                $folder = 'embedding';
                $file = $type . $cObj . '.bin';
            }

            $media['source'] = $src;
            $media['target'] = "$folder/section_$file";
            $media['type'] = $type;
            $media['rID'] = $rID;

            self::$_sectionMedia[$key][$mediaId] = $media;

            if ($type === 'oleObject') {
                return array($rID, ++self::$_objectId);
            }

            return $rID;
        }

        if ($type === 'oleObject') {
            $rID = self::$_sectionMedia[$key][$mediaId]['rID'];
            return array($rID, ++self::$_objectId);
        }
        return self::$_sectionMedia[$key][$mediaId]['rID'];
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

        self::$_sectionMedia['links'][] = $link;

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
            return self::$_sectionMedia[$key];
        }

        $arrImages = self::$_sectionMedia['images'];
        $arrObjects = self::$_sectionMedia['embeddings'];
        $arrLinks = self::$_sectionMedia['links'];
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
            return count(self::$_sectionMedia[$key]);
        }

        $cImages = count(self::$_sectionMedia['images']);
        $cObjects = count(self::$_sectionMedia['embeddings']);
        $cLinks = count(self::$_sectionMedia['links']);
        return ($cImages + $cObjects + $cLinks);
    }

    /**
     * Add new Header Media Element
     *
     * @param int $headerCount
     * @param string $src
     * @param PHPWord_Section_MemoryImage|null $memoryImage
     * @return int
     */
    public static function addHeaderMediaElement($headerCount, $src, PHPWord_Section_MemoryImage $memoryImage = null)
    {
        $mediaId = md5($src);
        $key = 'header' . $headerCount;

        if (!array_key_exists($key, self::$_headerMedia)) {
            self::$_headerMedia[$key] = array();
        }

        if (!array_key_exists($mediaId, self::$_headerMedia[$key])) {
            $cImg = self::countHeaderMediaElements($key);
            $rID = $cImg + 1;

            $cImg++;
            $inf = pathinfo($src);
            $isMemImage = (substr(strtolower($inf['extension']), 0, 3) == 'php') ? true : false;

            $media = array();
            if ($isMemImage) {
                $ext = $memoryImage->getImageExtension();
                $media['isMemImage'] = true;
                $media['createfunction'] = $memoryImage->getImageCreateFunction();
                $media['imagefunction'] = $memoryImage->getImageFunction();
            } else {
                $ext = $inf['extension'];
                if ($ext == 'jpeg') { // Office crashes when adding a jpEg Image, so rename to jpg
                    $ext = 'jpg';
                }
            }
            $file = 'image' . $cImg . '.' . strtolower($ext);

            $media['source'] = $src;
            $media['target'] = 'media/' . $key . '_' . $file;
            $media['type'] = 'image';
            $media['rID'] = $rID;

            self::$_headerMedia[$key][$mediaId] = $media;

            return $rID;
        }
        return self::$_headerMedia[$key][$mediaId]['rID'];
    }

    /**
     * Get Header Media Elements Count
     *
     * @param string $key
     * @return int
     */
    public static function countHeaderMediaElements($key)
    {
        return count(self::$_headerMedia[$key]);
    }

    /**
     * Get Header Media Elements
     *
     * @return int
     */
    public static function getHeaderMediaElements()
    {
        return self::$_headerMedia;
    }

    /**
     * Add new Footer Media Element
     *
     * @param int $footerCount
     * @param string $src
     * @param PHPWord_Section_MemoryImage|null $memoryImage
     * @return int
     */
    public static function addFooterMediaElement($footerCount, $src, PHPWord_Section_MemoryImage $memoryImage = null)
    {
        $mediaId = md5($src);
        $key = 'footer' . $footerCount;

        if (!array_key_exists($key, self::$_footerMedia)) {
            self::$_footerMedia[$key] = array();
        }

        if (!array_key_exists($mediaId, self::$_footerMedia[$key])) {
            $cImg = self::countFooterMediaElements($key);
            $rID = $cImg + 1;

            $cImg++;
            $inf = pathinfo($src);
            $isMemImage = (substr(strtolower($inf['extension']), 0, 3) == 'php') ? true : false;

            $media = array();
            if ($isMemImage) {
                $ext = $memoryImage->getImageExtension();
                $media['isMemImage'] = true;
                $media['createfunction'] = $memoryImage->getImageCreateFunction();
                $media['imagefunction'] = $memoryImage->getImageFunction();
            } else {
                $ext = $inf['extension'];
                if ($ext == 'jpeg') { // Office crashes when adding a jpEg Image, so rename to jpg
                    $ext = 'jpg';
                }
            }
            $file = 'image' . $cImg . '.' . strtolower($ext);

            $media['source'] = $src;
            $media['target'] = 'media/' . $key . '_' . $file;
            $media['type'] = 'image';
            $media['rID'] = $rID;

            self::$_footerMedia[$key][$mediaId] = $media;

            return $rID;
        }
        return self::$_footerMedia[$key][$mediaId]['rID'];
    }

    /**
     * Get Footer Media Elements Count
     *
     * @param string $key
     * @return int
     */
    public static function countFooterMediaElements($key)
    {
        return count(self::$_footerMedia[$key]);
    }

    /**
     * Get Footer Media Elements
     *
     * @return int
     */
    public static function getFooterMediaElements()
    {
        return self::$_footerMedia;
    }
}
