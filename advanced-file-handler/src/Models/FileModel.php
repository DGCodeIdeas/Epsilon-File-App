<?php

namespace App\Models;

use App\Core\App;
use finfo;

/**
 * Handles all business logic related to file management.
 *
 * This includes listing, saving, and retrieving files from the filesystem.
 * It is designed to be completely independent of the HTTP layer.
 */
class FileModel
{
    /**
     * The directory where files are uploaded.
     *
     * @var string
     */
    private $uploadsDirectory;

    /**
     * The maximum allowed file size in bytes.
     *
     * @var int
     */
    private $maxFileSize;

    /**
     * An array of allowed MIME types.
     *
     * @var array
     */
    private $allowedMimeTypes;

    /**
     * FileModel constructor.
     *
     * Initializes the model by fetching configuration from the App container.
     */
    public function __construct()
    {
        $config = App::get('config');
        $this->uploadsDirectory = $config['uploads_directory'];
        $this->maxFileSize = $config['max_file_size'];
        $this->allowedMimeTypes = $config['allowed_mime_types'];
    }

    /**
     * Get all valid files from the uploads directory.
     *
     * @return array An array of filenames.
     */
    public function getAllFiles()
    {
        // Ensure the directory exists before trying to scan it.
        if (!is_dir($this->uploadsDirectory)) {
            return [];
        }

        $allFiles = scandir($this->uploadsDirectory);

        // Filter out '.' and '..' directory entries.
        return array_filter($allFiles, function ($file) {
            return !in_array($file, ['.', '..']);
        });
    }

    /**
     * Validate and save an uploaded file.
     *
     * @param array $fileData The file data from the $_FILES superglobal.
     * @return array An associative array with 'status' and 'message'.
     */
    public function saveFile(array $fileData)
    {
        try {
            // Basic validation
            $this->validateUpload($fileData);

            // Generate a secure, unique filename
            $extension = pathinfo($fileData['name'], PATHINFO_EXTENSION);
            $uniqueName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            $destination = $this->uploadsDirectory . $uniqueName;

            // Move the file to the destination
            if (!move_uploaded_file($fileData['tmp_name'], $destination)) {
                throw new \RuntimeException('Failed to move uploaded file.');
            }

            return ['status' => 'success', 'message' => 'File uploaded successfully.', 'filename' => $uniqueName];
        } catch (\RuntimeException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Get the full, absolute path for a given filename.
     *
     * @param string $filename The name of the file.
     * @return string|false The full path to the file or false if it's invalid.
     */
    public function getFilePath(string $filename)
    {
        // Security: prevent directory traversal attacks.
        // basename() will strip any directory information from the input.
        if (basename($filename) !== $filename) {
            return false;
        }

        $filePath = $this->uploadsDirectory . $filename;

        // Check if the file actually exists and is a file.
        if (file_exists($filePath) && is_file($filePath)) {
            return $filePath;
        }

        return false;
    }

    /**
     * Perform a series of checks on the uploaded file.
     *
     * @param array $fileData The file data from $_FILES.
     * @throws \RuntimeException if the file is invalid.
     */
    private function validateUpload(array $fileData)
    {
        // Check for upload errors
        if (!isset($fileData['error']) || is_array($fileData['error'])) {
            throw new \RuntimeException('Invalid parameters.');
        }

        switch ($fileData['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \RuntimeException('Exceeded filesize limit.');
            default:
                throw new \RuntimeException('Unknown errors.');
        }

        // Check filesize
        if ($fileData['size'] > $this->maxFileSize) {
            throw new \RuntimeException('Exceeded filesize limit.');
        }

        // Check MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($fileData['tmp_name']);
        if (false === in_array($mimeType, $this->allowedMimeTypes)) {
            throw new \RuntimeException('Invalid file format.');
        }
    }
}
