<?php

namespace App\Core;

/**
 * Represents an HTTP request.
 *
 * Provides a simple, testable API for accessing the request URI and method.
 */
class Request
{
    /**
     * Get the request URI path.
     *
     * This method parses the URI from the `$_SERVER` superglobal and
     * removes the query string.
     *
     * @return string The clean URI path.
     */
    public static function uri()
    {
        return trim(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            '/'
        );
    }

    /**
     * Get the request method.
     *
     * @return string The request method (e.g., 'GET', 'POST').
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
