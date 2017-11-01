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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Style\Paragraph;

/**
 * @codeCoverageIgnore
 */
class Footnote extends AbstractContainer
{
    /**
     * @var string Container type
     */
    protected $container = 'Footnote';

    /**
     * Paragraph style
     *
     * @var string|\PhpOffice\PhpWord\Style\Paragraph
     */
    protected $paragraphStyle;

    /**
     * Is part of collection
     *
     * @var bool
     */
    protected $collectionRelation = true;

    /**
     * Create new instance
     *
     * @param string|array|\PhpOffice\PhpWord\Style\Paragraph $paragraphStyle
     */
    public function __construct($paragraphStyle = null)
    {
        $this->paragraphStyle = $this->setNewStyle(new Paragraph(), $paragraphStyle);
        $this->setDocPart($this->container);
    }

    /**
     * Get paragraph style
     *
     * @return string|\PhpOffice\PhpWord\Style\Paragraph
     */
    public function getParagraphStyle()
    {
        return $this->paragraphStyle;
    }

    /**
     * Get Footnote Reference ID
     *
     * @deprecated 0.10.0
     *
     * @return int
     */
    public function getReferenceId()
    {
        return $this->getRelationId();
    }

    /**
     * Set Footnote Reference ID
     *
     * @deprecated 0.10.0
     *
     * @param int $rId
     */
    public function setReferenceId($rId)
    {
        $this->setRelationId($rId);
    }
}
