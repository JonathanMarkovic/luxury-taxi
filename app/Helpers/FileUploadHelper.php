<?php

namespace App\Helpers;

use App\Helpers\Core\Result;
use Psr\Http\Message\UploadedFileInterface;

class FileUploadHelper
{
    /**
     * Upload a file with validation and return a Result.
     *
     * @param UploadedFileInterface $uploadedFile The uploaded file from the request
     * @param array $config Configuration options:
     *   - 'directory' (string): Upload directory path (required)
     *   - 'allowedTypes' (array): Array of allowed media types (required)
     *   - 'maxSize' (int): Maximum file size in bytes (required)
     *   - 'filenamePrefix' (string): Prefix for generated filenames (default: 'upload_')
     * @return Result Success with filename, or failure with error message
     */
    public static function upload(UploadedFileInterface $uploadedFile, array $config): Result
    {
        $directory = $config['directory'] ?? null;
        $allowedTypes = $config['allowedTypes'] ?? [];
        $maxSize = $config['maxSize'] ?? 0;
        $filenamePrefix = $config['filenamePrefix'] ?? 'upload_';



        //* Step 1: Extract and Validate inputs
        //* These are the we messed up errors
        if (!$directory) {
            return Result::failure('Upload directory not specified in configuration');
        }

        if (empty($allowedTypes)) {
            return Result::failure('Allowed file types not specified in configuration');
        }

        if ($maxSize <= 0) {
            return Result::failure('Maximum file size not specified in configuration');
        }

        //* These are the user messed up errors
        $uploadError = $uploadedFile->getError();
        if ($uploadError !== UPLOAD_ERR_OK) {
            return Result::failure("Error uploading file. Error code: $uploadError");
        }

        if ($uploadedFile->getSize() > $maxSize) {
            return Result::failure("File too large (max {$maxSize}MB");
        }

        if (!in_array($uploadedFile->getClientMediaType(), $allowedTypes)) {
            return Result::failure('Invalid File Type. Only ' . implode(', ', $allowedTypes) . ' allowed');
        }

        //* Step 2: Generate safe filename and move file
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $filename = uniqid($filenamePrefix) . '.' . $extension;

        //* Step 3: Create directory and save file
        if (!is_dir($directory)) {
            try {
                mkdir($directory, 0755, true);
            } catch (\Throwable $th) {
                return Result::failure('Failed to create upload directory');
            }
        }

        $destination = $directory . DIRECTORY_SEPARATOR . $filename;

        try {
            $uploadedFile->moveTo($destination);
        } catch (\Exception $e) {
            return Result::failure('Failed to save uploaded file: ' . $e->getMessage());
        }

        return Result::success('File uploaded successfully', ['filename' => $filename]);
    }
}
