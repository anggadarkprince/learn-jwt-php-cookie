<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/session.php';

try {
    $session = SessionManager::getCurrentSession();
    if (!empty($session)) {
        header('Location: /index.php');
    }
} catch (Exception $exception) {
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (SessionManager::login($_POST['username'], $_POST['password'])) {
        header('Location: /index.php');
        exit(0);
    } else {
        $message = "Login Failed";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JWT Cookie - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
</head>
<body>
<div class="container py-3">
    <?php if ($message) { ?>
        <p class="text-danger"><?= $message ?> </p>
    <?php } ?>
    <h3 class="mb-3 font-weight-bold">Login</h3>
    <form action="/login.php" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" class="form-control">
            <div class="form-text">We'll never share your data with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" class="form-control">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary px-4">Login</button>
        </div>
    </form>
</div>
</body>
</html>
