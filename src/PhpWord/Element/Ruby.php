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

use PhpOffice\PhpWord\ComplexType\RubyProperties;

/**
 * Ruby element.
 * 
 * @see https://learn.microsoft.com/en-us/dotnet/api/documentformat.openxml.wordprocessing.ruby?view=openxml-3.0.1
 */
class Ruby extends AbstractElement
{
    /**
     * Ruby properties.
     *
     * @var RubyProperties
     */
    protected $properties;

    /**
     * Ruby text run.
     *
     * @var TextRun
     */
    protected $rubyTextRun;

    /**
     * Ruby base text run.
     *
     * @var TextRun
     */
    protected $baseTextRun;

    /**
     * Create a new Ruby Element.
     *
     * @param TextRun $baseTextRun
     * @param TextRun $rubyTextRun
     * @param RubyProperties $properties
     */
    public function __construct($baseTextRun, $rubyTextRun, $properties)
    {
        $this->baseTextRun = $baseTextRun;
        $this->rubyTextRun = $rubyTextRun;
        $this->properties = $properties;
    }

    /**
     * Get base text run.
     *
     * @return TextRun
     */
    public function getBaseTextRun()
    {
        return $this->baseTextRun;
    }

    /**
     * Get ruby text run.
     *
     * @return TextRun
     */
    public function getRubyTextRun()
    {
        return $this->rubyTextRun;
    }

    /**
     * Get properties.
     *
     * @return RubyProperties
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
