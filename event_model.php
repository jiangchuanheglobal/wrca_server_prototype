<?php
class EventModel {
    private $link_identifier;
    
    public function create_connection() {
        require_once './config.php';
        $this->link_identifier = mysql_connect(Config::$server, Config::$username, Config::$password);
        if (!$this->link_identifier) {
            return false;
        }
        mysql_set_charset("utf8", $this->link_identifier); // json_encode requires the string to be utf8
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

    public function  add_row() {

    }

    public function remove_row() {
        
    }

    public function update_row() {
        
    }

    public function get_row_by_id($id) {
        if (!$this->link_identifier) {
            return false;
        }        

        $query = "SELECT * FROM event WHERE id='$id'";
        $result = mysql_query($query, $link_identifier);
        if (!$result) {
            return false;
        }
        return mysql_fetch_assoc($result);
    }

    public function get_rows_by_id_range($low, $high) {
        if ($low > $high) {
            return false;
        }
        if (!$this->link_identifier) {
            return false;
        }
        $query = "SELECT * FROM event WHERE id BETWEEN '$low' AND '$high'";
        $result = mysql_query($query, $this->link_identifier);
        if (!$result) {
            return false;
        }
        while ($row = mysql_fetch_assoc($result)) {
            $arr[] = $row;
        }
        return $arr;
    }

    public function get_rows_by_date_range($start, $end) {
        if ($start > $end) {
            return false;
        }
        if (!$this->link_identifier) {
            return false;
        }
        $query = "SELECT * FROM event WHERE time BETWEEN '$start' AND '$end'";
        $result = mysql_query($query, $this->link_identifier);
        if (!$result) {
            return false;
        }
        while ($row = mysql_fetch_assoc($result)) {
            $arr[] = $row;
        }
        return $arr;

    }
    public function get_error() {
       return mysql_error(); 
    }

    public function dump() {
        
    }
}
?>
