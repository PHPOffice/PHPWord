<?php

declare(strict_types=1);

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\TextBox as TextBoxElement;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\TextBox as TextBoxStyle;
use PhpOffice\PhpWord\Writer\Word2007\Element\TextBox;
use PHPUnit\Framework\TestCase;

class TextBoxTest extends TestCase
{
    /**
     * @dataProvider textBoxColorProvider
     */
    public function testTextBoxGeneratesCorrectXml(
        ?string $bgColor,
        ?string $borderColor,
        string $expectedFillColorAttribute,
        string $expectedBorderColorAttribute
    ): void {
        // Arrange
        $xmlWriter = new XMLWriter();
        $style = new TextBoxStyle();

        if ($bgColor !== null) {
            $style->setBgColor($bgColor);
        }

        if ($borderColor !== null) {
            $style->setBorderColor($borderColor);
        }

        $textBoxElement = new TextBoxElement($style);
        $textBox = new TextBox($xmlWriter, $textBoxElement);

        // Act
        $textBox->write();
        $output = $xmlWriter->getData();

        // Assert
        self::assertStringContainsString($expectedFillColorAttribute, $output, 'Background color should be applied.');
        self::assertStringContainsString($expectedBorderColorAttribute, $output, 'Border color should be applied correctly.');
    }

    /**
     * Data provider for testing different combinations of background and border colors.
     */
    public static function textBoxColorProvider(): array
    {
        return [
            // Case 1: Background color set, border color set
            'With both colors' => [
                '#FF0000',
                '#000000',
                'fillcolor="#FF0000"',
                'stroke color="#000000"',
            ],
            // Case 2: Background color set, no border color
            'With background only' => [
                '#00FF00',
                null,
                'fillcolor="#00FF00"',
                'stroked="f" strokecolor="white"',
            ],
            // Case 3: No background color, border color set
            'With border only' => [
                null,
                '#123456',
                'filled="f"',
                'stroke color="#123456"',
            ],
            // Case 4: Neither background nor border color set
            'Without any colors' => [
                null,
                null,
                'filled="f"',
                'stroked="f" strokecolor="white"',
            ],
        ];
    }
}
