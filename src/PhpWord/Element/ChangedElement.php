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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * ChangedElement class
 */
class ChangedElement
{
    /**
      * change type TYPE_INSERTED|TYPE_DELETED
      *
      * @var int
      */
    private $changeType;

    /**
      * author name of change
      *
      * @var string
      */
    private $author;

    /**
      * date of change
      *
      * @var timestamp UTC
      */
    private $date;

    const TYPE_INSERTED = 1;
    const TYPE_DELETED  = 2;

    /**
      * Create a new Changed Element
      *
      * @param int $changeType
      * @param string $author
      * @param timestamp $date allways in UTC
      */
    public function __construct($changeType, $author, $date)
    {
        $this->changeType = $changeType;
        $this->author = $author;
        $this->date = $date;
    }

    /**
       * Get change type
       *
       * @return int
       */
    public function getChangeType()
    {
        return $this->changeType;
    }

    /**
       * Get author name of change
       *
       * @return string
       */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
       * Get date of change
       *
       * @return timestamp
       */
    public function getDate()
    {
        return $this->date;
    }
}
