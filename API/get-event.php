<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/get-event-by-id.class.php';

try {
    if(!(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST')) {
        throw new Exception('Cannot Get Request!');
        exit();
    }

    $json = file_get_contents('php://input');

    $event_id = json_decode($json, true);


    $get_event = new GetEventById($event_id);
    $get_event_query = $get_event->query();
    if(isset($get_event_query['error'])) {
        throw new Exception($get_event_query['error']);
        exit();
    }

    $response = array(
        'event' => $get_event_query['data']
    );

    echo json_encode($response, JSON_PRETTY_PRINT);
}
catch(Exception $e) {
    $response = array(
        'error' => $e->getMessage(),
    );
    
    echo json_encode($response, true);
}

