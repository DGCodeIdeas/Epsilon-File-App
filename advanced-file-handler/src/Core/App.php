<?php

namespace App\Core;

use Exception;

/**
 * A simple dependency injection container or service locator.
 *
 * It provides a central place to store and retrieve application-wide
 * services, like configuration arrays or database connections. This avoids
 * the use of globals and makes dependencies more explicit.
 */
class App
{
    /**
     * The container's registry for storing services.
     *
     * @var array
     */
    protected static $registry = [];

    /**
     * Bind a new service into the container.
     *
     * @param string $key   The key to bind the service to.
     * @param mixed  $value The service instance or value.
     */
    public static function bind($key, $value)
    {
        static::$registry[$key] = $value;
    }

    /**
     * Retrieve a service from the container.
     *
     * @param  string $key The key of the service to retrieve.
     * @return mixed
     * @throws Exception if the key is not found in the registry.
     */
    public static function get($key)
    {
        if (!array_key_exists($key, static::$registry)) {
            throw new Exception("No {$key} is bound in the container.");
        }

        return static::$registry[$key];
    }
}
