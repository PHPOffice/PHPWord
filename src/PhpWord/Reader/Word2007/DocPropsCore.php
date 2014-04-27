<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

/**
 * Core properties reader
 */
class DocPropsCore extends DocProps
{
    /**
     * Property mapping
     *
     * @var array
     */
    protected $mapping = array(
        'dc:creator' => 'setCreator',
        'dc:title' => 'setTitle',
        'dc:description' => 'setDescription',
        'dc:subject' => 'setSubject',
        'cp:keywords' => 'setKeywords',
        'cp:category' => 'setCategory',
        'cp:lastModifiedBy' => 'setLastModifiedBy',
        'dcterms:created' => 'setCreated',
        'dcterms:modified' => 'setModified',
    );

    /**
     * Callback functions
     *
     * @var array
     */
    protected $callbacks = array('dcterms:created' => 'strtotime', 'dcterms:modified' => 'strtotime');
}
