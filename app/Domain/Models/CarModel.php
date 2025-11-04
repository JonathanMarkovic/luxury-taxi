<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class CarModel extends BaseModel
{
    public function __construct(PDOService $pdoservice)
    {
        parent::__construct($pdoservice);
    }

    /**
     * Summary of fetchCars
     * Fetches all the cars from the database
     * @return array
     */
    public function fetchCars(): mixed
    {
        $sql = "SELECT * FROM cars";

        $cars = $this->selectAll($sql);
        return $cars;
    }

    /**
     * Summary of fetchCarByID
     * Fetches a single car based on ID
     * @param mixed $car_id
     * @return array|bool
     */
    public function fetchCarByID($car_id): mixed
    {
        $sql = "SELECT * FROM cars WHERE cars_id = $car_id";
        $car = $this->selectOne($sql);
        return $car;
    }
}
