<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL
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
