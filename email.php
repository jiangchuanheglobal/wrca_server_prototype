<?php
# email operation helper

class Email {
    public static function send($to, $subject, $message) {
        $headers = "From: jiangchuanheglobal@gmail.com" . "\r\n";
        return mail($to,$subject,$message,$headers); 
    }
}
?>
