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

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;
use PhpOffice\PhpWord\Style\Language;

/**
 * Styles reader.
 *
 * @since 0.10.0
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

        $fontDefaults = $xmlReader->getElement('w:docDefaults/w:rPrDefault');
        if ($fontDefaults !== null) {
            $fontDefaultStyle = $this->readFontStyle($xmlReader, $fontDefaults);
            if (array_key_exists('name', $fontDefaultStyle)) {
                $phpWord->setDefaultFontName($fontDefaultStyle['name']);
            }
            if (array_key_exists('ascii', $fontDefaultStyle)) {
                $phpWord->setDefaultFontAscii($fontDefaultStyle['ascii']);
            }
            if (array_key_exists('hAnsi', $fontDefaultStyle)) {
                $phpWord->setDefaultFontHAnsi($fontDefaultStyle['hAnsi']);
            }
            if (array_key_exists('cs', $fontDefaultStyle)) {
                $phpWord->setDefaultFontCs($fontDefaultStyle['cs']);
            }
            if (array_key_exists('size', $fontDefaultStyle)) {
                $phpWord->setDefaultFontSize($fontDefaultStyle['size']);
            }
            if (array_key_exists('lang', $fontDefaultStyle)) {
                $phpWord->getSettings()->setThemeFontLang(new Language($fontDefaultStyle['lang']));
            }
        }

        $paragraphDefaults = $xmlReader->getElement('w:docDefaults/w:pPrDefault');
        if ($paragraphDefaults !== null) {
            $paragraphDefaultStyle = $this->readParagraphStyle($xmlReader, $paragraphDefaults);
            if ($paragraphDefaultStyle != null) {
                $phpWord->setDefaultParagraphStyle($paragraphDefaultStyle);
            }
        }

        //潜在样式的定义。
        $latentLsdExceptions = $xmlReader->getElements('w:latentStyles/w:lsdException');
        if ($latentLsdExceptions->length > 0) {
            $latentNode = $xmlReader->getElement('w:latentStyles');
            $latentStyles = $this->readLatentStyle($xmlReader, $latentNode);
            foreach ($latentLsdExceptions as $node) {
                $lsdException = [];
                $lsdException['qFormat'] = $xmlReader->getAttribute('w:qFormat', $node);
                $lsdException['unhideWhenUsed'] = $xmlReader->getAttribute('w:unhideWhenUsed', $node);
                $lsdException['uiPriority'] = $xmlReader->getAttribute('w:uiPriority', $node);
                $lsdException['defSemiHidden'] = $xmlReader->getAttribute('w:defSemiHidden', $node);
                $lsdException['defUIPriority'] = $xmlReader->getAttribute('w:defUIPriority', $node);
                $lsdException['defLockedState'] = $xmlReader->getAttribute('w:defLockedState', $node);
                $lsdException['semiHidden'] = $xmlReader->getAttribute('w:semiHidden', $node);
                $lsdException['name'] = $xmlReader->getAttribute('w:name', $node);
                $latentStyles['lsdExceptions'][] = $lsdException;
            }
            if ($latentStyles != null) {
                $phpWord->setLatentStyle($latentStyles);
            }
        }

        $nodes = $xmlReader->getElements('w:style');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $type = $xmlReader->getAttribute('w:type', $node);
                $name = $xmlReader->getAttribute('w:val', $node, 'w:name');
                $styleId = $xmlReader->getAttribute('w:styleId', $node);
                if (null === $name) {
                    $name = $styleId;
                }
                $headingMatches = [];
                preg_match('/Heading\s*(\d)/i', $name, $headingMatches);
                // $default = ($xmlReader->getAttribute('w:default', $node) == 1);
                switch ($type) {
                    case 'paragraph':
                        $paragraphStyle = $this->readParagraphStyle($xmlReader, $node);
                        $fontStyle = $this->readFontStyle($xmlReader, $node);

                        if ($fontStyle) $fontStyle['styleId'] = $fontStyle;
                        else $paragraphStyle['styleId'] = $fontStyle;

                        if (!empty($headingMatches)) {
                            $phpWord->addTitleStyle($headingMatches[1], $fontStyle, $paragraphStyle);
                        } else {
                            if (empty($fontStyle)) {
                                if (is_array($paragraphStyle)) {
                                    $phpWord->addParagraphStyle($name, $paragraphStyle);
                                }
                            } else {
                                $phpWord->addFontStyle($name, $fontStyle, $paragraphStyle);
                            }
                        }

                        break;
                    case 'character':
                        $fontStyle = $this->readFontStyle($xmlReader, $node);
                        if (!empty($fontStyle)) {
                            $phpWord->addFontStyle($name, $fontStyle);
                        }

                        break;
                    case 'table':
                        $tStyle = $this->readTableStyle($xmlReader, $node);
                        if (!empty($tStyle)) {
                            $phpWord->addTableStyle($name, $tStyle);
                        }

                        break;
                }
            }
        }
    }
}
