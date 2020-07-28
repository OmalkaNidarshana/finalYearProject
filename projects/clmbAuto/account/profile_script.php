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


$cmpId = isset($_REQUEST['cmpId'])?$_REQUEST['cmpId']:$userInfo->cmpId;
$cmpType = isset($_REQUEST['cmpType'])?$_REQUEST['cmpType']:'';
$userId = isset($_REQUEST['userId'])?$_REQUEST['userId']:'';
$act = isset($_REQUEST['act'])?$_REQUEST['act']:'';

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";
$csFiles[] = STYLE_ROOT."main.css";

$page[] = '';
//$link->showQuery = true;
$lngth = strlen('omalka@com');
$hash = bin2hex(random_bytes($lngth));
//echo $hash;
$acc = new Account($link,$userInfo,$cmpId,$cmpType);


if($act == 'userInfo'){
    $page[] = $acc->getUserInfo($userId);
}else{
    $page[] = $acc->getCompanyInfo();
    if( empty($cmpType) && $userInfo->cmpType == 'SYS_OWNER' ){
        $page[] = $acc->getDistributorCompanyName();
    }
    $page[] = $acc->getUserList();
    $page[] = $acc->getAddUserForm();
}


include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";




?>