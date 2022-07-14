<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/config.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/db_config.php';

//Controller
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/check-user-exist-in-db.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/add-user-to-db.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/get-user-info.php';

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(isset($_GET["code"])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

    if(!isset($token['error'])) {
        $user_info = getUserInfoFromGoogleClientService($client, $token);
        $email = $user_info['email'];
        
        if(!chechUserIfExist($conn, $email)) {
            $adduser = addUserEmailToDb($conn, $email);
            if($adduser['error']) {
                echo $adduser['error'];
                header("Location: /");
                exit();
            }
        }
        
        $_SESSION['access_token'] = $token['access_token']; 

        header("Location: /");
        $conn->close();
        exit();

    } else {
        header("Location: /");
    }
}
