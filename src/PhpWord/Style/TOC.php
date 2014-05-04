<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

/**
 * TOC style
 */
class TOC extends AbstractStyle
{
    const TABLEADER_DOT = 'dot';
    const TABLEADER_UNDERSCORE = 'underscore';
    const TABLEADER_LINE = 'hyphen';
    const TABLEADER_NONE = '';

    /**
     * Tab Leader
     *
     * @var string
     */
    private $tabLeader;

    /**
     * Tab Position
     *
     * @var int
     */
    private $tabPos;

    /**
     * Indent
     *
     * @var int
     */
    private $indent;


    /**
     * Create a new TOC Style
     */
    public function __construct()
    {
        $this->tabPos = 9062;
        $this->tabLeader = self::TABLEADER_DOT;
        $this->indent = 200;
    }

    /**
     * Get Tab Position
     *
     * @return int
     */
    public function getTabPos()
    {
        return $this->tabPos;
    }

    /**
     * Set Tab Position
     *
     * @param int $pValue
     */
    public function setTabPos($pValue)
    {
        $this->tabPos = $pValue;
    }

    /**
     * Get Tab Leader
     *
     * @return string
     */
    public function getTabLeader()
    {
        return $this->tabLeader;
    }

    /**
     * Set Tab Leader
     *
     * @param string $pValue
     */
    public function setTabLeader($pValue = self::TABLEADER_DOT)
    {
        $this->tabLeader = $pValue;
    }

    /**
     * Get Indent
     *
     * @return int
     */
    public function getIndent()
    {
        return $this->indent;
    }

    /**
     * Set Indent
     *
     * @param string $pValue
     */
    public function setIndent($pValue)
    {
        $this->indent = $pValue;
    }
}
