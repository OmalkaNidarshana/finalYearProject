<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/cat/Category.php";
include_once $sysPath."/cat/CategoryTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/fldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";


$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";
$csFiles[] = STYLE_ROOT."main.css";

$isRegSrch = isset($_REQUEST['reguler_search']) ? true : false;
$regulerSrch = isset($_REQUEST['reguler']) ? $_REQUEST['reguler'] : array();

$isCustomSrch = isset($_REQUEST['custom_search']) ? true : false;
$custSrchVal = isset($_REQUEST['random_search']) ? $_REQUEST['random_search'] : '';

$category = new Category($link);
$script = new Script($link,$category->tblColumns,$category->fldDefinition);

$whereClause = array();
if($isRegSrch){
    $script->setRegulerSearch($regulerSrch);
    $whereClause = $script->analysRegulerSearch();
}

if($isCustomSrch){
    $script->setCustomSearch($custSrchVal);
    $whereClause = $script->analysCustomSearch();
}

$category->setFltrs($whereClause);


$page[] = $category->getCategorySummaryTable();
$page[] = $category->getRegulerSearchHtml();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";




?>