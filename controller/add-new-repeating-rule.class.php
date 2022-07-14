<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class AddNewRepeatingRule extends Connection {
    function __construct($rule, $end_date) {
        $this->rule = $rule;
        $this->end_date = $end_date;
    }

    function query() {
        try {
            $conn = $this->use_connection();
    
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }
    
            $stmt = $conn->prepare('INSERT INTO repeatingrules (rule, end_date) VALUES (?, ?)');
            $stmt->bind_param('ss', $this->rule, $this->end_date);
            $stmt->execute();
            
            $rule_id = $stmt->insert_id;
            
            $stmt->close();
            $conn->close();

            return array(
                'rule_id' => $rule_id
            );
        } 
        catch(Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
    }
}