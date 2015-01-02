<?php
# handle user table operation

class UserModel {

    // properties
    private $link_identifier = '';
    
    // methods
    public function create_connection() {
        require_once './config.php';
        $this->link_identifier = mysql_connect(Config::$server, Config::$username, Config::$password);

        if (!$this->link_identifier) {
            return false;
        }
        $result = mysql_select_db(Config::$database, $this->link_identifier);
        if (!$result) {
            return false;
        }
        return true;
    }
    public function destroy_connection() {
        if (!$this->link_identifier) {
            return;
        }
        mysql_close($this->link_identifier);
        $this->link_identifier = '';
    }
    
    public function insert_row($email, $password, $token) {
        if (!$this->link_identifier) {
            return false;
        }

        $query = "INSERT INTO user (id, email, password, token) VALUES (default, '$email', '$password',  '$token')";
        $result = mysql_query($query);
        if (!$result) {
            return false;
        }
        return true;
    }
    public function update_row($email, $password, $token) {
        if (!$this->link_identifier) {
            return false;
        } 
        $query = "UPDATE user set password='$password', token='$token' WHERE email='$email'";
        $result = mysql_query($query);
        if (!$result) {
            return false;            
        }
        return true;
    }

    public function get_row_by_email($email) {
        if (!$this->link_identifier) {
            return false;
        }

        $query = "SELECT * FROM user WHERE email = '$email'";
        $result = mysql_query($query);
        if (!$result) {
            return NULL;
        }

        return mysql_fetch_assoc($result);
    }
    public function contains_email($email) {
        if (!$this->link_identifier) {
            return false;
        }

        $query = "SELECT * FROM user WHERE email='$email'";
        $result = mysql_query($query);

        if (mysql_num_rows($result) == 0) {
            return false;
        }
        return true;
    }
    
    public function get_error() {
        return mysql_error();
    }
    public function size() {
         if (!$this->link_identifier) {
             return 0;
         }
         $query = "SELECT * FROM user";
         $result = mysql_query($query);
         return mysql_num_rows($result);
    }

    public function dump() {
        
    }
    
}
?>
