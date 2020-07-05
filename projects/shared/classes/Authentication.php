<?php

class Authentication{
    var $link;
    var $userName;
    var $password;
    var $isUserSuperAdmin = false;
    var $isUserAdmin = false;
    var $isUserEndUser = false;
    var $isUserNameExist = false;
    var $isUserNameEmail = false;
    var $userData;
    var $errMsg;

    function Authentication($link='',$userName='',$password=''){
        $this->link = $link;
        $this->userName =$userName;
        $this->password =$password;
        $this->userData = getUserInfoByUserName($this->link,$this->userName);
        
    }

    function setSessionTrue(){
        $_SESSION['loggedin'] = true;
    }

    function setSessionName(){
        $_SESSION['name'] = $this->userName;
    }

    function setErrorMasg($errMsg){
        $this->errMsg = $errMsg;
    }

    function checkLoginValidation(){
        if( empty($this->userData) && !empty($this->userName) ){
            $this->errMsg = "Invalid Logins.";
        }elseif( empty($this->userName) && empty($this->password) ){
            $this->errMsg = "Please Enter Your Logins.";
        }elseif( empty($this->userName) ){
            $this->errMsg = "Please Enter User Name.";
        }elseif( empty($this->password) ){
            $this->errMsg = "Please Enter Your Password.";
        }else{
            $pwd = $this->userData['PASSWORD'];
            if( $pwd != $this->password){
                $this->errMsg = "Incorrect Password.";
            }
        }
        return $this->errMsg;
    }

    function acceptValidation(){
        session_regenerate_id(delete_old_session);
        $this->setSessionTrue();
        $this->setSessionName();
    
        header("Location: http://".ROOT."main/home_script.php");
        exit; 
    }

    function verifyEmailDomain($email){
        $emailValidation = new Email();
        $emailValidation->setStreamTimeoutWait(20);
		$emailValidation->Debug= TRUE;
		$emailValidation->Debugoutput= 'html';
 
		$emailValidation->setEmailFrom($email);
 
		if ($emailValidation->check($email)) {
			return true;
		} elseif (Email::validate($email)) {
			return false;
		} else {
			return false;
		}
    }
    
    function verifyEmailFormatting($email){
        if(strstr($email, "@") == FALSE){
            return false;
        }else{
            list($user, $domain) = explode('@', $email);
    
            if(strstr($domain, '.') == FALSE){
                return false;
            }else{
                return true;
            }
        }
    }

    function verifingEmail(){
        if( $this->verifyEmailFormatting($this->userName) ){
            if( $this->verifyEmailDomain($this->userName) ){
                $this->errMsg  = 'Formatting and domain have been verified';
            }else{
                $this->errMsg  = 'Formatting was verified, but verification of the domain has failed';
            }
        }else{
            $this->errMsg = 'Email was not formatted correctly';
        }
        return $this->errMsg;
    }

    function isUserNameExist(){
       $userId =  getRecIdByUserName($this->link,$this->userName);
       if ( !empty($userId) ){
            $this->errMsg = 'User Name Alredy Exist';
       }


    }

    function validationUserName(){
        $errMsg = $this->verifingEmail();
        print_rr($errMsg);
    }

}

?>