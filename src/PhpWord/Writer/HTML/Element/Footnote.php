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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * Footnote element HTML writer
 *
 * @since 0.10.0
 */
class Footnote extends Element
{
    /**
     * Note type footnote|endnote
     *
     * @var string
     */
    protected $noteType = 'footnote';

    /**
     * Write footnote/endnote marks
     *
     * @return string
     */
    public function write()
    {
        $noteId = count($this->parentWriter->getNotes()) + 1;
        $noteMark = $this->noteType . '-' . $this->element->getRelationId();
        $this->parentWriter->addNote($noteId, $noteMark);
        $html = "<a name=\"{$noteMark}\"><a href=\"#note-{$noteId}\" class=\"NoteRef\"><sup>{$noteId}</sup></a>";

        return $html;
    }
}
