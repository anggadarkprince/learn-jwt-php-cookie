<?php
require_once __DIR__ . '/vendor/autoload.php';

setcookie('X-APP-SESSION', 'LOGOUT');

header('Location: /login.php');