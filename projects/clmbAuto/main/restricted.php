<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $projPath."/shared/classes/HTML.php";




$page[] = '';
$page[] = contentBox('<p style="color: red;">This page has been restricted</p>');

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>