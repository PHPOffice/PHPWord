<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Theme;

use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Colors\SystemColor;

class Theme
{
    private $colorScheme;
    private $fontScheme;

    public function getColorScheme(): ColorScheme
    {
        if (!isset($this->colorScheme)) {
            $this->colorScheme = new ColorScheme(array(
                'dk1'      => new SystemColor('windowText', new Hex('000')),
                'lt1'      => new SystemColor('window', new Hex('fff')),
                'dk2'      => new Hex('1F497D'),
                'lt2'      => new Hex('EEECE1'),
                'accent1'  => new Hex('4F81BD'),
                'accent2'  => new Hex('C0504D'),
                'accent3'  => new Hex('9BBB59'),
                'accent4'  => new Hex('8064A2'),
                'accent5'  => new Hex('4BACC6'),
                'accent6'  => new Hex('F79646'),
                'hlink'    => new Hex('0000FF'),
                'folHlink' => new Hex('800080'),
            ));
        }

        return clone $this->colorScheme;
    }

    public function getFontScheme(): FontScheme
    {
        if (!isset($this->fontScheme)) {
            $this->fontScheme = new FontScheme();
        }

        return clone $this->fontScheme;
    }
}
