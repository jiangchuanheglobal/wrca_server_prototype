<?php
# utility suite

class Utility {

    public static function hash_SSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    public static function get_hash_SSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

    public static function get_date_range_of_this_week() {
        date_default_timezone_set('America/New_York');
        $range['start'] = date("Y-m-d", time());
        $range['end'] = date("Y-m-d", strtotime('Sunday'));
        return $range;
    }
    public static function get_date_range_of_this_month() {
        date_default_timezone_set('America/New_York');
        $range['start'] = date("Y-m-d", time());
        $today =  date("Y-m-d H:i:s", time());
        $range['end'] = date("Y-m-t", strtotime($today));
        return $range;
    }
}
?>
