<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/orders/Order.php";
include_once $sysPath."/orders/OrderTableFormatter.php";
include_once $sysPath."/report/Report.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";
include_once $projPath."/dbControler/order.php";
include_once $projPath."/dbControler/report.php";

if( !$userInfo->isUserHasReportPriv() ){
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
//$link->showQuery = true;
$whereClause = array();
$order = new Report($link,$userInfo);
$script = new Script($link,$order->tblColumns,$order->fldDefinition);

//$whereClause[] = 'STATUS = '.getTextValue('SUBMITTED');
$order->setFltrs($whereClause);
if( isset($_REQUEST['date_range']) ){
    
    $dateRangeArr = $_REQUEST['date_range'];
    foreach( $dateRangeArr as $dateCategory => $lbl){
        if( $dateCategory == 'today' ){
            $dateFltr = date("Y-m-d");
            $fltr= 'ORDER_DATE ='.getTextValue($dateFltr);
            $order->setDateMode($dateCategory);
            $order->setDateLbl($lbl);
        }elseif($dateCategory == 'thisMonth'){
            $sql = "select DATE_SUB(CURRENT_DATE, INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY) ";
            $firstDay = $link->getObjectDataFromQuery($sql);
            $endDate = date("Y-m-d");
            $fltr = getTextValue($firstDay).'<= ORDER_DATE and ORDER_DATE<='.getTextValue($endDate);
            $order->setDateMode($dateCategory);
            $order->setDateLbl($lbl);
        }elseif($dateCategory == 'thisQater'){
            $sql = "select  MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) QUARTER - INTERVAL 1 QUARTER";
            $firstDay = $link->getObjectDataFromQuery($sql);
            $endDate = date("Y-m-d");
            $fltr = getTextValue($firstDay).'<= ORDER_DATE and ORDER_DATE<='.getTextValue($endDate);
            $order->setDateMode($dateCategory);
            $order->setDateLbl($lbl);
        }elseif($dateCategory == 'thisYear'){
            $sql ="select DATE_SUB(CURRENT_DATE, INTERVAL DAYOFYEAR(CURRENT_DATE)-1 DAY)";
            $firstDay = $link->getObjectDataFromQuery($sql);
            $endDate = date("Y-m-d");
            $fltr = getTextValue($firstDay).'<= ORDER_DATE and ORDER_DATE<='.getTextValue($endDate);
            $order->setDateMode($dateCategory);
            $order->setDateLbl($lbl);
        }elseif($dateCategory == 'lastYear'){
            $sql ="select MAKEDATE(YEAR(CURDATE())-1,1) ";
            $firstDay = $link->getObjectDataFromQuery($sql);
            $sql ="select DATE_SUB(LAST_DAY(DATE_ADD(NOW(), INTERVAL 12-MONTH(NOW()) MONTH)), INTERVAL 1 YEAR) ";
            $endDate = $link->getObjectDataFromQuery($sql);
            $fltr = getTextValue($firstDay).'<= ORDER_DATE and ORDER_DATE<='.getTextValue($endDate);
            $order->setDateMode($dateCategory);
            $order->setDateLbl($lbl);
        }

    }
}
if( !empty($fltr) ){
    $order->setDateFltrs($fltr);
}

$page[] = $order->getActionPanel();
$page[] = $order->getReOrderSummaryTable();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>