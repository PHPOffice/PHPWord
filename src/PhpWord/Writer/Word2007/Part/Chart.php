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

        $xmlWriter->writeBlock('c:roundedCorners', 'val', '0');

        $xmlWriter->startElement('c:chart');
        $this->writePlotArea($xmlWriter);
        $xmlWriter->endElement(); // c:chart

        $xmlWriter->endElement(); // c:chartSpace

        return $xmlWriter->getData();
    }

    /**
     * Write plot area
     */
    private function writePlotArea(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:plotArea');

        $method = "write{$this->element->getType()}Chart";
        $this->$method($xmlWriter);

        $xmlWriter->endElement(); // c:plotArea
    }

    /**
     * Write pie chart
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_PieChart.html
     */
    private function writePieChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:pieChart');

        $xmlWriter->writeBlock('c:varyColors', 'val', '1');

        $this->writeSeries($xmlWriter);

        $xmlWriter->endElement(); // c:pie3DChart
    }

    /**
     * Write doughnut chart
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_DoughnutChart.html
     */
    private function writeDoughnutChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:doughnutChart');

        $xmlWriter->writeBlock('c:varyColors', 'val', '1');
        $xmlWriter->writeBlock('c:holeSize', 'val', '75');

        $this->writeSeries($xmlWriter);

        $xmlWriter->endElement(); // c:doughnutChart
    }

    /**
     * Write bar chart
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_BarChart.html
     */
    private function writeBarChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:barChart');

        $xmlWriter->writeBlock('c:varyColors', 'val', '0');
        $xmlWriter->writeBlock('c:barDir', 'val', 'col'); // bar|col
        $xmlWriter->writeBlock('c:axId', 'val', '1');
        $xmlWriter->writeBlock('c:axId', 'val', '2');

        $this->writeSeries($xmlWriter);

        $xmlWriter->endElement(); // c:barChart

        // Axes
        $this->writeAxis($xmlWriter, 'cat');
        $this->writeAxis($xmlWriter, 'val');
    }

    /**
     * Write line chart
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_LineChart.html
     */
    private function writeLineChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:lineChart');

        $xmlWriter->writeBlock('c:varyColors', 'val', '0');
        $xmlWriter->writeBlock('c:axId', 'val', '1');
        $xmlWriter->writeBlock('c:axId', 'val', '2');

        $this->writeSeries($xmlWriter);

        $xmlWriter->endElement(); // c:lineChart

        // Axes
        $this->writeAxis($xmlWriter, 'cat');
        $this->writeAxis($xmlWriter, 'val');
    }

    /**
     * Write area chart
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_AreaChart.html
     */
    private function writeAreaChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:areaChart');

        $xmlWriter->writeBlock('c:varyColors', 'val', '0');
        $xmlWriter->writeBlock('c:axId', 'val', '1');
        $xmlWriter->writeBlock('c:axId', 'val', '2');

        $this->writeSeries($xmlWriter);

        $xmlWriter->endElement(); // c:areaChart

        // Axes
        $this->writeAxis($xmlWriter, 'cat');
        $this->writeAxis($xmlWriter, 'val');
    }

    /**
     * Write radar chart
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_RadarChart.html
     */
    private function writeRadarChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:radarChart');

        $xmlWriter->writeBlock('c:varyColors', 'val', '0');
        $xmlWriter->writeBlock('c:radarStyle', 'val', 'standard');
        $xmlWriter->writeBlock('c:axId', 'val', '1');
        $xmlWriter->writeBlock('c:axId', 'val', '2');

        $this->writeSeries($xmlWriter);

        $xmlWriter->endElement(); // c:radarChart

        // Axes
        $this->writeAxis($xmlWriter, 'cat');
        $this->writeAxis($xmlWriter, 'val');
    }

    /**
     * Write scatter chart
     *
     * @link http://www.datypic.com/sc/ooxml/t-draw-chart_CT_ScatterChart.html
     */
    private function writeScatterChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->startElement('c:scatterChart');

        $xmlWriter->writeBlock('c:varyColors', 'val', '0');
        $xmlWriter->writeBlock('c:scatterStyle', 'val', 'lineMarker');
        $xmlWriter->writeBlock('c:axId', 'val', '1');
        $xmlWriter->writeBlock('c:axId', 'val', '2');

        $this->writeSeries($xmlWriter, true);

        $xmlWriter->endElement(); // c:scatterChart

        // Axes
        $this->writeAxis($xmlWriter, 'cat');
        $this->writeAxis($xmlWriter, 'val');
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

        $xmlWriter->writeBlock('c:idx', 'val', '0');
        $xmlWriter->writeBlock('c:order', 'val', '0');

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
            'cat' => array('c:cat', 'c:strRef', 'c:strCache'),
            'val' => array('c:val', 'c:numRef', 'c:numCache'),
            'xVal' => array('c:xVal', 'c:strRef', 'c:strCache'),
            'yVal' => array('c:yVal', 'c:numRef', 'c:numCache'),
        );
        list($itemType, $itemRef, $itemCache) = $types[$type];

        $xmlWriter->startElement($itemType);
        $xmlWriter->startElement($itemRef);
        $xmlWriter->startElement($itemCache);

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

        $xmlWriter->endElement(); // $itemCache

        $xmlWriter->endElement(); // $itemRef
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
            'cat' => array('c:catAx', '1', 'b', '2'),
            'val' => array('c:valAx', '2', 'l', '1'),
        );
        list($axisType, $axisId, $axisPos, $axisCross) = $types[$type];

        $xmlWriter->startElement($axisType);

        $xmlWriter->writeBlock('c:axId', 'val', $axisId);
        $xmlWriter->writeBlock('c:axPos', 'val', $axisPos);
        $xmlWriter->writeBlock('c:crossAx', 'val', $axisCross);

        $xmlWriter->startElement('c:scaling');
        $xmlWriter->writeBlock('c:orientation', 'val', 'minMax');
        $xmlWriter->endElement(); // c:scaling

        $xmlWriter->startElement('c:spPr');
        $xmlWriter->writeElement('a:noFill');
        $xmlWriter->startElement('a:ln');
        $xmlWriter->startElement('a:solidFill');
        $xmlWriter->writeBlock('a:srgbClr', 'val', '0FB7');
        $xmlWriter->endElement(); // a:solidFill
        $xmlWriter->endElement(); // a:ln
        $xmlWriter->endElement(); // c:crossAx

        $xmlWriter->endElement(); // $type
    }
}
