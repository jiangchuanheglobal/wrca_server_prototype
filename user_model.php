<?php
# handle user table operation

class UserModel {

    // properties
    private $link_identifier = '';

    private $server = 'localhost';
    private $username = 'root';
    private $password = '';

    private $database_name = 'test';

    // methods
    public function create_connection() {
        $link_identifier = mysql_connect($server, $username, $password);
        if (!$link_identifier) {
            echo mysql_error();
            return false;
        }
        $result = mysql_select_db($database_name, $link_identifier);
        if (!$result) {
            echo 'select db error';
            return false;
        }
        return true;
    }
    public function destroy_connection() {
        if (!$link_identifier) {
            return;
        }
        mysql_close($link_identifier);
    }
    
    public function insert_row($email, $password, $token) {
        if (!$link_identifier) {
            return false;
        }

        $query = "INSERT INTO user (id, email, password, token) VALUES (default, $email, $password,  $token)"
        $result = mysql_db_query($query);
        if (!$result) {
            return false;
        }
        return true;
    }
    public function update_row($email, $password, $token) {
        if (!$link_identifier) {
            return false;
        } 
        $query = "UPDATE wrca set password='$password', token='$token' WHERE email='$email'";
        $result = mysql_query($query, $link_identifier);
        if (!$result) {
            return false;            
        }
        return true;
    }

    public function get_row_by_email($email) {
        if (!$link_identifier) {
            return false;
        }

        $query = "SELECT * FROM user WHERE email = $email";
        $result = mysql_db_query($query);
        if (!$result) {
            return NULL;
        }

        return mysql_fetch_assoc($result);
    }
    public function contains_email($email) {
        if (!$link_identifier) {
            return false;
        }

        $query = "SELECT * FROM user WHERE email='$email'";
        $result = mysql_db_query($query);

        if (mysql_num_rows($result) == 0) {
            return false;
        }
        return true;
    }

    public function size() {
         if (!$link_identifier) {
             return 0;
         }
         $query = "SELECT * FROM user";
         $result = mysql_db_query($query);
         return mysql_num_rows($result);
    }

    public function dump() {
        
    }
    
}
?>
