<?php

namespace Adadgio\Common\Utils;

/**
 * A set of methods enhancing PHP\ Reflection(s) to find
 * or match instances of class, method information, etc.
 */
class ReflectionAnalysis
{
    const FULL_NAME  = 'LONG';
    const SHORT_NAME = 'SHORT';

    private static $reflectionMethod;
    private static $reflectionMethodParams = array();

    /**
     * @param string $eventName looking like "\Namespace\ClassName::methodName"
     */
    public static function ofController($eventName)
    {
        // turns it into array(0 => namespace\ClassName, 1 => methodName)
        $eventInfo = explode('::', $eventName);

        self::$reflectionMethod = new \ReflectionMethod($eventInfo[0], $eventInfo[1]);
        self::$reflectionMethodParams = self::$reflectionMethod->getParameters();
    }

    /**
     * For each class method parameters, find their
     * and of which instance they are type hinted.
     *
     * @param string Class name to look for in param type hints.
     */
    public static function getArgumentTypeHintedWith($needleClassName)
    {
        foreach(self::$reflectionMethodParams as $param) {
            // a param from a reflection object is a simple
            // object containing only a name

            // first get parent class name (ie instance of name)
            // of that param (without the namespace)
            $reflectionClassName = self::getParamInstanceName($param, static::SHORT_NAME);

            if($reflectionClassName === $needleClassName) {
                return $param->name;
            }
        }

        return false;
    }
    
    private static function getParamInstanceName($reflectionParam, $longOrShortName)
    {
        $instanceClass = $reflectionParam->getClass();
        if (null === $instanceClass) {
            return false;
        }

        $instanceName = $instanceClass->name;

        if($longOrShortName === static::SHORT_NAME) {
            $namespaceParts = explode('\\', $instanceName);
            return end($namespaceParts);
        } else
        {
            return $instanceName;
        }
    }
}
