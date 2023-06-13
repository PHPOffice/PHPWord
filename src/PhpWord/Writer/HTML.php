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

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\PhpWord;

/**
 * HTML writer.
 *
 * Not supported: PreserveText, PageBreak, Object
 *
 * @since 0.10.0
 */
class HTML extends AbstractWriter implements WriterInterface
{
    /**
     * Is the current writer creating PDF?
     *
     * @var bool
     */
    protected $isPdf = false;

    /**
     * Is the current writer creating TCPDF?
     *
     * @var bool
     */
    protected $isTcpdf = false;

    /**
     * Footnotes and endnotes collection.
     *
     * @var array
     */
    protected $notes = [];

    /**
     * Create new instance.
     */
    public function __construct(?PhpWord $phpWord = null)
    {
        $this->setPhpWord($phpWord);

        $this->parts = ['Head', 'Body'];
        foreach ($this->parts as $partName) {
            $partClass = 'PhpOffice\\PhpWord\\Writer\\HTML\\Part\\' . $partName;
            if (class_exists($partClass)) {
                /** @var \PhpOffice\PhpWord\Writer\HTML\Part\AbstractPart $part Type hint */
                $part = new $partClass();
                $part->setParentWriter($this);
                $this->writerParts[strtolower($partName)] = $part;
            }
        }
    }

    /**
     * Save PhpWord to file.
     *
     * @param string $filename
     */
    public function save($filename = null): void
    {
        $this->writeFile($this->openFile($filename), $this->getContent());
    }

    /**
     * Get content.
     *
     * @return string
     *
     * @since 0.11.0
     */
    public function getContent()
    {
        $content = '';

        $content .= '<!DOCTYPE html>' . PHP_EOL;
        $content .= '<!-- Generated by PHPWord -->' . PHP_EOL;
        $langtext = '';
        $phpWord = $this->getPhpWord();
        $lang = $phpWord->getSettings()->getThemeFontLang();
        if (!empty($lang)) { // @phpstan-ignore-line
            $lang2 = $lang->getLatin();
            if (!$lang2) {
                $lang2 = $lang->getEastAsia();
            }
            if (!$lang2) {
                $lang2 = $lang->getBidirectional();
            }
            if ($lang2) {
                $langtext = " lang='" . $lang2 . "'";
            }
        }
        $content .= "<html$langtext>" . PHP_EOL;
        $content .= $this->getWriterPart('Head')->write();
        $content .= $this->getWriterPart('Body')->write();
        $content .= '</html>' . PHP_EOL;

        return $content;
    }

    /**
     * Get is PDF.
     *
     * @return bool
     */
    public function isPdf()
    {
        return $this->isPdf;
    }

    /**
     * Get is TCPDF.
     *
     * @return bool
     */
    public function isTcpdf()
    {
        return $this->isTcpdf;
    }

    /**
     * Get notes.
     *
     * @return array
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add note.
     *
     * @param int $noteId
     * @param string $noteMark
     */
    public function addNote($noteId, $noteMark): void
    {
        $this->notes[$noteId] = $noteMark;
    }

    /**
     * Escape string or not depending on setting.
     *
     * @param string $txt
     */
    public static function escapeOrNot($txt): string
    {
        if (\PhpOffice\PhpWord\Settings::isOutputEscapingEnabled()) {
            return htmlspecialchars($txt, ENT_QUOTES | (defined('ENT_SUBSTITUTE') ? ENT_SUBSTITUTE : 0), 'UTF-8');
        }

        return $txt;
    }
}
