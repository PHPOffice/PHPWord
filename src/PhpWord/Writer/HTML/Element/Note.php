<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Element;

/**
 * Note element HTML writer
 *
 * @since 0.10.0
 */
class Note extends Element
{
    /**
     * Write footnote/endnote marks
     *
     * @return string
     */
    public function write()
    {
        $noteId = count($this->parentWriter->getNotes()) + 1;
        $prefix = ($this->element instanceof \PhpOffice\PhpWord\Element\Endnote) ? 'endnote' : 'footnote';
        $noteMark = $prefix . '-' . $this->element->getRelationId();
        $this->parentWriter->addNote($noteId, $noteMark);
        $html = "<a name=\"{$noteMark}\"><a href=\"#note-{$noteId}\" class=\"NoteRef\"><sup>{$noteId}</sup></a>";

        return $html;
    }
}
