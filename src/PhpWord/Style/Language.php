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
namespace PhpOffice\PhpWord\Style;

/**
 * Language
 * A couple of predefined values are defined here, see the websites below for more values
 *
 * @see http://www.datypic.com/sc/ooxml/t-w_CT_Language.html
 * @see https://technet.microsoft.com/en-us/library/cc287874(v=office.12).aspx
 */
final class Language extends AbstractStyle
{

    const EN_US = 'en-US';
    const EN_US_ID = 1033;

    const EN_GB = 'en-GB';
    const EN_GB_ID = 2057;

    const FR_FR = 'fr-FR';
    const FR_FR_ID = 1036;

    const FR_BE = 'fr-BE';
    const FR_BE_ID = 2060;

    const ES_ES = 'es-ES';
    const ES_ES_ID = 3082;

    const DE_DE = 'de-DE';
    const DE_DE_ID = 1031;

    const HE_IL = 'he-IL';
    const HE_IL_ID = 1037;

    const JA_JP = 'ja-JP';
    const JA_JP_ID = 1041;

    const KO_KR = 'ko-KR';
    const KO_KR_ID = 1042;

    const ZH_CN = 'zh-CN';
    const ZH_CN_ID = 2052;

    const HI_IN = 'hi-IN';
    const HI_IN_ID = 1081;

    /**
     * Language ID, used for RTF document generation
     *
     * @var int
     * @see https://technet.microsoft.com/en-us/library/cc179219.aspx
     */
    private $langId;

    /**
     * Latin Language
     *
     * @var string
     */
    private $latin;

    /**
     * East Asian Language
     *
     * @var string
     */
    private $eastAsia;

    /**
     * Complex Script Language
     *
     * @var string
     */
    private $bidirectional;

    /**
     * 
     * @param string|null $latin
     * @param string|null $eastAsia
     * @param string|null $bidirectional
     */
    public function __construct($latin = null, $eastAsia = null, $bidirectional = null)
    {
        if (!empty($latin)) {
            $this->setLatin($latin);
        }
        if (!empty($eastAsia)) {
            $this->setEastAsia($eastAsia);
        }
        if (!empty($bidirectional)) {
            $this->setBidirectional($bidirectional);
        }
    }

    /**
     * Set the Latin Language
     *
     * @param string $latin
     *            The value for the latin language
     * @return self
     */
    public function setLatin($latin)
    {
        $this->validateLocale($latin);
        $this->latin = $latin;
        return $this;
    }

    /**
     * Get the Latin Language
     *
     * @return string|null
     */
    public function getLatin()
    {
        return $this->latin;
    }

    /**
     * Set the Language ID
     *
     * @param int $langId
     *            The value for the language ID
     * @return self
     * @see https://technet.microsoft.com/en-us/library/cc287874(v=office.12).aspx
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;
        return $this;
    }

    /**
     * Get the Language ID
     *
     * @return int
     */
    public function getLangId()
    {
        return $this->langId;
    }

    /**
     * Set the East Asian Language
     *
     * @param string $eastAsia
     *            The value for the east asian language
     * @return self
     */
    public function setEastAsia($eastAsia)
    {
        $this->validateLocale($eastAsia);
        $this->eastAsia = $eastAsia;
        return $this;
    }

    /**
     * Get the East Asian Language
     *
     * @return string|null
     */
    public function getEastAsia()
    {
        return $this->eastAsia;
    }

    /**
     * Set the Complex Script Language
     *
     * @param string $bidirectional
     *            The value for the complex script language
     * @return self
     */
    public function setBidirectional($bidirectional)
    {
        $this->validateLocale($bidirectional);
        $this->bidirectional = $bidirectional;
        return $this;
    }

    /**
     * Get the Complex Script Language
     *
     * @return string|null
     */
    public function getBidirectional()
    {
        return $this->bidirectional;
    }

    /**
     * Validates that the language passed is in the format xx-xx
     * 
     * @param string $locale
     * @return boolean
     */
    private function validateLocale($locale)
    {
        if ($locale !== null && strstr($locale, '-') === false) {
            throw new \InvalidArgumentException($locale . ' is not a valid language code');
        }
    }
}
