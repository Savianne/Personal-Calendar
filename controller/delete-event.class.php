<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class DeleteEvent extends Connection {
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

            $stmt = $conn->prepare('DELETE FROM events WHERE event_id = ?');
            $stmt->bind_param('i', $this->event_id);
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