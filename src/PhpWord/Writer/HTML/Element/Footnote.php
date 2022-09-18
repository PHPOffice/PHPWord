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

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * Footnote element HTML writer.
 *
 * @since 0.10.0
 */
class Footnote extends AbstractElement
{
    /**
     * Note type footnote|endnote.
     *
     * @var string
     */
    protected $noteType = 'footnote';

    /**
     * Write footnote/endnote marks; The actual content is written in parent writer (HTML).
     *
     * @return string
     */
    public function write()
    {
        if (!$this->element instanceof \PhpOffice\PhpWord\Element\Footnote) {
            return '';
        }
        /** @var \PhpOffice\PhpWord\Writer\HTML $parentWriter Type hint */
        $parentWriter = $this->parentWriter;

        $noteId = count($parentWriter->getNotes()) + 1;
        $noteMark = $this->noteType . '-' . $this->element->getRelationId();
        $content = "<a name=\"{$noteMark}\"><a href=\"#note-{$noteId}\" class=\"NoteRef\"><sup>{$noteId}</sup></a>";

        $parentWriter->addNote($noteId, $noteMark);

        return $content;
    }
}
