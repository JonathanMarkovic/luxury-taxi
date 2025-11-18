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
    public function upload(Request $request, Response $response, array $args): Response
    {
        $uploadedFiles = $request->getUploadedFiles();
        $myFile = $uploadedFiles['myFile'];

        $config = [
            'directory' => APP_BASE_DIR_PATH . '/public/uploads/images',
            'allowedTypes' => ['image/jpeg', 'image/png', 'image/gif'],
            'maxSize' => 2 * 1024 * 1024, //2mb
            'fileNamePrefix' => 'upload_'
        ];

        $result = FileUploadHelper::upload($myFile, $config);

        if ($result->isSuccess()) {

            $fileName = $result->getData()['filename'];
            if (!SessionManager::has('uploaded_files')) {
                SessionManager::set('uploaded_files', []);
            }

            $files = SessionManager::get('uploaded_files');
            $files[] = $fileName;
            SessionManager::set('uploaded_files', $files);
            FlashMessage::success($result->getMessage());
            // todo we need to extract the car id somehow
            $car_id = $args['car_id'];
            $this->car_image_model->upload($car_id, $config, $fileName);
        } else {
            FlashMessage::error($result->getMessage());
        }

        return $this->redirect($request, $response, 'upload.index');
    }
}
