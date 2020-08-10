<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";


include_once $projPath."/dbControler/category.php";


include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/Email.php";

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$catId = isset($_REQUEST['catId'])?$_REQUEST['catId']:'';
$cmpId = isset($_REQUEST['cmpId'])?$_REQUEST['cmpId']:'';
$cookieId = isset($_COOKIE['PHPSESSID'])?$_COOKIE['PHPSESSID']:'';

if( $action=='addCart' ){
    $data = array();
    $data['SESSION_ID'] = $cookieId;
    $data['CATEGORY_ID'] = $catId;
    $data['COMPANY_ID '] = $cmpId;
    insertUpdateCategoryIdIntoCart($link,$data);
}


?>