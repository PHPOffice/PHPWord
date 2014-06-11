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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Element\Chart as ChartElement;

/**
 * Word2007 chart part writer: word/charts/chartx.xml
 *
 * @since 0.12.0
 * @link http://www.datypic.com/sc/ooxml/e-draw-chart_chartSpace.html
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
 */
class Chart extends AbstractPart
{
    /**
     * Chart element
     *
     * @var \PhpOffice\PhpWord\Element\Chart $element
     */
    private $element;

    private $types = array(
        'pie'       => array('type' => 'pieChart', 'colors' => 1),
        'doughnut'  => array('type' => 'doughnutChart', 'colors' => 1, 'hole' => 75),
        'bar'       => array('type' => 'barChart', 'colors' => 0, 'axes' => true, 'bar' => 'col'),
        'line'      => array('type' => 'lineChart', 'colors' => 0, 'axes' => true),
        'area'      => array('type' => 'areaChart', 'colors' => 0, 'axes' => true),
        'radar'     => array('type' => 'radarChart', 'colors' => 0, 'axes' => true, 'radar' => 'standard'),
        'scatter'   => array('type' => 'scatterChart', 'colors' => 0, 'axes' => true, 'scatter' => 'marker'),
    );

    private $options = array();

    /**
     * Set chart element
     */
    public function setElement(ChartElement $element)
    {
        $this->element = $element;
    }

    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('c:chartSpace');
        $xmlWriter->writeAttribute('xmlns:c', 'http://schemas.openxmlformats.org/drawingml/2006/chart');
        $xmlWriter->writeAttribute('xmlns:a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');

        $xmlWriter->writeBlock('c:date1904', 'val', 1);
        $xmlWriter->writeBlock('c:lang', 'val', 'en-US');
        $xmlWriter->writeBlock('c:roundedCorners', 'val', 0);

        $this->writeChart($xmlWriter);
        $this->writeShape($xmlWriter);


        $xmlWriter->endElement(); // c:chartSpace

