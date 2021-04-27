<?php

class Authentication{
    var $link;
    var $userName;
    var $password;
    var $isUserSuperAdmin = false;
    var $isUserAdmin = false;
    var $isUserEndUser = false;
    var $isUserNameExist = false;
    var $isCustomerNameExist = false;
    var $isUserNameEmail = false;
    var $userData;
    var $errMsg;
    var $custName;

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

    function setCustomerName($custName){
        $this->custName = $custName;
    }

    function checkLoginValidation(){
        if( empty($this->userData) && !empty($this->userName) ){
            $this->errMsg = "Invalid User Name";
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
            $this->errMsg = 'User name alredy exist';
       }
       return $this->errMsg;
    }

    function isCustomerNameExist(){
        
        $custId =  getCustIdByCustomerName($this->link,$this->custName);
        if ( !empty($custId) ){
             $this->errMsg = 'Customer name alredy exist';
        }
        return $this->errMsg;
     }

    function validationUserName(){
        $errMsg = $this->verifingEmail();
    }

    function makeRandomPassword() { 
        $pass='';
        $salt = "abchefghjkmnpqrstuvwxyz0123456789"; 
        srand((double)microtime()*1000000);  
        $i = 0; 
        while ($i <= 7) { 
              $num = rand() % 33; 
              $tmp = substr($salt, $num, 1); 
              $pass = $pass . $tmp; 
              $i++; 
        } 
        return $pass; 
    } 

    function sendUserCreationEmailContent($to,$name,$userInfo){
        $subject = 'Brisk Lanka : Verify User Authentication';
        $headers = "Content-Type: text/html; charset=\"UTF-8\"; format=flowed \r\n";
        $content = 'Hi '.$name.',<br>';
        $content .= 'Now you have access to login www.brisksrilanka.com, Please confirm your login.<br>';
        $content .= 'Confirmation link :'.makeLocalUrl('account/user_creation.php','userId='.urlencode($to));
        $content .= '<br><br>';
        $content .= 'Thanks,<br>';
        $content .= 'Administrator,<br>';
        $content .= $userInfo->firstName.' '.$userInfo->LastName;
        mail($to,$subject,$content,$headers);
    }

    function sendorderCreationEmailContent($ordNumber,$customerName,$ordrId,$userInfo){
        $subject = 'Brisk Lanka : New Order Has Been Created : Order NO | '.$ordNumber;
        $headers = "Content-Type: text/html; charset=\"UTF-8\"; format=flowed \r\n";
        $content = 'Hi Team,<br>';
        $content .= 'New order has been created for '.$customerName.' by '.$userInfo->firstName.' '.$userInfo->LastName.'<br>';
        $content .= 'Order link :'.makeLocalUrl('orders/order_details.php','sec=ORDER&id='.$ordrId);
        mail('omalkanidarshana@gmail.com',$subject,$content,$headers);

    }
}

?>