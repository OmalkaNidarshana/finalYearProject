<?php
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/UserInfo.php";
include_once $projPath."/dbControler/auth_lib.php";
include_once $projPath."/dbControler/shared.php";
include_once $sysPath."/library/library.php";

session_start();
if( !isset($_SESSION['loggedin']) ){
    header("Location: http://".ROOT."main/login.php");
    exit; 
}

$userName = $_SESSION['name'];
$link = new dbConnection(HOST,DB_USER,DB_PWD,DB_NAME);
$assignCompany = array();

$userData = getUserInfoByUserName($link,$userName);
if( isset($userData['ASSIGN_COMPANY']) && !empty($userData['ASSIGN_COMPANY']) )
    $assignCompany = explode(",",$userData['ASSIGN_COMPANY']);
    
$userInfo = new UserInfo($link,$userName);
$userInfo->setFirstname($userData['FIRST_NAME']);
$userInfo->setLastname($userData['LAST_NAME']);
$userInfo->setIntId($userData['USER_INTID']);
$userInfo->setRole($userData['USER_TYPE']);
$userInfo->setCmpId($userData['COMPANY_ID']);
$userInfo->setCmpType($userData['COMPANY_TYPE']);
$userInfo->setAssignCompny($assignCompany);


?>