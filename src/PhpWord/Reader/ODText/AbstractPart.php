<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader\ODText;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Abstract part reader
 */
abstract class AbstractPart
{
    /**
     * Document file
     *
     * @var string
     */
    protected $docFile;

    /**
     * XML file
     *
     * @var string
     */
    protected $xmlFile;

    /**
     * Part relationships
     *
     * @var array
     */
    protected $rels = array();

    /**
     * Read part
     */
    abstract public function read(PhpWord &$phpWord);

    /**
     * Create new instance
     *
     * @param string $docFile
     * @param string $xmlFile
     */
    public function __construct($docFile, $xmlFile)
    {
        $this->docFile = $docFile;
        $this->xmlFile = $xmlFile;
    }

    /**
     * Set relationships
     *
     * @param array $value
     */
    public function setRels($value)
    {
        $this->rels = $value;
    }
}
