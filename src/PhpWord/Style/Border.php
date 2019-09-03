<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Exception\Exception;

trait Border
{
    protected $borders = array();

    protected function getAllowedSides(): array
    {
        return array(
            'top',
            'bottom',
            'left',
            'right',
        );
    }

    /**
     * Get all borders
     *
     * @return BorderSide[]
     */
    public function getBorders(): array
    {
        if (empty($this->borders)) {
            $this->setBorders(new BorderSide());
        }

        return $this->borders;
    }

    /**
     * Get specific border
     */
    public function getBorder(string $side): BorderSide
    {
        if (!in_array($side, $this->getAllowedSides())) {
            throw new Exception(sprintf('Invalid side `%s` provided', $side));
        }

        return $this->getBorders()[$side];
    }

    /**
     * Set same border for all sides
     */
    public function setBorders(BorderSide $borderSide): self
    {
        foreach ($this->getAllowedSides() as $side) {
            $this->borders[$side] = clone $borderSide;
        }

        return $this;
    }

    /**
     * Set same border for all sides
     */
    public function setBordersFromArray(array $borders): self
    {
        foreach ($borders as $side => $border) {
            $this->setBorder($side, $border);
        }

        return $this;
    }

    /**
     * Set same border for all sides
     */
    public function setBorder(string $side, BorderSide $borderSide): self
    {
        if (!in_array($side, $this->getAllowedSides())) {
            throw new Exception(sprintf('Invalid side `%s` provided', $side));
        } elseif (empty($this->borders)) {
            $this->setBorders(new BorderSide());
        }

        $this->borders[$side] = clone $borderSide;

        return $this;
    }

    /**
     * Check if any borders have been added
     */
    public function hasBorder(): bool
    {
        foreach ($this->borders as $border) {
            if (($border->getSize()->toFloat('twip') ?? 0) !== 0) {
                return true;
            }
        }

        return false;
    }
}
