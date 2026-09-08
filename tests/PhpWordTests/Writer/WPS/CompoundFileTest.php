<?php

namespace PhpOffice\PhpWordTests\Writer\WPS;

use PhpOffice\PhpWord\Shared\OLERead;
use PhpOffice\PhpWord\Writer\WPS\CompoundFile;
use PHPUnit\Framework\TestCase;

class CompoundFileTest extends TestCase
{
    public function testIndependentReaderRecoversContentsAcrossAllocationBoundaries(): void
    {
        // Mini-sector, sector, mini-stream cutoff and header DIFAT boundaries.
        foreach ([0, 1, 63, 64, 65, 511, 512, 513, 4095, 4096, 4097, 7200000] as $length) {
            $contents = substr(str_repeat("Works\0\xFF\x80\r\n", (int) ceil($length / 10)), 0, $length);
            $filename = tempnam(sys_get_temp_dir(), 'wps-cfb-');

            try {
                file_put_contents($filename, CompoundFile::encode($contents));
                $reader = new OLERead();
                $reader->read($filename);
                self::assertSame($length, $reader->props[1]['size']);
                self::assertSame($contents, substr($reader->getStream(1), 0, $length));
            } finally {
                unlink($filename);
            }
        }
    }
}
