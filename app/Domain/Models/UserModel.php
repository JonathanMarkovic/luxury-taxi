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

    public function updateUser($user_id, array $data): int
    {
        $sql = "UPDATE users
        SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone
        WHERE id = :id";

        return $this->execute($sql, ['id' => $user_id, 'first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'phone' => $data['phone']]);
    }

    /**
     * Summary of createCustomerAndGetId
     * Creates a user in the database
     * @param array $data
     * @return void
     */
    public function createCustomerAndGetId(array $data)
    {
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES (:first_name, :last_name, :email, :phone, :password, :role)";

        // TODO: Need to include the hashing of the password here
        $this->execute($sql, ['first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'phone' => $data['phone'], 'password' => $data['password'], 'role' => 'customer']);
    }

    /**
     * Summary of createGuestAndGetId
     * Creates a user in the database
     * @param array $data
     * @return void
     */
    public function createGuestAndGetId(array $data)
    {
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role) VALUES (:first_name, :last_name, :email, :phone, :password, :role)";

        // TODO: Need to include the hashing of the password here
        $this->execute($sql, ['first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'phone' => $data['phone'], 'password' => $data['password'], 'role' => 'guest']);
    }

    /**
     * Summary of promoteAdmin
     * Promotes a user to an admin
     * Used if the admin wants to hire someone. They can make an account
     * and then an existing admin can promote them to an admin.
     * @param mixed $user_id
     * @return int
     */
    public function promoteAdmin($user_id): int
    {
        $sql = "UPDATE users
                SET role = admin
                WHERE user_id = :user_id";
        return $this->execute($sql, ['user_id' => $user_id]);
    }

    /**
     * Create a new user account.
     *
     * @param array $data User data (first_name, last_name, email, phone, password, role)
     * @return int The ID of the newly created user
     */
    public function createUser(array $data): int {
        $sql = "INSERT INTO users (first_name, last_name, email, phone, password, role)
                VALUES (:first_name, :last_name, :email, :phone, :password, :role";

        $password = $data['password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $this->execute($sql, [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'role' => $data['role'],
        ]);

        return $this->pdo->lastInsertId();
    }

    public function findByEmail(string $email): ?array {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        return $this->selectOne($sql, ['email' => $email]);
    }

    public function emailExists(string $email): bool {
        $sql = "SELECT COUNT(*) as count FROM_users WHERE email = :email";
        $numResults = $this->selectOne($sql, ['email' => $email]);
        if ($numResults > 0) {
            return true;
        }

        return false;
    }
}
