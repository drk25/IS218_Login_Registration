<?php


class Session
{
    
    private $signed_in = FALSE;
    public  $user_id;
    public  $message;
    public  $count;

    
    
    
    public function __construct()
    {
        
        session_start();
        $this->check_login();
        $this->visitor_count();
        $this->check_message();
        
        
    }
    public function message($msg="") {
        if(!empty($msg)) {
            $_SESSION['message'] = $msg;
        } else {
            return $this->message;
        }
    }
    
    //check to see session  is set 
    //if its set, take $_SESSION['message'] value and assign it to message
    private function check_message() {
        
        if(isset($_SERVER['message'])) {
            $this->message = $_SESSION['message'];
            unset($_SESSION['message']);
        } else {
            $this->message = "";
        }
        }

    
    public function visitor_count() {
        if(isset($_SESSION['count'])) {
            return $this->count = $_SESSION['count']++;
        }
        else {
            return $_SESSION['count'] = 1;
        }
    }

       //public function getting private value (getter method)
    public function is_signed_in()
    {
        
        return $this->signed_in;
    }
    
    
    public function login($user)
    {
        
        if ( $user ) {
            //assign user_id a session id and user id object.
            //we know that it is available
            $this->user_id   = $_SESSION['user_id'] = $user->id;
            $this->signed_in = TRUE;
        }
    }
    
    public function logout()
    {
        
        unset($_SESSION['user_id']);
        unset($this->user_id);
        $this->signed_in = FALSE;
        session_destroy();
        
    }
    
    
    private function check_login()
    {
        
        //check to see id the session user_id is set
        if ( isset($_SESSION['user_id']) ) {
            $this->user_id   = $_SESSION['user_id'];
            $this->signed_in = TRUE;
        } else {
            unset($this->user_id);
            $this->signed_in = FALSE;
        }
    }
    
}//end of class	

$session = new Session();
$message = $session->message();


?>