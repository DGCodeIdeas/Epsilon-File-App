<?php

namespace App\Controllers;

/**
 * Handles the display of error pages.
 */
class ErrorController
{
    /**
     * Display a 404 'Not Found' error page.
     *
     * This method is typically called by the router when no route
     * matches the requested URI. It sets the HTTP response code
     * to 404 and renders the corresponding error view.
     *
     * @param string $message An optional message to display on the error page.
     */
    public static function notFound($message = 'The page you requested could not be found.')
    {
        http_response_code(404);

        // Make the message available to the view.
        // This is a simple way to pass data for an error page.
        require_once __DIR__ . '/../Views/errors/404.view.php';
        exit; // Stop execution after showing the error page.
    }
}
