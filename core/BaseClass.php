<?php

/**
 * BaseClass provides a mechanism for automatic getter and setter generation
 * using the magic method __call. It allows child classes to disallow specific
 * getters and setters by populating protected static arrays $noGetters and
 * $noSetters.
 *
 * The class inspects properties using reflection and automatically maps
 * method calls to the corresponding class properties, while respecting the
 * allowed or disallowed getters and setters defined in child classes.
 *
 * Properties:
 *
 * - static array $getters: List of allowed getter methods.
 * - static array $setters: List of allowed setter methods.
 *
 * Methods:
 *
 * @method mixed __call(string $func, array $params)
 *         Dynamically handles getter and setter calls for class properties.
 * @throws BadMethodCallException If the method or property is disallowed or does not exist.
 *
 */

abstract class BaseClass
{
    // Set allowed methods for getters and setters in child class
    protected static $getters = [];
    protected static $setters = [];

    /**
     * Dynamically handles calls to getter and setter methods.
     *
     * This method is invoked when calling inaccessible methods on an object.
     * It checks if the called method corresponds to a property and invokes
     * the appropriate getter or setter based on the parameters provided.
     *
     * @param  string $func           The name of the method being called.
     * @param  array  $params         The parameters for the setter, if any.
     * @return mixed                  The value of the property for getters, or null for setters.
     * @throws BadMethodCallException If the method or property is disallowed or does not exist.
     */
    public function __call(string $func, array $params): ?string 
    {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED);

        // iterate over all instance variables
        foreach ($props as $prop) {
            // Check if the named called is valid. Without this the first found is returned
            // and not the one which the caller searches
            // Create getters, to params with getters
            if ($prop->getName() === $func && empty($params)) {
                // Check for not allowed getters
                self::checkAllowed($func, static::$getters);
                return $prop->getValue($this);
                // create setters, if params are set
            } elseif ($prop->getName() === $func && isset($params)) {
                // Check for not allowed setters
                self::checkAllowed($func, static::$setters);
                return $prop->setValue($this, ...$params);
            }
        }

        // attribute not found
        throw new BadMethodCallException('Method {$func} does not exist.');
    }

    /**
     * Checks if the method is on the white list
     *
     * @param  string $func       The name of the method to check.
     * @param  array  $exceptions The list of allowed methods.
     * @throws BadMethodCallException If the method is disallowed.
     */
    private static function checkAllowed(string $func, array $exceptions): void
    {
        if (!in_array($func, $exceptions)) {
            throw new BadMethodCallException('{$func} is not allowed.');
        }
    }
}

/* NOTE: Resources
*
* https://www.php.net/manual/en/class.reflectionclass.php
* https://www.php.net/manual/en/reflectionclass.getproperties.php
* https://www.php.net/manual/en/reflectionclass.getname.php
* https://www.php.net/manual/en/reflectionclassconstant.getvalue.php
* https://www.php.net/manual/en/reflectionproperty.setvalue.php
* https://stackoverflow.com/questions/12868066/dynamically-create-php-class-functions#12868100
*/
