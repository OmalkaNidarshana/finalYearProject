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
        if( $dateCategory = 'today' ){
            $date = date();
            print_rr($date);
        }elseif($dateCategory = 'thisMonth'){

        }elseif($dateCategory = 'thisQater'){

        }elseif($dateCategory = 'thisYear'){

        }elseif($dateCategory = 'lastYear'){

        }else{
            
        }

    }
    exit;
}
$page[] = $order->getActionPanel();
$page[] = $order->getReportSummaryTable();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>