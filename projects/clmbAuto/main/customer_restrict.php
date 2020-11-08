<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $projPath."/shared/classes/HTML.php";




$page[] = '';
$page[] = contentBox('<p style="color: red;">You are not assigned for this company</p>');

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";

?>