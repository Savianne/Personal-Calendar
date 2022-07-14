<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/config.php';

if(!isset($_SESSION['access_token'])) {
    header('Location: /');
    exit();
}

$client->revokeToken();

session_destroy();

header('Location: /');