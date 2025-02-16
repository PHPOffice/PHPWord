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

namespace PhpOffice\PhpWord\Reader\ODText;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;
use PhpOffice\PhpWord\Style\Language;

/**
 * Styles reader.
 *
 * @since 1.4.0
 */
class Styles extends AbstractPart
{
    /**
     * Read styles.xml.
     */
    public function read(PhpWord $phpWord): void
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);
        $fontDefaults = $xmlReader->getElement('office:styles/style:default-style/style:text-properties');

        if ($fontDefaults !== null) {
            $phpWord->setDefaultFontName($fontDefaults->getAttribute('style:font-name'));
            $phpWord->setDefaultAsianFontName($fontDefaults->getAttribute('style:font-name-asian'));
            $phpWord->setDefaultFontSize((int) (str_replace('pt', '', $fontDefaults->getAttribute('fo:font-size'))));
            $phpWord->setDefaultFontColor(str_replace('#', '', $fontDefaults->getAttribute('fo:color')));
            $phpWord->getSettings()->setThemeFontLang(new Language($fontDefaults->getAttribute('fo:language')));
        }
    }
}
