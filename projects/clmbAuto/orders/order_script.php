<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/orders/Order.php";
include_once $sysPath."/orders/OrderTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";
include_once $projPath."/dbControler/order.php";

if( !$userInfo->isUserHasOrdrPriv() ){
    header("Location: ".makeLocalUrl('main/restricted.php','') );
    exit; 
}

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";


$csFiles[] = STYLE_ROOT."main.css";
$csFiles[] = STYLE_ROOT."side_modal.css";

$isRegSrch = isset($_REQUEST['reguler_search']) ? true : false;
$regulerSrch = isset($_REQUEST['reguler']) ? $_REQUEST['reguler'] : array();

$isCustomSrch = isset($_REQUEST['custom_search']) ? true : false;
$custSrchVal = isset($_REQUEST['random_search']) ? $_REQUEST['random_search'] : '';

$custId = isset($_REQUEST['custId']) ? $_REQUEST['custId'] : '';
$repId = isset($_REQUEST['repId']) ? $_REQUEST['repId'] : '';
//$link->showQuery = true;
$whereClause = array();
$order = new Order($link,$userInfo);
$script = new Script($link,$order->tblColumns,$order->fldDefinition);

if( !empty($custId) ){
    $whereClause[] = 'CUSTOMER_ID ='.$custId;
}
if( !empty($repId) ){
    $whereClause[] = 'CREATED_BY ='.$repId;
}

if($isRegSrch){
    $script->setRegulerSearch($regulerSrch);
    $whereClause = $script->analysRegulerSearch();
}

if($isCustomSrch){
    $script->setCustomSearch($custSrchVal);
    $whereClause = $script->analysCustomSearch();
}

$order->setFltrs($whereClause);


$page[] = $order->getOrderSummaryTable();
$page[] = $order->getRegulerSearchHtml();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>