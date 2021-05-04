<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/commission/Commission.php";
include_once $sysPath."/outstanding/OutstandingTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";
include_once $projPath."/dbControler/commission.php";

if( !$userInfo->isUserHasCommissionPriv() ){
    header("Location: ".makeLocalUrl('main/restricted.php','') );
    exit; 
}

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";


$csFiles[] = STYLE_ROOT."main.css";
$csFiles[] = STYLE_ROOT."side_modal.css";

$repId = isset($_REQUEST['repId']) ? $_REQUEST['repId'] : '';

$whereClause = array();

$outS = new Commission($link,$userInfo,$repId);
$script = new Script($link,$outS->tblColumns,$outS->fldDefinition);

if( !empty($repId) ){
    $whereClause[] = 'SALES_REP_ID ='.$repId;
}

$outS->setFltrs($whereClause);
$page[] = $outS->getTopDataPanel();
$page[] = $outS->getCommissionSummaryTable();



include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";
