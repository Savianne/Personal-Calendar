<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class AddNewEventInfo extends Connection{
    function __construct($title, $date) {
        $this->title = $title;
        $this->date = $date;
    }

    function query() {
        try {
            $conn = $this->use_connection();
    
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }
    
            $stmt = $conn->prepare('INSERT INTO eventinfo (title, date) VALUES (?, ?)');
            $stmt->bind_param('ss', $this->title, $this->date);
            $stmt->execute();
            
            $event_info_id = $stmt->insert_id;
            
            $stmt->close();
            $conn->close();

            return array(
                'event_info_id' => $event_info_id
            );
        } 
        catch(Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
    }
}