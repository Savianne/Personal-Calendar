<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class SetEventTime extends Connection{
    function __construct($event_id, $start_time, $length_in_min) {
        $this->event_id = $event_id;
        $this->start_time = $start_time;
        $this->length_in_min = $length_in_min;
    }

    function query() {
        try {
            $conn = $this->use_connection();
    
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }
    
            $stmt = $conn->prepare('INSERT INTO eventtime (event_id, start_time, length_in_min) VALUES (?, ?, ?)');
            $stmt->bind_param('iss', $this->event_id, $this->start_time, $this->length_in_min);
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