<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/get-user-info.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/config.php';

header('Content-Type: application/json');


// if(!(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')) {
//     $flag = array(
//         'error' => 'Cannot Get Request!',
//     );

//     echo json_encode($flag, true);
//     die();
// }

if(!isset($_SESSION['access_token'])) {
    $flag = array(
        'error' => 'unauthenticate',
    );

    echo json_encode($flag, true);
    die();
}

$token = $_SESSION['access_token'];

$user_info = getUserInfoFromGoogleClientService($client, $token);

$flag = array(
    'user_info' => $user_info
);

echo json_encode($flag, true);