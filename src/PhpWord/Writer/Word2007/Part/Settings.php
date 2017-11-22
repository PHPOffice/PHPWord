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

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\ComplexType\ProofState;
use PhpOffice\PhpWord\ComplexType\TrackChangesView;
use PhpOffice\PhpWord\Style\Language;

/**
 * Word2007 settings part writer: word/settings.xml
 *
 * @see  http://www.schemacentral.com/sc/ooxml/t-w_CT_Settings.html
 */
class Settings extends AbstractPart
{
    static $algorithmMapping = [
        1 => 'md2',
        2 => 'md4',
        3 => 'md5',
        4 => 'sha1',
        5 => '', // 'mac' -> not possible with hash()
        6 => 'ripemd',
        7 => 'ripemd160',
        8 => '',
        9 => '', //'hmac' -> not possible with hash()
        10 => '',
        11 => '',
        12 => 'sha256',
        13 => 'sha384',
        14 => 'sha512',
    ];
    static $initialCodeArray = [
        0xE1F0,
        0x1D0F,
        0xCC9C,
        0x84C0,
        0x110C,
        0x0E10,
        0xF1CE,
        0x313E,
        0x1872,
        0xE139,
        0xD40F,
        0x84F9,
        0x280C,
        0xA96A,
        0x4EC3
    ];
    static $encryptionMatrix =
        [
            [0xAEFC, 0x4DD9, 0x9BB2, 0x2745, 0x4E8A, 0x9D14, 0x2A09],
            [0x7B61, 0xF6C2, 0xFDA5, 0xEB6B, 0xC6F7, 0x9DCF, 0x2BBF],
            [0x4563, 0x8AC6, 0x05AD, 0x0B5A, 0x16B4, 0x2D68, 0x5AD0],
            [0x0375, 0x06EA, 0x0DD4, 0x1BA8, 0x3750, 0x6EA0, 0xDD40],
            [0xD849, 0xA0B3, 0x5147, 0xA28E, 0x553D, 0xAA7A, 0x44D5],
            [0x6F45, 0xDE8A, 0xAD35, 0x4A4B, 0x9496, 0x390D, 0x721A],
            [0xEB23, 0xC667, 0x9CEF, 0x29FF, 0x53FE, 0xA7FC, 0x5FD9],
            [0x47D3, 0x8FA6, 0x0F6D, 0x1EDA, 0x3DB4, 0x7B68, 0xF6D0],
            [0xB861, 0x60E3, 0xC1C6, 0x93AD, 0x377B, 0x6EF6, 0xDDEC],
            [0x45A0, 0x8B40, 0x06A1, 0x0D42, 0x1A84, 0x3508, 0x6A10],
            [0xAA51, 0x4483, 0x8906, 0x022D, 0x045A, 0x08B4, 0x1168],
            [0x76B4, 0xED68, 0xCAF1, 0x85C3, 0x1BA7, 0x374E, 0x6E9C],
            [0x3730, 0x6E60, 0xDCC0, 0xA9A1, 0x4363, 0x86C6, 0x1DAD],
            [0x3331, 0x6662, 0xCCC4, 0x89A9, 0x0373, 0x06E6, 0x0DCC],
            [0x1021, 0x2042, 0x4084, 0x8108, 0x1231, 0x2462, 0x48C4]
        ];
    static $passwordMaxLength = 15;

    /**
     * Settings value
     *
     * @var array
     */
    private $settings = array();

    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $this->getSettings();

        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('w:settings');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
        $xmlWriter->writeAttribute('xmlns:sl', 'http://schemas.openxmlformats.org/schemaLibrary/2006/main');
        $xmlWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
        $xmlWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
        $xmlWriter->writeAttribute('xmlns:w10', 'urn:schemas-microsoft-com:office:word');

        foreach ($this->settings as $settingKey => $settingValue) {
            $this->writeSetting($xmlWriter, $settingKey, $settingValue);
        }

        $xmlWriter->endElement(); // w:settings

