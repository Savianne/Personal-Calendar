<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class EditEventTime extends Connection {
    function __construct($event) {
        $this->event = $event;
    }

    function query() {
        try {
            $conn = $this->use_connection();
    
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }

            $stmt = $conn->prepare('UPDATE eventtime SET start_time = ?, length_in_min = ? WHERE event_id = ?');
            $stmt->bind_param('i', $start_time, $length_in_min);
            $start_time = $this->event['start_time'];
            $length_in_min = $this->event['length_in_min'];
            $stmt->execute();
            
            $stmt->close();
            $conn->close();

            return array(
                'success' => true
            );
        } 
        catch(Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
    }
}