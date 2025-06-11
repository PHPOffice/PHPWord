<?php

declare(strict_types=1);

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\Element\CheckBox as CheckBoxElement;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\Word2007\Element\CheckBox;
use PHPUnit\Framework\TestCase;

class CheckBoxTest extends TestCase
{
    /**
     * @dataProvider checkBoxColorProvider
     */
    public function testCheckBoxGeneratesCorrectXml(
        string $expectedCheckedAttribute
    ): void {
        // Arrange
        $xmlWriter = new XMLWriter();

        $checkBoxElement = new CheckBoxElement('test', 'test');
        $checkBox = new CheckBox($xmlWriter, $checkBoxElement);

        // Act
        $checkBox->write();
        $output = $xmlWriter->getData();

        // Assert
        self::assertStringContainsString($expectedCheckedAttribute, $output, 'Default checked should be applied.');
    }

    /**
     * Data provider for testing different combinations of background and border colors.
     */
    public static function checkBoxColorProvider(): array
    {
        return [
            'Default checked' => [
                'w:val="1"',
            ],
            'Default unchecked' => [
                'w:val="0"',
            ],
        ];
    }
}
