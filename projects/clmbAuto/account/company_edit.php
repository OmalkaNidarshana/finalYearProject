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

$act = isset($_REQUEST['act'])?$_REQUEST['act']:'';

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";
$csFiles[] = STYLE_ROOT."main.css";

$page[] = '';

$acc = new Account($link,$userInfo,$userInfo->cmpId);

$page[] = $acc->getCompanyEditForm();
$page[] = $acc->getCompanyPrivileges();


include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";




?>