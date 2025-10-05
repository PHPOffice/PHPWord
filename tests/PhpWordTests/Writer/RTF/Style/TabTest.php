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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Writer\RTF\Style;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Tab as TabStyle;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\RTF\Style\Tab as TabWriter;
use PHPUnit\Framework\TestCase;

class TabTest extends TestCase
{
    protected function tearDown(): void
    {
        Settings::setDefaultRtl(null);
    }

    /**
     * @param TabWriter $field
     */
    public function removeCr($field): string
    {
        return str_replace("\r\n", "\n", $field->write());
    }

    /**
     * Test tab stops.
     * See page 83 of RTF Specification 1.9.1 for Tabs.
     */
    public function testTabStop(): void
    {
        $parentWriter = new RTF();
        $style = new TabStyle();
        $writer = new TabWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setPosition(3000);
        $style->setType($style::TAB_STOP_CLEAR);
        $expect = '\tx3000';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setType(TabStyle::TAB_STOP_LEFT);
        $expect = '\tx3000';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setType(TabStyle::TAB_STOP_CENTER);
        $expect = '\tcq\tx3000';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setType(TabStyle::TAB_STOP_RIGHT);
        $expect = '\tqr\tx3000';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setType(TabStyle::TAB_STOP_DECIMAL);
        $expect = '\tqdec\tx3000';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setType(TabStyle::TAB_STOP_BAR);
        $expect = '\tb3000';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setType(TabStyle::TAB_STOP_NUM); // No equivalent specified in RTF
        $expect = '\tx3000';
        self::assertEquals($expect, $this->removeCr($writer));
    }

    /**
     * Test tab leaders.
     * See page 83 of RTF Specification 1.9.1 for Formatting.
     */
    public function testTabLeader(): void
    {
        $parentWriter = new RTF();
        $style = new TabStyle();
        $writer = new TabWriter($style);
        $writer->setParentWriter($parentWriter);

        $style->setPosition(600);
        $style->setLeader(TabStyle::TAB_LEADER_NONE);
        $expect = '\tx600';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setLeader(TabStyle::TAB_LEADER_DOT);
        $expect = '\tldot\tx600';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setLeader(TabStyle::TAB_LEADER_HYPHEN);
        $expect = '\tlhyph\tx600';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setLeader(TabStyle::TAB_LEADER_UNDERSCORE);
        $expect = '\tlul\tx600';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setLeader(TabStyle::TAB_LEADER_HEAVY);
        $expect = '\tlth\tx600';
        self::assertEquals($expect, $this->removeCr($writer));

        $style->setLeader(TabStyle::TAB_LEADER_MIDDLEDOT);
        $expect = '\tlmdot\tx600';
        self::assertEquals($expect, $this->removeCr($writer));
    }
}
