<?php
/**
 * Position element in block plotArea
 */


namespace PhpOffice\PhpWord\Style;


class ChartManualLayout
{
    /**
     * mode
     *
     * @var string
     */
    protected $yMode = 'edge';

    /**
     * mode
     *
     * @var string
     */
    protected $xMode = 'edge';

    /**
     * axis X
     *
     * @var string
     */
    protected $axisX = null;

    /**
     * axis Y
     *
     * @var string
     */
    protected $axisY = null;

    /**
     * height
     *
     * @var string
     */
    protected $height = null;

    /**
     * width
     *
     * @var string
     */
    protected $width = null;

    /**
     * @return string
     */
    public function getYMode(): ?string
    {
        return $this->yMode;
    }

    /**
     * @param string $yMode
     *
     * @return self
     */
    public function setYMode(string $yMode): self
    {
        $this->yMode = $yMode;
        return $this;
    }

    /**
     * @return string
     */
    public function getXMode(): ?string
    {
        return $this->xMode;
    }

    /**
     * @param string $xMode
     *
     * @return self
     */
    public function setXMode(string $xMode): self
    {
        $this->xMode = $xMode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAxisX(): ?string
    {
        return $this->axisX;
    }

    /**
     * @param string $axisX
     *
     * @return self
     */
    public function setAxisX(string $axisX = null): self
    {
        $this->axisX = $axisX;
        return $this;
    }

    /**
     * @return string
     */
    public function getAxisY(): ?string
    {
        return $this->axisY;
    }

    /**
     * @param string $axisY
     *
     * @return self
     */
    public function setAxisY(string $axisY = null): self
    {
        $this->axisY = $axisY;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * @param string $height
     *
     * @return self
     */
    public function setHeight(string $height = null): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * @param string $width
     *
     * @return self
     */
    public function setWidth(string $width = null): self
    {
        $this->width = $width;
        return $this;
    }
}