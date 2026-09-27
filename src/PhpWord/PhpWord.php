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

namespace PhpOffice\PhpWord;

use BadMethodCallException;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\AbstractStyle;

/**
 * PHPWord main class.
 *
 * @method Collection\Titles getTitles()
 * @method Collection\Footnotes getFootnotes()
 * @method Collection\Endnotes getEndnotes()
 * @method Collection\Charts getCharts()
 * @method Collection\Comments getComments()
 * @method int addBookmark(Element\Bookmark $bookmark)
 * @method int addTitle(Element\Title $title)
 * @method int addFootnote(Element\Footnote $footnote)
 * @method int addEndnote(Element\Endnote $endnote)
 * @method int addChart(Element\Chart $chart)
 * @method int addComment(Element\Comment $comment)
 * @method Style\Paragraph addParagraphStyle(string $styleName, mixed $styles)
 * @method Style\Font addFontStyle(string $styleName, mixed $fontStyle, mixed $paragraphStyle = null)
 * @method Style\Font addLinkStyle(string $styleName, mixed $styles)
 * @method Style\Font addTitleStyle(mixed $depth, mixed $fontStyle, mixed $paragraphStyle = null)
 * @method Style\Table addTableStyle(string $styleName, mixed $styleTable, mixed $styleFirstRow = null)
 * @method Style\Numbering addNumberingStyle(string $styleName, mixed $styles)
 */
class PhpWord
{
    /**
     * Collection of sections.
     *
     * @var Section[]
     */
    private $sections = [];

    /**
     * Collections.
     *
     * @var array
     */
    private $collections = [];

    /**
     * Metadata.
     *
     * @var array
     *
     * @since 0.12.0
     */
    private $metadata = [];
    
    /**
     * Style register.
     *
     * @var array
     */
    private $styles = [];

    /**
     * Create new instance.
     *
     * Collections are created dynamically
     */
    public function __construct()
    {
        // Reset Media and styles
        Media::resetElements();
        Settings::setDefaultRtl(null);

        // Collection
        $collections = ['Bookmarks', 'Titles', 'Footnotes', 'Endnotes', 'Charts', 'Comments'];
        foreach ($collections as $collection) {
            $class = 'PhpOffice\\PhpWord\\Collection\\' . $collection;
            $this->collections[$collection] = new $class();
        }

        // Metadata
        $metadata = ['DocInfo', 'Settings', 'Compatibility'];
        foreach ($metadata as $meta) {
            $class = 'PhpOffice\\PhpWord\\Metadata\\' . $meta;
            $this->metadata[$meta] = new $class();
        }
    }

    /**
     * Dynamic function call to reduce static dependency.
     *
     * @since 0.12.0
     *
     * @param mixed $function
     * @param mixed $args
     *
     * @return mixed
     */
    public function __call($function, $args)
    {
        $function = strtolower($function);

        $getCollection = [];
        $addCollection = [];

        $collections = ['Bookmark', 'Title', 'Footnote', 'Endnote', 'Chart', 'Comment'];
        foreach ($collections as $collection) {
            $getCollection[] = strtolower("get{$collection}s");
            $addCollection[] = strtolower("add{$collection}");
        }

        // Run get collection method
        if (in_array($function, $getCollection)) {
            $key = ucfirst(str_replace('get', '', $function));

            return $this->collections[$key];
        }

        // Run add collection item method
        if (in_array($function, $addCollection)) {
            $key = ucfirst(str_replace('add', '', $function) . 's');

            $collectionObject = $this->collections[$key];

            return $collectionObject->addItem($args[0] ?? null);
        }

        // Exception
        throw new BadMethodCallException("Method $function is not defined.");
    }

    /**
     * Get document properties object.
     *
     * @return Metadata\DocInfo
     */
    public function getDocInfo()
    {
        return $this->metadata['DocInfo'];
    }

    /**
     * Get compatibility.
     *
     * @return Metadata\Compatibility
     *
     * @since 0.12.0
     */
    public function getCompatibility()
    {
        return $this->metadata['Compatibility'];
    }

