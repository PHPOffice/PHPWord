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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
namespace PhpOffice\PhpWord\ComplexType;

/**
 * Test class for PhpOffice\PhpWord\ComplexType\Language
 *
 * @coversDefaultClass \PhpOffice\PhpWord\ComplexType\Languge
 */
class LanguageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test setting language with constructor
     */
    public function testConstructor()
    {
        $language = new Language(Language::DE_DE, Language::KO_KR, Language::HE_IL);

        $this->assertEquals(Language::DE_DE, $language->getLatin());
        $this->assertEquals(Language::KO_KR, $language->getEastAsia());
        $this->assertEquals(Language::HE_IL, $language->getBidirectional());
    }

    /**
     * Tests the getters and setters
     */
    public function testGetSet()
    {
        $language = new Language();
        $language->setLatin(Language::DE_DE);
        $language->setEastAsia(Language::KO_KR);
        $language->setBidirectional(Language::HE_IL);
        $language->setLangId(Language::DE_DE_ID);

        $this->assertEquals(Language::DE_DE, $language->getLatin());
        $this->assertEquals(Language::KO_KR, $language->getEastAsia());
        $this->assertEquals(Language::HE_IL, $language->getBidirectional());
        $this->assertEquals(Language::DE_DE_ID, $language->getLangId());
    }

    /**
     * Test throws exception if wrong locale is given
     *
     * @expectedException \InvalidArgumentException
     */
    public function testWrongLanguage()
    {
        $language = new Language();
        $language->setLatin('fr');
    }
}
