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
     * @dataProvider checkBoxCheckedProvider
     */
    public function testCheckBoxGeneratesCorrectXml(
        bool $checked,
        string $expectedCheckedAttribute
    ): void {
        // Arrange
        $xmlWriter = new XMLWriter();

        $checkBoxElement = new CheckBoxElement('test', 'test');
        $checkBoxElement->setDefaultChecked($checked);

        $checkBox = new CheckBox($xmlWriter, $checkBoxElement);

        // Act
        $checkBox->write();
        $output = $xmlWriter->getData();

        // Assert
        self::assertStringContainsString($expectedCheckedAttribute, $output, 'Default checked should be applied.');
    }

    /**
     * Data provider for testing checked state.
     */
    public static function checkBoxCheckedProvider(): array
    {
        return [
            'Default checked' => [
                'checked' => true,
                'w:default w:val="1"',
            ],
            'Default unchecked' => [
                'checked' => false,
                'w:default w:val="0"',
            ],
        ];
    }
}
