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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * TrackChange element
 * @see http://datypic.com/sc/ooxml/t-w_CT_TrackChange.html
 */
class TrackChange extends AbstractContainer
{
    /**
     * @var string Container type
     */
    protected $container = 'TrackChange';

    /**
     * Author
     *
     * @var string
     */
    private $author;

    /**
     * Date
     *
     * @var \DateTime
     */
    private $date;

    /**
     * Create a new TrackChange Element
     *
     * @param string $author
     * @param \DateTime $date
     */
    public function __construct($author, \DateTime $date = null)
    {
        $this->author = $author;
        $this->date = $date;
    }

    /**
     * Get TrackChange Author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get TrackChange Date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
