<?php
# router, business logic

class controller {

    // properties
    private static final field_verification_code = 'verification_code';
    // methods

    public static router() {

    }
    public static function on_requst_verification_code($email) {
        $residentModel = new ResidentModel();
        $result = $residentModel->create_connection();
        if (!$result) {
            $response = array('success' => 0, 'message' => 'create connection failed.');
            echo json_encode($response);
            return 
        }
        
        $result = $residentModel->contains_email($email);
        if (!$result) {
            $response = array('success' => 0, 'message' => 'cannot find email.');
            echo json_encode($response);
            $residentModel->destroy_connection();
            return;
        }
        
        $result = Email::send($email, $content);
        if (!$result) {
            $response = array('success' => 0, 'message' => 'send email fail.');
            echo json_encode($response);
            $residentModel->destroy_connection();
            return;
        }
        $response = array('success' => 1, 'message' => 'please check your email box.');
        echo json_encode($response);
        $residentModel->destroy_connection();
    }
    public static function on_register($email, $password, $verification_code) {
        $residentModel = new ResidentModel();
        if (!$result) {
            $response = array('success' => 0, 'message' => 'create connection failed.');
            echo json_encode($response);
            return; 
        }
        $result = residentModel->contains_email($email);
        $row = $residentModel->get_row_by_email($email);
        if ($row["verification_code"] != $verification_code) {
            $response = array('success' => 0, 'message' => 'verification code not match.'); 
            echo json_encode($response);
            $residentModel->destroy_connection();
            return;
        }

        $userMode = new UserModel();
        //$hash = Utility::hash_SSHA($password);
        //$encrypted_password = $hash["encrypted"]; // encrypted password
        //$salt = $hash["salt"]; // salt
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $result = $userMode->insert_row($email, $password, $token);
        if (!$result) {
            $response = array('success' => 0, 'message' => 'insert row error.'); 
            echo json_encode($response);
            $residentModel->destroy_connection();
            return;
            
        }
        $response = array('success' => 1, 'message' => 'register ok.'); 
        echo json_encode($response);
        $residentModel->destroy_connection();
    }
    public static function on_login($email, $password) {
        $userModel = new UserModel();
        $result = $userModel->create_connection();
        if (!$result) {
            $response = array('success' => 0, 'message' => 'create connect failed'); 
            echo json_encode($response);
            return;

        }
        $result = $userModel->contains_email($email);
        if (!$result) {
            $response = array('success' => 0, 'message' => 'cannot find email'); 
            echo json_encode($response);
            $residentModel->destroy_connection();
            return;
        }
        $row = $userMode->get_row_by_email($email);
        if ($row["password"] != $password) {
            $response = array('success' => 0, 'message' => 'password not match'); 
            echo json_encode($response);
            $residentModel->destroy_connection();
            return;
        }
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $userMode->update_row($email. $password, $token);
        $response = array('success' => 1, 'message' => 'login ok', 'token' => '$token'); 
        echo json_encode($response);
        $userMode->destroy_connection();
    }
    public static function on_retrieve_password($email) {
        $userMode = new UserModel();
        $result = $userModel->create_connection();
        if (!$result) {
            $response = array('success' => 0, 'message' => 'create connect failed'); 
            echo json_encode($response);
            return;
        }

        $result = $userModel->contains_email($email);
        if (!$result) {
            $response = array('success' => 0, 'message' => 'cannot find email'); 
            echo json_encode($response);
            $residentModel->destroy_connection();
            return; 
        } 
        $row = $userMode->get_row_by_email($email);
        Email::send($email, $row["password"]);
        $response = array('success' => 1, 'message' => 'please check your email box'); 
        echo json_encode($response);
        $residentModel->destroy_connection();
    }

    public static function on_request_events() {
        
    }

}
?>
