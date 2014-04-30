<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 settings part writer
 */
class Settings extends AbstractPart
{
    /**
     * Write word/settings.xml
     */
    public function writeSettings()
    {
        $settings = array(
            'w:zoom' => array('@attributes' => array('w:percent' => '100')),
            'w:embedSystemFonts' => '',
            'w:defaultTabStop' => array('@attributes' => array('w:val' => '708')),
            'w:hyphenationZone' => array('@attributes' => array('w:val' => '425')),
            'w:doNotHyphenateCaps' => '',
            'w:characterSpacingControl' => array('@attributes' => array('w:val' => 'doNotCompress')),
            'w:doNotValidateAgainstSchema' => '',
            'w:doNotDemarcateInvalidXml' => '',
            'w:compat' => array(
                'w:useNormalStyleForList' => '',
                'w:doNotUseIndentAsNumberingTabStop' => '',
                'w:useAltKinsokuLineBreakRules' => '',
                'w:allowSpaceOfSameStyleInTable' => '',
                'w:doNotSuppressIndentation' => '',
                'w:doNotAutofitConstrainedTables' => '',
                'w:autofitToFirstFixedWidthCell' => '',
                'w:underlineTabInNumList' => '',
                'w:displayHangulFixedWidth' => '',
                'w:splitPgBreakAndParaMark' => '',
                'w:doNotVertAlignCellWithSp' => '',
                'w:doNotBreakConstrainedForcedTable' => '',
                'w:doNotVertAlignInTxbx' => '',
                'w:useAnsiKerningPairs' => '',
                'w:cachedColBalance' => '',
            ),
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
            'w:uiCompat97To2003' => '',
            'w:themeFontLang' => array('@attributes' => array('w:val' => 'de-DE')),
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
            'w:doNotIncludeSubdocsInStats' => '',
            'w:doNotAutoCompressPictures' => '',
            'w:decimalSymbol' => array('@attributes' => array('w:val' => ',')),
            'w:listSeparator' => array('@attributes' => array('w:val' => ';')),
        );

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

        foreach ($settings as $settingKey => $settingValue) {
            $this->writeSetting($xmlWriter, $settingKey, $settingValue);
        }

        $xmlWriter->endElement(); // w:settings

        return $xmlWriter->getData();
    }

    /**
     * Write indivual setting, recursive to any child settings
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $settingKey
     * @param array|string $settingValue
     */
    protected function writeSetting($xmlWriter, $settingKey, $settingValue)
    {
        if ($settingValue == '') {
            $xmlWriter->writeElement($settingKey);
        } else {
            $xmlWriter->startElement($settingKey);
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
}
