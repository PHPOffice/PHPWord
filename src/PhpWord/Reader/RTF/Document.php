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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\RTF;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * RTF document reader
 *
 * References:
 * - How to Write an RTF Reader http://latex2rtf.sourceforge.net/rtfspec_45.html
 * - PHP rtfclass by Markus Fischer https://github.com/mfn/rtfclass
 * - JavaScript RTF-parser by LazyGyu https://github.com/lazygyu/RTF-parser
 *
 * @since 0.11.0
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 */
class Document
{
    /** @const int */
    const PARA = 'readParagraph';
    const STYL = 'readStyle';
    const SKIP = 'readSkip';

    /**
     * PhpWord object
     *
     * @var \PhpOffice\PhpWord\PhpWord
     */
    private $phpWord;

    /**
     * Section object
     *
     * @var \PhpOffice\PhpWord\Element\Section
     */
    private $section;

    /**
     * Textrun object
     *
     * @var \PhpOffice\PhpWord\Element\TextRun
     */
    private $textrun;

    /**
     * RTF content
     *
     * @var string
     */
    public $rtf;

    /**
     * Content length
     *
     * @var int
     */
    private $length = 0;

    /**
     * Character index
     *
     * @var int
     */
    private $offset = 0;

    /**
     * Current control word
     *
     * @var string
     */
    private $control = '';

    /**
     * Text content
     *
     * @var string
     */
    private $text = '';

    /**
     * Parsing a control word flag
     *
     * @var bool
     */
    private $isControl = false;

    /**
     * First character flag: watch out for control symbols
     *
     * @var bool
     */
    private $isFirst = false;

    /**
     * Group groups
     *
     * @var array
     */
    private $groups = array();

    /**
     * Parser flags; not used
     *
     * @var array
     */
    private $flags = array();

    /**
     * Parse RTF content
     *
     * - Marks controlling characters `{`, `}`, and `\`
     * - Removes line endings
     * - Builds control words and control symbols
     * - Pushes every other character into the text queue
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @return void
     * @todo Use `fread` stream for scalability
     */
    public function read(PhpWord $phpWord)
    {
        $markers = array(
            123 => 'markOpening',   // {
            125 => 'markClosing',   // }
            92  => 'markBackslash', // \
            10  => 'markNewline',   // LF
            13  => 'markNewline',   // CR
        );

        $this->phpWord = $phpWord;
        $this->section = $phpWord->addSection();
        $this->textrun = $this->section->addTextRun();
        $this->length = strlen($this->rtf);

        $this->flags['paragraph'] = true; // Set paragraph flag from the beginning

        // Walk each characters
        while ($this->offset < $this->length) {
            $char  = $this->rtf[$this->offset];
            $ascii = ord($char);

            if (isset($markers[$ascii])) { // Marker found: {, }, \, LF, or CR
                $markerFunction = $markers[$ascii];
                $this->$markerFunction();
            } else {
                if (false === $this->isControl) { // Non control word: Push character
                    $this->pushText($char);
                } else {
                    if (preg_match("/^[a-zA-Z0-9-]?$/", $char)) { // No delimiter: Buffer control
                        $this->control .= $char;
                        $this->isFirst = false;
                    } else { // Delimiter found: Parse buffered control
                        if ($this->isFirst) {
                            $this->isFirst = false;
                        } else {
                            if (' ' == $char) { // Discard space as a control word delimiter
                                $this->flushControl(true);
                            }
                        }
                    }
                }
            }
            $this->offset++;
        }
        $this->flushText();
    }

    /**
     * Mark opening braket `{` character.
     *
     * @return void
     */
    private function markOpening()
    {
        $this->flush(true);
        array_push($this->groups, $this->flags);
    }

    /**
     * Mark closing braket `}` character.
     *
     * @return void
     */
    private function markClosing()
    {
        $this->flush(true);
        $this->flags = array_pop($this->groups);
    }

    /**
     * Mark backslash `\` character.
     *
     * @return void
     */
    private function markBackslash()
    {
        if ($this->isFirst) {
            $this->setControl(false);
            $this->text .= '\\';
        } else {
            $this->flush();
            $this->setControl(true);
            $this->control = '';
        }
    }

    /**
     * Mark newline character: Flush control word because it's not possible to span multiline.
     *
     * @return void
     */
    private function markNewline()
    {
        if ($this->isControl) {
            $this->flushControl(true);
        }
    }

