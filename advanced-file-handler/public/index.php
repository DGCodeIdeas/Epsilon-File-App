<?php

// public/index.php

/**
 * Front Controller
 *
 * This file is the single entry point for all web requests to the application.
 * It is responsible for bootstrapping the application: initializing the autoloader,
 * loading configuration, and dispatching the request to the router.
 */

// 1. Define Base Path
// Use realpath to resolve '..' and get a clean, absolute path.
define('BASE_PATH', realpath(__DIR__ . '/../') . '/');

// 2. Autoloader
// Register a PSR-4 style autoloader.
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'App\\';

    // Base directory for the namespace prefix
    $base_dir = BASE_PATH . 'src/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators for the relative class name,
    // and append with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// 3. Load Core Classes and Configuration
require BASE_PATH . 'src/Core/App.php';
require BASE_PATH . 'src/Core/Router.php';
require BASE_PATH . 'src/Core/Request.php';

use App\Core\App;
use App\Core\Router;
use App\Core\Request;

// 4. Bind Configuration into the App Container
App::bind('config', require BASE_PATH . 'config/config.php');

// 5. Define Routes and Dispatch
$router = new Router();

// Define application routes
$router->get('', 'HomeController@show'); // Home page
$router->get('download', 'FileController@download'); // Download a file
$router->post('upload', 'FileController@upload'); // Upload a file

// Dispatch the router, passing the URI and request method
try {
    $router->dispatch(Request::uri(), Request::method());
} catch (Exception $e) {
    // A simple error handler for now. In a real app, this would be more robust.
    // For example, logging the error and showing a user-friendly error page.
    // The ErrorController handles 404, this handles exceptions during dispatch.
    http_response_code(500);
    echo '<h1>500 Internal Server Error</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
}
