<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class CarImageModel extends BaseModel
{
    public function __construct(PDOService $pdoservice) {
        parent::__construct($pdoservice);
    }

    /**
     * Summary of fetchImages
     * Fetched all car images from the database
     * @return array
     */
    public function fetchImages(): mixed {
        $sql = "SELECT * FROM car_images";

        $car_images = $this->selectAll($sql);
        return $car_images;
    }

    /**
     * Summary of fetchImagesById
     * Fetched a list of car images that belong to a specific car's ID
     * This will be used to load the car images in the view cars page
     * @param mixed $id
     * @return array
     */
    public function fetchImagesById($car_id): mixed {
        $sql = "SELECT * FROM car_images WHERE car_id = :id";

        $car_images = $this->selectAll($sql, [':id' => $car_id]);

        return $car_images;
    }

    /**
     * Summary of upload
     * Uploads the image path to the database linked by the car_id
     * @param mixed $car_id
     * @return void
     */
    public function upload($car_id) {

    }
}
