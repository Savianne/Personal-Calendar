<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/get-event-foreign-ids.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/delete-event.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/delete-event-info.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/delete-event-repeat-rule.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controller/delete-event-time.class.php';

try {
    if(!(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')) {
        throw new Exception('Cannot Get Request!');
        exit();
    }

    $json = file_get_contents('php://input');

    $event_id = json_decode($json, true);

    $event_id = $event_id['event_id'];


    $get_event_foreign_ids = new GetEventForeinIds($event_id);

    $get_event_foreign_ids_query = $get_event_foreign_ids->query();
    if(isset($get_event_foreign_ids_query['error'])) {
        throw new Exception($get_event_foreign_ids_query['error']);
        exit();
    }

    $foreign_ids = $get_event_foreign_ids_query['data'];

    $delete_event = new DeleteEvent($event_id);
    
    $delete_event_query = $delete_event->query();
    if(isset($delete_event_query['error'])) {
        throw new Exception($delete_event_query['error']);
        exit();
    }

    $delete_event_info = new DeleteEventInfo($foreign_ids['event_info_id']);
    
    $delete_event_info_query = $delete_event_info->query();
    if(isset($delete_event_info_query['error'])) {
        throw new Exception($delete_event_info_query['error']);
        exit();
    }

    if($foreign_ids['rule_id']) {
        $delete_event_rule = new DeleteEventRepeatingRule($foreign_ids['rule_id']);
        
        $delete_event_rule_query = $delete_event_rule->query();
        if(isset($delete_event_rule_query['error'])) {
            throw new Exception($delete_event_rule_query['error']);
            exit();
        }        
    }
    

    $delete_event_time = new DeleteEventTime($foreign_ids['event_id']);
    
    $elete_event_time_query = $delete_event_time->query();
    if(isset($elete_event_time_query['error'])) {
        throw new Exception($elete_event_time_query['error']);
        exit();
    }

    $response = array(
        'success' => true,
    );


    echo json_encode($response, JSON_PRETTY_PRINT);
}
catch(Exception $e) {
    $response = array(
        'error' => $e->getMessage(),
    );
    
    echo json_encode($response, true);
}

