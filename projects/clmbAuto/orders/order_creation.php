<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";
include_once $sysPath."/orders/Order.php";

include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/Email.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/dbControler/category.php";

$csFiles[] = STYLE_ROOT."main.css";

$categoryIds = isset($_REQUEST['catIds'])?$_REQUEST['catIds']:array();

$ord = new Order($link,$userInfo);
$ord->setCategoryIds($categoryIds);
$page[] = $ord->getOrderCreationSubmit();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";
?>