<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests;

use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use RuntimeException;

class SampleTest extends TestCase
{
    /** @var bool */
    protected static $alwaysTrue = true;

    /**
     *
     * @param string $sample
     *
     * @preserveGlobalState disabled
     *
     * @runInSeparateProcess
     *
     * @dataProvider providerSample
     */
    public function testSample($sample): void
    {
        ob_start();
        require $sample;
        ob_end_clean();

        self::assertTrue(self::$alwaysTrue);
    }

    public static function providerSample(): array
    {
        $skipped = [];
        if (getenv('SKIP_URL_IMAGE_TEST') === '1') {
            $skipped[] = 'Sample_13_Images.php';
            $skipped[] = 'Sample_30_ReadHTML.php';
        }
        $result = [];
        foreach (self::getSamples() as $samples) {
            foreach ($samples as $sample) {
                if (!in_array($sample, $skipped)) {
                    $file = 'samples/' . $sample;
                    $result[$sample] = [$file];
                }
            }
        }

        return $result;
    }

    /**
     * Returns an array of all known samples.
     *
     * @return string[][] [$name => $path]
     */
    public static function getSamples(): array
    {
        // Populate samples
        $baseDir = realpath('samples');
        if ($baseDir === false) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('realpath returned false');
            // @codeCoverageIgnoreEnd
        }
        $directory = new RecursiveDirectoryIterator($baseDir);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/Sample_\\d+_.+[.]php$/', RecursiveRegexIterator::GET_MATCH);

        $files = [];
        /** @var string[] $file */
        foreach ($regex as $file) {
            $file = str_replace(str_replace('\\', '/', $baseDir) . '/', '', str_replace('\\', '/', $file[0]));
            $info = pathinfo($file);
            $category = 'PhpWord';
            $name = str_replace('_', ' ', (string) preg_replace('/(|\.php)/', '', $info['filename']));
            if (!isset($files[$category])) {
                $files[$category] = [];
            }
            $files[$category][$name] = $file;
        }

        // Sort everything
        ksort($files);
        foreach ($files as &$f) {
            asort($f);
        }

        return $files;
    }
}
