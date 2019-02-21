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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\TrackChange
 *
 * @runTestsInSeparateProcesses
 */
class TrackChangeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * New instance
     */
    public function testConstructDefault()
    {
        $author = 'Test User';
        $date = new \DateTime('2000-01-01');
        $oTrackChange = new TrackChange(TrackChange::INSERTED, $author, $date);

        $oText = new Text('dummy text');
        $oText->setTrackChange($oTrackChange);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TrackChange', $oTrackChange);
        $this->assertEquals($author, $oTrackChange->getAuthor());
        $this->assertEquals($date, $oTrackChange->getDate());
        $this->assertEquals(TrackChange::INSERTED, $oTrackChange->getChangeType());
    }

    /**
     * New instance with invalid \DateTime (produced by \DateTime::createFromFormat(...))
     */
    public function testConstructDefaultWithInvalidDate()
    {
        $author = 'Test User';
        $date = false;
        $oTrackChange = new TrackChange(TrackChange::INSERTED, $author, $date);

        $oText = new Text('dummy text');
        $oText->setTrackChange($oTrackChange);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\TrackChange', $oTrackChange);
        $this->assertEquals($author, $oTrackChange->getAuthor());
        $this->assertEquals($date, null);
        $this->assertEquals(TrackChange::INSERTED, $oTrackChange->getChangeType());
    }
}
