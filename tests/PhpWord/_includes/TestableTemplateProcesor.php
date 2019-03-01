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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

/**
 * This class is used to expose publicly methods that are otherwise private or protected.
 * This makes testing those methods easier
 *
 * @author troosan
 */
class TestableTemplateProcesor extends TemplateProcessor
{
    public function __construct($mainPart = null, $settingsPart = null)
    {
        $this->tempDocumentMainPart = $mainPart;
        $this->tempDocumentSettingsPart = $settingsPart;
    }

    public function fixBrokenMacros($documentPart)
    {
        return parent::fixBrokenMacros($documentPart);
    }

    public function splitTextIntoTexts($text)
    {
        return parent::splitTextIntoTexts($text);
    }

    public function textNeedsSplitting($text)
    {
        return parent::textNeedsSplitting($text);
    }

    public function getVariablesForPart($documentPartXML)
    {
        $documentPartXML = parent::fixBrokenMacros($documentPartXML);

        return parent::getVariablesForPart($documentPartXML);
    }

    public function findXmlBlockStart($offset, $blockType)
    {
        return parent::findXmlBlockStart($offset, $blockType);
    }

    public function findContainingXmlBlockForMacro($macro, $blockType = 'w:p')
    {
        return parent::findContainingXmlBlockForMacro($macro, $blockType);
    }

    public function getSlice($startPosition, $endPosition = 0)
    {
        return parent::getSlice($startPosition, $endPosition);
    }

    /**
     * @return string
     */
    public function getMainPart()
    {
        return $this->tempDocumentMainPart;
    }

    /**
     * @return string
     */
    public function getSettingsPart()
    {
        return $this->tempDocumentSettingsPart;
    }
}
