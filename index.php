<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/session.php';

try {
    $session = SessionManager::getCurrentSession();
} catch (Exception $exception) {
    header('Location: /login.php');
    exit(0);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JWT Cookie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
</head>
<body>
<div class="container py-4">
    <div class="card mx-auto w-75">
        <div class="card-body p-4">
            <h3>Hello <strong class="text-success"><?= $session->username ?></strong> as <strong class="text-info"><?= $session->role ?></strong></h3>
            <p>Welcome to your dashboard</p>
            <div class="mb-4" id="user-profile">
                Fetching user profile...
            </div>
            <a href="logout.php" class="btn btn-sm btn-danger px-3">Logout</a>
        </div>
    </div>
</div>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('token')) {
        localStorage.setItem("X-APP-JWT", urlParams.get('token') || '');
    }

    const userProfile = document.getElementById('user-profile');
    fetch('src/user.php', {
        method: "GET",
        cache: 'no-cache',
        headers: {
            // !! only use another storage if can't use secure cookie (don't forget to encrypt)
            'Authorization': 'Bearer ' + (localStorage.getItem("X-APP-JWT") || ''),
            'Accept': 'application/json',
        },
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === 401) {
                window.location.replace("/logout.php");
            }
            userProfile.innerHTML = "";
            for (const property in result.data) {
                if (result.data.hasOwnProperty(property)) {
                    const template = `
                        <div class="row border-bottom">
                            <label class="col-sm-2 col-form-label fw-bold">${capitalize(property)}</label>
                            <div class="col-sm-10">
                                <p class="form-control-plaintext">${result.data[property]}</p>
                            </div>
                        </div>
                    `;
                    userProfile.insertAdjacentHTML('beforeend', template);
                }
            }
        })
        .catch(console.log);

    function capitalize(word) {
        const lower = word.toLowerCase();
        return word.charAt(0).toUpperCase() + lower.slice(1);
    }
    console.log(document.cookie)
</script>
</body>
</html>