    /**
     * Get compatibility.
     *
     * @return Metadata\Settings
     *
     * @since 0.14.0
     */
    public function getSettings()
    {
        return $this->metadata['Settings'];
    }

    /**
     * Get all sections.
     *
     * @return Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * Returns the section at the requested position.
     *
     * @param int $index
     *
     * @return null|Section
     */
    public function getSection($index)
    {
        if (array_key_exists($index, $this->sections)) {
            return $this->sections[$index];
        }

        return null;
    }

    /**
     * Create new section.
     *
     * @param null|array|string $style
     *
     * @return Section
     */
    public function addSection($style = null)
    {
        $section = new Section(count($this->sections) + 1, $style);
        $section->setPhpWord($this);
        $this->sections[] = $section;

        return $section;
    }

    /**
     * Sorts the sections using the callable passed.
     *
     * @see http://php.net/manual/en/function.usort.php for usage
     *
     * @param callable $sorter
     */
    public function sortSections($sorter): void
    {
        usort($this->sections, $sorter);
    }

    /**
     * Get default font name.
     *
     * @return string
     */
    public function getDefaultFontName()
    {
        return Settings::getDefaultFontName();
    }

    /**
     * Set default font name.
     *
     * @param string $fontName
     */
    public function setDefaultFontName($fontName): void
    {
        Settings::setDefaultFontName($fontName);
    }

    /**
     * Get default asian font name.
     */
    public function getDefaultAsianFontName(): string
    {
        return Settings::getDefaultAsianFontName();
    }

    /**
     * Set default asian font name.
     *
     * @param string $fontName
     */
    public function setDefaultAsianFontName($fontName): void
    {
        Settings::setDefaultAsianFontName($fontName);
    }

    /**
     * Set default font color.
     */
    public function setDefaultFontColor(string $fontColor): void
    {
        Settings::setDefaultFontColor($fontColor);
    }

    /**
     * Get default font color.
     */
    public function getDefaultFontColor(): string
    {
        return Settings::getDefaultFontColor();
    }

    /**
     * Get default font size.
     *
     * @return int
     */
    public function getDefaultFontSize()
    {
        return Settings::getDefaultFontSize();
    }

    /**
     * Set default font size.
     *
     * @param int $fontSize
     */
    public function setDefaultFontSize($fontSize): void
    {
        Settings::setDefaultFontSize($fontSize);
    }

    /**
     * Save to file or download.
     *
     * All exceptions should already been handled by the writers
     *
     * @param string $filename
     * @param string $format
     * @param bool $download
     *
     * @return bool
     */
    public function save($filename, $format = 'Word2007', $download = false)
    {
        $mime = [
            'Word2007' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'ODText' => 'application/vnd.oasis.opendocument.text',
            'RTF' => 'application/rtf',
            'HTML' => 'text/html',
            'PDF' => 'application/pdf',
        ];

        $writer = IOFactory::createWriter($this, $format);

        if ($download === true) {
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Type: ' . $mime[$format]);
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            $filename = 'php://output'; // Change filename to force download
        }

        $writer->save($filename);

        return true;
    }

    /**
     * Create new section.
     *
     * @deprecated 0.10.0
     *
     * @param array $settings
     *
     * @return Section
     *
     * @codeCoverageIgnore
     */
    public function createSection($settings = null)
    {
        return $this->addSection($settings);
    }

    /**
     * Get document properties object.
     *
     * @deprecated 0.12.0
     *
     * @return Metadata\DocInfo
     *
     * @codeCoverageIgnore
     */
    public function getDocumentProperties()
    {
        return $this->getDocInfo();
    }

    /**
     * Set document properties object.
     *
     * @deprecated 0.12.0
     *
     * @param Metadata\DocInfo $documentProperties
     *
     * @return self
     *
     * @codeCoverageIgnore
     */
    public function setDocumentProperties($documentProperties)
    {
        $this->metadata['Document'] = $documentProperties;

        return $this;
    }
    
