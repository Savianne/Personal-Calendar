<?php

session_start();

header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/get-user-id.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/add-new-event-info.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/add-new-repeating-rule.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/set-event-time.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/insert-new-event.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/GoogleOauth2Client/get-user-info.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/get-user-id-by-email.class.php';

try {
    if(!(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')) {
        throw new Exception('Cannot Get Request!');
        exit();
    }

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

    $json = file_get_contents('php://input');

    $data = json_decode($json, true);

    $add_event_info = new AddNewEventInfo($data['title'], $data['date']);
    $add_event_info_query = $add_event_info->query();
    if(isset($add_event_info_query['error'])) {
        throw new Exception($add_event_info_query['error']);
        exit();
    }

    $repeating_rule_id = null;
   
    if(isset($data['repeating'])) {
        $add_repeating_rule = new AddNewRepeatingRule($data['repeating']['rule'], isset($data['repeating']['end_date'])? $data['repeating']['end_date'] : null);
        $add_repeating_rule_query = $add_repeating_rule->query();
        if(isset($add_repeating_rule_query['error'])) {
            throw new Exception($add_repeating_rule_query['error']);
            exit();
        }

        $repeating_rule_id = $add_repeating_rule_query['rule_id'];
    }

    $insert_event = new InsertNewEvent(
        $add_event_info_query['event_info_id'], 
        $repeating_rule_id,
        $user_id_query
    );

    $insert_event_query = $insert_event->query();

    if(isset($insert_event_query['error'])) {
        throw new Exception($insert_event_query['error']);
        exit();
    }

    if(isset($data['time'])) {
        $set_time = new SetEventTime($insert_event_query['event_id'], $data['time']['start_time'], $data['time']['length_in_min']);
        $set_time_query = $set_time->query();
        if(isset($set_time_query['error'])) {
            throw new Exception($set_time_query['error']);
            exit();
        }
    }

    $response = array(
        'success' => true,
        'event_id' => $insert_event_query['event_id'],
    );
    
    echo json_encode($response, true);
}
catch(Exception $e) {
    $response = array(
        'error' => $e->getMessage(),
    );
    
    echo json_encode($response, true);
}




