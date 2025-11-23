<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\CarImageController;
use App\Domain\Models\CarModel;
use App\Helpers\FileUploadHelper;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CarsController extends BaseController
{
    public function __construct(Container $container, private CarModel $car_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $cars = $this->car_model->fetchCars();
        $data['data'] = [
            'title' => 'Admin',
            'message' => 'Welcome to the admin page',
            'cars' => $cars
        ];

        return $this->render($response, 'admin/cars/carIndexView.php', $data);
    }

    /**
     * Summary of create
     * Loads the car creation page for the user
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function create(Request $request, Response $response, array $args): Response
    {
        $data['data'] = [
            'title' => 'Cars',
            'message' => 'Welcome to the Cars Creation page'
        ];

        return $this->render($response, 'admin/cars/carCreateView.php', $data);
    }

    /**
     * Summary of store
     * Extracts and validates the input data for a new car
     * before calling on the CarsModel to store the car's
     * information in the database.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function store(Request $request, Response $response, array $args): Response
    {
        // Get form data
        $formData = $request->getParsedBody();

        // Extract fields from $formData
        $brand = $formData['brand'];
        $model = $formData['model'];
        $year = $formData['year'];
        $capacity = $formData['capacity'];
        $approx_price = $formData['approx_price'];
        $description = $formData['description'];

        //todo validate the values
        $errors = [];

        if (empty($brand)) {
            $errors[] = "Please fill out Brand field";
        }

        if (empty($model)) {
            $errors[] = "Please fill out Brand field";
        }

        if (empty($year)) {
            $errors[] = "Please fill out Brand field";
        }

        if (empty($capacity)) {
            $errors[] = "Please fill out Brand field";
        }

        if (empty($approx_price)) {
            $errors[] = "Please fill out Brand field";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                FlashMessage::error($error);
            }

            return $this->redirect($request, $response, 'cars.create');
        }

        // If validation passes, create the car
        try {
            // Create $userData array with keys:
            $carData = [
                'brand' => $brand,
                'model' => $model,
                'year' => $year,
                'capacity' => $capacity,
                'approx_price' => $approx_price,
                'description' => $description
            ];

            // Call $this->userModel->createAndGetId($carData)
            $userId = $this->car_model->createAndGetId($carData);

            //* Try to upload files

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

            // Display success message using FlashMessage::success()
            FlashMessage::success("Car created successfully!");

            // Redirect back to 'upload.index'
            return $this->redirect($request, $response, 'cars.index');
        } catch (\Exception $e) {
            // Display error message using FlashMessage::error()
            FlashMessage::error("Car creation failed. Please try again.");

            // Redirect back to 'auth.register' route
            return $this->redirect($request, $response, 'cars.store');
        }
    }

    /**
     * Summary of delete
     * Extracts the car_id from the arguments section of the URI and sends it to
     * the CarsModel for removal from the database.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $car_id = $args['car_id'];

        if (is_numeric($car_id)) {
            $this->car_model->deleteCar($car_id);
        }

        return $this->redirect($request, $response, 'cars.index');
    }

    /**
     * Summary of update
     * Extracts and validates the user inputs before
     * calling on the CarsModel class to update the car
     * @param Request $request
     * @param Response $reponse
     * @param array $args
     * @return Response
     */
    public function update(Request $request, Response $response, array $args): Response
    {
        $car_id = $args['car_id'];

        $data = $request->getParsedBody();

        //todo validate inputs here

        $this->car_model->updateCar($car_id, $data);
        FlashMessage::success("Car Added Successfully");

        return $this->redirect($request, $response, 'cars.index');
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
