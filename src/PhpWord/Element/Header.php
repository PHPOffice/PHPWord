<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

/**
 * Header element
 */
class Header extends Footer
{

    /**
     * Container type
     *
     * @var string
     */
    protected $container = 'header';

    /**
     * Add a Watermark Element
     *
     * @param string $src
     * @param mixed $style
     * @return Image
     */
    public function addWatermark($src, $style = null)
    {
        return $this->addImage($src, $style, true);
    }
}
