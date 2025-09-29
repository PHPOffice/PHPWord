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
 * @see         https://github.com/PHPOffice/PhpWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\PDF;

/**
 * TCPDF writer.
 *
 * @deprecated 0.13.0 Use `DomPDF` or `MPDF` instead.
 * @see  http://www.tcpdf.org/
 * @since 0.11.0
 */
class TcpdfNoDie extends TCPDF
{
    /**
     * By default, TCPDF will die sometimes rather than throwing exception.
     * And this is controlled by a defined constant in the global namespace,
     * not by an instance property. Ugh!
     * Using this class instead of the class which it extends will probably
     * be suitable for most users. But not for those who have customized
     * their config file. Which is why this isn't the default, so that
     * there is no breaking change for those users.
     * Note that if both TCPDF and TcpdfNoDie are used in the same process,
     * the first one used "wins" the battle of the defines.
     */
    protected function defines(): void
    {
        if (!defined('K_TCPDF_EXTERNAL_CONFIG')) {
            define('K_TCPDF_EXTERNAL_CONFIG', true);
        }
        if (!defined('K_TCPDF_THROW_EXCEPTION_ERROR')) {
            define('K_TCPDF_THROW_EXCEPTION_ERROR', true);
        }
    }
}
