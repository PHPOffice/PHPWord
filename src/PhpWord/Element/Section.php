<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\Section as SectionSettings;
use PhpOffice\PhpWord\Element\PageBreak;
use PhpOffice\PhpWord\Element\TOC;

/**
 * Section
 */
class Section extends AbstractContainer
{
    /**
     * Section settings
     *
     * @var \PhpOffice\PhpWord\Style\Section
     */
    private $settings;

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
     * @param array $settings
     */
    public function __construct($sectionCount, $settings = null)
    {
        $this->container = 'section';
        $this->sectionId = $sectionCount;
        $this->setDocPart($this->container, $this->sectionId);
        $this->settings = new SectionSettings();
        $this->setSettings($settings);
    }

    /**
     * Set section settings
     *
     * @param array $settings
     */
    public function setSettings($settings = null)
    {
        if (!is_null($settings) && is_array($settings)) {
            foreach ($settings as $key => $value) {
                if (is_null($value)) {
                    continue;
                }
                $this->settings->setSettingValue($key, $value);
            }
        }
    }

    /**
     * Get Section Settings
     *
     * @return \PhpOffice\PhpWord\Style\Section
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Add a PageBreak Element
     */
    public function addPageBreak()
    {
        $this->elements[] = new PageBreak();
    }

    /**
     * Add a Table-of-Contents Element
     *
     * @param mixed $fontStyle
     * @param mixed $tocStyle
     * @param integer $minDepth
     * @param integer $maxDepth
     * @return \PhpOffice\PhpWord\Element\TOC
     */
    public function addTOC($fontStyle = null, $tocStyle = null, $minDepth = 1, $maxDepth = 9)
    {
        $toc = new TOC($fontStyle, $tocStyle, $minDepth, $maxDepth);
        $this->elements[] = $toc;
        return $toc;
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
        $collectionArray = $header ? 'headers' : 'footers';
        $containerClass = 'PhpOffice\\PhpWord\\Element\\';
        $containerClass .= ($header ? 'Header' : 'Footer');
        $collection = &$this->$collectionArray;

        if (in_array($type, array(Header::AUTO, Header::FIRST, Header::EVEN))) {
            $index = count($collection);
            $container = new $containerClass($this->sectionId, ++$index, $type);
            $collection[$index] = $container;
            return $container;
        } else {
            throw new Exception('Invalid header/footer type.');
        }

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
