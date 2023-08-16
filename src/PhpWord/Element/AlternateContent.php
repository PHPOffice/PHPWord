<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Shared\Text;
use PhpOffice\PhpWord\Style\AbstractStyle;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Textrun/paragraph element.
 */
class AlternateContent extends AbstractContainer
{
    /**
     * @var string Container type
     */
    protected $container = 'AlternateContent';

    /**
     * Shape Node.
     *
     * @var PhpOffice\PhpWord\ComplexType\AltShape
     */
    private $shape;

    /**
     * Shape Node.
     *
     * @var PhpOffice\PhpWord\ComplexType\AltLine
     */
    private $line;

    /**
     * v:fill.
     *
     * @var array
     */
    private $fill;

    /**
     * v:stroke.
     *
     * @var array
     */
    private $stroke;

    /**
     * v:imagedata.
     *
     * @var array
     */
    private $imagedata;

    /**
     * v:lock.
     *
     * @var array
     */
    private $lock;

    /**
     * v:textbox.
     *
     * @var array
     */
    private $textbox;



    /**
     * Create new instance.
     *
     * @param array $alternateChilds
     */
    public function __construct($alternateChilds)
    {
        $this->setAttrByArray($alternateChilds);
    }

    /**
     * Set style by using associative array.
     *
     * @param array $values
     *
     * @return self
     */
    public function setAttrByArray($values = [])
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * Get shape
     *
     * @return PhpOffice\PhpWord\ComplexType\AltShape
     * @author <presleylee@qq.com>
     * @since 2023/8/15 5:00 下午
     */
    public function getShape() {
        return $this->shape;
    }

    /**
     * Get shape
     *
     * @return PhpOffice\PhpWord\ComplexType\AltShape
     * @author <presleylee@qq.com>
     * @since 2023/8/15 5:00 下午
     */
    public function getLine() {
        return $this->line;
    }

    /**
     * Get fill
     *
     * @return array
     * @author <presleylee@qq.com>
     * @since 2023/8/15 5:00 下午
     */
    public function getFill() {
        return $this->fill;
    }

    /**
     * Get stroke
     *
     * @return array
     * @author <presleylee@qq.com>
     * @since 2023/8/15 5:00 下午
     */
    public function getStroke() {
        return $this->stroke;
    }

    /**
     * Get stroke
     *
     * @return array
     * @author <presleylee@qq.com>
     * @since 2023/8/15 5:00 下午
     */
    public function getImagedata() {
        return $this->imagedata;
    }

    /**
     * Get lock
     *
     * @return array
     * @author <presleylee@qq.com>
     * @since 2023/8/15 5:00 下午
     */
    public function getLock() {
        return $this->lock;
    }

    /**
     * Get textbox
     *
     * @return array
     * @author <presleylee@qq.com>
     * @since 2023/8/15 5:00 下午
     */
    public function getTextBox() {
        return $this->textbox;
    }

}
