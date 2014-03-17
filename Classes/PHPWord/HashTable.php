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

namespace PhpOffice\PhpWord;

/**
 * @codeCoverageIgnore Legacy from PHPExcel
 */
class HashTable
{
    /**
     * HashTable elements
     *
     * @var array
     */
    public $_items = array();

    /**
     * HashTable key map
     *
     * @var array
     */
    public $_keyMap = array();

    /**
     * @param PhpOffice\PhpWord\IComparable[] $pSource Optional source array to create HashTable from
     * @throws Exception
     */
    public function __construct($pSource = null)
    {
        if (!is_null($pSource)) {
            $this->addFromSource($pSource);
        }
    }

    /**
     * Add HashTable items from source
     *
     * @param PhpOffice\PhpWord\IComparable[] $pSource Source array to create HashTable from
     * @throws Exception
     */
    public function addFromSource($pSource = null)
    {
        // Check if an array was passed
        if ($pSource == null) {
            return;
        } elseif (!is_array($pSource)) {
            throw new Exception('Invalid array parameter passed.');
        }

        foreach ($pSource as $item) {
            $this->add($item);
        }
    }

    /**
     * Add HashTable item
     *
     * @param PhpOffice\PhpWord\IComparable $pSource Item to add
     * @throws Exception
     */
    public function add(IComparable $pSource = null)
    {
        // Determine hashcode
        $hashCode = null;
        $hashIndex = $pSource->getHashIndex();
        if (is_null($hashIndex)) {
            $hashCode = $pSource->getHashCode();
        } elseif (isset ($this->_keyMap[$hashIndex])) {
            $hashCode = $this->_keyMap[$hashIndex];
        } else {
            $hashCode = $pSource->getHashCode();
        }

        // Add value
        if (!isset($this->_items[$hashCode])) {
            $this->_items[$hashCode] = $pSource;
            $index = count($this->_items) - 1;
            $this->_keyMap[$index] = $hashCode;
            $pSource->setHashIndex($index);
        } else {
            $pSource->setHashIndex($this->_items[$hashCode]->getHashIndex());
        }
    }

    /**
     * Remove HashTable item
     *
     * @param PhpOffice\PhpWord\IComparable $pSource Item to remove
     * @throws Exception
     */
    public function remove(IComparable $pSource = null)
    {
        if (isset($this->_items[$pSource->getHashCode()])) {
            unset($this->_items[$pSource->getHashCode()]);

            $deleteKey = -1;
            foreach ($this->_keyMap as $key => $value) {
                if ($deleteKey >= 0) {
                    $this->_keyMap[$key - 1] = $value;
                }

                if ($value == $pSource->getHashCode()) {
                    $deleteKey = $key;
                }
            }
            unset($this->_keyMap[count($this->_keyMap) - 1]);
        }
    }

    /**
     * Clear HashTable
     *
     */
    public function clear()
    {
        $this->_items = array();
        $this->_keyMap = array();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_items);
    }

    /**
     * @param string $pHashCode
     * @return int Index
     */
    public function getIndexForHashCode($pHashCode = '')
    {
        return array_search($pHashCode, $this->_keyMap);
    }

    /**
     * @param int $pIndex
     * @return PhpOffice\PhpWord\IComparable
     */
    public function getByIndex($pIndex = 0)
    {
        if (isset($this->_keyMap[$pIndex])) {
            return $this->getByHashCode($this->_keyMap[$pIndex]);
        }

        return null;
    }

    /**
     * @param string $pHashCode
     * @return PhpOffice\PhpWord\IComparable
     *
     */
    public function getByHashCode($pHashCode = '')
    {
        if (isset($this->_items[$pHashCode])) {
            return $this->_items[$pHashCode];
        }

        return null;
    }

    /**
     * @return PhpOffice\PhpWord\IComparable[]
     */
    public function toArray()
    {
        return $this->_items;
    }

    /**
     * Implement PHP __clone to create a deep clone, not just a shallow copy.
     */
    public function __clone()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if (is_object($value)) {
                $this->$key = clone $value;
            }
        }
    }
}