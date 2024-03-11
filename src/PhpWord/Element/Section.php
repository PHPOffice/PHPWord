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

use Exception;
use PhpOffice\PhpWord\ComplexType\FootnoteProperties;
use PhpOffice\PhpWord\Style\Section as SectionStyle;

class Section extends AbstractContainer
{
    /**
     * @var string Container type
     */
    protected $container = 'Section';

    /**
     * Section style.
     *
     * @var ?\PhpOffice\PhpWord\Style\Section
     */
    private $style;

    /**
     * Section headers, indexed from 1, not zero.
     *
     * @var Header[]
     */
    private $headers = [];

    /**
     * Section footers, indexed from 1, not zero.
     *
     * @var Footer[]
     */
    private $footers = [];

    /**
     * The properties for the footnote of this section.
     *
     * @var FootnoteProperties
     */
    private $footnoteProperties;

    /**
     * Create new instance.
     *
     * @param int $sectionCount
     * @param null|array|\PhpOffice\PhpWord\Style|string $style
     */
    public function __construct(int $sectionCount, $style = null)
    {
        $this->sectionId = $sectionCount;
        $this->setDocPart($this->container, $this->sectionId);
        if (null === $style) {
            $style = new SectionStyle();
        }
        $this->style = $this->setNewStyle(new SectionStyle(), $style);
    }

    /**
     * Set section style.
     *
     * @param array|null $style
     */
    public function setStyle(?array $style = null): void
    {
        if (is_array($style)) {
            $this->style->setStyleByArray($style);
        }
    }

    /**
     * Get section style.
     *
     * @return ?\PhpOffice\PhpWord\Style\Section
     */
    public function getStyle(): ?SectionStyle
    {
        return $this->style;
    }

    /**
     * Add header.
     *
     * @param string $type
     *
     * @return Header
     *
     * @throws Exception
     * @since 0.10.0
     */
    public function addHeader(string $type = Header::AUTO)
    {
        return $this->addHeaderFooter($type, true);
    }

    /**
     * Add footer.
     *
     * @param string $type
     *
     * @return Footer
     *
     * @throws Exception
     * @since 0.10.0
     */
    public function addFooter(string $type = Header::AUTO)
    {
        return $this->addHeaderFooter($type, false);
    }

    /**
     * Get header elements.
     *
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get footer elements.
     *
     * @retu
     * rn Footer[]
     */
    public function getFooters(): array
    {
        return $this->footers;
    }

    /**
     * Get the footnote properties.
     *
     * @return FootnoteProperties|null
     */
    public function getFootnoteProperties(): ?FootnoteProperties
    {
        return $this->footnoteProperties;
    }

    /**
     * Set the footnote properties.
     */
    public function setFootnoteProperties(?FootnoteProperties $footnoteProperties = null): void
    {
        $this->footnoteProperties = $footnoteProperties;
    }

    /**
     * Is there a header for this section that is for the first page only?
     *
     * If any of the Header instances have a type of Header::FIRST then this method returns true.
     * False otherwise.
     *
     * @return bool
     */
    public function hasDifferentFirstPage(): bool
    {
        foreach ($this->headers as $header) {
            if ($header->getType() == Header::FIRST) {
                return true;
            }
        }
        foreach ($this->footers as $footer) {
            if ($footer->getType() == Header::FIRST) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add header/footer.
     *
     * @param string $type
     * @param bool $header
     *
     * @return Footer|Header
     * @throws Exception
     *
     * @since 0.10.0
     */
    private function addHeaderFooter(string $type = Header::AUTO, bool $header = true)
    {
        $containerClass = substr(static::class, 0, strrpos(static::class, '\\')) . '\\' .
            ($header ? 'Header' : 'Footer');
        $collectionArray = $header ? 'headers' : 'footers';
        $collection = &$this->$collectionArray;

        if (in_array($type, [Header::AUTO, Header::FIRST, Header::EVEN])) {
            $index = count($collection);
            /** @var \PhpOffice\PhpWord\Element\Header|\PhpOffice\PhpWord\Element\Footer $container Type hint */
            $container = new $containerClass($this->sectionId, ++$index, $type);
            $container->setPhpWord($this->phpWord);

            $collection[$index] = $container;

            return $container;
        }

        throw new Exception('Invalid header/footer type.');
    }
}
