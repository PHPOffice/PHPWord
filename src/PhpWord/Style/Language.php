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

namespace PhpOffice\PhpWord\Style;

use InvalidArgumentException;

/**
 * Language
 * A couple of predefined values are defined here, see the websites below for more values.
 *
 * @see http://www.datypic.com/sc/ooxml/t-w_CT_Language.html
 * @see https://technet.microsoft.com/en-us/library/cc287874(v=office.12).aspx
 */
final class Language extends AbstractStyle
{
    const AR_SA = 'ar-SA';
    const AR_SA_ID = 1025;

    const BG_BG = 'bg-BG';
    const BG_BG_ID = 1026;

    const CS_CZ = 'cs-CZ';
    const CS_CZ_ID = 1029;

    const DA_DK = 'da-DK';
    const DA_DK_ID = 1030;

    const DE_CH = 'de-CH';
    const DE_CH_ID = 2055;

    const DE_DE = 'de-DE';
    const DE_DE_ID = 1031;

    const EN_GB = 'en-GB';
    const EN_GB_ID = 2057;

    const EN_US = 'en-US';
    const EN_US_ID = 1033;

    const ES_ES = 'es-ES';
    const ES_ES_ID = 3082;

    const FR_BE = 'fr-BE';
    const FR_BE_ID = 2060;

    const FR_CH = 'fr-CH';
    const FR_CH_ID = 4108;

    const FR_FR = 'fr-FR';
    const FR_FR_ID = 1036;

    const HE_IL = 'he-IL';
    const HE_IL_ID = 1037;

    const HI_IN = 'hi-IN';
    const HI_IN_ID = 1081;

    const HR_HR = 'hr-HR';
    const HR_HR_ID = 1050;

    const HU_HU = 'hu-HU';
    const HU_HU_ID = 1038;

    const ID_ID = 'id-ID';
    const ID_ID_ID = 1057;

    const IT_CH = 'it-CH';
    const IT_CH_ID = 2064;

    const IT_IT = 'it-IT';
    const IT_IT_ID = 1040;

    const JA_JP = 'ja-JP';
    const JA_JP_ID = 1041;

    const KK_KZ = 'kk-KZ';
    const KK_KK_ID = 1087;

    const KO_KR = 'ko-KR';
    const KO_KR_ID = 1042;

    const LT_LT = 'lt-LT';
    const LT_LT_ID = 1063;

    const LV_LV = 'lv-LV';
    const LV_LV_ID = 1062;

    const MS_MY = 'ms-MY';
    const MS_MY_ID = 1086;

    const NB_NO = 'nb-NO';
    const NB_NO_ID = 1044;

    const NL_NL = 'nl-NL';
    const NL_NL_ID = 1043;

    const PL_PL = 'pl-PL';
    const PL_PL_ID = 1045;

    const PT_BR = 'pt-BR';
    const PT_BR_ID = 1046;

    const PT_PT = 'pt-PT';
    const PT_PT_ID = 2070;

    const RO_RO = 'ro-RO';
    const RO_RO_ID = 1048;

    const SL_SI = 'sl-SI';
    const SL_SI_ID = 1060;

    const SK_SK = 'sk-SK';
    const SK_SK_ID = 1051;

    const SR_LATN_RS = 'sr-latn-RS';
    const SR_LATN_RS_ID = 2074;

    const SV_SE = 'sv-SE';
    const SV_SE_ID = 1053;

    const TH_TH = 'th-TH';
    const TH_TH_ID = 1054;

    const TR_TR = 'tr-TR';
    const TR_TR_ID = 1055;

    const UK_UA = 'uk-UA';
    const UK_UA_ID = 1058;

    const RU_RU = 'ru-RU';
    const RU_RU_ID = 1049;

    const VI_VN = 'vi-VN';
    const VI_VN_ID = 1066;

    const ZH_CN = 'zh-CN';
    const ZH_CN_ID = 2052;

    const ZH_TW = 'zh-TW';
    const ZH_TW_ID = 1028;

    /**
     * Language ID, used for RTF document generation.
     *
     * @var int
     *
     * @see https://technet.microsoft.com/en-us/library/cc179219.aspx
     */
    private $langId;

    /**
     * Latin Language.
     *
     * @var null|string
     */
    private $latin;

    /**
     * East Asian Language.
     *
     * @var null|string
     */
    private $eastAsia;

    /**
     * Complex Script Language.
     *
     * @var null|string
     */
    private $bidirectional;

    /**
     * Constructor.
     */
    public function __construct(string $latin = '', string $eastAsia = '', string $bidirectional = '', int $langId = 0)
    {
        $this->langId = $langId;
        if (!empty($latin)) {
            $this->setLatin($latin);
            $this->convertLangId($latin);
        }
        if (!empty($eastAsia)) {
            $this->setEastAsia($eastAsia);
            $this->convertLangId($eastAsia);
        }
        if (!empty($bidirectional)) {
            $this->setBidirectional($bidirectional);
            $this->convertLangId($bidirectional);
        }
    }

