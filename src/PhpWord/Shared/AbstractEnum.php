<?php
namespace PhpOffice\PhpWord\Shared;

abstract class AbstractEnum
{

    private static $constCacheArray = null;

    private static function getConstants()
    {
        if (self::$constCacheArray == null) {
            self::$constCacheArray = array();
        }
        $calledClass = get_called_class();
        if (! array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    /**
     * Returns all values for this enum
     *
     * @return array
     */
    public static function values()
    {
        return array_values(self::getConstants());
    }

    /**
     * Returns true the value is valid for this enum
     *
     * @param strign $value
     * @return boolean true if value is valid
     */
    public static function isValid($value)
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, true);
    }

    /**
     * Validates that the value passed is a valid value
     *
     * @param string $value
     * @throws \InvalidArgumentException if the value passed is not valid for this enum
     */
    public static function validate($value)
    {
        if (!self::isValid($value)) {
            $calledClass = get_called_class();
            $values = array_values(self::getConstants());
            throw new \InvalidArgumentException("$value is not a valid value for $calledClass, possible values are " . implode(', ', $values));
        }
    }
}
