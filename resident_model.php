<?php
# handle resident table operation

class ResidentModel {
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
    
    public function contains_email($email) {
        if (!$link_identifier) {
            return false;
        }

        $query = "SELECT * FROM resident WHERE email='$email'";
        $result = mysql_db_query($query);

        if (mysql_num_rows($result) == 0) {
            return false;
        }
        return true;
    }
    public function get_row_by_email($email) {
        if (!$link_identifier) {
            return false;
        }

        $query = "SELECT * FROM resident WHERE email = $email";
        $result = mysql_db_query($query);
        if (!$result) {
            return NULL;
        }

        return mysql_fetch_assoc($result);
    }

}
?>
