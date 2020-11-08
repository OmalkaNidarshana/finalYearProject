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
include_once $projPath."/dbControler/order.php";

$act = isset($_REQUEST['act'])?$_REQUEST['act']:'';
$userName = isset($_REQUEST['userId'])?$_REQUEST['userId']:'';
$cmpId = isset($_REQUEST['cmpId'])?$_REQUEST['cmpId']:$userInfo->cmpId;

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";
$csFiles[] = STYLE_ROOT."main.css";

if($userInfo->role == 'SALES_REP'){
    $ids = implode(",",$userInfo->assignCompny);
}else{
    $ids = '';
}
$acc = new Account($link,$userInfo,$cmpId);

$customer = getCustomerListByIds($link,$ids);

$page[] = '';
$page[] = $acc->getCustomerList($customer);

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";




?>