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
    public function fetchImagesById($id): mixed {
        $sql = "SELECT * FROM car_images WHERE car_id = :id";

        $car_images = $this->selectAll($sql, [':id' => $id]);

        return $car_images;
    }
}
