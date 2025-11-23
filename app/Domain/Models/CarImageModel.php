<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class CarImageModel extends BaseModel
{
    public function __construct(PDOService $pdoservice)
    {
        parent::__construct($pdoservice);
    }

    /**
     * Summary of fetchImages
     * Fetched all car images from the database
     * @return array
     */
    public function fetchImages(): mixed
    {
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
    public function fetchImagesById($cars_id): mixed
    {
        $sql = "SELECT * FROM car_images WHERE cars_id = :id";

        $car_images = $this->selectAll($sql, [':id' => $cars_id]);

        return $car_images;
    }

    /**
     * Summary of upload
     * Uploads the image file path to the database for later loading
     * @param mixed $car_id
     * @param mixed $config array of all the information needed for the file upload
     * @param mixed $fileName
     * @return bool|string
     */
    public function upload($car_id, $config, $fileName): int
    {
        // The file path to be stored in the database
        $image_path = $config['directory'] . DIRECTORY_SEPARATOR . $fileName;

        $sql = "INSERT INTO car_images (car_id, image_path) VALUES (:car_id, :image_path)";

        $this->execute($sql, ['car_id' => $car_id, 'image_path' => $image_path]);

        $lastId = $this->pdo->lastInsertId();
        return $lastId;
    }

    public function addImage($cars_id, $image_path) {
        $sql = "INSERT INTO car_images (cars_id, image_path) VALUES (:cars_id, :image_path)";
        return $this->execute($sql, [
            'cars_id' => $cars_id,
            'image_path' => $image_path
        ]);
    }
}
