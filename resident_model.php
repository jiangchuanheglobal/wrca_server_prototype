<?php
# handle resident table operation

class ResidentModel 
{
    // properties
    private $link_identifier=''; 

    // methods
    public function get_error() {
        return mysql_error();
    }
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
    
    public function contains_email($email) {
        if (!isset($this->link_identifier)) {
            echo 'err';
            return false;
        }

        $query = "SELECT * FROM resident WHERE email='$email'";
        $result = mysql_query($query);
        if (mysql_num_rows($result) == 0) {
            return false;
        }
        return true;
    }
    public function get_row_by_email($email) {
        if (!$this->link_identifier) {
            return false;
        }

        $query = "SELECT * FROM resident WHERE email = '$email'";
        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
    }
}
?>
