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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\Element\Chart as ChartElement;
use PhpOffice\PhpWord\Shared\DateConverter;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 chart part writer: word/charts/chartx.xml
 *
 * @since 0.12.0
 * @see  http://www.datypic.com/sc/ooxml/e-draw-chart_chartSpace.html
 */
class Chart extends AbstractPart
{
    /**
     * Chart element
     *
     * @var \PhpOffice\PhpWord\Element\Chart
     */
    private $element;

    /**
     * Type definition
     *
     * @var array
     */
    private $types = array(
        'pie'                    => array('type' => 'pie', 'colors' => 1),
        'doughnut'               => array('type' => 'doughnut', 'colors' => 1, 'hole' => 75, 'no3d' => true),
        'bar'                    => array('type' => 'bar', 'colors' => 0, 'axes' => true, 'bar' => 'bar', 'grouping' => 'clustered'),
        'stacked_bar'            => array('type' => 'bar', 'colors' => 0, 'axes' => true, 'bar' => 'bar', 'grouping' => 'stacked'),
        'percent_stacked_bar'    => array('type' => 'bar', 'colors' => 0, 'axes' => true, 'bar' => 'bar', 'grouping' => 'percentStacked'),
        'column'                 => array('type' => 'bar', 'colors' => 0, 'axes' => true, 'bar' => 'col', 'grouping' => 'clustered'),
        'stacked_column'         => array('type' => 'bar', 'colors' => 0, 'axes' => true, 'bar' => 'col', 'grouping' => 'stacked'),
        'percent_stacked_column' => array('type' => 'bar', 'colors' => 0, 'axes' => true, 'bar' => 'col', 'grouping' => 'percentStacked'),
        'line'                   => array('type' => 'line', 'colors' => 0, 'axes' => true),
        'area'                   => array('type' => 'area', 'colors' => 0, 'axes' => true),
        'radar'                  => array('type' => 'radar', 'colors' => 0, 'axes' => true, 'radar' => 'standard', 'no3d' => true),
        'scatter'                => array('type' => 'scatter', 'colors' => 0, 'axes' => true, 'scatter' => 'smoothMarker', 'no3d' => true),
    );

    /**
     * Chart options
     *
     * @var array
     */
    private $options = array();

    /**
     * Set chart element.
     *
     * @param \PhpOffice\PhpWord\Element\Chart $element
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
        $this->writeChart($xmlWriter);
        $this->writeShape($xmlWriter);

        $xmlWriter->endElement(); // c:chartSpace


        return $xmlWriter->getData();
    }

    /**
     * Write chart
     *
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_Chart.html
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     */
    private function writeChart(XMLWriter $xmlWriter)
    {
        $xmlWriter->writeElementBlock('c:date1904', 'val', 0);
        $xmlWriter->startElement('c:chart');

        $this->writePlotArea($xmlWriter);

        $xmlWriter->endElement(); // c:chart
    }

