<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";


include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/Email.php";


$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$cmpId = isset($_REQUEST['cmpId'])?$_REQUEST['cmpId']:'';
$cmpType = isset($_REQUEST['cmpType'])?$_REQUEST['cmpType']:'';
$frstName = isset($_REQUEST['FIRST_NAME'])?$_REQUEST['FIRST_NAME']:'';
$lstName = isset($_REQUEST['LST_NAME'])?$_REQUEST['LST_NAME']:'';
$userName = isset($_REQUEST['USER_NAME'])?$_REQUEST['USER_NAME']:'';
$title = isset($_REQUEST['TITLE'])?$_REQUEST['TITLE']:'';
$userType = isset($_REQUEST['USER_TYPE'])?$_REQUEST['USER_TYPE']:'';
$add1 = isset($_REQUEST['ADD_1'])?$_REQUEST['ADD_1']:'';
$add2 = isset($_REQUEST['ADD_2'])?$_REQUEST['ADD_2']:'';
$cntry = isset($_REQUEST['CNTRY'])?$_REQUEST['CNTRY']:'';
$phoneCode = isset($_REQUEST['PHONE_CODE'])?$_REQUEST['PHONE_CODE']:'';
$phoneNum = isset($_REQUEST['PHONE_NUM'])?$_REQUEST['PHONE_NUM']:'';

$auth = new Authentication($link,$userName,'');
$auth->validationUserName();
?>