        return $xmlWriter->getData();
    }

    /**
     * Write indivual setting, recursive to any child settings.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param string $settingKey
     * @param array|string $settingValue
     */
    protected function writeSetting($xmlWriter, $settingKey, $settingValue)
    {
        if ($settingValue == '') {
            $xmlWriter->writeElement($settingKey);
        } else {
            $xmlWriter->startElement($settingKey);

            /** @var array $settingValue Type hint */
            foreach ($settingValue as $childKey => $childValue) {
                if ($childKey == '@attributes') {
                    foreach ($childValue as $key => $val) {
                        $xmlWriter->writeAttribute($key, $val);
                    }
                } else {
                    $this->writeSetting($xmlWriter, $childKey, $childValue);
                }
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Get settings.
     */
    private function getSettings()
    {
        /** @var \PhpOffice\PhpWord\Metadata\Settings $documentSettings */
        $documentSettings = $this->getParentWriter()->getPhpWord()->getSettings();

        // Default settings
        $this->settings = array(
            'w:defaultTabStop'          => array('@attributes' => array('w:val' => '708')),
            'w:hyphenationZone'         => array('@attributes' => array('w:val' => '425')),
            'w:characterSpacingControl' => array('@attributes' => array('w:val' => 'doNotCompress')),
            'w:decimalSymbol'           => array('@attributes' => array('w:val' => $documentSettings->getDecimalSymbol())),
            'w:listSeparator'           => array('@attributes' => array('w:val' => ';')),
            'w:compat'                  => array(),
            'm:mathPr'                  => array(
                'm:mathFont'   => array('@attributes' => array('m:val' => 'Cambria Math')),
                'm:brkBin'     => array('@attributes' => array('m:val' => 'before')),
                'm:brkBinSub'  => array('@attributes' => array('m:val' => '--')),
                'm:smallFrac'  => array('@attributes' => array('m:val' => 'off')),
                'm:dispDef'    => '',
                'm:lMargin'    => array('@attributes' => array('m:val' => '0')),
                'm:rMargin'    => array('@attributes' => array('m:val' => '0')),
                'm:defJc'      => array('@attributes' => array('m:val' => 'centerGroup')),
                'm:wrapIndent' => array('@attributes' => array('m:val' => '1440')),
                'm:intLim'     => array('@attributes' => array('m:val' => 'subSup')),
                'm:naryLim'    => array('@attributes' => array('m:val' => 'undOvr')),
            ),
            'w:clrSchemeMapping' => array(
                '@attributes' => array(
                    'w:bg1'               => 'light1',
                    'w:t1'                => 'dark1',
                    'w:bg2'               => 'light2',
                    'w:t2'                => 'dark2',
                    'w:accent1'           => 'accent1',
                    'w:accent2'           => 'accent2',
                    'w:accent3'           => 'accent3',
                    'w:accent4'           => 'accent4',
                    'w:accent5'           => 'accent5',
                    'w:accent6'           => 'accent6',
                    'w:hyperlink'         => 'hyperlink',
                    'w:followedHyperlink' => 'followedHyperlink',
                ),
            ),
        );

        $this->setOnOffValue('w:mirrorMargins', $documentSettings->hasMirrorMargins());
        $this->setOnOffValue('w:hideSpellingErrors', $documentSettings->hasHideSpellingErrors());
        $this->setOnOffValue('w:hideGrammaticalErrors', $documentSettings->hasHideGrammaticalErrors());
        $this->setOnOffValue('w:trackRevisions', $documentSettings->hasTrackRevisions());
        $this->setOnOffValue('w:doNotTrackMoves', $documentSettings->hasDoNotTrackMoves());
        $this->setOnOffValue('w:doNotTrackFormatting', $documentSettings->hasDoNotTrackFormatting());
        $this->setOnOffValue('w:evenAndOddHeaders', $documentSettings->hasEvenAndOddHeaders());

        $this->setThemeFontLang($documentSettings->getThemeFontLang());
        $this->setRevisionView($documentSettings->getRevisionView());
        $this->setDocumentProtection($documentSettings->getDocumentProtection());
        $this->setProofState($documentSettings->getProofState());
        $this->setZoom($documentSettings->getZoom());
        $this->getCompatibility();
    }

    /**
     * Adds a boolean attribute to the settings array
     *
     * @param string $settingName
     * @param bool $booleanValue
     */
    private function setOnOffValue($settingName, $booleanValue)
    {
        if ($booleanValue !== null && is_bool($booleanValue)) {
            if ($booleanValue) {
                $this->settings[$settingName] = array('@attributes' => array());
            } else {
                $this->settings[$settingName] = array('@attributes' => array('w:val' => 'false'));
            }
        }
    }

    /**
     * Get protection settings.
     *
     * @param \PhpOffice\PhpWord\Metadata\Protection $documentProtection
     */
    private function setDocumentProtection($documentProtection)
    {
        if ($documentProtection->getEditing() !== null) {
            if (empty($documentProtection->getPassword())) {
                $this->settings['w:documentProtection'] = array(
                    '@attributes' => array(
                        'w:enforcement' => 1,
                        'w:edit' => $documentProtection->getEditing(),
                    )
                );
            } else {
                if ($documentProtection->getSalt() == null) {
                    $documentProtection->setSalt(openssl_random_pseudo_bytes(16));
                }
                $this->settings['w:documentProtection'] = array(
                    '@attributes' => array(
                        'w:enforcement' => 1,
                        'w:edit' => $documentProtection->getEditing(),
                        'w:cryptProviderType' => 'rsaFull',
                        'w:cryptAlgorithmClass' => 'hash',
                        'w:cryptAlgorithmType' => 'typeAny',
                        'w:cryptAlgorithmSid' => $documentProtection->getMswordAlgorithmSid(),
                        'w:cryptSpinCount' => $documentProtection->getSpinCount(),
                        'w:hash' => $this->getEncodedPasswordHash($documentProtection),
                        'w:salt' => base64_encode($documentProtection->getSalt()),
                    )
                );
            }
        }
    }

    /**
     * Set the Proof state
     *
     * @param ProofState $proofState
     */
    private function setProofState(ProofState $proofState = null)
    {
        if ($proofState != null && $proofState->getGrammar() !== null && $proofState->getSpelling() !== null) {
            $this->settings['w:proofState'] = array(
                '@attributes' => array(
                    'w:spelling' => $proofState->getSpelling(),
                    'w:grammar'  => $proofState->getGrammar(),
                ),
            );
        }
    }

    /**
     * Set the Revision View
     *
     * @param TrackChangesView $trackChangesView
     */
    private function setRevisionView(TrackChangesView $trackChangesView = null)
    {
        if ($trackChangesView != null) {
            $revisionView['w:markup'] = $trackChangesView->hasMarkup() ? 'true' : 'false';
            $revisionView['w:comments'] = $trackChangesView->hasComments() ? 'true' : 'false';
            $revisionView['w:insDel'] = $trackChangesView->hasInsDel() ? 'true' : 'false';
            $revisionView['w:formatting'] = $trackChangesView->hasFormatting() ? 'true' : 'false';
            $revisionView['w:inkAnnotations'] = $trackChangesView->hasInkAnnotations() ? 'true' : 'false';

            $this->settings['w:revisionView'] = array('@attributes' => $revisionView);
        }
    }

    /**
     * Sets the language
     *
     * @param Language $language
     */
    private function setThemeFontLang(Language $language = null)
    {
        $latinLanguage = ($language == null || $language->getLatin() === null) ? 'en-US' : $language->getLatin();
        $lang = array();
        $lang['w:val'] = $latinLanguage;
        if ($language != null) {
            $lang['w:eastAsia'] = $language->getEastAsia() === null ? 'x-none' : $language->getEastAsia();
            $lang['w:bidi'] = $language->getBidirectional() === null ? 'x-none' : $language->getBidirectional();
        }
        $this->settings['w:themeFontLang'] = array('@attributes' => $lang);
    }

    /**
     * Set the magnification
     *
     * @param mixed $zoom
     */
    private function setZoom($zoom = null)
    {
        if ($zoom !== null) {
            $attr = is_int($zoom) ? 'w:percent' : 'w:val';
            $this->settings['w:zoom'] = array('@attributes' => array($attr => $zoom));
        }
    }

    /**
     * Get compatibility setting.
     */
    private function getCompatibility()
    {
        $compatibility = $this->getParentWriter()->getPhpWord()->getCompatibility();
        if ($compatibility->getOoxmlVersion() !== null) {
            $this->settings['w:compat']['w:compatSetting'] = array(
                '@attributes' => array(
                    'w:name' => 'compatibilityMode',
                    'w:uri' => 'http://schemas.microsoft.com/office/word',
                    'w:val' => $compatibility->getOoxmlVersion(),
                )
            );
        }
    }


    /**
     * Create a hashed password that MS Word will be able to work with
     * @link https://blogs.msdn.microsoft.com/vsod/2010/04/05/how-to-set-the-editing-restrictions-in-word-using-open-xml-sdk-2-0/
     *
     * @param \PhpOffice\PhpWord\Metadata\Protection $protection
     * @return string
     */
    private function getEncodedPasswordHash($protection)
    {
        $orig_encoding = mb_internal_encoding();
        mb_internal_encoding("UTF-8");

        $password = $protection->getPassword();
        $password = mb_substr($password, 0, min(self::$passwordMaxLength, mb_strlen($password)));

        //   Get the single-byte values by iterating through the Unicode characters of the truncated password.
        //   For each character, if the low byte is not equal to 0, take it. Otherwise, take the high byte.
        $pass_utf8 = mb_convert_encoding($password, 'UCS-2LE', 'UTF-8');
        $byteChars = [];
        for ($i = 0; $i < mb_strlen($password); $i++) {
            $byteChars[$i] = ord(substr($pass_utf8, $i * 2, 1));
            if ($byteChars[$i] == 0) {
                $byteChars[$i] = ord(substr($pass_utf8, $i * 2 + 1, 1));
            }
        }

        // build low-order word and hig-order word and combine them
        $combinedKey = $this->buildCombinedKey($byteChars);
        // build reversed hexadecimal string
        $hex         = str_pad(strtoupper(dechex($combinedKey & 0xFFFFFFFF)), 8, '0', \STR_PAD_LEFT);
        $reversedHex = $hex[6] . $hex[7] . $hex[4] . $hex[5] . $hex[2] . $hex[3] . $hex[0] . $hex[1];

        $generatedKey = mb_convert_encoding($reversedHex, 'UCS-2LE', 'UTF-8');

        // Implementation Notes List:
        //   Word requires that the initial hash of the password with the salt not be considered in the count.
        //   The initial hash of salt + key is not included in the iteration count.
        $algorithm    = $this->getAlgorithm($protection->getMswordAlgorithmSid());
        $generatedKey = hash($algorithm, $protection->getSalt() . $generatedKey, true);

        for ($i = 0; $i < $protection->getSpinCount(); $i++) {
            $generatedKey = hash($algorithm, $generatedKey . pack("CCCC", $i, $i >> 8, $i >> 16, $i >> 24), true);
        }
        $generatedKey = base64_encode($generatedKey);

        mb_internal_encoding($orig_encoding);

        return $generatedKey;
    }

    /**
     * Get algorithm from self::$algorithmMapping
     *
     * @param int $sid
     * @return string
     */
    private function getAlgorithm($sid)
    {
        $algorithm = self::$algorithmMapping[$sid];
        if ($algorithm == '') {
            $algorithm = 'sha1';
        }

        return $algorithm;
    }

    /**
     * Build combined key from low-order word and high-order word
     *
     * @param array $byteChars -> byte array representation of password
     * @return int
     */
    private function buildCombinedKey($byteChars)
    {
        // Compute the high-order word
        // Initialize from the initial code array (see above), depending on the passwords length.
        $highOrderWord = self::$initialCodeArray[sizeof($byteChars) - 1];

        // For each character in the password:
        //   For every bit in the character, starting with the least significant and progressing to (but excluding)
        //   the most significant, if the bit is set, XOR the key’s high-order word with the corresponding word from
        //   the Encryption Matrix
        for ($i = 0; $i < sizeof($byteChars); $i++) {
            $tmp       = self::$passwordMaxLength - sizeof($byteChars) + $i;
            $matrixRow = self::$encryptionMatrix[$tmp];
            for ($intBit = 0; $intBit < 7; $intBit++) {
                if (($byteChars[$i] & (0x0001 << $intBit)) != 0) {
                    $highOrderWord = ($highOrderWord ^ $matrixRow[$intBit]);
                }
            }
        }

        // Compute low-order word
        // Initialize with 0
        $lowOrderWord = 0;
        // For each character in the password, going backwards
        for ($i = sizeof($byteChars) - 1; $i >= 0; $i--) {
            // low-order word = (((low-order word SHR 14) AND 0x0001) OR (low-order word SHL 1) AND 0x7FFF)) XOR character
            $lowOrderWord = (((($lowOrderWord >> 14) & 0x0001) | (($lowOrderWord << 1) & 0x7FFF)) ^ $byteChars[$i]);
        }
        // Lastly, low-order word = (((low-order word SHR 14) AND 0x0001) OR (low-order word SHL 1) AND 0x7FFF)) XOR strPassword length XOR 0xCE4B.
        $lowOrderWord = (((($lowOrderWord >> 14) & 0x0001) | (($lowOrderWord << 1) & 0x7FFF)) ^ sizeof($byteChars) ^ 0xCE4B);

        // Combine the Low and High Order Word
        return $this->int32(($highOrderWord << 16) + $lowOrderWord);
    }

    /**
     * Simulate behaviour of (signed) int32
     *
     * @param int $value
     * @return int
     */
    private function int32($value)
    {
        $value = ($value & 0xFFFFFFFF);

        if ($value & 0x80000000) {
            $value = -((~$value & 0xFFFFFFFF) + 1);
        }

        return $value;
    }
}
