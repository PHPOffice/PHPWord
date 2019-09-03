<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Theme;

class FontScheme
{
    private $name;
    private $headingFonts;
    private $bodyFonts;

    public function __construct(string $name = 'Office', HeadingFonts $headingFonts = null, BodyFonts $bodyFonts = null)
    {
        $this->name = $name;
        $this->headingFonts = $headingFonts ?? new HeadingFonts();
        $this->bodyFonts = $bodyFonts ?? new BodyFonts();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHeadingFonts(): HeadingFonts
    {
        return clone $this->headingFonts;
    }

    public function getBodyFonts(): BodyFonts
    {
        return clone $this->bodyFonts;
    }
}
