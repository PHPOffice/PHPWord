<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Container\Container;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Textrun/paragraph element
 */
class TextRun extends Container
{
    /**
     * Paragraph style
     *
     * @var string|Paragraph
     */
    private $paragraphStyle;

    /**
     * Create new instance
     *
     * @param string|array|Paragraph $paragraphStyle
     * @param string $docPartType section|header|footer
     * @param int $docPartId
     */
    public function __construct($paragraphStyle = null, $docPartType = 'section', $docPartId = 1)
    {
        $this->containerType = 'textrun';
        $this->docPartType = $docPartType;
        $this->docPartId = $docPartId;
        // Set paragraph style
        if (is_array($paragraphStyle)) {
            $this->paragraphStyle = new Paragraph();
            foreach ($paragraphStyle as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->paragraphStyle->setStyleValue($key, $value);
            }
        } else {
            $this->paragraphStyle = $paragraphStyle;
        }
    }

    /**
     * Get Paragraph style
     *
     * @return string|Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }
}
