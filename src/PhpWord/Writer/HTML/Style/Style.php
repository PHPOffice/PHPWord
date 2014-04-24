<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\HTML\Style;

use PhpOffice\PhpWord\Style\AbstractStyle;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Style HTML writer
 *
 * @since 0.10.0
 */
class Style
{
    /**
     * Parent writer
     *
     * @var \PhpOffice\PhpWord\Writer\HTML
     */
    protected $parentWriter;

    /**
     * Style
     *
     * @var \PhpOffice\PhpWord\Style\AbstractStyle
     */
    protected $style;

    /**
     * Curly bracket
     *
     * @var bool
     */
    protected $curlyBracket = false;

    /**
     * Create new instance
     *
     * @param bool $curlyBracket
     */
    public function __construct(HTML $parentWriter = null, AbstractStyle $style = null, $curlyBracket = false)
    {
        $this->parentWriter = $parentWriter;
        $this->style = $style;
        $this->curlyBracket = $curlyBracket;
    }

    /**
     * Write element
     *
     * @return string
     */
    public function write()
    {
        $css = '';
        $styleType = str_replace('PhpOffice\\PhpWord\\Style\\', '', get_class($this->style));
        $styleWriterClass = 'PhpOffice\\PhpWord\\Writer\\HTML\\Style\\' . $styleType;
        if (class_exists($styleWriterClass) === true) {
            $styleWriter = new $styleWriterClass($this->parentWriter, $this->style, $this->curlyBracket);
            $css = $styleWriter->write();
        }

        return $css;
    }

    /**
     * Takes array where of CSS properties / values and converts to CSS string
     *
     * @param array $css
     * @param boolean $curlyBracket
     * @return string
     */
    public function assembleCss($css, $curlyBracket = false)
    {
        $pairs = array();
        foreach ($css as $key => $value) {
            if ($value != '') {
                $pairs[] = $key . ': ' . $value;
            }
        }
        $string = implode('; ', $pairs);
        if ($curlyBracket) {
            $string = '{ ' . $string . ' }';
        }

        return $string;
    }
}
