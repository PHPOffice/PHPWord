<?php


namespace PhpOffice\PhpWord\ComplexType;


use PhpOffice\PhpWord\Shared\Text;

class AltShape
{
    /**
     * Shape Id
     *
     * @var string
     */
    private $id;

    /**
     * Shape spid
     *
     * @var string
     */
    private $spid;

    /**
     * Shape spt
     *
     * @var string
     */
    private $spt;

    /**
     * Shape type
     *
     * @var string
     */
    private $type;

    /**
     * Shape style
     *
     * @var string
     */
    private $style;

    /**
     * Shape filled
     *
     * @var string
     */
    private $filled;

    /**
     * Shape stroked
     *
     * @var string
     */
    private $stroked;

    /**
     * Shape coordsize
     *
     * @var string
     */
    private $coordsize;

    /**
     * Shape gfxdata
     *
     * @var string
     */
    private $gfxdata;


    public function __construct($id)
    {
        $this->setId($id);
    }

    /**
     * Set shape Id
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setId($value = null) {
        $this->id = $value;
    }

    /**
     * Get shape Id
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set shape spid
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setSpid($value = null) {
        $this->spid = $value;
    }

    /**
     * Set shape spid
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getSpid() {
        return $this->spid;
    }

    /**
     * Set shape spt
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setSpt($value = null) {
        $this->spt = $value;
    }

    /**
     * Set shape spt
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getSpt() {
        return $this->spt;
    }

    /**
     * Set shape type
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setType($value = null) {
        $this->type = $value;
    }

    /**
     * Set shape type
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set shape style
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setStyle($value = null) {
        $this->style = $value;
    }

    /**
     * Set shape style
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getStyle() {
        return $this->style;
    }

    /**
     * Set shape filled
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setFilled($value = null) {
        $this->filled = $value;
    }

    /**
     * Set shape filled
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getFilled() {
        return $this->filled;
    }

    /**
     * Set shape stroked
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setStroked($value = null) {
        $this->stroked = $value;
    }

    /**
     * Set shape filled
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getStroked() {
        return $this->stroked;
    }

    /**
     * Set shape coordsize
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setCoordsize($value = null) {
        $this->coordsize = $value;
    }

    /**
     * Set shape coordsize
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getCoordsize() {
        return $this->coordsize;
    }

    /**
     * Set shape coordsize
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function setGfxdata($value = null) {
        $this->gfxdata = $value;
    }

    /**
     * Set shape coordsize
     *
     * @return string
     * @author <presleylee@qq.com>
     * @since 2023/8/15 3:24 下午
     */
    public function getGfxdata() {
        return $this->gfxdata;
    }

    /**
     * Set style value template method.
     *
     * Some child classes have their own specific overrides.
     * Backward compability check for versions < 0.10.0 which use underscore
     * prefix for their private properties.
     * Check if the set method is exists. Throws an exception?
     *
     * @param string $key
     * @param array|int|string $value
     *
     * @return self
     */
    public function setAttrValue($key, $value)
    {
        if (isset($this->aliases[$key])) {
            $key = $this->aliases[$key];
        }

        if ($key === 'align') {
            $key = 'alignment';
        }

        $method = 'set' . Text::removeUnderscorePrefix($key);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }

        return $this;
    }


    /**
     * Set style by using associative array.
     *
     * @param array $values
     *
     * @return self
     */
    public function setAttrByArray($values = [])
    {
        foreach ($values as $key => $value) {
            $this->setAttrValue($key, $value);
        }

        return $this;
    }
}