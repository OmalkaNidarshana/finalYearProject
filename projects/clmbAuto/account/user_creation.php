<?php

include_once "../path.php";
//include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";

include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/PHPMailer.php";
include_once $projPath."/dbControler/shared.php";
include_once $projPath."/dbControler/auth_lib.php";

//$csFiles[] = STYLE_ROOT."login.css";
$csFiles[] = STYLE_ROOT."loginMain.css";
$csFiles[] = STYLE_ROOT."util.css";

$jsFiles[] = JS_ROOT."loginMain.js";
$jsFiles[] = JS_ROOT."map-custom.js";

$link = new dbConnection(HOST,DB_USER,DB_PWD,DB_NAME);
$userId = isset($_REQUEST['userId'])?$_REQUEST['userId']:'';

$userRecId = getPendingUserByUserId($link,$userId);

/*if( isset($_SESSION['loggedin']) ){
    header("Location: ".makeLocalUrl('main/home_script.php','') );
    exit; 
}-
*/
if( isset($_REQUEST['save'])){
    $userName = $_REQUEST['username'];
    $pwd = $_REQUEST['pass'];
    $rePwd = $_REQUEST['re-pass'];
    $link = new dbConnection(HOST,DB_USER,DB_PWD,DB_NAME);
    $auth = new Authentication($link,$userName,$pwd);
    
   if( $userName != $userId){
        $errMsg = 'Invalid user name';
    }elseif($rePwd != $pwd){
        $errMsg = 'Password does not match';
    }

    if( !empty($errMsg) ){
        $errMsgCntnt = $errMsg;
    }else{
        updateUserPassword($link,$userName,$pwd);
        header("Location: http://".ROOT."main/login.php");
        exit;
    }
}else{
    if( empty($userRecId) ){
        header("Location: ".makeLocalUrl('auth/user_auth_error.php','') );
        exit; 
    }
}



$html ='';
$html .='<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>'.TITLE.'</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">';

$html .='<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">';
$html .= '<link rel="stylesheet" href="http://'.ADMIN_STYLE_ROOT.'bower_components/font-awesome/css/font-awesome.min.css">';
$html .='<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>';
$html .='<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>';

if(!empty($csFiles)){
    foreach($csFiles as $csFile){
        $html .= '<link rel="stylesheet" href="http://'.$csFile.'">';
    }
}

if(!empty($jsFiles)){
    foreach($jsFiles as $jsFile){
        $html .= '<script src="http://'.$jsFile.'"></script>';
    }
}

$html .='<div class="limiter">
            <div class="container-login100" style="background-image: url(http://'.IMG_ROOT.'oginImage.WEBPl);">
                <div class="wrap-login100 p-t-30 p-b-50">
                    <span class="login100-form-title p-b-41">
                        Brisk Srilanka
                    </span>
                    <form class="login100-form validate-form p-b-33 p-t-5" method="POST">';
                        if( !empty($errMsgCntnt) ){
                            $html .='<div class="wrap-input100 validate-input" style="padding-left: 100px;">
                                <span style="font-size: 12px; color: red;">'.$errMsgCntnt.'</span>
                            </div>';
                        }
                        $html .='<div class="wrap-input100 validate-input" data-validate = "Enter username">
                            <input class="input100" type="text" name="username" placeholder="User name">
                            
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Enter password">
                            <input class="input100" type="password" name="pass" placeholder="Password">
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Re Enter password">
                            <input class="input100" type="password" name="re-pass" placeholder="Re Enter password">
                        </div>

                        <div class="container-login100-form-btn m-t-32">
                            <button class="login100-form-btn" name="save">
                                Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>';

$html .= '</html>';
echo $html;
?>