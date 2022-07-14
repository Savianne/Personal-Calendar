<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/controller/connection.class.php';

class GetUserInfo Extends Connection {
    function __construct($user_email) {
        $this->user_email = $user_email;
    }

    function query() {
        try {
            $conn = $this->use_connection();
    
            if($conn->connect_error) {
                throw new Exception($conn->connect_error);
                exit();
            }
    
            // prepare and bind
            $stmt = $conn->prepare("SELECT user_id FROM user WHERE user_email = ?");
            $stmt->bind_param("s", $this->user_email);
            $stmt->execute();

            $result = $stmt->get_result(); // get the mysqli result
            $id = $result->fetch_assoc(); // fetch data

            $stmt->close();
            $conn->close();

            return $id['user_id'];
        } 
        catch(Exception $e) {
            return array(
                'error' => $e->getMessage()
            );
        }
    }

}
