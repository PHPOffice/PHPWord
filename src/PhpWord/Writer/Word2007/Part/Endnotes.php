<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

/**
 * Word2007 endnotes part writer
 */
class Endnotes extends Footnotes
{
    /**
     * Name of XML root element
     *
     * @var string
     */
    protected $rootNode = 'w:endnotes';

    /**
     * Name of XML node element
     *
     * @var string
     */
    protected $elementNode = 'w:endnote';

    /**
     * Name of XML reference element
     *
     * @var string
     */
    protected $refNode = 'w:endnoteRef';

    /**
     * Reference style name
     *
     * @var string
     */
    protected $refStyle = 'EndnoteReference';
}
