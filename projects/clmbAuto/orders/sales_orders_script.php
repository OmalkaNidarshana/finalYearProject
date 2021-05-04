<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/invoice/Invoice.php";
include_once $sysPath."/invoice/InvoiceTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";
include_once $projPath."/dbControler/order.php";
include_once $projPath."/dbControler/invoice.php";

/*if( !$userInfo->userIsAdmistrtor() || !$userInfo->userIsAccountManager() ){
    header("Location: ".makeLocalUrl('main/restricted.php','') );
    exit; 
}
*/

if( !$userInfo->isUserHasAccountPriv() ){
    header("Location: ".makeLocalUrl('main/restricted.php','') );
    exit; 
}

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";
$jsFiles[] = JS_ROOT."invoice.js";


$csFiles[] = STYLE_ROOT."main.css";
//$csFiles[] = STYLE_ROOT."side_modal.css";

$isRegSrch = isset($_REQUEST['reguler_search']) ? true : false;
$regulerSrch = isset($_REQUEST['reguler']) ? $_REQUEST['reguler'] : array();

$isCustomSrch = isset($_REQUEST['custom_search']) ? true : false;
$custSrchVal = isset($_REQUEST['random_search']) ? $_REQUEST['random_search'] : '';

//$link->showQuery = true;

$inv = new Invoice($link,$userInfo);
$script = new Script($link,$inv->tblColumns,$inv->fldDefinition);

$whereClause = array();
$whereClause[] = 'STATUS !='.getTextValue('PENDING');
if($isRegSrch){
    $script->setRegulerSearch($regulerSrch);
    $whereClause = $script->analysRegulerSearch();
}

if($isCustomSrch){
    $script->setCustomSearch($custSrchVal);
    $whereClause = $script->analysCustomSearch();
}

$inv->setFltrs($whereClause);


$page[] = $inv->getSalesSummaryTable();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>