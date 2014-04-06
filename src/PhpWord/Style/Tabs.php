<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Tabs style
 */
class Tabs
{
    /**
     * Tabs
     *
     * @var array
     */
    private $tabs;

    /**
     * Create new tab collection style
     *
     * @param array $tabs
     */
    public function __construct(array $tabs)
    {
        $this->tabs = $tabs;
    }

    /**
     * Return XML
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter &$xmlWriter
     */
    public function toXml(XMLWriter &$xmlWriter = null)
    {
        if (isset($xmlWriter)) {
            $xmlWriter->startElement("w:tabs");
            foreach ($this->tabs as &$tab) {
                $tab->toXml($xmlWriter);
            }
            $xmlWriter->endElement();
        }
    }
}
