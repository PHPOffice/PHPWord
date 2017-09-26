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
namespace PhpOffice\PhpWord\ComplexType;

/**
 * Visibility of Annotation Types
 *
 * @see http://www.datypic.com/sc/ooxml/e-w_revisionView-1.html
 */
final class TrackChangesView
{

    /**
     * Display Visual Indicator Of Markup Area
     *
     * @var boolean
     */
    private $markup;

    /**
     * Display Comments
     *
     * @var boolean
     */
    private $comments;

    /**
     * Display Content Revisions
     *
     * @var boolean
     */
    private $insDel;

    /**
     * Display Formatting Revisions
     *
     * @var boolean
     */
    private $formatting;

    /**
     * Display Ink Annotations
     *
     * @var boolean
     */
    private $inkAnnotations;

    /**
     * Get Display Visual Indicator Of Markup Area
     *
     * @return boolean True if markup is shown
     */
    public function hasMarkup()
    {
        return $this->markup;
    }

    /**
     * Set Display Visual Indicator Of Markup Area
     *
     * @param boolean $markup
     *            Set to true to show markup
     */
    public function setMarkup($markup)
    {
        $this->markup = $markup === null ? true : $markup;
    }

    /**
     * Get Display Comments
     *
     * @return boolean True if comments are shown
     */
    public function hasComments()
    {
        return $this->comments;
    }

    /**
     * Set Display Comments
     *
     * @param boolean $comments
     *            Set to true to show comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments === null ? true : $comments;
    }

    /**
     * Get Display Content Revisions
     *
     * @return boolean True if content revisions are shown
     */
    public function hasInsDel()
    {
        return $this->insDel;
    }

    /**
     * Set Display Content Revisions
     *
     * @param boolean $insDel
     *            Set to true to show content revisions
     */
    public function setInsDel($insDel)
    {
        $this->insDel = $insDel === null ? true : $insDel;
    }

    /**
     * Get Display Formatting Revisions
     *
     * @return boolean True if formatting revisions are shown
     */
    public function hasFormatting()
    {
        return $this->formatting;
    }

    /**
     * Set Display Formatting Revisions
     *
     * @param boolean|null $formatting
     *            Set to true to show formatting revisions
     */
    public function setFormatting($formatting = null)
    {
        $this->formatting = $formatting === null ? true : $formatting;
    }

    /**
     * Get Display Ink Annotations
     *
     * @return boolean True if ink annotations are shown
     */
    public function hasInkAnnotations()
    {
        return $this->inkAnnotations;
    }

    /**
     * Set Display Ink Annotations
     *
     * @param boolean $inkAnnotations
     *            Set to true to show ink annotations
     */
    public function setInkAnnotations($inkAnnotations)
    {
        $this->inkAnnotations = $inkAnnotations === null ? true : $inkAnnotations;
    }
}
