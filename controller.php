<?php
# router, business logic

class Controller {

    // properties
    //private static final $field_verification_code = 'verification_code';
    // methods

    public static function dispatch_get() {
        if (!isset($_GET['object'])) {
            $response = array('success' => 0, 'message' => 'get, object value not set'); 
            echo json_encode($response);
            return;
        }
        switch ($_GET['object']) {
            case 'verificationCode':
                Controller::on_request_verification_code($_GET['email']);
                break;
            case 'password':
                Controller::on_retrieve_password($_GET['email']);
                break;
            case 'events':
                Controller::on_request_events();
                break;
            default:
               $response = array('success' => 0, 'message' => 'get, object type not support');  
               echo json_encode($response);
        }
    }
    public static function dispatch_post() {
        if (!isset($_POST['object'])) {
            $response = array('success' => 0, 'message' => 'post object value not set'); 
            echo json_encode($response);
            return;
        }
        switch ($_POST['object']) {
        case 'register':
            controller::on_register($_POST['email'], $_POST['password'], $_POST['verificationCode']);
            break;
        case 'login':
            controller::on_login($_POST['email'], $_POST['password']);
            break;
        default:
            $response = array('success' => 0, 'message' => 'post object not support');  
            echo json_encode($response);
        }
    }
    public static function dispatch_put() {

    }
    public static function dispatch_delete() {

    }
    public static function route() {
        switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            controller::dispatch_get();
            break;
        case 'POST':
            controller::dispatch_post();
            break;
        case 'PUT':
            controller::dispatch_put();
            break;
        case 'DELETE':
            controller::dispatch_delete();
            break;
        default:
            $response = array('success' => 0, 'message' => 'request method not support.'); 
            echo json_encode($response);
        } 
    }
    public static function on_request_verification_code($email) {
        if (empty($email)) {
            $response = array('success' => 0, 'message' => 'email value is empty'); 
            echo json_encode($response);
            return;
        }
        require_once './resident_model.php';
        $residentModel = new ResidentModel();
        $result = $residentModel->create_connection();
        if (!$result) {
            $response = array('success' => 0, 'message' => $residentModel->get_error());
            echo json_encode($response);
            return; 
        }
        
        $result = $residentModel->contains_email($email);
        if (!$result) {
            $response = array('success' => 0, 'message' => 'cannot find email.');
            echo json_encode($response);
            $residentModel->destroy_connection();
            return;
        }
        
        $row = $residentModel->get_row_by_email($email);
        require_once './email.php';
        $result = Email::send($email, 
            "WRCA app verification code", 
            "Hi, this is your WRCA verification code#". $row['verification_code']);
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
        require_once './resident_model.php';
        require_once './user_model.php';
        $residentModel = new ResidentModel();
        $result = $residentModel->create_connection();
        if (!$result) {
            $response = array('success' => 0, 'message' => $residentModel->get_error());
            echo json_encode($response);
            return; 
        }
        $result = $residentModel->contains_email($email);
        if (!$result) {
            $response = array('success' => 0, 'message' => 'cannot find email');
            echo json_encode($response);
            return;
        }
        $row = $residentModel->get_row_by_email($email);
        if ($row["verification_code"] != $verification_code) {
            $response = array('success' => 0, 'message' => 'verification code not match.'); 
            echo json_encode($response);
            $residentModel->destroy_connection();
            return;
        }

        $residentModel->destroy_connection(); // email & verification code ok.

        $userModel = new UserModel();
        $result = $userModel->create_connection();
        if (!$result) {
            $response = array('success' => 0, 'message' => 'create connection failed.' . $userModel->get_error());
            echo json_encode($response);
            return;
        }

        // already register ?
        $result = $userModel->contains_email($email);
        if ($result) {
            $response = array('success' => 0, 'message' => 'already registered!');
            echo json_encode($response);
            return;
        }
        //$hash = Utility::hash_SSHA($password);
        //$encrypted_password = $hash["encrypted"]; // encrypted password
        //$salt = $hash["salt"]; // salt


        // let's insert
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $result = $userModel->insert_row($email, $password, $token);
        if (!$result) {
            $response = array('success' => 0, 'message' => $userModel->get_error()); 
            echo json_encode($response);
            $userModel->destroy_connection();
            return;
        }
        $response = array('success' => 1, 'message' => 'register ok.'); 
        echo json_encode($response);
        $user_model->destroy_connection();
    }
    public static function on_login($email, $password) {
        require_once './user_model.php';
        $userModel = new UserModel();
        $result = $userModel->create_connection();
        if (!$result) {
            $response = array('success' => 0, 'message' => 'create connect failed'); 
            echo json_encode($response);
            return;
        }
        $result = $userModel->contains_email($email);
        if (!$result) {
            $response = array('success' => 0, 'message' => 'user not exist'); 
            echo json_encode($response);
            $userModel->destroy_connection();
            return;
        }
        $row = $userModel->get_row_by_email($email);
        if ($row["password"] != $password) {
            $response = array('success' => 0, 'message' => 'password not match'); 
            echo json_encode($response);
            $userModel->destroy_connection();
            return;
        }

        // let's update token
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $result = $userModel->update_row($email, $password, $token);
        if (!$result) {
            $response = array('success' => 0, 'message' => $userModel->get_error()); 
            echo json_encode($response);
            return;
        }
        $response = array('success' => 1, 'message' => 'login ok', 'token' => "$token"); 
        echo json_encode($response);
        $userModel->destroy_connection();
    }
    public static function on_retrieve_password($email) {
        require_once './user_model.php';
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
            $userModel->destroy_connection();
            return; 
        } 

        // send email containing password
        require_once './email.php';
        $row = $userModel->get_row_by_email($email);
        Email::send($email, 
            "WRCA app retrieve password",
            "Hi, this your password#" . $row["password"]);
        $response = array('success' => 1, 'message' => 'please check your email box'); 
        echo json_encode($response);
        $userModel->destroy_connection();
    }

    public static function on_request_events() {
        if (!$_GET['type'] || !$_GET['cursorPos']) {
            $response = array('success' => 0, 'message' => 'params value not set'); 
            echo json_encode($response);
            return;
        }
        require_once './event_model.php';
        require_once './Utility.php';
        $eventModel = new EventModel();
        $result = $eventModel->create_connection();
        if (!$result) {
            $response = array('success' => 0, 'message' => 'create connect failed'); 
            echo json_encode($response);
            return;
        }

        switch ($_GET['type']) {
        case 'all':
            $rows = $eventModel->get_rows_by_id_range($_GET['cursorPos'], $_GET['cursorPos']+5); 
            if ($rows) {
                $response['success'] = 1;
                $response['events'] = $rows;
            } else {
                $response['success'] = 0;
            }
            break;
        case 'week':
            $range = Utility::get_date_range_of_this_week();
            //echo $range['start'];
            //echo "\t";
            //echo $range['end'];
            //echo "\t";
            $rows = $eventModel->get_rows_by_date_range($range['start'], $range['end']);
            if ($rows) {
                $response['success']  = 1;
                $response['events'] = $rows;
            } else {
                $response['success'] = 0;
            }
            break;
        case 'month':
            $range = Utility::get_date_range_of_this_month();
            $rows = $eventModel->get_rows_by_date_range($range['start'], $range['end']);
            if ($rows) {
                $response['success']  = 1;
                $response['events'] = $rows;
            } else {
                $response['success'] = 0;
            }
            break;
        default:
            $response['success'] = 0;
            $response['message'] = "type not support";
        } 
        echo json_encode($response);
        $eventModel->destroy_connection();
    }

}
?>
