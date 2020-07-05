<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/main/Main.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/SortableTable.php";

include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/TableFormatter.php";

$jsFiles[] = JS_ROOT."sortable_table.js";

$csFiles[] = STYLE_ROOT."main.css";


//--------------------------Start - Home Chart Define Area-----------------------------\\
$areaChart = new MainChart('','areaChart','','area','Sales');
$page[] = $areaChart->runChart();

$barChrt = new MainChart('','barChart','','bar','Income');
$page[] = $barChrt->runChart();
//--------------------------End - Home Chart Define Area-----------------------------\\


//--------------------------Start - Manuf. Logos Define Area-----------------------------\\
$main = new Main($link);
$page[] = $main->getManufactuerLogos();
$page[] = $main->getCustomerLogos();
//--------------------------End - Manuf. Logos Define Area-----------------------------\\
include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>