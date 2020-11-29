<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";

include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/fldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";

$act = isset($_REQUEST['act'])?$_REQUEST['act']:'';
$userName = isset($_REQUEST['userId'])?$_REQUEST['userId']:'';
$cmpId = isset($_REQUEST['cmpId'])?$_REQUEST['cmpId']:$userInfo->cmpId;

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";
$csFiles[] = STYLE_ROOT."main.css";

$page[] = '';

if( isset($_REQUEST['addUser']) ){
    print_rr($_FILES);
    exit;
}

$userData = getUserInfoByUserName($link,$userName);

$acc = new Account($link,$userInfo,$cmpId);

if( $act == 'userInfo' ){
    $page[] = $acc->getUserInfo($userName);
    $page[] = $acc->getUserPrivileges($userName);
    if($userInfo->role == 'ADMINISTRATOR'){
        if($userData['USER_TYPE'] == 'SALES_REP')
            $page[] = $acc->getAssignCustomer($userName);
    }
    
}elseif( $act == 'custDetail' ){
    $page[] = $acc->getCompanyInfo();
}else{
    $page[] = $acc->getCompanyInfo();
    $page[] = $acc->getCompanyCustomerList();
    $page[] = $acc->getUserList();
    $page[] = $acc->getAddUserForm();
    $page[] = $acc->getCompanyAddForm();
}

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";




?>