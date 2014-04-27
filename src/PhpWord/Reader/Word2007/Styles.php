<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Styles reader
 */
class Styles extends AbstractPart
{
    /**
     * Read styles.xml
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function read(PhpWord &$phpWord)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        $nodes = $xmlReader->getElements('w:style');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $type = $xmlReader->getAttribute('w:type', $node);
                $name = $xmlReader->getAttribute('w:styleId', $node);
                if (is_null($name)) {
                    $name = $xmlReader->getAttribute('w:val', $node, 'w:name');
                }
                preg_match('/Heading(\d)/', $name, $headingMatches);
                // $default = ($xmlReader->getAttribute('w:default', $node) == 1);
                switch ($type) {

                    case 'paragraph':
                        $pStyle = $this->readParagraphStyle($xmlReader, $node);
                        $fStyle = $this->readFontStyle($xmlReader, $node);
                        if (!empty($headingMatches)) {
                            $phpWord->addTitleStyle($headingMatches[1], $fStyle, $pStyle);
                        } else {
                            if (empty($fStyle)) {
                                if (is_array($pStyle)) {
                                    $phpWord->addParagraphStyle($name, $pStyle);
                                }
                            } else {
                                $phpWord->addFontStyle($name, $fStyle, $pStyle);
                            }
                        }
                        break;

                    case 'character':
                        $fStyle = $this->readFontStyle($xmlReader, $node);
                        if (!empty($fStyle)) {
                            $phpWord->addFontStyle($name, $fStyle);
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
