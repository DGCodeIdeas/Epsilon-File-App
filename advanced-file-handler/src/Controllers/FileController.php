<?php

namespace App\Controllers;

use App\Models\FileModel;

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
            $this->triggerNotFound("File not specified.");
            return;
        }

        $filename = $_GET['file'];
        $fileModel = new FileModel();
        $filePath = $fileModel->getFilePath($filename);

        if ($filePath === false) {
            $this->triggerNotFound("File not found or access denied.");
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
    }

    /**
     * A helper method to trigger a 404 error.
     *
     * @param string $message The error message to display.
     */
    private function triggerNotFound($message)
    {
        // In this architecture, we call the ErrorController directly.
        // This is a simple way to do it without a complex redirection.
        $errorController = new ErrorController();
        $errorController->notFound($message);
    }
}
