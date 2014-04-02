<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Container;

/**
 * Footer element
 */
class Footer extends Container
{
    /**
     * Create new instance
     *
     * @param int $sectionId
     */
    public function __construct($sectionId)
    {
        $this->container = 'footer';
        $this->containerId = $sectionId;
    }
}
