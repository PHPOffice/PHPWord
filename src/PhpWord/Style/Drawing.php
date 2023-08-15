<?php


namespace PhpOffice\PhpWord\Style;


/**
 * Draing style.
 *
 * OOXML:
 * - ecx: alignment, outline level
 * - Indentation: left, right, firstline, hanging
 * - Spacing: before, after, line spacing
 * - Pagination: widow control, keep next, keep line, page break before
 * - Formatting exception: suppress line numbers, don't hyphenate
 * - Textbox options
 * - Tabs
 * - Shading
 * - Borders
 *
 * OpenOffice:
 * - Indents & spacing
 * - Alignment
 * - Text flow
 * - Outline & numbering
 * - Tabs
 * - Dropcaps
 * - Tabs
 * - Borders
 * - Background
 *
 * @see  http://www.schemacentral.com/sc/ooxml/t-w_CT_PPr.html
 */
class Drawing extends AbstractStyle
{
    /**
     * Drawing inline.
     *
     * @var array
     */
    private $inline;

    /**
     * Drawing extent.
     *
     * @var array
     */
    private $extent;

    /**
     * Drawing effectExtent.
     *
     * @var array
     */
    private $effectExtent;

    /**
     * Drawing docPr.
     *
     * @var array
     */
    private $docPr;

    /**
     * Drawing nvGraphicFPr.
     *
     * @var array
     */
    private $nvGraphicFPr;

    /**
     * Drawing graphic.
     *
     * @var array
     */
    private $graphic;

    /**
     * Drawing image width.
     *
     * @var int
     */
    private $width;

    /**
     * Drawing image height.
     *
     * @var int
     */
    private $height;

    public function __construct()
    {
    }

    /**
     * Get style values.
     *
     * An experiment to retrieve all style values in one function. This will
     * reduce function call and increase cohesion between functions. Should be
     * implemented in all styles.
     *
     * @ignoreScrutinizerPatch
     *
     * @return array
     */
    public function getStyleValues()
    {
        $styles = [
            'inline' => $this->getInline(),
            'extent' => $this->getExtent(),
            'effectExtent' => $this->getEffectExtent(),
            'docPr' => $this->getDocPr(),
            'nvGraphicFPr' => $this->getNvGraphicFPr(),
            'graphic' => $this->getGraphic(),
        ];

        return $styles;
    }

    /**
     * Set inline info.
     *
     * @param array $value
     *
     * @return self
     */
    public function setInline($value = null) {
        if (is_array($value)) {
            $this->inline = $value;
        }
    }

    /**
     * Get inline.
     *
     * @return array
     */
    public function getInline()
    {
        return $this->inline;
    }

    /**
     * Set extent info.
     *
     * @param array $value
     *
     * @return self
     */
    public function setExtent($value = null) {
        if (is_array($value)) {
            $this->extent = $value;
        }
    }

    /**
     * Get extent.
     *
     * @return array
     */
    public function getExtent()
    {
        return $this->extent;
    }

    /**
     * Set effectExtent info.
     *
     * @param array $value
     *
     * @return self
     */
    public function setEffectExtent($value = null) {
        if (is_array($value)) {
            $this->effectExtent = $value;
        }
    }

    /**
     * Get effectExtent.
     *
     * @return array
     */
    public function getEffectExtent()
    {
        return $this->effectExtent;
    }

    /**
     * Set docPr info.
     *
     * @param array $value
     *
     * @return self
     */
    public function setDocPr($value = null) {
        if (is_array($value)) {
            $this->docPr = $value;
        }
    }

    /**
     * Get effectExtent.
     *
     * @return array
     */
    public function getDocPr()
    {
        return $this->docPr;
    }

    /**
     * Set nvGraphicFPr info.
     *
     * @param array $value
     *
     * @return self
     */
    public function setNvGraphicFPr($value = null) {
        if (is_array($value)) {
            $this->nvGraphicFPr = $value;
        }
    }

    /**
     * Get nvGraphicFPr.
     *
     * @return array
     */
    public function getNvGraphicFPr()
    {
        return $this->nvGraphicFPr;
    }

    /**
     * Set graphic info.
     *
     * @param array $value
     *
     * @return self
     */
    public function setGraphic($value = null) {
        if (is_array($value)) {
            $this->graphic = $value;
        }
    }

    /**
     * Get graphic.
     *
     * @return array
     */
    public function getGraphic()
    {
        return $this->graphic;
    }

    /**
     * Set image width.
     *
     * @param int $value
     *
     * @return self
     */
    public function setWidth($value = null) {
        $this->width = $value;
    }

    /**
     * Get image width.
     *
     * @return array
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set image height.
     *
     * @param int $value
     *
     * @return self
     */
    public function setHeight($value = null) {
        $this->height = $value;
    }

    /**
     * Get image height.
     *
     * @return array
     */
    public function getHeight()
    {
        return $this->height;
    }
}