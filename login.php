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
        // token already stored in secure cookie, this token passed for example to store jwt on another secure storage
        header('Location: /index.php?token=' . $_SESSION['X-APP-SESSION']);
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
<div class="container py-4">
    <div class="card mx-auto" style="width: 22rem;">
        <div class="card-body p-4">
            <h3 class="mb-3 fw-bold">Login</h3>
            <?php if ($message): ?>
                <div class="alert alert-warning py-2">
                    <?= $message ?>
                </div>
            <?php endif; ?>
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
    </div>
</div>
</body>
</html>
