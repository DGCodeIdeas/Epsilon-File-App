<?php

namespace App\Controllers;

use App\Models\FileModel;
use App\Controllers\ErrorController;

/**
 * Handles file-related actions like uploading and downloading.
 */
class FileController
{
    /**
     * Handle the file upload request from the client-side script.
     *
     * This method expects a POST request with a file in $_FILES['userfile'].
     * It uses the FileModel to validate and save the file, then returns
     * a JSON response indicating the outcome.
     */
    public function upload()
    {
        header('Content-Type: application/json');

        if (!isset($_FILES['userfile'])) {
            echo json_encode(['status' => 'error', 'message' => 'No file uploaded.']);
            return;
        }

        $fileModel = new FileModel();
        $result = $fileModel->saveFile($_FILES['userfile']);

        echo json_encode($result);
    }

    /**
     * Handle the file download request.
     *
     * This method expects a GET request with a 'file' parameter.
     * It uses the FileModel to get a secure path to the requested file
     * and then streams it to the user's browser as a download.
     */
    public function download()
    {
        if (!isset($_GET['file'])) {
            ErrorController::notFound("File not specified.");
            return;
        }

        $filename = $_GET['file'];
        $fileModel = new FileModel();
        $filePath = $fileModel->getFilePath($filename);

        if ($filePath === false) {
            ErrorController::notFound("File not found or access denied.");
            return;
        }

        // Set headers to trigger a browser download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Clear output buffer
        flush();

        // Read the file and stream it to the output buffer
        readfile($filePath);
        exit; // Terminate script to prevent any further output
    }
}
