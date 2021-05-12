## JWT with PHP and secure cookie

### Encode JWT
Create token from payload data and secret key
```
$payload = [
    "id" => 1,
    "email" => "anggadarkprince@gmail.com",
    "role" => "customer"    
];
$jwt = JWT::encode($payload, 'SECRET_KEY', 'HS256');
setcookie("X-APP-SESSION", $jwt, time() + 3600, "/", "localhost", true, true)
```

### Decode JWT
Extract data from secure cookie
```
$jwt = $_COOKIE['X-APP-SESSION'] ?? '';
$payload = JWT::decode($jwt, 'SECRET_KEY', ['HS256']);
```