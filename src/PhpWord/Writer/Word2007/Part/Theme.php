<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Style\Colors\SystemColor;
use PhpOffice\PhpWord\Style\Theme\Theme as ThemeStyle;

/**
 * Word2007 theme writer: word/theme/theme1.xml
 *
 * @todo Generate content dynamically
 * @since 0.10.0
 */
class Theme extends AbstractPart
{
    private $theme;

    public function __construct()
    {
        $this->theme = new ThemeStyle();
    }

    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $str = '';

        $str .= '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $str .= '<a:theme xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main" name="Office Theme">';
        $str .= '<a:themeElements>';
        $str .= $this->writeColorScheme();
        $str .= $this->writeFontScheme();
        $str .= $this->writeFormatScheme();
        $str .= '</a:themeElements>';
        $str .= '<a:objectDefaults />';
        $str .= '<a:extraClrSchemeLst />';
        $str .= '</a:theme>';

        return $str;
    }

    /**
     * Write color scheme
     *
     * @return string
     */
    private function writeColorScheme()
    {
        $name = 'Office';
        $colors = $this->theme->getColorScheme()->getColors();

        $str = '<a:clrScheme name="' . htmlentities($name, ENT_XML1) . '">';

        foreach ($colors as $tag => $color) {
            $str .= '<a:' . htmlentities($tag, ENT_XML1) . '>';

            if ($color instanceof SystemColor) {
                $str .= '<a:sysClr val="' . htmlentities($color->getName(), ENT_XML1) . '" lastClr="' . htmlentities($color->getLastColor()->toHex() ?? 'auto', ENT_XML1) . '" />';
            } else {
                $str .= '<a:srgbClr val="' . htmlentities($color->toHex() ?? 'auto', ENT_XML1) . '" />';
            }

            $str .= '</a:dk1>';
        }

        $str .= '</a:clrScheme>';

        return $str;
    }

    /**
     * Write font scheme
     *
     * @return string
     */
    private function writeFontScheme()
    {
        $fontScheme = $this->theme->getFontScheme();

        $str = '';

        $str .= '<a:fontScheme name="Office">';

        $headingFonts = $fontScheme->getHeadingFonts();
        $str .= '<a:majorFont>';
        $str .= '<a:latin typeface="' . htmlentities($headingFonts->getLatin(), ENT_XML1) . '" />';
        $str .= '<a:ea typeface="' . htmlentities($headingFonts->getEastAsian(), ENT_XML1) . '" />';
        $str .= '<a:cs typeface="' . htmlentities($headingFonts->getComplexScript(), ENT_XML1) . '" />';
        foreach ($headingFonts->getFonts() as $script => $font) {
            $str .= '<a:font script="' . htmlentities($script, ENT_XML1) . '" typeface="' . htmlentities($font, ENT_XML1) . '" />';
        }
        $str .= '</a:majorFont>';

        $bodyFonts = $fontScheme->getBodyFonts();
        $str .= '<a:minorFont>';
        $str .= '<a:latin typeface="' . htmlentities($bodyFonts->getLatin(), ENT_XML1) . '" />';
        $str .= '<a:ea typeface="' . htmlentities($bodyFonts->getEastAsian(), ENT_XML1) . '" />';
        $str .= '<a:cs typeface="' . htmlentities($bodyFonts->getComplexScript(), ENT_XML1) . '" />';
        foreach ($bodyFonts->getFonts() as $script => $font) {
            $str .= '<a:font script="' . htmlentities($script, ENT_XML1) . '" typeface="' . htmlentities($font, ENT_XML1) . '" />';
        }
        $str .= '</a:minorFont>';

        $str .= '</a:fontScheme>';

        return $str;
    }

    /**
     * Write format scheme
     *
     * @return string
     */
    private function writeFormatScheme()
    {
        $str = '';

        $str .= '<a:fmtScheme name="Office">';
        $str .= $this->writeFormatFill();
        $str .= $this->writeFormatLine();
        $str .= $this->writeFormatEffect();
        $str .= $this->writeFormatBackground();
        $str .= '</a:fmtScheme>';

        return $str;
    }

    /**
     * Write fill format scheme
     *
     * @return string
     */
    private function writeFormatFill()
    {
        $str = '';

        $str .= '<a:fillStyleLst>';
        $str .= '<a:solidFill>';
        $str .= '<a:schemeClr val="phClr" />';
        $str .= '</a:solidFill>';
        $str .= '<a:gradFill rotWithShape="1">';
        $str .= '<a:gsLst>';
        $str .= '<a:gs pos="0">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:tint val="50000" />';
        $str .= '<a:satMod val="300000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '<a:gs pos="35000">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:tint val="37000" />';
        $str .= '<a:satMod val="300000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '<a:gs pos="100000">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:tint val="15000" />';
        $str .= '<a:satMod val="350000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '</a:gsLst>';
        $str .= '<a:lin ang="16200000" scaled="1" />';
        $str .= '</a:gradFill>';
        $str .= '<a:gradFill rotWithShape="1">';
        $str .= '<a:gsLst>';
        $str .= '<a:gs pos="0">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:shade val="51000" />';
        $str .= '<a:satMod val="130000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '<a:gs pos="80000">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:shade val="93000" />';
        $str .= '<a:satMod val="130000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '<a:gs pos="100000">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:shade val="94000" />';
        $str .= '<a:satMod val="135000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '</a:gsLst>';
        $str .= '<a:lin ang="16200000" scaled="0" />';
        $str .= '</a:gradFill>';
        $str .= '</a:fillStyleLst>';

        return $str;
    }

    /**
     * Write line format scheme
     *
     * @return string
     */
    private function writeFormatLine()
    {
        $str = '';

        $str .= '<a:lnStyleLst>';
        $str .= '<a:ln w="9525" cap="flat" cmpd="sng" algn="ctr">';
        $str .= '<a:solidFill>';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:shade val="95000" />';
        $str .= '<a:satMod val="105000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:solidFill>';
        $str .= '<a:prstDash val="solid" />';
        $str .= '</a:ln>';
        $str .= '<a:ln w="25400" cap="flat" cmpd="sng" algn="ctr">';
        $str .= '<a:solidFill>';
        $str .= '<a:schemeClr val="phClr" />';
        $str .= '</a:solidFill>';
        $str .= '<a:prstDash val="solid" />';
        $str .= '</a:ln>';
        $str .= '<a:ln w="38100" cap="flat" cmpd="sng" algn="ctr">';
        $str .= '<a:solidFill>';
        $str .= '<a:schemeClr val="phClr" />';
        $str .= '</a:solidFill>';
        $str .= '<a:prstDash val="solid" />';
        $str .= '</a:ln>';
        $str .= '</a:lnStyleLst>';

        return $str;
    }

    /**
     * Write effect format scheme
     *
     * @return string
     */
    private function writeFormatEffect()
    {
        $str = '';

        $str .= '<a:effectStyleLst>';
        $str .= '<a:effectStyle>';
        $str .= '<a:effectLst>';
        $str .= '<a:outerShdw blurRad="40000" dist="20000" dir="5400000" rotWithShape="0">';
        $str .= '<a:srgbClr val="000000">';
        $str .= '<a:alpha val="38000" />';
        $str .= '</a:srgbClr>';
        $str .= '</a:outerShdw>';
        $str .= '</a:effectLst>';
        $str .= '</a:effectStyle>';
        $str .= '<a:effectStyle>';
        $str .= '<a:effectLst>';
        $str .= '<a:outerShdw blurRad="40000" dist="23000" dir="5400000" rotWithShape="0">';
        $str .= '<a:srgbClr val="000000">';
        $str .= '<a:alpha val="35000" />';
        $str .= '</a:srgbClr>';
        $str .= '</a:outerShdw>';
        $str .= '</a:effectLst>';
        $str .= '</a:effectStyle>';
        $str .= '<a:effectStyle>';
        $str .= '<a:effectLst>';
        $str .= '<a:outerShdw blurRad="40000" dist="23000" dir="5400000" rotWithShape="0">';
        $str .= '<a:srgbClr val="000000">';
        $str .= '<a:alpha val="35000" />';
        $str .= '</a:srgbClr>';
        $str .= '</a:outerShdw>';
        $str .= '</a:effectLst>';
        $str .= '<a:scene3d>';
        $str .= '<a:camera prst="orthographicFront">';
        $str .= '<a:rot lat="0" lon="0" rev="0" />';
        $str .= '</a:camera>';
        $str .= '<a:lightRig rig="threePt" dir="t">';
        $str .= '<a:rot lat="0" lon="0" rev="1200000" />';
        $str .= '</a:lightRig>';
        $str .= '</a:scene3d>';
        $str .= '<a:sp3d>';
        $str .= '<a:bevelT w="63500" h="25400" />';
        $str .= '</a:sp3d>';
        $str .= '</a:effectStyle>';
        $str .= '</a:effectStyleLst>';

        return $str;
    }

    /**
     * Write background format scheme
     *
     * @return string
     */
    private function writeFormatBackground()
    {
        $str = '';

        $str .= '<a:bgFillStyleLst>';
        $str .= '<a:solidFill>';
        $str .= '<a:schemeClr val="phClr" />';
        $str .= '</a:solidFill>';
        $str .= '<a:gradFill rotWithShape="1">';
        $str .= '<a:gsLst>';
        $str .= '<a:gs pos="0">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:tint val="40000" />';
        $str .= '<a:satMod val="350000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '<a:gs pos="40000">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:tint val="45000" />';
        $str .= '<a:shade val="99000" />';
        $str .= '<a:satMod val="350000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '<a:gs pos="100000">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:shade val="20000" />';
        $str .= '<a:satMod val="255000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '</a:gsLst>';
        $str .= '<a:path path="circle">';
        $str .= '<a:fillToRect l="50000" t="-80000" r="50000" b="180000" />';
        $str .= '</a:path>';
        $str .= '</a:gradFill>';
        $str .= '<a:gradFill rotWithShape="1">';
        $str .= '<a:gsLst>';
        $str .= '<a:gs pos="0">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:tint val="80000" />';
        $str .= '<a:satMod val="300000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '<a:gs pos="100000">';
        $str .= '<a:schemeClr val="phClr">';
        $str .= '<a:shade val="30000" />';
        $str .= '<a:satMod val="200000" />';
        $str .= '</a:schemeClr>';
        $str .= '</a:gs>';
        $str .= '</a:gsLst>';
        $str .= '<a:path path="circle">';
        $str .= '<a:fillToRect l="50000" t="50000" r="50000" b="50000" />';
        $str .= '</a:path>';
        $str .= '</a:gradFill>';
        $str .= '</a:bgFillStyleLst>';

        return $str;
    }
}
