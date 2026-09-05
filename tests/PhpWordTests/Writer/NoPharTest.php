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

namespace PhpOffice\PhpWordTests\Writer;

use PhpOffice\PhpWord\Exception\Exception as PhpWordException;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

class NoPharTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Settings::restoreDefaults();
    }

    /**
     * @dataProvider providerFileType
     */
    public function testConstruct(string $fileType): void
    {
        $this->expectException(PhpWordException::class);
        $this->expectExceptionMessage('Invalid protocol');
        $phpWord = new PhpWord();
        if ($fileType === 'PDF') {
            $rendererName = Settings::PDF_RENDERER_DOMPDF;
            $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');
            self::assertNotFalse($rendererLibraryPath);
            Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        }
        $writer = IOFactory::createWriter($phpWord, $fileType);
        $writer->save('phar://poc.docx');
    }

    public static function providerFileType(): array
    {
        return [
            ['EPub3'],
            ['HTML'],
            ['ODText'],
            ['RTF'],
            ['Word2007'],
            ['PDF\DomPDF'],
            ['PDF\MPDF'],
            ['PDF\TCPDF'],
            ['PDF'],
        ];
    }
}
