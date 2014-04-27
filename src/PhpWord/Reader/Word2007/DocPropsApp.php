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
 * Extended properties reader
 */
class DocPropsApp extends DocProps
{
    /**
     * Property mapping
     *
     * @var array
     */
    protected $mapping = array('Company' => 'setCompany', 'Manager' => 'setManager');
}
