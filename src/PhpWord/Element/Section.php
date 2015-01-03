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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\Section as SectionStyle;

/**
 * Section
 */
class Section extends AbstractContainer
{
    /**
     * @var string Container type
     */
    protected $container = 'Section';

    /**
     * Section style
     *
     * @var \PhpOffice\PhpWord\Style\Section
     */
    private $style;

    /**
     * Section headers, indexed from 1, not zero
     *
     * @var Header[]
     */
    private $headers = array();

    /**
     * Section footers, indexed from 1, not zero
     *
     * @var Footer[]
     */
    private $footers = array();

    /**
     * Create new instance
     *
     * @param int $sectionCount
     * @param array $style
     */
    public function __construct($sectionCount, $style = null)
    {
        $this->sectionId = $sectionCount;
        $this->setDocPart($this->container, $this->sectionId);
        $this->style = new SectionStyle();
        $this->setStyle($style);
    }

    /**
     * Set section style.
     *
     * @param array $style
     * @return void
     */
    public function setStyle($style = null)
    {
        if (!is_null($style) && is_array($style)) {
            $this->style->setStyleByArray($style);
        }
    }

    /**
     * Get section style
     *
     * @return \PhpOffice\PhpWord\Style\Section
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Add header
     *
     * @param string $type
     * @return Header
     * @since 0.10.0
     */
    public function addHeader($type = Header::AUTO)
    {
        return $this->addHeaderFooter($type, true);
    }

    /**
     * Add footer
     *
     * @param string $type
     * @return Footer
     * @since 0.10.0
     */
    public function addFooter($type = Header::AUTO)
    {
        return $this->addHeaderFooter($type, false);
    }

    /**
     * Get header elements
     *
     * @return Header[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get footer elements
     *
     * @return Footer[]
     */
    public function getFooters()
    {
        return $this->footers;
    }

    /**
     * Is there a header for this section that is for the first page only?
     *
     * If any of the Header instances have a type of Header::FIRST then this method returns true.
     * False otherwise.
     *
     * @return boolean
     */
    public function hasDifferentFirstPage()
    {
        foreach ($this->headers as $header) {
            if ($header->getType() == Header::FIRST) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add header/footer
     *
     * @param string $type
     * @param boolean $header
     * @return Header|Footer
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * @since 0.10.0
     */
    private function addHeaderFooter($type = Header::AUTO, $header = true)
    {
        $containerClass = substr(get_class($this), 0, strrpos(get_class($this), '\\')) . '\\' .
            ($header ? 'Header' : 'Footer');
        $collectionArray = $header ? 'headers' : 'footers';
        $collection = &$this->$collectionArray;

        if (in_array($type, array(Header::AUTO, Header::FIRST, Header::EVEN))) {
            $index = count($collection);
            /** @var \PhpOffice\PhpWord\Element\AbstractContainer $container Type hint */
            $container = new $containerClass($this->sectionId, ++$index, $type);
            $container->setPhpWord($this->phpWord);

            $collection[$index] = $container;
            return $container;
        } else {
            throw new Exception('Invalid header/footer type.');
        }

    }

    /**
     * Set section style
     *
     * @param array $settings
     * @deprecated 0.12.0
     * @codeCoverageIgnore
     */
    public function setSettings($settings = null)
    {
        $this->setStyle($settings);
    }

    /**
     * Get section style
     *
     * @return \PhpOffice\PhpWord\Style\Section
     * @deprecated 0.12.0
     * @codeCoverageIgnore
     */
    public function getSettings()
    {
        return $this->getStyle();
    }

    /**
     * Create header
     *
     * @return Header
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function createHeader()
    {
        return $this->addHeader();
    }

    /**
     * Create footer
     *
     * @return Footer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function createFooter()
    {
        return $this->addFooter();
    }

    /**
     * Get footer
     *
     * @return Footer
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getFooter()
    {
        if (empty($this->footers)) {
            return null;
        } else {
            return $this->footers[1];
        }
    }
}
