<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Footer element
 */
class Footer extends AbstractContainer
{
    const AUTO  = 'default';  // default and odd pages
    const FIRST = 'first';
    const EVEN  = 'even';

    /**
     * Header type
     *
     * @var string
     */
    private $type = self::AUTO;

    /**
     * Create new instance
     *
     * @param int $sectionId
     * @param int $footerId
     * @param string $type
     */
    public function __construct($sectionId, $footerId = 1, $type = self::AUTO)
    {
        $this->container = 'footer';
        $this->sectionId = $sectionId;
        $this->setType($type);
        $this->setDocPart($this->container, ($sectionId - 1) * 3 + $footerId);
    }

    /**
     * Set type
     *
     * @param string $value
     * @since 0.10.0
     */
    public function setType($value = self::AUTO)
    {
        $this->type = $value;
    }

    /**
     * Get type
     *
     * @return string
     * @since 0.10.0
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * First page only footer
     *
     * @return string
     */
    public function firstPage()
    {
        return $this->type = self::FIRST;
    }
    
    /**
     * Even numbered pages only
     *
     * @return string
     */
    public function evenPage()
    {
        return $this->type = self::EVEN;
    }
}
