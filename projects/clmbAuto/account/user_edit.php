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
$userName = isset($_REQUEST['userId'])?$_REQUEST['userId']:'';

$jsFiles[] = JS_ROOT."sortable_table.js";
$jsFiles[] = JS_ROOT."main.js";
$csFiles[] = STYLE_ROOT."main.css";

$page[] = '';

$acc = new Account($link,$userInfo,$userInfo->cmpId);

if( isset($_REQUEST['editUserData']) ){
    $userData = $_REQUEST['editUser'];
    foreach( $userData as $key=>$val){
        if( empty($val) ){
            $userDataArr[$key] = '';
        }else{
            $userDataArr[$key] = $val;
        }
    }
    $userDataArr['USER_IMAGE'] = $_FILES['USER_IMAGE']['name'];
    
    $target_dir = $sysPath.'/resources/image/userImg/';
    $target_file = $target_dir . basename($_FILES["USER_IMAGE"]["name"]);
    move_uploaded_file($_FILES["USER_IMAGE"]["tmp_name"], $target_file);

    updateUserData($link,$userName,$userDataArr);
}

$page[] = $acc->getUserEditForm($userName);
$page[] = $acc->getEditUserPrivileges($userName);


include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";




?>