    /**
     * Set the Latin Language.
     *
     * @param string $latin
     *            The value for the latin language
     */
    public function setLatin(string $latin): self
    {
        $this->latin = $this->validateLocale($latin);

        return $this;
    }

    /**
     * Get the Latin Language.
     */
    public function getLatin(): ?string
    {
        return $this->latin;
    }

    /**
     * Set the Language ID.
     *
     * @param int $langId
     *            The value for the language ID
     *
     * @return self
     *
     * @see https://technet.microsoft.com/en-us/library/cc287874(v=office.12).aspx
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;

        return $this;
    }

    /**
     * Get the Language ID.
     *
     * @return int
     */
    public function getLangId()
    {
        return $this->langId;
    }

    /**
     * Set the East Asian Language.
     *
     * @param string $eastAsia
     *            The value for the east asian language
     *
     * @return self
     */
    public function setEastAsia($eastAsia)
    {
        $this->eastAsia = $this->validateLocale($eastAsia);

        return $this;
    }

    /**
     * Get the East Asian Language.
     *
     * @return null|string
     */
    public function getEastAsia()
    {
        return $this->eastAsia;
    }

    /**
     * Set the Complex Script Language.
     *
     * @param string $bidirectional
     *            The value for the complex script language
     *
     * @return self
     */
    public function setBidirectional($bidirectional)
    {
        $this->bidirectional = $this->validateLocale($bidirectional);

        return $this;
    }

    /**
     * Get the Complex Script Language.
     *
     * @return null|string
     */
    public function getBidirectional()
    {
        return $this->bidirectional;
    }

    /**
     * Validates that the language passed is in the format xx-xx.
     *
     * @param ?string $locale
     * @param bool $throw
     *
     * @return ?string
     */
    private function validateLocale($locale, $throw = true)
    {
        if ($locale === null) {
            return null;
        }
        $locale = str_replace('_', '-', $locale);

        if (strlen($locale) === 2) {
            return strtolower($locale) . '-' . strtoupper($locale);
        }
        if ($locale === 'und') {
            return 'en-GB';
        }
        if ($throw && $locale !== 'zxx' && strstr($locale, '-') === false) {
            throw new InvalidArgumentException($locale . ' is not a valid language code');
        }

        return $locale;
    }

    private function convertLangId(string $locale): void
    {
        if ($this->langId === 0 && $locale !== '') {
            $locale = $this->validateLocale($locale, false);
            $locale = strtoupper(str_replace('-', '_', $locale)) . '_ID';
            if (defined("self::$locale")) {
                $this->langId = constant("self::$locale");
            }
        }
    }

    private const ID_TO_LANG = [
        self::AR_SA_ID => self::AR_SA,
        self::BG_BG_ID => self::BG_BG,
        self::CS_CZ_ID => self::CS_CZ,
        self::DA_DK_ID => self::DA_DK,
        self::DE_CH_ID => self::DE_CH,
        self::DE_DE_ID => self::DE_DE,
        self::EN_GB_ID => self::EN_GB,
        self::EN_US_ID => self::EN_US,
        self::ES_ES_ID => self::ES_ES,
        self::FR_BE_ID => self::FR_BE,
        self::FR_CH_ID => self::FR_CH,
        self::FR_FR_ID => self::FR_FR,
        self::HE_IL_ID => self::HE_IL,
        self::HI_IN_ID => self::HI_IN,
        self::HR_HR_ID => self::HR_HR,
        self::HU_HU_ID => self::HU_HU,
        self::ID_ID_ID => self::ID_ID,
        self::IT_CH_ID => self::IT_CH,
        self::IT_IT_ID => self::IT_IT,
        self::JA_JP_ID => self::JA_JP,
        self::KK_KK_ID => self::KK_KZ,
        self::KO_KR_ID => self::KO_KR,
        self::LT_LT_ID => self::LT_LT,
        self::LV_LV_ID => self::LV_LV,
        self::MS_MY_ID => self::MS_MY,
        self::NB_NO_ID => self::NB_NO,
        self::NL_NL_ID => self::NL_NL,
        self::PL_PL_ID => self::PL_PL,
        self::PT_BR_ID => self::PT_BR,
        self::PT_PT_ID => self::PT_PT,
        self::RO_RO_ID => self::RO_RO,
        self::SL_SI_ID => self::SL_SI,
        self::SK_SK_ID => self::SK_SK,
        self::SR_LATN_RS_ID => self::SR_LATN_RS,
        self::SV_SE_ID => self::SV_SE,
        self::TH_TH_ID => self::TH_TH,
        self::TR_TR_ID => self::TR_TR,
        self::UK_UA_ID => self::UK_UA,
        self::RU_RU_ID => self::RU_RU,
        self::VI_VN_ID => self::VI_VN,
        self::ZH_CN_ID => self::ZH_CN,
        self::ZH_TW_ID => self::ZH_TW,
    ];

    /** @param int $langId */
    public static function idToLang($langId): string
    {
        return self::ID_TO_LANG[$langId] ?? '';
    }
}
