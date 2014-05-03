<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Element\Image;

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
