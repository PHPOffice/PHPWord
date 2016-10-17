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

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

/**
 * Word2007 settings part writer: word/settings.xml
 *
 * @link http://www.schemacentral.com/sc/ooxml/t-w_CT_Settings.html
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
     * @return void
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
     *
     * @return void
     */
    private function getSettings()
    {
        // Default settings
        $this->settings = array(
            'w:zoom' => array('@attributes' => array('w:percent' => '100')),
            'w:defaultTabStop' => array('@attributes' => array('w:val' => '708')),
            'w:hyphenationZone' => array('@attributes' => array('w:val' => '425')),
            'w:characterSpacingControl' => array('@attributes' => array('w:val' => 'doNotCompress')),
            'w:themeFontLang' => array('@attributes' => array('w:val' => 'en-US')),
            'w:decimalSymbol' => array('@attributes' => array('w:val' => '.')),
            'w:listSeparator' => array('@attributes' => array('w:val' => ';')),
            'w:compat' => '',
            'm:mathPr' => array(
                'm:mathFont' => array('@attributes' => array('m:val' => 'Cambria Math')),
                'm:brkBin' => array('@attributes' => array('m:val' => 'before')),
                'm:brkBinSub' => array('@attributes' => array('m:val' => '--')),
                'm:smallFrac' => array('@attributes' => array('m:val' => 'off')),
                'm:dispDef' => '',
                'm:lMargin' => array('@attributes' => array('m:val' => '0')),
                'm:rMargin' => array('@attributes' => array('m:val' => '0')),
                'm:defJc' => array('@attributes' => array('m:val' => 'centerGroup')),
                'm:wrapIndent' => array('@attributes' => array('m:val' => '1440')),
                'm:intLim' => array('@attributes' => array('m:val' => 'subSup')),
                'm:naryLim' => array('@attributes' => array('m:val' => 'undOvr')),
            ),
            'w:clrSchemeMapping' => array(
                '@attributes' => array(
                    'w:bg1' => 'light1',
                    'w:t1' => 'dark1',
                    'w:bg2' => 'light2',
                    'w:t2' => 'dark2',
                    'w:accent1' => 'accent1',
                    'w:accent2' => 'accent2',
                    'w:accent3' => 'accent3',
                    'w:accent4' => 'accent4',
                    'w:accent5' => 'accent5',
                    'w:accent6' => 'accent6',
                    'w:hyperlink' => 'hyperlink',
                    'w:followedHyperlink' => 'followedHyperlink',
                ),
            ),
        );

        // Other settings
        $this->getProtection();
        $this->getCompatibility();
    }

    /**
     * Get protection settings.
     *
     * @return void
     */
    private function getProtection()
    {
        $protection = $this->getParentWriter()->getPhpWord()->getProtection();
        if ($protection->getEditing() !== null) {
            $this->settings['w:documentProtection'] = array(
                '@attributes' => array(
                    'w:enforcement' => 1,
                    'w:edit' => $protection->getEditing(),
                )
            );
        }
    }

    /**
     * Get compatibility setting.
     *
     * @return void
     */
    private function getCompatibility()
    {
        $compatibility = $this->getParentWriter()->getPhpWord()->getCompatibility();
        if ($compatibility->getOoxmlVersion() !== null) {
            $this->settings['w:compat']['w:compatSetting'] = array('@attributes' => array(
                'w:name'    => 'compatibilityMode',
                'w:uri'     => 'http://schemas.microsoft.com/office/word',
                'w:val'     => $compatibility->getOoxmlVersion(),
            ));
        }
    }
}
