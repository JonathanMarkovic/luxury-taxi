<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Domain\Models\CarImageModel;
use App\Helpers\FileUploadHelper;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CarImageController extends BaseController
{
    public function __construct(Container $container, private CarImageModel $car_image_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the Car Image Upload page',
        ];

        //TODO: Need to add a view for the file upload page
        return $response;
    }

    /**
     * Summary of upload
     * Uploads and image to the websites file system
     * Will also call on the CarImageModel to store the image file path for later rendering
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    /**
     * Process file upload.
     * Adapted for multiple file uploads
     */
    public function upload(Request $request, Response $response, array $args): Response
    {
        // TODO: Get uploaded files using getUploadedFiles()
        // TODO: Extract 'myfile' from the array

        $uploadedFiles = $request->getUploadedFiles();
        $files = $uploadedFiles['myfile'];

        // Create $config array
        $config = [
            'directory' => APP_BASE_DIR_PATH . '/public/uploads/images',
            'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif'],
            'maxSize' => 2 * 1024 * 1024,
            'filenamePrefix' => 'upload_'
        ];

        if (!SessionManager::has('uploaded_files')) {
            SessionManager::set('uploaded_files', []);
        }

        $sessionFiles = SessionManager::get('uploaded_files');

        // Loop through all of the uploaded files
        foreach ($files as $file) {
            if ($file->getError !== UPLOAD_ERR_OK) {
                FlashMessage::error("Error uploading file.");
                continue;
            }

            // Upload using FileUploadHelper
            $result = FileUploadHelper::upload($file, $config);

            if ($result->isSuccess()) {
                $fileName = $result->getData()['filename'];
                $sessionFiles[] = $fileName;

                FlashMessage::success("Successfully uploaded: {$fileName}");
            } else {
                FlashMessage::error($result->getMessage());
            }
        }

        // Save updated list
        SessionManager::set('uploaded_files', $sessionFiles);

        // Redirect back to 'upload.index'
        return $this->redirect($request, $response, 'cars.index');
    }
}
