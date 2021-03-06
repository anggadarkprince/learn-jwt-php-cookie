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
                $_SESSION['X-APP-SESSION'] = $jwt; // emulate return of jwt
                setcookie("X-APP-SESSION", $jwt, time() + 3600, "/", "localhost", true, true);

                return true;
            }
        }

        return false;
    }

    private static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { // Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) { // Apache
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    private static function getBearerToken() {
        $headers = self::getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Get current active session by signed provided token.
     *
     * @return Session
     * @throws Exception
     */
    public static function getCurrentSession(): Session
    {
        $jwt = self::getBearerToken() ?? $_COOKIE['X-APP-SESSION'] ?? '';
        if (!empty($jwt)) {
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
