<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/db_config.php';

class Connection {
    protected $db_host = DB_HOST;
    protected $db_user = DB_USERNAME;
    protected $db_password = DB_PASSWORD;
    protected $db_name = DB_NAME;

    protected function use_connection() {
        return $conn = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);
    }
}