<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class DeleteEventRepeatingRule extends Connection {
    function __construct($rule_id) {
        $this->rule_id = $rule_id;
    }

    function query() {
        try {
            $conn = $this->use_connection();
    
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }

            $stmt = $conn->prepare('DELETE FROM repeatingrules WHERE rule_id = ?');
            $stmt->bind_param('i', $this->rule_id);
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