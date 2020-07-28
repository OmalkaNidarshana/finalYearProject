<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";


include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/Email.php";


$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$cmpId = isset($_REQUEST['cmpId'])?$_REQUEST['cmpId']:'';
$cmpType = isset($_REQUEST['cmpType'])?$_REQUEST['cmpType']:'';
$frstName = isset($_REQUEST['FIRST_NAME'])?$_REQUEST['FIRST_NAME']:'';
$lstName = isset($_REQUEST['LST_NAME'])?$_REQUEST['LST_NAME']:'';
$userName = isset($_REQUEST['USER_NAME'])?$_REQUEST['USER_NAME']:'';
$title = isset($_REQUEST['TITLE'])?$_REQUEST['TITLE']:'';
$userType = isset($_REQUEST['USER_TYPE'])?$_REQUEST['USER_TYPE']:'';
$add1 = isset($_REQUEST['ADD_1'])?$_REQUEST['ADD_1']:'';
$add2 = isset($_REQUEST['ADD_2'])?$_REQUEST['ADD_2']:'';
$city = isset($_REQUEST['CITY'])?$_REQUEST['CITY']:'';
$cntry = isset($_REQUEST['CNTRY'])?$_REQUEST['CNTRY']:'';
$phoneCode = isset($_REQUEST['PHONE_CODE'])?$_REQUEST['PHONE_CODE']:'';
$phoneNum = isset($_REQUEST['PHONE_NUM'])?$_REQUEST['PHONE_NUM']:'';
$phone = '';
if( !empty($phoneCode) && !empty($phoneNum) ){
    $phone = $phoneCode.$phoneNum;
}

global $userInfo;
$userNameErrMsg = '';
$auth = new Authentication($link,$userName,'');
$userNameErrMsg = $auth->isUserNameExist();

if(!empty($userNameErrMsg)){
    $errorMsg['userName'] = $userNameErrMsg;
    echo json_encode($errorMsg);
    //exit;
}else{
    if( $action == 'addUser'){

        $hashVal = $auth->makeRandomPassword();
        $password = md5($hashVal); 
    
        $insertData['USER_NAME'] = getTextValue($userName);
        $insertData['FIRST_NAME'] = getTextValue($frstName);
        $insertData['LAST_NAME'] = getTextValue($lstName);
        $insertData['TITLE '] = getTextValue($title);
        $insertData['USER_TYPE '] = getTextValue($userType);
        $insertData['ADDRESS_1'] = getTextValue($add1);
        $insertData['ADDRESS_2'] = getTextValue($add2);
        $insertData['CITY '] = getTextValue($city);
        $insertData['COUNTRY'] = getTextValue($cntry);
        $insertData['TEMP_PASSWORD'] = getTextValue($password);
        $insertData['PHONE '] = getTextValue($phone);
        $insertData['COMPANY_ID'] = $userInfo->cmpId;
        $insertData['CREATED_DATE'] = getTextValue(getCurrentDateTime());
        $insertData['CREATED_BY'] = $userInfo->intId;
        $insertData['MODIFY_DATE'] = getTextValue(getCurrentDateTime());
        $insertData['MODIFY_BY'] = $userInfo->intId;

        $sql = "insert into user_info (".implode(",",array_keys($insertData)).") values (".implode(",",array_values($insertData)).")";
        $link->insertUpdate($sql);
    }
}
?>
