<?php
session_start();
include_once "../path.php";
include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/UserInfo.php";
include_once $projPath."/dbControler/auth_lib.php";
include_once $sysPath."/library/library.php";

$csFiles[] = STYLE_ROOT."login.css";

$html ='';
if( isset($_REQUEST['login'])){
    $userName = $_REQUEST['userName'];
    $pwd = $_REQUEST['pwd'];
    $link = new dbConnection(HOST,DB_USER,DB_PWD,DB_NAME);
    $auth = new Authentication($link,$userName,$pwd);
    
    $errMsg = $auth->checkLoginValidation();

    if( !empty($errMsg) ){
        $msgBody = getHtmlAlertBox($errMsg);
    }else{
        $auth->acceptValidation();
    }
}


$html .='<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">';
$html .='<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>';
$html .='<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>';

if(!empty($csFiles)){
    foreach($csFiles as $csFile){
        $html .= '<link rel="stylesheet" href="http://'.$csFile.'">';
    }
}



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
            if( !empty($msgBody) ){
                $html .= $msgBody;
            }
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
    echo $html;
   
?>