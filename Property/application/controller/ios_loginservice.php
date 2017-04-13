<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require APP . 'model/sinmodel.php';

class Ios_loginservice extends Controller {

    function __construct() {
        parent::__construct();

        $this->signin_model = new Sinmodel($this->db);
    }

    public function signInUser() {

        //echo "saad";
        $requestBody = file_get_contents("php://input");
        $jsonValues = json_decode($requestBody, true);

        $email = $jsonValues["email"];
        $password = $jsonValues["password"];
        
        $returnValue = array();
        
        if (empty($email) || empty($password)) {
            $returnValue["status"] = "Error";
            $returnValue["message"] = "Missing required field";
            echo json_encode($returnValue);
            return;
        }
        
        $secure_password = md5($password);
        
        //$result = $this->signin_model->signIn($email, $secure_password);
        $result = $this->getSessionID($email, $secure_password);
                
        
        if ($result) {
            echo $result;
            return;
        }
        else
        {
            echo $result;
            return;
        }
        
    }
    
    //Get the Session ID 
    public function getSessionID($email, $password) {
        $userdetail = $this->signin_model->signIn($email, $password);
        if (!empty($userdetail)) {
            // is_auth is important here because we will test this to make sure they can view other pages
            // that are needing credentials.
            $_SESSION['is_auth'] = true;
            $_SESSION['id'] = $userdetail[0]['ID']; //Use to record transactions
            $_SESSION['name'] = $userdetail[0]['FULL_NAME']; //Shown on welcome
            $_SESSION['email'] = $userdetail[0]['EMAIL']; //Shown on profile Page
            $_SESSION['userTypeId'] = $userdetail[0]['USER_TYPE_ID'];  //Shown on profile Page
            $_SESSION['createdDate'] = $userdetail[0]['CREATED_DATE']; //Shown on profile Page
            $_SESSION['userTypeDescription'] = $userdetail[0]['USER_TYPE_DESCRIPTION']; //Shown on profile Page
            $_SESSION["status"] = "Success";
            $_SESSION["message"] = "User logged in Successfully";
            return json_encode($_SESSION);
        }
        else
        {
            $_SESSION['is_auth'] = false;
            $_SESSION["status"] = "Error";
            $_SESSION["message"] = "Invalid Username or Password";
            return json_encode($_SESSION);
        }
    }

    public function registerUser() {
        
        $requestBody = file_get_contents("php://input");
        $jsonValues = json_decode($requestBody, true);


        $username = $jsonValues["username"];
        $email = $jsonValues["email"];
        $password = $jsonValues["password"];
        $userType = $jsonValues["usertype"];

        $returnValue = array();

        if (empty($email) || empty($password) || empty($username) || empty($userType)) {
            $returnValue["status"] = "error";
            $returnValue["message"] = "Some fields are missing in the received JSON";
            echo json_encode($returnValue);
            return;
        }
        
        $userExists = $this->checkIfUserExists($username, $password);
        
        if($userExists)
        {
            $returnValue["status"] = "error";
            $returnValue["message"] = "User with this email already exists";
            echo json_encode($returnValue);
            return;
        }
        
        $secure_password = md5($password);

        $result = $this->signin_model->registerUser($username, $email, $secure_password, $userType);
            
        if ($result) {
            $returnValue["status"] = "Success";
            $returnValue["message"] = "User is registered";
            echo json_encode($returnValue);
            return;
        }
    }

    public function checkIfUserExists($userName, $password)
    {
        $result = $this->signin_model->signIn($userName, $password);
        
        if(!empty($results))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}
