<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Container;

use PhpOffice\PhpWord\TOC;
use PhpOffice\PhpWord\Container\Footer;
use PhpOffice\PhpWord\Container\Header;
use PhpOffice\PhpWord\Container\Settings;
use PhpOffice\PhpWord\Element\PageBreak;

/**
 * Section
 */
class Section extends Container
{
    /**
     * Section settings
     *
     * @var Settings
     */
    private $settings;

    /**
     * Section headers
     *
     * @var Header[]
     */
    private $headers = array();

    /**
     * Section footer
     *
     * @var Footer
     */
    private $footer = null;


    /**
     * Create new instance
     *
     * @param int $sectionCount
     * @param mixed $settings
     */
    public function __construct($sectionCount, $settings = null)
    {
        $this->container = 'section';
        $this->containerId = $sectionCount;
        $this->settings = new Settings();
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
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->settings->setSettingValue($key, $value);
            }
        }
    }

    /**
     * Get Section Settings
     *
     * @return Settings
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
     * @param mixed $styleFont
     * @param mixed $styleTOC
     * @return TOC
     */
    public function addTOC($styleFont = null, $styleTOC = null)
    {
        $toc = new TOC($styleFont, $styleTOC);
        $this->elements[] = $toc;
        return $toc;
    }

    /**
     * Add header
     *
     * @return Header
     */
    public function addHeader()
    {
        $header = new Header($this->containerId);
        $this->headers[] = $header;
        return $header;
    }

    /**
     * Add footer
     *
     * @return Footer
     */
    public function addFooter()
    {
        $footer = new Footer($this->containerId);
        $this->footer = $footer;
        return $footer;
    }

    /**
     * Get Headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get footer element
     *
     * @return Footer
     */
    public function getFooter()
    {
        return $this->footer;
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
        $value = array_filter($this->headers, function (Header &$header) {
            return $header->getType() == Header::FIRST;
        });
        return count($value) > 0;
    }

    /**
     * Create header
     *
     * @deprecated 0.9.2
     */
    public function createHeader()
    {
        return $this->addHeader();
    }

    /**
     * Create footer
     *
     * @deprecated 0.9.2
     */
    public function createFooter()
    {
        return $this->addFooter();
    }
}
