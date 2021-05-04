<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/outstanding/Outstanding.php";
include_once $sysPath."/outstanding/OutstandingTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";
include_once $projPath."/dbControler/outstanding.php";

if( $userInfo->userIsSalesRep() ){
    header("Location: ".makeLocalUrl('main/restricted.php','') );
    exit; 
}

if( !$userInfo->isUserHasReportPriv() ){
    header("Location: ".makeLocalUrl('main/restricted.php','') );
    exit; 
}

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";


$csFiles[] = STYLE_ROOT."main.css";
$csFiles[] = STYLE_ROOT."side_modal.css";

$whereClause = array();
$outS = new Outstanding($link,$userInfo);
$script = new Script($link,$outS->tblColumns,$outS->fldDefinition);

$page[] = $outS->outstandingSummaryTable();


include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>