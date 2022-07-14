<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class GetEventForeinIds Extends Connection {
    function __construct($event_id) {
        $this->event_id = $event_id;
    }

    function query() {
        try {
            $conn = $this->use_connection();
    
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }
    
            // prepare and bind
            $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");

            $stmt->bind_param("i", $this->event_id);
            $stmt->execute();

            $result = $stmt->get_result(); // get the mysqli result
            $event = $result->fetch_assoc(); // fetch data

            $stmt->close();
            $conn->close();

            return array(
                'data' => $event,
            );
        } 
        catch(Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
    }

}
