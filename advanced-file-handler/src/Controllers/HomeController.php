<?php

namespace App\Controllers;

use App\Models\FileModel;

/**
 * Handles requests for the home page.
 */
class HomeController
{
    /**
     * Show the main home page with the file uploader and file list.
     *
     * This method fetches the list of currently uploaded files from the
     * FileModel and passes them to the home view template for rendering.
     */
    public function show()
    {
        $fileModel = new FileModel();
        $files = $fileModel->getAllFiles();

        // This is a simple way to render a view and pass data to it.
        // The view file will have access to the $files variable.
        require_once __DIR__ . '/../Views/home.view.php';
    }
}
