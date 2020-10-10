<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";
;

include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/Email.php";
include_once $projPath."/dbControler/shared.php";
//-------------------------User Adding----------------------------------------//
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
//---------------------End - User Adding-----------------------------------//

//-----------------------Customer Adding------------------------------------//

$custName = isset($_REQUEST['COMPANY_NAME'])?$_REQUEST['COMPANY_NAME']:'';
$address = isset($_REQUEST['ADRESS'])?$_REQUEST['ADRESS']:'';
$postalCode = isset($_REQUEST['POSATL_CODE'])?$_REQUEST['POSATL_CODE']:'';
$cuntory = isset($_REQUEST['COUNTRY'])?$_REQUEST['COUNTRY']:'';
$email = isset($_REQUEST['EMAIL'])?$_REQUEST['EMAIL']:'';
//-----------------------End - Customer Adding------------------------------------//

$phone = '';
if( !empty($phoneCode) && !empty($phoneNum) ){
    $phone = $phoneCode.$phoneNum;
}

global $userInfo;
$userNameErrMsg = '';
$auth = new Authentication($link,$userName,'');



if( $action == 'addUser'){
    $userNameErrMsg = $auth->isUserNameExist();
    
    if(!empty($userNameErrMsg)){
        $errorMsg['userName'] = $userNameErrMsg;
        echo json_encode($errorMsg);
        exit;
    }else{
        $hashVal = $auth->makeRandomPassword();
        $password = md5($hashVal); 
    
        $insertData['USER_NAME'] = getTextValue($userName);
        $insertData['FIRST_NAME'] = getTextValue($frstName);
        $insertData['LAST_NAME'] = getTextValue($lstName);

        if( !empty($title) )
            $insertData['TITLE '] = getTextValue($title);
        if( !empty($userType) )
            $insertData['USER_TYPE '] = getTextValue($userType);
        if( !empty($add1) )
            $insertData['ADDRESS_1'] = getTextValue($add1);
        if( !empty($add2) )
            $insertData['ADDRESS_2'] = getTextValue($add2);
        if( !empty($city) )
            $insertData['CITY '] = getTextValue($city);
        if( !empty($cntry) )
            $insertData['COUNTRY'] = getTextValue($cntry);
        if( !empty($password) )
            $insertData['TEMP_PASSWORD'] = getTextValue($password);
        if( !empty($phone) )
            $insertData['PHONE '] = getTextValue($phone);

        $insertData['COMPANY_ID'] = $userInfo->cmpId;
        $insertData['CREATED_DATE'] = getCurrentDateTime();
        $insertData['CREATED_BY'] = $userInfo->intId;
        $insertData['MODIFY_DATE'] = getCurrentDateTime();
        $insertData['MODIFY_BY'] = $userInfo->intId;

        $sql = "insert into user_info (".implode(",",array_keys($insertData)).") values (".implode(",",array_values($insertData)).")";
        $link->insertUpdate($sql);

        $userData = getUserInfoByUserId($link,$userName);
        insertUserRoleIntoRoleTable($link,$userData['USER_INTID'],$userData['USER_TYPE']);
        $defaultPrivArray = array('CATEGORY','CREDIT_PERIOD','INVOICE');
        insertDefaultPrivilegesIntoUser($link,$userData['USER_INTID'],$defaultPrivArray);
        $errorMsg['noneError'] = '';
        echo json_encode($errorMsg);
        exit;
    }
}


if( $action == 'addCustomer' ){
    $auth->setCustomerName($custName);
    $customerNameErrMsg = $auth->isCustomerNameExist();
    if( !empty($customerNameErrMsg) ){
        $errorMsg['custName'] = $customerNameErrMsg;
        echo json_encode($errorMsg);
        exit;
    }else{
        $insertData['COMPANY_NAME'] = getTextValue($custName);
        $insertData['DIST_ID'] = $userInfo->cmpId;
        if( !empty($address) )
            $insertData['ADRESS'] = getTextValue($address);
        
        if( !empty($city) )
            $insertData['CITY'] = getTextValue($city);

        if( !empty($cuntory) )
            $insertData['COUNTRY'] = getTextValue($cuntory);
        
        if( !empty($postalCode) )
            $insertData['POSATL_CODE'] = getTextValue($postalCode);
        
        if( !empty($email) )
            $insertData['EMAIL'] = getTextValue($email);
        
        if( !empty($phone) )
            $insertData['PHONE'] = getTextValue($phone);

        $insertData['COMPANY_TYPE'] = getTextValue('CUSTOMER');
        $insertData['CREATED_DATE'] = getCurrentDateTime();
        $insertData['CREATED_BY'] = $userInfo->intId;
        $insertData['MODIFY_DATE'] = getCurrentDateTime();
        $insertData['MODIFY_BY'] = $userInfo->intId;
        $sql = "insert into company (".implode(",",array_keys($insertData)).") values (".implode(",",array_values($insertData)).")";
        $link->insertUpdate($sql);
        
        $errorMsg['noneErrorCustomer'] = '';
        echo json_encode($errorMsg);
        exit;
    }

}



?>
