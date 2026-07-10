<?php

namespace PhpWord\Writer;

use PhpWord\PhpWord;
use PhpWord\Shared\Html;
use PhpWord\Style\Font;
use PhpWord\Style\Paragraph;
use PhpWord\Style\Section;

class Wps extends AbstractWriter
{
    public function write(PhpWord $phpWord)
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('wps');
        $xmlWriter->startElement('body');

        foreach ($phpWord->getSections() as $section) {
            $this->writeSection($xmlWriter, $section);
        }

        $xmlWriter->endElement();
        $xmlWriter->endElement();
        $xmlWriter->endDocument();

        return $xmlWriter->getData();
    }

    private function writeSection($xmlWriter, $section)
    {
        $xmlWriter->startElement('section');
        $xmlWriter->startElement('header');
        $xmlWriter->endElement();
        $xmlWriter->startElement('footer');
        $xmlWriter->endElement();

        foreach ($section->getElements() as $element) {
            if ($element instanceof Paragraph) {
                $this->writeParagraph($xmlWriter, $element);
            } elseif ($element instanceof TextRun) {
                $this->writeTextRun($xmlWriter, $element);
            }
        }

        $xmlWriter->endElement();
    }

    private function writeParagraph($xmlWriter, $paragraph)
    {
        $xmlWriter->startElement('p');
        $xmlWriter->startElement('r');

        foreach ($paragraph->getElements() as $element) {
            if ($element instanceof Text) {
                $xmlWriter->startElement('t');
                $xmlWriter->text($element->getText());
                $xmlWriter->endElement();
            }
        }

        $xmlWriter->endElement();
        $xmlWriter->endElement();
    }

    private function writeTextRun($xmlWriter, $textRun)
    {
        foreach ($textRun->getElements() as $element) {
            if ($element instanceof Text) {
                $xmlWriter->startElement('t');
                $xmlWriter->text($element->getText());
                $xmlWriter->endElement();
            }
        }
    }
}
