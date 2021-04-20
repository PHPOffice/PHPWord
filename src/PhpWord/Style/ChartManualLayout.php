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
     * axis Y
     *
     * @var string
     */
    protected $axisX = 0;

    /**
     * axis Y
     *
     * @var string
     */
    protected $axisY = 0;

    /**
     * axis X
     *
     * @var string
     */
    protected $height = 1;

    /**
     * axis X
     *
     * @var string
     */
    protected $width = 1;

    /**
     * @return string
     */
    public function getYMode(): string
    {
        return $this->yMode;
    }

    /**
     * @param string $yMode
     */
    public function setYMode(string $yMode): self
    {
        $this->yMode = $yMode;
        return $this;
    }

    /**
     * @return string
     */
    public function getXMode(): string
    {
        return $this->xMode;
    }

    /**
     * @param string $xMode
     */
    public function setXMode(string $xMode): self
    {
        $this->xMode = $xMode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAxisX(): string
    {
        return $this->x;
    }

    /**
     * @param string $x
     */
    public function setAxisX(string $x): self
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return string
     */
    public function getAxisY(): string
    {
        return $this->y;
    }

    /**
     * @param string $y
     */
    public function setAxisY(string $y): self
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * @param string $height
     */
    public function setHeight(string $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth(string $width): self
    {
        $this->width = $width;
        return $this;
    }
}