    /**
     * Flush control word or text.
     *
     * @param bool $isControl
     * @return void
     */
    private function flush($isControl = false)
    {
        if ($this->isControl) {
            $this->flushControl($isControl);
        } else {
            $this->flushText();
        }
    }

    /**
     * Flush control word.
     *
     * @param bool $isControl
     * @return void
     */
    private function flushControl($isControl = false)
    {
        if (1 === preg_match("/^([A-Za-z]+)(-?[0-9]*) ?$/", $this->control, $match)) {
            list(, $control, $parameter) = $match;
            $this->parseControl($control, $parameter);
        }

        if (true === $isControl) {
            $this->setControl(false);
        }
    }

    /**
     * Flush text in queue.
     *
     * @return void
     */
    private function flushText()
    {
        if ($this->text != '') {
            if (isset($this->flags['property'])) { // Set property
                $this->flags['value'] = $this->text;
            } else { // Set text
                if (true === $this->flags['paragraph']) {
                    $this->flags['paragraph'] = false;
                    $this->flags['text'] = $this->text;
                }
            }

            // Add text if it's not flagged as skipped
            if (!isset($this->flags['skipped'])) {
                $this->readText();
            }

            $this->text = '';
        }
    }

    /**
     * Reset control word and first char state.
     *
     * @param bool $value
     * @return void
     */
    private function setControl($value)
    {
        $this->isControl = $value;
        $this->isFirst = $value;
    }

    /**
     * Push text into queue.
     *
     * @param string $char
     * @return void
     */
    private function pushText($char)
    {
        if ('<' == $char) {
            $this->text .= "&lt;";
        } elseif ('>' == $char) {
            $this->text .= "&gt;";
        } else {
            $this->text .= $char;
        }
    }

    /**
     * Parse control.
     *
     * @param string $control
     * @param string $parameter
     * @return void
     */
    private function parseControl($control, $parameter)
    {
        $controls = array(
            'par'       => array(self::PARA,    'paragraph',    true),
            'b'         => array(self::STYL,    'font',         'bold',         true),
            'i'         => array(self::STYL,    'font',         'italic',       true),
            'u'         => array(self::STYL,    'font',         'underline',    true),
            'strike'    => array(self::STYL,    'font',         'strikethrough',true),
            'fs'        => array(self::STYL,    'font',         'size',         $parameter),
            'qc'        => array(self::STYL,    'paragraph',    'alignment',    Jc::CENTER),
            'sa'        => array(self::STYL,    'paragraph',    'spaceAfter',   $parameter),
            'fonttbl'   => array(self::SKIP,    'fonttbl',      null),
            'colortbl'  => array(self::SKIP,    'colortbl',     null),
            'info'      => array(self::SKIP,    'info',         null),
            'generator' => array(self::SKIP,    'generator',    null),
            'title'     => array(self::SKIP,    'title',        null),
            'subject'   => array(self::SKIP,    'subject',      null),
            'category'  => array(self::SKIP,    'category',     null),
            'keywords'  => array(self::SKIP,    'keywords',     null),
            'comment'   => array(self::SKIP,    'comment',      null),
            'shppict'   => array(self::SKIP,    'pic',          null),
            'fldinst'   => array(self::SKIP,    'link',         null),
        );

        if (isset($controls[$control])) {
            list($function) = $controls[$control];
            if (method_exists($this, $function)) {
                $directives = $controls[$control];
                array_shift($directives); // remove the function variable; we won't need it
                $this->$function($directives);
            }
        }
    }

    /**
     * Read paragraph.
     *
     * @param array $directives
     * @return void
     */
    private function readParagraph($directives)
    {
        list($property, $value) = $directives;
        $this->textrun = $this->section->addTextRun();
        $this->flags[$property] = $value;
    }

    /**
     * Read style.
     *
     * @param array $directives
     * @return void
     */
    private function readStyle($directives)
    {
        list($style, $property, $value) = $directives;
        $this->flags['styles'][$style][$property] = $value;
    }

    /**
     * Read skip.
     *
     * @param array $directives
     * @return void
     */
    private function readSkip($directives)
    {
        list($property) = $directives;
        $this->flags['property'] = $property;
        $this->flags['skipped'] = true;
    }

    /**
     * Read text.
     *
     * @return void
     */
    private function readText()
    {
        $text = $this->textrun->addText($this->text);
        if (isset($this->flags['styles']['font'])) {
            $text->getFontStyle()->setStyleByArray($this->flags['styles']['font']);
        }
    }
}
