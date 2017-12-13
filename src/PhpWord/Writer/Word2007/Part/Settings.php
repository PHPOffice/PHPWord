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
use PhpOffice\PhpWord\Shared\Microsoft\PasswordEncoder;
use PhpOffice\PhpWord\Style\Language;

/**
 * Word2007 settings part writer: word/settings.xml
 *
 * @see  http://www.schemacentral.com/sc/ooxml/t-w_CT_Settings.html
 */
class Settings extends AbstractPart
{
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
        } elseif (is_array($settingValue) && !empty($settingValue)) {
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
        $this->setOnOffValue('w:updateFields', $documentSettings->hasUpdateFields());

        $this->setThemeFontLang($documentSettings->getThemeFontLang());
        $this->setRevisionView($documentSettings->getRevisionView());
        $this->setDocumentProtection($documentSettings->getDocumentProtection());
        $this->setProofState($documentSettings->getProofState());
        $this->setZoom($documentSettings->getZoom());
        $this->setCompatibility();
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
            if ($documentProtection->getPassword() == null) {
                $this->settings['w:documentProtection'] = array(
                    '@attributes' => array(
                        'w:enforcement' => 1,
                        'w:edit'        => $documentProtection->getEditing(),
                    ),
                );
            } else {
                if ($documentProtection->getSalt() == null) {
                    $documentProtection->setSalt(openssl_random_pseudo_bytes(16));
                }
                $passwordHash = PasswordEncoder::hashPassword($documentProtection->getPassword(), $documentProtection->getAlgorithm(), $documentProtection->getSalt(), $documentProtection->getSpinCount());
                $this->settings['w:documentProtection'] = array(
                    '@attributes' => array(
                        'w:enforcement'         => 1,
                        'w:edit'                => $documentProtection->getEditing(),
                        'w:cryptProviderType'   => 'rsaFull',
                        'w:cryptAlgorithmClass' => 'hash',
                        'w:cryptAlgorithmType'  => 'typeAny',
                        'w:cryptAlgorithmSid'   => PasswordEncoder::getAlgorithmId($documentProtection->getAlgorithm()),
                        'w:cryptSpinCount'      => $documentProtection->getSpinCount(),
                        'w:hash'                => $passwordHash,
                        'w:salt'                => base64_encode($documentProtection->getSalt()),
                    ),
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
            $revisionView = array();
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
    private function setCompatibility()
    {
        $compatibility = $this->getParentWriter()->getPhpWord()->getCompatibility();
        if ($compatibility->getOoxmlVersion() !== null) {
            $this->settings['w:compat']['w:compatSetting'] = array(
                '@attributes' => array(
                    'w:name' => 'compatibilityMode',
                    'w:uri'  => 'http://schemas.microsoft.com/office/word',
                    'w:val'  => $compatibility->getOoxmlVersion(),
                ),
            );
        }
    }
}
