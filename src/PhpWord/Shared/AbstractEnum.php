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

    public static function values()
    {
        return array_values(self::getConstants());
    }

    public static function validate($value)
    {
        $values = array_values(self::getConstants());
        if (!in_array($value, $values, true)) {
            $calledClass = get_called_class();
            throw new \InvalidArgumentException("$value is not a valid value for $calledClass, possible values are " . implode(', ', $values));
        }
    }
}
