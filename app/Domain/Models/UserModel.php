<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class UserModel extends BaseModel
{
    public function __construct(PDOService $pdoservice)
    {
        parent::__construct($pdoservice);
    }

    /**
     * Summary of fetchUsers
     * Fetches all the users from the database
     * This would be used by the admin to see who has used the service recently
     * @return array
     */
    public function fetchUsers(): mixed
    {
        $sql = "SELECT * FROM users";
        $users = $this->selectAll($sql);
        return $users;
    }

    /**
     * Summary of fetchUserById
     * Fetches a single user from the database based on their ID
     * @param mixed $user_id
     * @return array
     */
    public function fetchUserById($user_id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";

        $user = $this->selectOne($sql, [':id' => $user_id]);

        return $user;
    }
}
