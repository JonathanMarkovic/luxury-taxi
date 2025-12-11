<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\CarImageController;
use App\Domain\Models\CarImageModel;
use App\Domain\Models\CarModel;
use App\Helpers\FileUploadHelper;
use App\Helpers\FlashMessage;
use App\Helpers\SessionManager;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CarsController extends BaseController
{
    public function __construct(Container $container, private CarModel $car_model, private CarImageModel $car_image_model)
    {
        parent::__construct($container);
    }

    public function index(Request $request, Response $response, array $args): Response
    {
        $cars = $this->car_model->fetchCars();

        // Reference operator (&) creates a copy so images are added to the copy
        foreach ($cars as &$car) {
            $car['images'] = $this->car_image_model->fetchImagesById($car['cars_id']);
        }
        unset($car);

        // Debugging: without nesting inside 'data'
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
            $errors[] = "Please fill out Model field";
        }

        if (empty($year)) {
            $errors[] = "Please fill out Year field";
        }

        if (empty($capacity)) {
            $errors[] = "Please fill out Capacity field";
        }

        if (empty($approx_price)) {
            $errors[] = "Please fill out Price field";
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
            $carId = $this->car_model->createAndGetId($carData);

            //* Try to upload files

            // Debugging
            error_log("Car created with ID: " . $carId);

            // Process file uploads
            $uploadedFiles = $request->getUploadedFiles();

            if (isset($uploadedFiles['myfile']) && !empty($uploadedFiles['myfile'])) {
                $files = $uploadedFiles['myfile'];

                $config = [
                    'directory' => APP_BASE_DIR_PATH . '/public/uploads/images',
                    'allowedTypes' => ['image/jpeg', 'image/png'],
                    'maxSize' => 2 * 1024 * 1024,
                    'filenamePrefix' => 'car_' . $carId . "_"
                ];

                foreach ($files as $file) {
                    if ($file->getError() !== UPLOAD_ERR_OK) {
                        FlashMessage::error("Error uploading one of the files.");
                        continue;
                    }

                    $result = FileUploadHelper::upload($file, $config);

                    if ($result->isSuccess()) {
                        $fileName = $result->getData()['filename'];

                        try {
                            // save to database
                            $this->car_image_model->addImage($carId, $fileName);
                            FlashMessage::success("Successfully uploaded: {$fileName}");
                        } catch (\Exception $imgException) {
                            FlashMessage::error("Error saving image: " . $imgException->getMessage());
                        }
                    } else {
                        FlashMessage::error($result->getMessage());
                    }
                }
            } else {
                error_log("No files uploaded or myfile not set");
            }

            // Display success message using FlashMessage::success()
            FlashMessage::success("Car created successfully!");

            // Redirect back to 'upload.index'
            return $this->redirect($request, $response, 'cars.index');
        } catch (\Exception $e) {
            // Display error message using FlashMessage::error()
            FlashMessage::error("Car creation failed. Please try again.");

            // Redirect back to 'auth.register' route
            return $this->redirect($request, $response, 'cars.create');
        }
    }

    /**
     * Summary of delete
     * Extracts the cars_id from the arguments section of the URI and sends it to
     * the CarsModel for removal from the database.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $cars_id = $args['cars_id'];

        if (is_numeric($cars_id)) {
            $result = $this->car_model->deleteCar($cars_id);

            if ($result !== 0) {
                FlashMessage::success("Car has been successfully deleted.");
            } else {
                FlashMessage::error("Cannot delete car, it has active reservations.");
            }
        } else {
            FlashMessage::error('Invalid car ID.');
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
        $cars_id = $args['cars_id'];

        // Get form data
        $formData = $request->getParsedBody();

        // Extract fields from $formData
        $brand = $formData['brand'] ?? '';
        $model = $formData['model'] ?? '';
        $year = $formData['year'] ?? '';
        $capacity = $formData['capacity'] ?? '';
        $approx_price = $formData['approx_price'] ?? '';
        $description = $formData['description'] ?? '';

        // Validate the values
        $errors = [];

        if (empty($brand)) {
            $errors[] = "Please fill out Brand field";
        }

        if (empty($model)) {
            $errors[] = "Please fill out Model field";
        }

        if (empty($year)) {
            $errors[] = "Please fill out Year field";
        }

        if (empty($capacity)) {
            $errors[] = "Please fill out Capacity field";
        }

        if (empty($approx_price)) {
            $errors[] = "Please fill out Price field";
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                FlashMessage::error($error);
            }

            return $this->redirect($request, $response, 'cars.edit', ['cars_id' => $cars_id]);
        }

        // If validation passes, update the car
        try {
            // Create car data array
            $carData = [
                'brand' => $brand,
                'model' => $model,
                'year' => $year,
                'capacity' => $capacity,
                'approx_price' => $approx_price,
                'description' => $description
            ];

            // Update the car
            $this->car_model->updateCar($cars_id, $carData);

            error_log("Car updated with ID: " . $cars_id);

            // Process file uploads (if any new files were uploaded)
            $uploadedFiles = $request->getUploadedFiles();

            if (isset($uploadedFiles['myfile']) && !empty($uploadedFiles['myfile'])) {
                $files = $uploadedFiles['myfile'];

                // Check if any files were actually uploaded
                $hasFiles = false;
                foreach ($files as $file) {
                    if ($file->getError() === UPLOAD_ERR_OK) {
                        $hasFiles = true;
                        break;
                    }
                }

                if ($hasFiles) {
                    error_log("Number of files: " . count($files));

                    $config = [
                        'directory' => APP_BASE_DIR_PATH . '/public/uploads/images',
                        'allowedTypes' => ['image/jpeg', 'image/png'],
                        'maxSize' => 2 * 1024 * 1024,
                        'filenamePrefix' => 'car_' . $cars_id . "_"
                    ];

                    foreach ($files as $file) {

                        if ($file->getError() !== UPLOAD_ERR_OK) {
                            if ($file->getError() !== UPLOAD_ERR_NO_FILE) {
                                FlashMessage::error("Error uploading one of the files.");
                            }
                            continue;
                        }

                        $result = FileUploadHelper::upload($file, $config);

                        if ($result->isSuccess()) {
                            $fileName = $result->getData()['filename'];

                            try {
                                // Add new image to database
                                $this->car_image_model->addImage($cars_id, $fileName);
                                FlashMessage::success("Successfully uploaded: {$fileName}");
                            } catch (\Exception $imgException) {
                                FlashMessage::error("Error saving image: " . $imgException->getMessage());
                            }
                        } else {
                            FlashMessage::error($result->getMessage());
                        }
                    }
                }
            } else {
                error_log("No new files uploaded");
            }

            // Display success message
            FlashMessage::success("Car updated successfully!");

            // Redirect back to cars index
            return $this->redirect($request, $response, 'cars.index');
        } catch (\Exception $e) {
            FlashMessage::error("Car update failed: " . $e->getMessage());

            return $this->redirect($request, $response, 'cars.edit', ['cars_id' => $cars_id]);
        }
    }

    /**
     * Display the edit form for a car
     */
    public function edit(Request $request, Response $response, array $args): Response
    {
        $cars_id = $args['cars_id'];

        $car = $this->car_model->fetchCarByID($cars_id);
        $car_images = $this->car_image_model->fetchImagesById($cars_id);

        // Debug logging
        error_log("=== EDIT METHOD DEBUG ===");
        error_log("Car ID: " . $cars_id);
        error_log("Car data: " . print_r($car, true));
        error_log("Car images: " . print_r($car_images, true));
        error_log("Number of images: " . (is_array($car_images) ? count($car_images) : 'not an array'));

        if (!$car) {
            FlashMessage::error("Car not found.");
            return $this->redirect($request, $response, 'cars.index');
        }

        $data['data'] = [
            'title' => 'Edit Car',
            'car' => $car,
            'car_images' => $car_images ?? []
        ];

        return $this->render($response, 'admin/cars/carEditView.php', $data);
    }
}
