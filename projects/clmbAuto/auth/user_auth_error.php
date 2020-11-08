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

//$csFiles[] = STYLE_ROOT."login.css";
$csFiles[] = STYLE_ROOT."loginMain.css";
$csFiles[] = STYLE_ROOT."util.css";

$jsFiles[] = JS_ROOT."loginMain.js";
$jsFiles[] = JS_ROOT."map-custom.js";

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
            <div class="container-login100" style="background-image: url(http://'.IMG_ROOT.'loginImage.WEBP);">
                <div class="wrap-login100 p-t-30 p-b-50">
                    <span class="login100-form-title p-b-41">
                        Brisk Srilanka
                    </span>
                    <form class="login100-form validate-form" style="padding: 50px 50px 50px 120px;">
                        <span style="color:red;">Authentication is failed, Please contact your admistrator.</span>
                    </form>
                </div>
            </div>
        </div>';
$html .= '</html>';
echo $html;
?>
