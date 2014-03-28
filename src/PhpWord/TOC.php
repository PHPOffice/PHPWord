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
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Style\Font;

/**
 * Table of contents
 */
class TOC
{
    /**
     * Title Elements
     *
     * @var array
     */
    private static $_titles = array();

    /**
     * TOC Style
     *
     * @var array
     */
    private static $_styleTOC;

    /**
     * Font Style
     *
     * @var array
     */
    private static $_styleFont;

    /**
     * Title Anchor
     *
     * @var array
     */
    private static $_anchor = 252634154;

    /**
     * Title Bookmark
     *
     * @var array
     */
    private static $_bookmarkId = 0;

	/**
	 * Min title depth to show
	 *
	 * @var int
	 */
	private $_minDepth = 1;
	
	/**
	 * Max title depth to show
	 *
	 * @var int
	 */
	private $_maxDepth = 9;

    /**
     * Create a new Table-of-Contents Element
     *
     * @param array $styleFont
     * @param array $styleTOC
     */
    public function __construct($styleFont = null, $styleTOC = null, $minDepth = 1, $maxDepth = 9)
    {
        self::$_styleTOC = new \PhpOffice\PhpWord\Style\TOC();

        if (!is_null($styleTOC) && is_array($styleTOC)) {
            foreach ($styleTOC as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                self::$_styleTOC->setStyleValue($key, $value);
            }
        }

        if (!is_null($styleFont)) {
            if (is_array($styleFont)) {
                self::$_styleFont = new Font();

                foreach ($styleFont as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    self::$_styleFont->setStyleValue($key, $value);
                }
            } else {
                self::$_styleFont = $styleFont;
            }
        }
		
		$this->_minDepth = $minDepth;
		$this->_maxDepth = $maxDepth;
    }

    /**
     * Add a Title
     *
     * @param string $text
     * @param int $depth
     * @return array
     */
    public static function addTitle($text, $depth = 0)
    {
        $anchor = '_Toc' . ++self::$_anchor;
        $bookmarkId = self::$_bookmarkId++;

        $title = array();
        $title['text'] = $text;
        $title['depth'] = $depth;
        $title['anchor'] = $anchor;
        $title['bookmarkId'] = $bookmarkId;

        self::$_titles[] = $title;

        return array($anchor, $bookmarkId);
    }

    /**
     * Get all titles
     *
     * @return array
     */
    public function getTitles()
    {
        $titles = self::$_titles;
		foreach ($titles as $i=>$title) {
			if ($this->_minDepth > $title['depth']) {
				unset($titles[$i]);
			}
			if (($this->_maxDepth != 0) && ($this->_maxDepth < $title['depth'])) {
				unset($titles[$i]);
			}
		}
		$titles = array_merge(array(), $titles);
		
		return $titles;
    }

    /**
     * Get TOC Style
     *
     * @return \PhpOffice\PhpWord\Style\TOC
     */
    public static function getStyleTOC()
    {
        return self::$_styleTOC;
    }

    /**
     * Get Font Style
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public static function getStyleFont()
    {
        return self::$_styleFont;
    }
	
	/**
	 * Get Max Depth
	 * 
	 * @return int Max depth of titles
	 */
	public function getMaxDepth() {
		return $this->_maxDepth;
	}
	
	/**
	 * Get Min Depth
	 * 
	 * @return int Min depth of titles
	 */
	public function getMinDepth() {
		return $this->_minDepth;
	}
}
