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
        $sql = "SELECT * FROM cars WHERE cars_id = :id";
        $car = $this->selectOne($sql, ['id' => $car_id]);
        return $car;
    }

    /**
     * Summary of createAndGetId
     * Inserts the car into the database and
     * returns it's ID
     * @param mixed $data
     * @return bool|string
     */
    public function createAndGetId($data): int
    {
        $sql = "INSERT INTO cars (brand, model, year, capacity, approx_price) VALUES (:brand, :model, :year, :capacity, :approx_price)";
        $this->execute($sql, [
            'brand' => $data['brand'],
            'model' => ['model'],
            'year' => $data['year'],
            'capacity' => $data['capacity'],
            'approx_price' => $data['approx_price']
        ]);

        $last_id = $this->pdo->lastInsertId();
        return $last_id;
    }

    /**
     * Summary of deleteCar
     * Deletes a car from the database based on
     * ID
     * @param mixed $car_id
     * @return void
     */
    public function deleteCar($car_id): int
    {
        $sql = "DELETE FROM cars WHERE car_id = :car_id";
        return $this->execute($sql, ['car_id' => $car_id]);
    }

    /**
     * Summary of updateCar
     * Updates a car from the car table based
     * on ID
     * @param mixed $car_id
     * @param mixed $data
     * @return int
     */
    public function updateCar($car_id, $data): int
    {
        $sql = "UPDATE cars
                SET brand = :brand,
                model = :model,
                year = :year,
                capacity = :capacity,
                approx_price = :approx_price";
        return $this->execute($sql, [
            'brand' => $data['brand'],
            'model' => ['model'],
            'year' => $data['year'],
            'capacity' => $data['capacity'],
            'approx_price' => $data['approx_price']
        ]);
    }
}
