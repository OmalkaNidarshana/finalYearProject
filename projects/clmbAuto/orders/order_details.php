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
include_once $projPath."/dbControler/invoice.php";

if( !$userInfo->isUserHasOrdrPriv() ){
    header("Location: ".makeLocalUrl('main/restricted.php','') );
    exit; 
}

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";
$jsFiles[] = JS_ROOT."order.js";
$jsFiles[] = JS_ROOT."invoice.js";


$csFiles[] = STYLE_ROOT."main.css";
//$csFiles[] = STYLE_ROOT."side_modal.css";

$isRegSrch = isset($_REQUEST['reguler_search']) ? true : false;
$regulerSrch = isset($_REQUEST['reguler']) ? $_REQUEST['reguler'] : array();

$isCustomSrch = isset($_REQUEST['custom_search']) ? true : false;
$regulerSrch = isset($_REQUEST['reguler']) ? $_REQUEST['reguler'] : array();

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

//$link->showQuery = true;

$order = new Order($link,$userInfo,$id);
$script = new Script($link,$order->tblColumns,$order->fldDefinition);

$whereClause = array();
if($isRegSrch){
    $script->setRegulerSearch($regulerSrch);
    $whereClause = $script->analysRegulerSearch();
}

if($isCustomSrch){
    $script->setCustomSearch($custSrchVal);
    $whereClause = $script->analysCustomSearch();
}

$order->setFltrs($whereClause);

$page[] = $order->getActionPanel();
$page[] = $order->getTotalAmoutPanel();
$page[] = $order->getOrderHeaderDetails();
$page[] = $order->OrderLineItems();
$page[] = $order->loadEditLinePopup();
$page[] = $order->loadRejectItemPopup();
$page[] = $order->getInvoiceAddingForm();
$page[] = $order->getVerifingNote();

//$page[] = $order->getRegulerSearchHtml();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>