    /**
     * Add paragraph style.
     *
     * @param string $styleName
     * @param AbstractStyle|array $styles
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function addParagraphStyle($styleName, $styles)
    {
        return $this->setStyleValues($styleName, new Style\Paragraph(), $styles);
    }

    /**
     * Add font style.
     *
     * @param string $styleName
     * @param AbstractStyle|array $fontStyle
     * @param AbstractStyle|array $paragraphStyle
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addFontStyle($styleName, $fontStyle, $paragraphStyle = null)
    {
        return $this->setStyleValues($styleName, new Style\Font('text', $paragraphStyle), $fontStyle);
    }

    /**
     * Add link style.
     *
     * @param string $styleName
     * @param AbstractStyle|array $styles
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addLinkStyle($styleName, $styles)
    {
        return $this->setStyleValues($styleName, new Style\Font('link'), $styles);
    }

    /**
     * Add numbering style.
     *
     * @param string $styleName
     * @param AbstractStyle|array $styleValues
     *
     * @return \PhpOffice\PhpWord\Style\Numbering
     *
     * @since 0.10.0
     */
    public function addNumberingStyle($styleName, $styleValues)
    {
        return $this->setStyleValues($styleName, new Style\Numbering(), $styleValues);
    }

    /**
     * Add title style.
     *
     * @param null|int $depth Provide null to set title font
     * @param AbstractStyle|array $fontStyle
     * @param AbstractStyle|array $paragraphStyle
     *
     * @return \PhpOffice\PhpWord\Style\Font
     */
    public function addTitleStyle($depth, $fontStyle, $paragraphStyle = null)
    {
        if (empty($depth)) {
            $styleName = 'Title';
        } else {
            $styleName = "Heading_{$depth}";
        }

        return $this->setStyleValues($styleName, new Style\Font('title', $paragraphStyle), $fontStyle);
    }

    /**
     * Add table style.
     *
     * @param string $styleName
     * @param array $styleTable
     * @param null|array $styleFirstRow
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function addTableStyle($styleName, $styleTable, $styleFirstRow = null)
    {
        return $this->setStyleValues($styleName, new Style\Table($styleTable, $styleFirstRow), null);
    }

    /**
     * Set default paragraph style.
     *
     * @param AbstractStyle|array $styles Paragraph style definition
     *
     * @return \PhpOffice\PhpWord\Style\Paragraph
     */
    public function setDefaultParagraphStyle($styles)
    {
        return $this->addParagraphStyle('Normal', $styles);
    }

    /**
     * Get all styles.
     *
     * @return AbstractStyle[]
     */
    public function getStyles()
    {
        $styles = Style::getStyles();
        $index = Style::countStyles() + 1;
        foreach ($this->styles AS $name => $style) {
            if (isset($styles[$name])) {
                $style->setIndex($styles[$name]->getIndex());
            } else {
                $style->setIndex($index);
                $index ++;
            }
            $styles[$name] = $style;
        }
        return $styles;
    }

    /**
     * Get style by name.
     *
     * @param string $styleName
     *
     * @return ?AbstractStyle Paragraph|Font|Table|Numbering
     */
    public function getStyle($styleName)
    {
        return $this->styles[$styleName] ?? Style::getStyle($styleName);
    }

    /**
     * Set style values and put it to style collection.
     *
     * The $styleValues could be an array or object
     *
     * @param string $name
     * @param AbstractStyle $style
     * @param AbstractStyle|array $value
     *
     * @return AbstractStyle
     */
    private function setStyleValues($name, $style, $value = null)
    {
        if (!isset($this->styles[$name])) {
            if ($value !== null) {
                if (is_array($value)) {
                    $style->setStyleByArray($value);
                } elseif ($value instanceof AbstractStyle) {
                    if (get_class($style) == get_class($value)) {
                        $style = $value;
                    }
                }
            }
            $style->setStyleName($name);
            $this->styles[$name] = $style;
        }

        return $this->styles[$name];
    }
}
