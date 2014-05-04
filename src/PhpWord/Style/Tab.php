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
 * Tab style
 */
class Tab extends AbstractStyle
{
    /**
     * Tab stop types
     *
     * @const string
     */
    const TAB_STOP_CLEAR   = 'clear';
    const TAB_STOP_LEFT    = 'left';
    const TAB_STOP_CENTER  = 'center';
    const TAB_STOP_RIGHT   = 'right';
    const TAB_STOP_DECIMAL = 'decimal';
    const TAB_STOP_BAR     = 'bar';
    const TAB_STOP_NUM     = 'num';

    /**
     * Tab leader types
     *
     * @const string
     */
    const TAB_LEADER_NONE       = 'none';
    const TAB_LEADER_DOT        = 'dot';
    const TAB_LEADER_HYPHEN     = 'hyphen';
    const TAB_LEADER_UNDERSCORE = 'underscore';
    const TAB_LEADER_HEAVY      = 'heavy';
    const TAB_LEADER_MIDDLEDOT  = 'middleDot';

    /**
     * Tab stop type
     *
     * @var string
     */
    private $val = self::TAB_STOP_CLEAR;

    /**
     * Tab leader character
     *
     * @var string
     */
    private $leader = self::TAB_LEADER_NONE;

    /**
     * Tab stop position
     *
     * @var int
     */
    private $position = 0;

    /**
     * Create a new instance of Tab. Both $val and $leader
     * must conform to the values put forth in the schema. If they do not
     * they will be changed to default values.
     *
     * @param string $val Defaults to 'clear' if value is not possible.
     * @param int $position Must be numeric; otherwise defaults to 0.
     * @param string $leader Defaults to null if value is not possible.
     */
    public function __construct($val = null, $position = 0, $leader = null)
    {
        $stopTypes = array(
            self::TAB_STOP_CLEAR, self::TAB_STOP_LEFT,self::TAB_STOP_CENTER,
            self::TAB_STOP_RIGHT, self::TAB_STOP_DECIMAL, self::TAB_STOP_BAR, self::TAB_STOP_NUM
        );
        $leaderTypes = array(
            self::TAB_LEADER_NONE, self::TAB_LEADER_DOT, self::TAB_LEADER_HYPHEN,
            self::TAB_LEADER_UNDERSCORE, self::TAB_LEADER_HEAVY, self::TAB_LEADER_MIDDLEDOT
        );

        $this->val = $this->setEnumVal($val, $stopTypes, $this->val);
        $this->position = $this->setNumericVal($position, $this->position);
        $this->leader = $this->setEnumVal($leader, $leaderTypes, $this->leader);
    }

    /**
     * Get stop type
     *
     * @return string
     */
    public function getStopType()
    {
        return $this->val;
    }

    /**
     * Get leader
     *
     * @return string
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }
}
