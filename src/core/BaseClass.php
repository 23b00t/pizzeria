<?php

namespace app\core;

use ReflectionClass;
use ReflectionProperty;
use BadMethodCallException;

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
    public function __call(string $func, array $params): mixed 
    {
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PRIVATE | ReflectionProperty::IS_PROTECTED);

        // Iterate over all instance variables
        foreach ($props as $prop) {
            // Check if the called method matches the property name
            if ($prop->getName() === $func) {
                // For getters: return the property value if no parameters are passed
                if (empty($params)) {
                    self::checkAllowed($func, static::$getters);
                    return $prop->getValue($this);
                }

                // For setters: set the property value if parameters are provided
                self::checkAllowed($func, static::$setters);
                $prop->setValue($this, ...$params);
                return null;
            }
        }

        // Method not found
        throw new BadMethodCallException("Method '{$func}' does not exist.");
    }

    /**
     * Checks if the method is on the whitelist.
     *
     * This method verifies if the given method name is allowed based on the 
     * provided exceptions array. If not allowed, it throws an exception.
     *
     * @param  string $func       The name of the method to check.
     * @param  array  $exceptions The list of allowed methods.
     * @throws BadMethodCallException If the method is disallowed.
     */
    private static function checkAllowed(string $func, array $exceptions): void
    {
        if (!in_array($func, $exceptions)) {
            throw new BadMethodCallException("'{$func}' is not allowed.");
        }
    }
}