        return $xmlWriter->getData();
    }

    /**
     * Write chart
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_Chart.html
     */
    private function writeChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:chart');

        $xmlWriter->writeBlock('c:autoTitleDeleted', 'val', 1);
        $xmlWriter->writeBlock('c:dispBlanksAs', 'val', 'zero');

        $this->writePlotArea($xmlWriter);
        // $this->writeLegend($xmlWriter);

        $xmlWriter->endElement(); // c:chart
    }

    /**
     * Write plot area
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_PlotArea.html
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_PieChart.html
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_DoughnutChart.html
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_BarChart.html
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_LineChart.html
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_AreaChart.html
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_RadarChart.html
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_ScatterChart.html
     */
    private function writePlotArea(XMLWriter $xmlWriter)
    {
        $type = $this->element->getType();
        $this->options = $this->types[$type];

        $xmlWriter->startElement('c:plotArea');
        $xmlWriter->writeElement('c:layout');

        // Chart
        $xmlWriter->startElement('c:' . $this->options['type']);

        $xmlWriter->writeBlock('c:varyColors', 'val', $this->options['colors']);
        if (isset($this->options['hole'])) {
            $xmlWriter->writeBlock('c:holeSize', 'val', $this->options['hole']);
        }
        if (isset($this->options['bar'])) {
            $xmlWriter->writeBlock('c:barDir', 'val', $this->options['bar']); // bar|col
        }
        if (isset($this->options['radar'])) {
            $xmlWriter->writeBlock('c:radarStyle', 'val', $this->options['radar']);
        }
        if (isset($this->options['scatter'])) {
            $xmlWriter->writeBlock('c:scatterStyle', 'val', $this->options['scatter']);
        }
        if (isset($this->options['axes'])) {
            $xmlWriter->writeBlock('c:axId', 'val', 1);
            $xmlWriter->writeBlock('c:axId', 'val', 2);
        }

        // Series
        $this->writeSeries($xmlWriter, isset($this->options['scatter']));

        $xmlWriter->endElement(); // chart type

        // Axes
        if (isset($this->options['axes'])) {
            $this->writeAxis($xmlWriter, 'cat');
            $this->writeAxis($xmlWriter, 'val');
        }

        $xmlWriter->endElement(); // c:plotArea
    }

    /**
     * Write series
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param bool $scatter
     */
    private function writeSeries(XMLWriter $xmlWriter, $scatter = false)
    {
        $xmlWriter->startElement('c:ser');

        $xmlWriter->writeBlock('c:idx', 'val', 0);
        $xmlWriter->writeBlock('c:order', 'val', 0);

        if (isset($this->options['scatter'])) {
            $xmlWriter->startElement('c:spPr');
            $xmlWriter->startElement('a:ln');
            $xmlWriter->writeElement('a:noFill');
            $xmlWriter->endElement(); // a:ln
            $xmlWriter->endElement(); // c:spPr
        }

        if ($scatter === true) {
            $this->writeSeriesItems($xmlWriter, 'xVal', $this->element->getLabels());
            $this->writeSeriesItems($xmlWriter, 'yVal', $this->element->getData());
        } else {
            $this->writeSeriesItems($xmlWriter, 'cat', $this->element->getLabels());
            $this->writeSeriesItems($xmlWriter, 'val', $this->element->getData());
        }

        $xmlWriter->endElement(); // c:ser
    }

    /**
     * Write series items
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $type
     * @param array $values
     */
    private function writeSeriesItems(XMLWriter $xmlWriter, $type, $values)
    {
        $types = array(
            'cat' => array('c:cat', 'c:strLit'),
            'val' => array('c:val', 'c:numLit'),
            'xVal' => array('c:xVal', 'c:strLit'),
            'yVal' => array('c:yVal', 'c:numLit'),
        );
        list($itemType, $itemLit) = $types[$type];

        $xmlWriter->startElement($itemType);
        $xmlWriter->startElement($itemLit);

        $index = 0;
        foreach ($values as $value) {
            $xmlWriter->startElement('c:pt');
            $xmlWriter->writeAttribute('idx', $index);

            $xmlWriter->startElement('c:v');
            $xmlWriter->writeRaw($value);
            $xmlWriter->endElement(); // c:v

            $xmlWriter->endElement(); // c:pt
            $index++;
        }

        $xmlWriter->endElement(); // $itemLit
        $xmlWriter->endElement(); // $itemType
    }

    /**
     * Write axis
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $type
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_CatAx.html
     */
    private function writeAxis(XMLWriter $xmlWriter, $type)
    {
        $types = array(
            'cat' => array('c:catAx', 1, 'b', 2),
            'val' => array('c:valAx', 2, 'l', 1),
        );
        list($axisType, $axisId, $axisPos, $axisCross) = $types[$type];

        $xmlWriter->startElement($axisType);

        $xmlWriter->writeBlock('c:axId', 'val', $axisId);
        $xmlWriter->writeBlock('c:axPos', 'val', $axisPos);
        $xmlWriter->writeBlock('c:crossAx', 'val', $axisCross);
        $xmlWriter->writeBlock('c:auto', 'val', 1);

        if (isset($this->options['axes'])) {
            $xmlWriter->writeBlock('c:delete', 'val', 0);
            $xmlWriter->writeBlock('c:majorTickMark', 'val', 'none');
            $xmlWriter->writeBlock('c:minorTickMark', 'val', 'none');
            $xmlWriter->writeBlock('c:tickLblPos', 'val', 'none'); // nextTo
            // $xmlWriter->writeBlock('c:crosses', 'val', 'autoZero');
        }
        if (isset($this->options['radar'])) {
            $xmlWriter->writeElement('c:majorGridlines');
        }

        $xmlWriter->startElement('c:scaling');
        $xmlWriter->writeBlock('c:orientation', 'val', 'minMax');
        $xmlWriter->endElement(); // c:scaling

        $xmlWriter->startElement('c:spPr');
        $xmlWriter->writeElement('a:noFill');

        $xmlWriter->startElement('a:ln');
        $xmlWriter->startElement('a:solidFill');
        // $xmlWriter->writeBlock('a:srgbClr', 'val', '0FF000');
        $xmlWriter->endElement(); // a:solidFill
        $xmlWriter->endElement(); // a:ln

        $xmlWriter->endElement(); // c:spPr

        $xmlWriter->endElement(); // $axisType
    }

    /**
     * Write legend
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_Legend.html
     */
    private function writeLegend(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:legend');
        $xmlWriter->writeElement('c:layout');
        $xmlWriter->writeBlock('c:legendPos', 'val', 'r');
        $xmlWriter->endElement(); // c:legend
    }

    /**
     * Write shape
     *
     * @link http://www.datypic.com/sc/ooxml/t-a_CT_ShapeProperties.html
     */
    private function writeShape(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:spPr');
        $xmlWriter->startElement('a:ln');
        $xmlWriter->writeElement('a:noFill');
        $xmlWriter->endElement(); // a:ln
        $xmlWriter->endElement(); // c:spPr
    }
}
