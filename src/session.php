<?php

use Firebase\JWT\JWT;

$userCollections = [
    new User(1, 'Angga Ari Wijaya', 'angga', 'anggaari', 'customer'),
    new User(2, 'Valerian Aditya', 'valerian', 'valerian123', 'manager'),
    new User(3, 'Diana Eka', 'diana', 'diana', 'manager'),
];

class User {
    public $id;
    public $name;
    public $username;
    public $password;
    public $role;

    public function __construct(int $id, string $name, string $username, string $password, $role = 'customer')
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }
}

class Session
{
    public $username;
    public $role;

    public function __construct(string $username, string $role)
    {
        $this->username = $username;
        $this->role = $role;
    }
}

class SessionManager
{
    const SECRET_KEY = "ze7OGZYWAEKCW08z6KF4jLMfGz09PlLO";

    /**
     * User login and generate JWT token.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public static function login(string $username, string $password): bool
    {
        global $userCollections;
        foreach ($userCollections as $user) {
            if ($username == $user->username && $password == $user->password) {
                $payload = [
                    "username" => $user->username,
                    "role" => $user->role
                ];

                $jwt = JWT::encode($payload, SessionManager::SECRET_KEY, 'HS256');
                setcookie("X-APP-SESSION", $jwt, time() + 3600, "/", "localhost", true, true);

                return true;
            }
        }

        return false;
    }

    /**
     * Get current active session by signed provided token.
     *
     * @return Session
     * @throws Exception
     */
    public static function getCurrentSession(): Session
    {
        if (isset($_COOKIE['X-APP-SESSION'])) {
            $jwt = $_COOKIE['X-APP-SESSION'];
            try {
                $payload = JWT::decode($jwt, SessionManager::SECRET_KEY, ['HS256']);
                return new Session($payload->username, $payload->role);
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        } else {
            throw new Exception("User is not login");
        }
    }

}
