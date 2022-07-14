<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class DeleteEventInfo extends Connection {
    function __construct($event_info_id) {
        $this->event_info_id = $event_info_id;
    }

    function query() {
        try {
            $conn = $this->use_connection();
    
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }

            $stmt = $conn->prepare('DELETE FROM eventinfo WHERE event_info_id = ?');
            $stmt->bind_param('i', $this->event_info_id);
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