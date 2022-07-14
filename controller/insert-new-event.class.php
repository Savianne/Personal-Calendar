<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class InsertNewEvent extends Connection {
    function __construct($event_info_id, $rule_id, $user_id) {
        $this->event_info_id = $event_info_id;
        $this->rule_id = $rule_id;
        $this->user_id = $user_id;
    }

    function query() {
        try {
            $conn = $this->use_connection();
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }

            $stmt = $conn->prepare('INSERT INTO events (event_info_id, rule_id, user_id) values (?, ?, ?)');
            $stmt->bind_param('iii', $this->event_info_id, $this->rule_id, $this->user_id);
            $stmt->execute();

            $event_id = $stmt->insert_id;
            
            $stmt->close();
            $conn->close();

            return array(
                'event_id' => $event_id
            );
             
        }
        catch(Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
    }
}

 