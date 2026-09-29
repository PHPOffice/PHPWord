<?php

namespace PhpOffice\PhpWord\Tests\Writer\Word2007\Part;

use PhpOffice\PhpWord\Element\Chart;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Writer\Word2007\Part\Chart as ChartWriter;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ChartNullHandlingTest extends TestCase
{
    /**
     * Test that the Style class correctly sets/gets the property.
     */
    public function testSetGetDisplayBlanksAs(): void
    {
        // Note: Pass empty arrays for categories/values to avoid constructor errors
        $chart = new Chart('line', [], []);
        $style = $chart->getStyle();

        // 1. Default should be 'gap'
        self::assertEquals('gap', $style->getDisplayBlanksAs());

        // 2. Test setting 'span'
        $style->setDisplayBlanksAs('span');
        self::assertEquals('span', $style->getDisplayBlanksAs());

        // 3. Test invalid value (should remain 'span')
        $style->setDisplayBlanksAs('invalid_option');
        self::assertEquals('span', $style->getDisplayBlanksAs());
    }

    public function testWriteChartHandlesNullsAndGaps(): void
    {
        // 1. Setup Data
        $categories = ['Jan', 'Feb', 'Mar'];
        $values = [10, null, 20];
        $seriesNames = ['My Series']; // <--- ADD THIS

        // 2. Create Chart with Series Name
        $chart = new Chart('line', $categories, $values, $seriesNames);
        $chart->getStyle()->setDisplayBlanksAs('gap');

        // 3. Setup Writer
        $xmlWriter = new XMLWriter();
        $chartWriter = new ChartWriter();

        // Mock the parent writer
        $chartWriter->setParentWriter($this->createMock(\PhpOffice\PhpWord\Writer\Word2007::class));

        // 4. Inject Chart into Writer
        $reflectionWriter = new ReflectionClass(ChartWriter::class);
        $elementProperty = $reflectionWriter->getProperty('element');
        $elementProperty->setAccessible(true);
        $elementProperty->setValue($chartWriter, $chart);

        // 5. Run
        $method = $reflectionWriter->getMethod('writeChart');
        $method->setAccessible(true);
        $method->invokeArgs($chartWriter, [$xmlWriter]);

        $xml = $xmlWriter->getData();

        // --- ASSERTIONS ---
        self::assertStringContainsString('<c:dispBlanksAs val="gap"/>', $xml);

        // Check for the empty point (null value)
        self::assertMatchesRegularExpression('/<c:pt idx="1"\s*\/?>/', $xml);

        // Ensure no zero value was written for index 1
        self::assertDoesNotMatchRegularExpression(
            '/<c:pt idx="1"[^>]*>.*?<c:v>0<\/c:v>.*?<\/c:pt>/s',
            $xml
        );
    }
}
