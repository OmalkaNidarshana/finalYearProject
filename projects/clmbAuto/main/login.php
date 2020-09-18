<?php
session_start();
include_once "../path.php";
include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/UserInfo.php";
include_once $projPath."/dbControler/auth_lib.php";
include_once $sysPath."/library/library.php";

//$csFiles[] = STYLE_ROOT."login.css";
$csFiles[] = STYLE_ROOT."loginMain.css";
$csFiles[] = STYLE_ROOT."util.css";

$jsFiles[] = JS_ROOT."loginMain.js";
$jsFiles[] = JS_ROOT."map-custom.js";

$html ='';
if( isset($_REQUEST['login'])){
    $userName = $_REQUEST['userName'];
    $pwd = $_REQUEST['pwd'];
    $link = new dbConnection(HOST,DB_USER,DB_PWD,DB_NAME);
    $auth = new Authentication($link,$userName,$pwd);
    
    $errMsg = $auth->checkLoginValidation();

    if( !empty($errMsg) ){
        $errMsgCntnt = $errMsg;
    }else{
        $auth->acceptValidation();
    }
}


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

/*
$html .='<div class="sidenav ">
            <div class="login-main-text ">
                <h2> Welcome To Colombo<br>Auto Supplier</h2>
            </div>
            <img src="http://'.IMG_ROOT.'login.jpg" height="42" width="100px" class="loginImage">
            <div class="login-main-text ">
                <h2>Thanks For Beign a<br> Our Partner</h2>
            </div>
      </div>
      <div class="main">
         <div class="col-md-6 col-sm-12">
            <div class="login-form">';
         
            $html .='<form action="login.php" method="POST">
                  <div class="form-group">
                     <label>User Name</label>
                     <input type="text" class="form-control" placeholder="User Name" name="userName">
                  </div>
                  <div class="form-group">
                     <label>Password</label>
                     <input type="password" class="form-control" placeholder="Password" name="pwd">
                  </div>
                  <button type="submit" class="btn btn-black" name="login">Login</button>
                  <button type="submit" class="btn btn-black" name="forgot">Forgot Password</button>
                </form>
            </div>
         </div>
      </div>';
    echo $html;*/

    $html .='<div class="limiter" >
    <div class="container-login100" style="background-color: currentColor;">
        <div class="wrap-login100">
            <div class="login100-form-title" style="background-image: url(http://'.IMG_ROOT.'loginImage.WEBP);">
                <span class="login100-form-title-1">
                    Colombo Auto Suppliers
                </span>
            </div>

            <form class="login100-form validate-form">';
                if( !empty($errMsgCntnt) ){
                    $html .='<div class="validate-input m-b-26">
                        <span style="font-size: 12px; color: red;;">'.$errMsgCntnt.'</span>
                    </div>';
                }
                $html .='<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
                    <span class="label-input100">Username</span>
                    <input class="input100" type="text" name="userName" placeholder="Enter username">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
                    <span class="label-input100">Password</span>
                    <input class="input100" type="password" name="pwd" placeholder="Enter password">
                    <span class="focus-input100"></span>
                </div>

                <div class="flex-sb-m w-full p-b-30">
                    <div class="contact100-form-checkbox">
                        <input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
                        <label class="label-checkbox100" for="ckb1">
                            Remember me
                        </label>
                    </div>

                    <div>
                        <a href="#" class="txt1">
                            Forgot Password?
                        </a>
                    </div>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" name="login">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>';
   echo $html;
?>