    /**
     * Write plot area.
     *
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_PlotArea.html
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_PieChart.html
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_DoughnutChart.html
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_BarChart.html
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_LineChart.html
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_AreaChart.html
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_RadarChart.html
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_ScatterChart.html
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     */
    private function writePlotArea(XMLWriter $xmlWriter)
    {
        $type = $this->element->getType();
        $style = $this->element->getStyle();
        $this->options = $this->types[$type];

        $title = $style->getTitle();
        $showLegend = $style->isShowLegend();
        $legendPosition = $style->getLegendPosition();

        //Chart title
        if ($title) {
            $xmlWriter->startElement('c:title');
            $xmlWriter->startElement('c:tx');
            $xmlWriter->startElement('c:rich');
            $xmlWriter->writeRaw('
                    <a:bodyPr/>
                    <a:lstStyle/>
                    <a:p>
                    <a:pPr>
                    <a:defRPr/></a:pPr><a:r><a:rPr/><a:t>' . $title . '</a:t></a:r>
                    <a:endParaRPr/>
                    </a:p>');
            $xmlWriter->endElement(); // c:rich
            $xmlWriter->endElement(); // c:tx
            $xmlWriter->endElement(); // c:title
        } else {
            $xmlWriter->writeElementBlock('c:autoTitleDeleted', 'val', 1);
        }

        //Chart legend
        if ($showLegend) {
            $xmlWriter->startElement('c:legend');
            $xmlWriter->writeElementBlock('c:legendPos', 'val', $legendPosition);
            // by #rat

            if ($style->getLegendPositionInBlock()) {
                $xmlWriter->startElement('c:layout');
                $xmlWriter->startElement('c:manualLayout');
                //                $xmlWriter->writeElementBlock('c:layoutTarget', 'val', 'inner');

                $xmlWriter->writeElementBlock('c:xMode', 'val', $style->getLegendPositionInBlock()->getXMode());
                $xmlWriter->writeElementBlock('c:yMode', 'val', $style->getLegendPositionInBlock()->getYMode());
                if ($style->getLegendPositionInBlock()->getAxisX()) {
                    $xmlWriter->writeElementBlock('c:x', 'val', $style->getLegendPositionInBlock()->getAxisX());
                }
                if ($style->getLegendPositionInBlock()->getAxisY()) {
                    $xmlWriter->writeElementBlock('c:y', 'val', $style->getLegendPositionInBlock()->getAxisY());
                }
                if ($style->getLegendPositionInBlock()->getWidth()) {
                    $xmlWriter->writeElementBlock('c:w', 'val', $style->getLegendPositionInBlock()->getWidth());
                }
                if ($style->getLegendPositionInBlock()->getHeight()) {
                    $xmlWriter->writeElementBlock('c:h', 'val', $style->getLegendPositionInBlock()->getHeight());
                }

                $xmlWriter->endElement(); // c:manualLayout
                $xmlWriter->endElement(); // c:layout
                // by #rat
            }

            $this->writeLabelStyle($xmlWriter, $style->getTextLegendColor(), $style->getTextLegendSize());
            $xmlWriter->endElement(); // c:legend
        }

        $xmlWriter->startElement('c:plotArea');
        // by #rat
        $xmlWriter->startElement('c:layout');
        if ($style->getChartPositionInBlock()) {
            $xmlWriter->startElement('c:manualLayout');
            $xmlWriter->writeElementBlock('c:layoutTarget', 'val', 'inner');
            $xmlWriter->writeElementBlock('c:xMode', 'val', $style->getChartPositionInBlock()->getXMode());
            $xmlWriter->writeElementBlock('c:yMode', 'val', $style->getChartPositionInBlock()->getYMode());
            $xmlWriter->writeElementBlock('c:x', 'val', $style->getChartPositionInBlock()->getAxisX());
            $xmlWriter->writeElementBlock('c:y', 'val', $style->getChartPositionInBlock()->getAxisY());
            $xmlWriter->writeElementBlock('c:w', 'val', $style->getChartPositionInBlock()->getWidth());
            $xmlWriter->writeElementBlock('c:h', 'val', $style->getChartPositionInBlock()->getHeight());
            $xmlWriter->endElement(); // c:manualLayout
        }
        $xmlWriter->endElement(); // c:layout
        // by #rat

        // Chart
        $chartType = $this->options['type'];
        $chartType .= $style->is3d() && !isset($this->options['no3d']) ? '3D' : '';
        $chartType .= 'Chart';
        $xmlWriter->startElement("c:{$chartType}");

        $xmlWriter->writeElementBlock('c:varyColors', 'val', $this->options['colors']);
        if ($type == 'area') {
            $xmlWriter->writeElementBlock('c:grouping', 'val', 'standard');
        }
        if (isset($this->options['hole'])) {
            // by #rat
            $holeSize = (!empty($style->getHoleSize())) ? $style->getHoleSize() : $this->options['hole'];
            $xmlWriter->writeElementBlock('c:holeSize', 'val', $holeSize);
        }
        if (isset($this->options['bar'])) {
            $xmlWriter->writeElementBlock('c:barDir', 'val', $this->options['bar']); // bar|col
            $xmlWriter->writeElementBlock('c:grouping', 'val', $this->options['grouping']); // 3d; standard = percentStacked
        }
        if (isset($this->options['radar'])) {
            $xmlWriter->writeElementBlock('c:radarStyle', 'val', $this->options['radar']);
        }
        if (isset($this->options['scatter'])) {
            $xmlWriter->writeElementBlock('c:scatterStyle', 'val', $this->options['scatter']);
        }

        // Series
        $this->writeSeries($xmlWriter, isset($this->options['scatter']));

        if ($style->getLineChartGapWidth()) {
            $xmlWriter->writeElementBlock("c:gapWidth", 'val',  $style->getLineChartGapWidth());
        }

        $xmlWriter->writeElementBlock('c:overlap', 'val', '100');

        // Axes
        if (isset($this->options['axes'])) {
            $xmlWriter->writeElementBlock('c:axId', 'val', 1);
            $xmlWriter->writeElementBlock('c:axId', 'val', 2);
        }

        $xmlWriter->endElement(); // chart type

        // Axes
        if (isset($this->options['axes'])) {
            $this->writeAxis($xmlWriter, 'cat');
            $this->writeAxis($xmlWriter, 'val');
        }

        $xmlWriter->endElement(); // c:plotArea
    }

    /**
     * Write series.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param bool $scatter
     */
    private function writeSeries(XMLWriter $xmlWriter, $scatter = false)
    {
        $series = $this->element->getSeries();
        $style = $this->element->getStyle();
        $colors = $style->getColors();

        $index = 0;
        $colorIndex = 0;
        foreach ($series as $seriesItem) {
            if ($style->isDate()) {
                $categories = DateConverter::officeDateFormat($seriesItem['categories']) ;
            } else {
                $categories = $seriesItem['categories'];
            }

            $values = $seriesItem['values'];

            $xmlWriter->startElement('c:ser');

            // by #rat
            if ($scatter || $this->options['type'] == 'line') {
                if ($style->isLineGradient()) {
                    $this->addGradient($xmlWriter, $colors);
                } else {
                    $xmlWriter->startElement('c:spPr');
                    $xmlWriter->startElement('a:ln');
                    $xmlWriter->writeAttribute('w',  $style->getLineWidth());
                    $xmlWriter->startElement('a:solidFill');
                    $xmlWriter->writeElementBlock('a:srgbClr', 'val', $colors[$index % count($colors)]);
                    $xmlWriter->endElement(); // a:solidFill
                    $xmlWriter->endElement(); // a:ln
                    $xmlWriter->writeElement('a:effectLst');
                    $xmlWriter->endElement(); // c:spPr
                }
            } else {
                $xmlWriter->startElement('c:spPr');
                $xmlWriter->startElement('a:solidFill');
                $xmlWriter->writeElementBlock('a:srgbClr', 'val', $colors[$index % count($colors)]);
                $xmlWriter->endElement(); // a:solidFill

                if ($style->isSchemaSeparator() === true) {
                    $this->addSchemaSeparator($xmlWriter, $style);
                } else {
                    $xmlWriter->writeElementBlock('a:ln', 'w', $style->getLineWidth());
                }
                $xmlWriter->writeElement('a:effectLst');
                $xmlWriter->endElement(); // c:spPr
            }
            // by #rat

            $xmlWriter->startElement('c:marker');
            if ($style->isShowMarker()) {
                $xmlWriter->writeElementBlock('a:symbol', 'val', $style->getMarkerShape());
                $xmlWriter->writeElementBlock('a:size', 'val', $style->getMarkerSize());
                $xmlWriter->startElement('a:spPr');

                $xmlWriter->startElement('a:solidFill');
                $xmlWriter->writeElementBlock('a:srgbClr', 'val', $colors[$index % count($colors)]);
                $xmlWriter->endElement(); // a:solidFill

                $xmlWriter->startElement('a:ln');
                $xmlWriter->writeAttribute('w', 9525);
                $xmlWriter->writeElement('a:noFill');
                $xmlWriter->endElement(); // a:ln

                $xmlWriter->writeElement('a:effectLst');
                $xmlWriter->endElement(); // a:spPr
            } else {
                $xmlWriter->writeElementBlock('a:symbol', 'val', 'none');
            }
            $xmlWriter->endElement(); // c:marker
            // by #rat

            $xmlWriter->writeElementBlock('c:idx', 'val', $index);
            $xmlWriter->writeElementBlock('c:order', 'val', $index);

            if (!is_null($seriesItem['name']) && $seriesItem['name'] != '') {
                $xmlWriter->startElement('c:tx');
                $xmlWriter->startElement('c:strRef');
                $xmlWriter->startElement('c:strCache');
                $xmlWriter->writeElementBlock('c:ptCount', 'val', 1);
                $xmlWriter->startElement('c:pt');
                $xmlWriter->writeAttribute('idx', $index);
                $xmlWriter->startElement('c:v');
                $xmlWriter->writeRaw($seriesItem['name']);
                $xmlWriter->endElement(); // c:v
                $xmlWriter->endElement(); // c:pt
                $xmlWriter->endElement(); // c:strCache
                $xmlWriter->endElement(); // c:strRef
                $xmlWriter->endElement(); // c:tx
            }

            // The c:dLbls was added to make word charts look more like the reports in SurveyGizmo
            // This section needs to be made configurable before a pull request is made
            $xmlWriter->startElement('c:dLbls');

            if ($style->getDataLabelOptions()['showVal']) {
                $formatCode = (is_string($style->getFormat())) ? $style->getFormatPattern() : 'General';
                if ($formatCode == '#\ ##0') {
                    $xmlWriter->writeElementBlock('c:numFmt', ['formatCode' => '#,##0', 'sourceLinked' => 0]);
                }

                $xmlWriter->startElement('c:txPr');
                $xmlWriter->startElement('a:bodyPr');
                $xmlWriter->writeAttribute('wrap', "square");
                $xmlWriter->writeAttribute('lIns', '38100');
                $xmlWriter->writeAttribute('tIns', '19050');
                $xmlWriter->writeAttribute('rIns', '38100');
                $xmlWriter->writeAttribute('bIns', '19050');
                $xmlWriter->writeAttribute('anchor', 'ctr');
                $xmlWriter->writeElement('a:spAutoFit');
                $xmlWriter->endElement(); // a:bodyPr
                $xmlWriter->startElement('a:p');
                $xmlWriter->startElement('a:pPr');
                $xmlWriter->startElement('a:defRPr');
                $xmlWriter->writeAttribute('sz', $style->getValSize());
                $xmlWriter->startElement('a:solidFill');
                if ($style->getValColor() == 'bg1') {
                    $xmlWriter->writeElementBlock('a:schemeClr', 'val', $style->getValColor());
                } else {
                    $xmlWriter->writeElementBlock('a:srgbClr', 'val', $style->getValColor());
                }
                $xmlWriter->endElement(); // a:solidFill
                $xmlWriter->endElement(); // a:defRPr
                $xmlWriter->endElement(); // a:pPr
                $xmlWriter->writeElementBlock('a:endParaRPr', 'lang', "ru-RU");
                $xmlWriter->endElement(); // a:p
                $xmlWriter->endElement(); // c:txPr
            }

            if (is_string($style->getShowValList()[$index])) {
                $xmlWriter->writeElementBlock("c:dLblPos", 'val', $style->getShowValList()[$index]);
            }
            foreach ($style->getDataLabelOptions() as $option => $val) {
                if ($style->getShowValList() && $option == 'showVal' ) {
                    $xmlWriter->writeElementBlock("c:{$option}", 'val', (int) ((bool) $style->getShowValList()[$index]));
                } else {
                    $xmlWriter->writeElementBlock("c:{$option}", 'val', (int) $val);
                }
            }

            $xmlWriter->endElement(); // c:dLbls

            if ($scatter === true) {
                $this->writeSeriesItem($xmlWriter, 'xVal', $categories);
                $this->writeSeriesItem($xmlWriter, 'yVal', $values);
            } else {
                $this->writeSeriesItem($xmlWriter, 'cat', $categories);
                $this->writeSeriesItem($xmlWriter, 'val', $values);

                // check that there are colors
                if (is_array($colors) && count($colors) > 0) {
                    // assign a color to each value
                    $valueIndex = 0;
                    for ($i = 0; $i < count($values); $i++) {
                        // check that there are still enought colors
                        $xmlWriter->startElement('c:dPt');
                        $xmlWriter->writeElementBlock('c:idx', 'val', $valueIndex);

                        if (in_array($this->options['type'], ['doughnut', 'pie',]) || (!empty($this->options['grouping']) && $this->options['grouping'] == 'clustered')) {
                            $xmlWriter->startElement('c:spPr');
                            $xmlWriter->startElement('a:solidFill');
                            $xmlWriter->writeElementBlock('a:srgbClr', 'val', $colors[$colorIndex++ % count($colors)]);
                            $xmlWriter->endElement(); // a:solidFill
                            $this->addSchemaSeparator($xmlWriter, $style);
                            $xmlWriter->endElement(); // c:spPr
                        }

                        $xmlWriter->endElement(); // c:dPt
                        $valueIndex++;
                    }
                }
            }
            $xmlWriter->writeElementBlock('a:smooth', 'val', 1);

            $xmlWriter->endElement(); // c:ser
            $index++;
        }
    }

    /**
     * Write series items.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $type
     * @param array $values
     */
    private function writeSeriesItem(XMLWriter $xmlWriter, $type, $values)
    {
        $value = ($this->element->getStyle()->isDate()) ? 'numLit' : 'strLit';//#rat


        $types = array(
            'cat'  => array('c:cat', 'c:strLit'),
            'val'  => array('c:val', 'c:numLit'),
            'xVal' => array('c:xVal', "c:{$value}"),//#rat
            'yVal' => array('c:yVal', 'c:numLit'),
        );

        list($itemType, $itemLit) = $types[$type];

        $xmlWriter->startElement($itemType);
        $xmlWriter->startElement($itemLit);

        if ($this->element->getStyle()->isCatFormat() && $type == 'cat') {
            $xmlWriter->startElement('c:formatCode');
            $xmlWriter->writeRaw($this->element->getStyle()->getFormatPattern());
            $xmlWriter->endElement(); // c:formatCode
        }

        if ($this->element->getStyle()->isValFormat() && $type == 'val') {
            $xmlWriter->startElement('c:formatCode');
            $xmlWriter->writeRaw($this->element->getStyle()->getFormatPattern());
            $xmlWriter->endElement(); // c:formatCode
        }

        if ($this->element->getStyle()->isXValFormat() && $type == 'xVal') {
            $xmlWriter->startElement('c:formatCode');
            $xmlWriter->writeRaw($this->element->getStyle()->getFormatPattern());
            $xmlWriter->endElement(); // c:formatCode
        }

        if ($this->element->getStyle()->isYValFormat() && $type == 'yVal') {
            $xmlWriter->startElement('c:formatCode');
            $xmlWriter->writeRaw($this->element->getStyle()->getFormatPattern());
            $xmlWriter->endElement(); // c:formatCode
        }

        $xmlWriter->writeElementBlock('c:ptCount', 'val', count($values));

        $index = 0;

        foreach ($values as $value) {
            $xmlWriter->startElement('c:pt');
            $xmlWriter->writeAttribute('idx', $index);
            if (\PhpOffice\PhpWord\Settings::isOutputEscapingEnabled()) {
                $xmlWriter->writeElement('c:v', $value);
            } else {
                $xmlWriter->startElement('c:v');
                $xmlWriter->writeRaw($value);
                $xmlWriter->endElement(); // c:v
            }
            $xmlWriter->endElement(); // c:pt
            $index++;
        }

        $xmlWriter->endElement(); // $itemLit
        $xmlWriter->endElement(); // $itemType
    }

    /**
     * Write axis
     *
     * @see  http://www.datypic.com/sc/ooxml/t-draw-chart_CT_CatAx.html
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param string $type
     */
    private function writeAxis(XMLWriter $xmlWriter, $type)
    {
        $style = $this->element->getStyle();
        $categories = array_column($this->element->getSeries(),'categories')[0];

        if ($style->isDate()) {
            $categories = DateConverter::officeDateFormat($categories);
        }
        $series = array_column($this->element->getSeries(),'values');

        $types = array(
            'cat' => array('c:catAx', 1, 'b', 2),
            'val' => array('c:valAx', 2, 'l', 1),
        );
        list($axisType, $axisId, $axisPos, $axisCross) = $types[$type];
        // #rat
        $line = $style->showAxes();

        if ($style->getFormat() == 'time' || ($style->getFormat() && !in_array($style->getFormat(), ['percent', 'text', 'number']))) {
            $xmlWriter->startElement('c:valAx');
        } else {
            $xmlWriter->startElement($axisType);
        }

        $xmlWriter->writeElementBlock('c:axId', 'val', $axisId);
        $xmlWriter->writeElementBlock('c:axPos', 'val', $axisPos);

        $categoryAxisTitle = $style->getCategoryAxisTitle();
        $valueAxisTitle = $style->getValueAxisTitle();

        if ($axisType == 'c:catAx') {
            if (!is_null($categoryAxisTitle)) {
                $this->writeAxisTitle($xmlWriter, $categoryAxisTitle);
            }
        } elseif ($axisType == 'c:valAx') {
            if (!is_null($valueAxisTitle)) {
                $this->writeAxisTitle($xmlWriter, $valueAxisTitle);
            }
        }

        $xmlWriter->writeElementBlock('c:crossAx', 'val', $axisCross);
        $xmlWriter->writeElementBlock('c:auto', 'val', 1);

        if (isset($this->options['axes'])) {
            $sourceLinked = 1;
            $formatCode = (is_string($style->getFormat())) ? $style->getFormatPattern() : 'General';
            if ($formatCode == '#\ ##0') {
                $formatCode = '#,##0';
                $sourceLinked = 0;
            }
            $xmlWriter->writeElementBlock('c:delete', 'val', 0);
            $xmlWriter->writeElementBlock('c:majorTickMark', 'val', $style->getMajorTickPosition());
            $xmlWriter->writeElementBlock('c:minorTickMark', 'val', 'none');
            $xmlWriter->writeElementBlock('c:numFmt', ['formatCode' => $formatCode, 'sourceLinked' => $sourceLinked]);

            if ($style->showAxisLabels()) {
                if ($axisType == 'c:catAx') {
                    $this->writeLabelStyle(
                        $xmlWriter,
                        $style->getAxisLabelCategoryColor(),
                        $style->getAxisLabelCategorySize(),
                        $style->getCategoryLabelPosition()
                    );
                } else {
                    $this->writeLabelStyle(
                        $xmlWriter,
                        $style->getAxisLabelValueColor(),
                        $style->getAxisLabelValueSize(),
                        $style->getValueLabelPosition()
                    );
                }
            } else {
                $xmlWriter->writeElementBlock('c:tickLblPos', 'val', 'none');
            }
            $xmlWriter->writeElementBlock('c:crosses', 'val', 'autoZero');
        }
        if (isset($this->options['radar']) || ($type == 'cat' && $style->showGridX()) || ($type == 'val' && $style->showGridY())) {
            $xmlWriter->startElement('c:majorGridlines');
            $xmlWriter->startElement('c:spPr');
            $xmlWriter->startElement('a:ln');
            $xmlWriter->writeAttribute('w', '6350');
            $xmlWriter->writeAttribute('cap', 'flat');
            $xmlWriter->writeAttribute('cmpd', 'sng');
            $xmlWriter->writeAttribute('algn', 'ctr');
            $xmlWriter->startElement('a:solidFill');
            $xmlWriter->writeElementBlock('a:srgbClr', 'val', 'E5E7EC');
            $xmlWriter->endElement(); //solidFill
            $xmlWriter->endElement(); //ln
            $xmlWriter->writeElement('a:effectLst');
            $xmlWriter->endElement();// spPr
            $xmlWriter->endElement();// majorGridlines
        }


        $xmlWriter->startElement('c:scaling');
        $xmlWriter->writeElementBlock('c:orientation', 'val', 'minMax');

        if ($style->isAlongLength() && $type == 'cat' && count($categories) > 1) {
            $xmlWriter->writeElementBlock('c:max', 'val', $categories[array_key_last($categories)]);
            $xmlWriter->writeElementBlock('c:min', 'val', $categories[array_key_first($categories)]);
        }
        $step = 1;
        if (count($categories) == 1 && $type == 'cat' && $style->isAlongLength()) {
            if (in_array($style->getFormat(), ['hour','30_min', '5_min'])) {
                $step = 0.0416666;
            }
            $xmlWriter->writeElementBlock('c:max', 'val', $categories[0] + $step);
            $xmlWriter->writeElementBlock('c:min', 'val', $categories[0] - $step);
        }

        if ($type == 'val') {
            if ($style->getAxisValMax() !== null) {
                $xmlWriter->writeElementBlock('c:max', 'val', $style->getAxisValMax());
            }
            if ($style->getAxisValMin() !== null) {
                $xmlWriter->writeElementBlock('c:min', 'val', $style->getAxisValMin());
            }
        }

        $xmlWriter->endElement(); // c:scaling

        $this->writeShape($xmlWriter, $line);

        if ($style->getFormat() == 'time' || ($style->getFormat() == 'date' && count($series) == 1)) {
            $xmlWriter->writeElementBlock('c:crossBetween', 'val', 'between');
        }

        if ($type == 'cat') {
            if (count($categories) == 1) {
                $xmlWriter->writeElementBlock('c:majorUnit', 'val', 1*$step);
            }

            if ($this->element->getType() == 'scatter') {
                if ((count($categories) <= 12 && !in_array($style->getFormat(), ['month', 'week', 'year']))
                    || ($style->getFormat() == 'day' && $categories[array_key_last($categories)] - $categories[array_key_first($categories)] <= 10)
                    || ($style->getFormat() == 'week' && $categories[array_key_last($categories)] - $categories[array_key_first($categories)] < 7)
                ) {
                    $xmlWriter->writeElementBlock('c:majorUnit', 'val', 1);
                }
            }

            if ($style->getFormat() == 'time') {
                $xmlWriter->writeElementBlock('c:majorUnit', 'val', '0.0833333333333333');
            }
        }

        $xmlWriter->endElement(); // $axisType
    }

    /**
     * Write shape
     *
     * @see  http://www.datypic.com/sc/ooxml/t-a_CT_ShapeProperties.html
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param bool $line
     */
    private function writeShape(XMLWriter $xmlWriter, $line = false)
    {
        $xmlWriter->startElement('c:spPr');
        $xmlWriter->startElement('a:ln');
        if ($line === true) {
            $xmlWriter->writeElement('a:solidFill');
        } else {
            $xmlWriter->writeElement('a:noFill');
        }
        $xmlWriter->endElement(); // a:ln
        $xmlWriter->endElement(); // c:spPr
    }

    private function writeAxisTitle(XMLWriter $xmlWriter, $title)
    {
        $xmlWriter->startElement('c:title'); //start c:title
        $xmlWriter->startElement('c:tx'); //start c:tx
        $xmlWriter->startElement('c:rich'); // start c:rich
        $xmlWriter->writeElement('a:bodyPr');
        $xmlWriter->writeElement('a:lstStyle');
        $xmlWriter->startElement('a:p');
        $xmlWriter->startElement('a:pPr');
        $xmlWriter->writeElement('a:defRPr');
        $xmlWriter->endElement(); // end a:pPr
        $xmlWriter->startElement('a:r');
        $xmlWriter->writeElementBlock('a:rPr', 'lang', 'en-US');

        $xmlWriter->startElement('a:t');
        $xmlWriter->writeRaw($title);
        $xmlWriter->endElement(); //end a:t

        $xmlWriter->endElement(); // end a:r
        $xmlWriter->endElement(); //end a:p
        $xmlWriter->endElement(); //end c:rich
        $xmlWriter->endElement(); // end c:tx
        $xmlWriter->writeElementBlock('c:overlay', 'val', '0');
        $xmlWriter->endElement(); // end c:title
    }

    private function writeLabelStyle(XMLWriter $xmlWriter, $color, $size, $label = null)
    {
        $xmlWriter->startElement('c:txPr'); //start c:txPr
        $xmlWriter->writeElement('a:bodyPr');
        $xmlWriter->writeAttribute('rot', '-60000000');
        $xmlWriter->writeAttribute('spcFirstLastPara', '1');
        $xmlWriter->writeAttribute('vertOverflow', 'ellipsis');
        $xmlWriter->writeAttribute('vert', 'horz');
        $xmlWriter->writeAttribute('wrap', 'square');
        $xmlWriter->writeAttribute('anchor', 'ctr');
        $xmlWriter->writeAttribute('anchorCtr', '1');

        $xmlWriter->writeElement('a:lstStyle');
        $xmlWriter->startElement('a:p');
        $xmlWriter->startElement('a:pPr');
        $xmlWriter->startElement('a:defRPr');
        $xmlWriter->writeAttribute('sz', $size);
        $xmlWriter->writeAttribute('b', '0');
        $xmlWriter->writeAttribute('i', '0');
        $xmlWriter->writeAttribute('u', 'none');
        $xmlWriter->writeAttribute('strike', 'noStrike');
        $xmlWriter->writeAttribute('kern', '1200');
        $xmlWriter->writeAttribute('baseline', '0');
        $xmlWriter->startElement('a:solidFill');

        $xmlWriter->writeElementBlock('a:srgbClr', 'val', $color);

        $xmlWriter->endElement(); // end a:solidFill
        $xmlWriter->writeElementBlock('a:latin', 'typeface', '+mn-lt');
        $xmlWriter->writeElementBlock('a:ea', 'typeface', '+mn-ea');
        $xmlWriter->writeElementBlock('a:cs', 'typeface', '+mn-cs');

        $xmlWriter->endElement(); // end a:defRPr
        $xmlWriter->endElement(); // end a:pPr
        $xmlWriter->writeElementBlock('a:rPr', 'lang', 'ru-RU');

        $xmlWriter->endElement(); //end a:p
        $xmlWriter->endElement(); // end c:txPr
        if ($label !== null) {
            $xmlWriter->writeElementBlock('c:tickLblPos', 'val', $label);
        }
    }

    private function addSchemaSeparator(XMLWriter $xmlWriter, \PhpOffice\PhpWord\Style\Chart $style) {
        $xmlWriter->startElement('a:ln');
        $xmlWriter->writeAttribute('w', $style->getSchemaSeparatorSize());
        $xmlWriter->startElement('a:solidFill');
        $xmlWriter->writeElementBlock('a:schemeClr', 'val', 'bg1');
        $xmlWriter->endElement(); // a:solidFill
        $xmlWriter->endElement(); // a:ln
    }

    private function addGradient(XMLWriter $xmlWriter, $colors) {
        $xmlWriter->startElement('c:spPr');
        $xmlWriter->startElement('a:ln');
        $xmlWriter->writeAttribute('w', 12500);
        $xmlWriter->startElement('a:gradFill');
        $xmlWriter->startElement('a:gsLst');

        $xmlWriter->startElement('a:gs');
        $xmlWriter->writeAttribute('pos', '0');
        $xmlWriter->startElement('a:srgbClr');
        $xmlWriter->writeAttribute('val', $colors[0]);
        $xmlWriter->writeElementBlock('a:alpha', 'val', '30000');
        $xmlWriter->endElement(); // a:srgbClr
        $xmlWriter->endElement(); // a:gs

        $xmlWriter->startElement('a:gs');
        $xmlWriter->writeAttribute('pos', '20000');
        $xmlWriter->writeElementBlock('a:srgbClr', 'val', $colors[0]);
        $xmlWriter->endElement(); // a:gs

        $xmlWriter->startElement('a:gs');
        $xmlWriter->writeAttribute('pos', '100000');
        $xmlWriter->startElement('a:srgbClr');
        $xmlWriter->writeAttribute('val', $colors[0]);
        $xmlWriter->writeElementBlock('a:alpha', 'val', '30000');
        $xmlWriter->endElement(); // a:srgbClr
        $xmlWriter->endElement(); // a:gs

        $xmlWriter->startElement('a:gs');
        $xmlWriter->writeAttribute('pos', '80000');
        $xmlWriter->writeElementBlock('a:srgbClr', 'val', $colors[0]);
        $xmlWriter->endElement(); // a:gs

        $xmlWriter->endElement(); // a:gsLst
        $xmlWriter->writeElementBlock('a:lin', 'ang', '0');
        $xmlWriter->writeAttribute('scaled', '0');

        $xmlWriter->endElement(); // a:gradFill
        $xmlWriter->writeElement('a:round');
        $xmlWriter->endElement(); // a:ln
        $xmlWriter->writeElement('a:effectLst');
        $xmlWriter->endElement(); // c:spPr
    }
}