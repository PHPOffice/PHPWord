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

namespace PhpOffice\PhpWord\Reader\ODText;

use DateTime;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Content reader.
 *
 * @since 0.10.0
 */
class Content extends AbstractPart
{
    /**
     * Read content.xml.
     */
    public function read(PhpWord $phpWord): void
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        $trackedChanges = [];

        $nodes = $xmlReader->getElements('office:body/office:text/*');
        if ($nodes->length > 0) {
            $section = $phpWord->addSection();
            foreach ($nodes as $node) {
                // $styleName = $xmlReader->getAttribute('text:style-name', $node);
                switch ($node->nodeName) {
                    case 'text:h': // Heading
                        $depth = $xmlReader->getAttribute('text:outline-level', $node);
                        $section->addTitle($node->nodeValue, $depth);

                        break;
                    case 'text:p': // Paragraph
                        $children = $node->childNodes;
                        foreach ($children as $child) {
                            switch ($child->nodeName) {
                                case 'text:change-start':
                                    $changeId = $child->getAttribute('text:change-id');
                                    if (isset($trackedChanges[$changeId])) {
                                        $changed = $trackedChanges[$changeId];
                                    }

                                    break;
                                case 'text:change-end':
                                    unset($changed);

                                    break;
                                case 'text:change':
                                    $changeId = $child->getAttribute('text:change-id');
                                    if (isset($trackedChanges[$changeId])) {
                                        $changed = $trackedChanges[$changeId];
                                    }

                                    break;
                            }
                        }

                        $element = $section->addText($node->nodeValue);
                        if (isset($changed) && is_array($changed)) {
                            $element->setTrackChange($changed['changed']);
                            if (isset($changed['textNodes'])) {
                                foreach ($changed['textNodes'] as $changedNode) {
                                    $element = $section->addText($changedNode->nodeValue);
                                    $element->setTrackChange($changed['changed']);
                                }
                            }
                        }

                        break;
                    case 'text:list': // List
                        $listItems = $xmlReader->getElements('text:list-item/text:p', $node);
                        foreach ($listItems as $listItem) {
                            // $listStyleName = $xmlReader->getAttribute('text:style-name', $listItem);
                            $section->addListItem($listItem->nodeValue, 0);
                        }

                        break;
                    case 'text:tracked-changes':
                        $changedRegions = $xmlReader->getElements('text:changed-region', $node);
                        foreach ($changedRegions as $changedRegion) {
                            $type = ($changedRegion->firstChild->nodeName == 'text:insertion') ? TrackChange::INSERTED : TrackChange::DELETED;
                            $creatorNode = $xmlReader->getElements('office:change-info/dc:creator', $changedRegion->firstChild);
                            $author = $creatorNode[0]->nodeValue;
                            $dateNode = $xmlReader->getElements('office:change-info/dc:date', $changedRegion->firstChild);
                            $date = $dateNode[0]->nodeValue;
                            $date = preg_replace('/\.\d+$/', '', $date);
                            $date = DateTime::createFromFormat('Y-m-d\TH:i:s', $date);
                            $changed = new TrackChange($type, $author, $date);
                            $textNodes = $xmlReader->getElements('text:deletion/text:p', $changedRegion);
                            $trackedChanges[$changedRegion->getAttribute('text:id')] = ['changed' => $changed, 'textNodes' => $textNodes];
                        }

                        break;
                }
            }
        }
    }
}
