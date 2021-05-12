<?php
require_once __DIR__ . '/vendor/autoload.php';

setcookie('X-APP-SESSION', 'LOGOUT', 0, "/", "localhost", true, true);

header('Location: /login.php');