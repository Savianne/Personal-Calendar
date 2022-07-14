<?php
session_start();

// header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/get-user-info.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/select-all-events.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/get-user-id-by-email.class.php';

try {
    // if(!(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')) {
    //     throw new Exception('Cannot Get Request!');
    //     exit();
    // }

    if(!isset($_SESSION['access_token'])) {
        throw new Exception('unauthenticated!');
    }

    $email = getUserInfoFromGoogleClientService($client, $_SESSION['access_token'])['email'];

    $user_id = new GetUserIdByEmail($email); 

    $user_id_query = $user_id->query(); 

    if(isset($user_id_query['error'])) {
        throw new Exception($user_id_query['error']);
        exit();
    }

    $select_all_events = new SelectAllEvents($user_id_query);

    $select_all_events_query = $select_all_events->query();

    if(isset($select_all_events_query['error'])) {
        throw new Exception($select_all_events_query['error']);
        exit();
    }

    $response = array(
        'events' => $select_all_events_query['data']
    );

    echo json_encode($response, JSON_PRETTY_PRINT);
}
catch(Exception $e) {
    $response = array(
        'error' => $e->getMessage(),
    );
    
    echo json_encode($response, true);
}