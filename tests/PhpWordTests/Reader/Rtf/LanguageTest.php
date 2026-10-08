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

namespace PhpOffice\PhpWordTests\Reader\Rtf;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWordTests\AbstractTestReader;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Styles.
 */
class LanguageTest extends AbstractTestReader
{
    public function testUnderline(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()
            ->setThemeFontLang(new Language('', '', '', Language::DA_DK_ID));
        $section = $phpWord->addSection();

        $text = 'Some random text.';

        $newWord = $this->writeAndReload($phpWord, 'RTF');
        self::assertSame(
            Language::DA_DK_ID,
            $newWord->getSettings()
                ->getThemeFontLang()
                ->getLangId()
        );
        self::assertSame(
            Language::DA_DK,
            $newWord->getSettings()
                ->getThemeFontLang()
                ->getLatin()
        );
    }
}
