<?php

session_start();

// header("Access-Control-Allow-Origin: *");

header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/config.php';

if(!(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')) {
    $flag = array(
        'error' => 'Cannot Get Request!',
    );

    echo json_encode($flag, true);
    die();
}

$flag = array(
    'auth_url' => $client->createAuthUrl(),
);

echo json_encode($flag, true);
