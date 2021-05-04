<?php

function getTableSchemaInformation($link,$table){
    $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ".getTextValue($table);
    $data =  $link->getRecordSetFromQuery($sql);
    return $data;

}

function getCompanyDataByCmpId($link,$cmpId){
    $sql = 'select * from company where COMPANY_ID = '.$cmpId;
    $data =  $link->getRowDataFromQuery($sql);
    return $data;
}

function getDistributorName($link){
    $sql = 'select COMPANY_ID,COMPANY_NAME from company where COMPANY_TYPE = '.getTextValue('DISTRIBUTOR');
    $data =  $link->getRecordSetFromQuery($sql);
    return $data;
}

function geUserInformationByCmpId($link,$cmpId){
    $sql = 'select * from user_info where COMPANY_ID = '.$cmpId;
    $data =  $link->getRecordSetFromQuery($sql);
    return $data;
}

function getUserInfoByUserId($link,$userName){
    $sql = 'select * from user_info where USER_NAME = '.getTextValue($userName);
    $data =  $link->getRowDataFromQuery($sql);
    return $data;
}

function insertDefaultPrivilegesIntoUser($link,$userIndtId,$defaultPrivArray){
    global $userInfo;
    foreach($defaultPrivArray as $prive){
        $inserData['PRIVE_NAME'] = getTextValue($prive);
        $inserData['USER_ID'] = $userIndtId;
        $inserData['CREATED_BY'] = $userInfo->intId;
        $inserData['CREATED_DATE'] = getCurrentDateTime();
        $sql = "insert into user_privileges (".implode(",",array_keys($inserData)).") values (".implode(",",array_values($inserData)).")";
        $link->insertUpdate($sql);
    }
    
}

function insertUserRoleIntoRoleTable($link,$userIndtId,$role){
    global $userInfo;
    $inserData['ROLE_NAME'] = getTextValue($role);
    $inserData['USER_ID'] = $userIndtId;
    $inserData['CREATED_BY'] = $userInfo->intId;
    $inserData['CREATED_DATE'] = getCurrentDateTime();
    $sql = "insert into user_role (".implode(",",array_keys($inserData)).") values (".implode(",",array_values($inserData)).")";

    $link->insertUpdate($sql);
}

function getSystemRoles($link){
    $sql = "select * from sys_role";
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getSystemPrivileges($link){
    $sql = "select * from sys_privilege";
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getCompanyPrivilegesByCmpId($link,$cmpId){
    $sql = "select PRIVE_NAME from company_privileges where COMPANY_ID =".$cmpId;
    $data = $link->getColumnDataFromQuery($sql);
    return $data;
}

function getUserPrivilegesByCmpId($link,$userId){
    $sql = "select PRIVE_NAME from user_privileges where USER_ID =".$userId;
    $data = $link->getColumnDataFromQuery($sql);
    return $data;
}

function getCustomerListByDistId($link,$cmpId){
    $sql = "select * from company where DIST_ID =".$cmpId." and COMPANY_TYPE =".getTextValue('CUSTOMER');
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getCustomerList($link){
    $sql = "select * from company where COMPANY_TYPE =".getTextValue('CUSTOMER');
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getCustIdByCustomerName($link,$customerName){
    $sql = "select COMPANY_ID  from company where COMPANY_NAME =".getTextValue($customerName);
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}

function updateCompanyData($link,$cmpId,$cmpData){
    $updateArr = array();
    $data = array();

    $updateArr['COMPANY_NAME'] = getTextValue($cmpData['COMPANY_NAME']);
    $updateArr['ADRESS'] = getTextValue($cmpData['ADRESS']);
    $updateArr['POSATL_CODE'] = $cmpData['POSATL_CODE'];
    $updateArr['CITY'] = getTextValue($cmpData['CITY']);
    $updateArr['COUNTRY'] = getTextValue($cmpData['COUNTRY']);
    $updateArr['EMAIL'] = getTextValue($cmpData['EMAIL']);
    $updateArr['PHONE'] = getTextValue($cmpData['PHONE']);

    foreach( $updateArr as $k=>$v){
        $data[] = $k.'='.$v;
    }
    $sql = "update company set ".implode(",",$data)." where COMPANY_ID=".$cmpId;
    $link->insertUpdate($sql);
}

function updateUserData($link,$userName,$userDataArr){
    $updateArr = array();
    $data = array();

    $updateArr['FIRST_NAME'] = getTextValue($userDataArr['FIRST_NAME']);
    $updateArr['LAST_NAME'] = getTextValue($userDataArr['LAST_NAME']);
    $updateArr['USER_NAME'] = getTextValue($userDataArr['USER_NAME']);
    $updateArr['TITLE'] = getTextValue($userDataArr['TITLE']);
    $updateArr['ADDRESS_1'] = getTextValue($userDataArr['ADDRESS_1']);
    $updateArr['ADDRESS_2'] = getTextValue($userDataArr['ADDRESS_2']);
    $updateArr['CITY'] = getTextValue($userDataArr['CITY']);
    $updateArr['USER_IMAGE'] = getTextValue($userDataArr['USER_IMAGE']);
    $updateArr['COUNTRY'] = getTextValue($userDataArr['COUNTRY']);
    $updateArr['PHONE'] = getTextValue($userDataArr['PHONE']);

    foreach( $updateArr as $k=>$v){
        $data[] = $k.'='.$v;
    }
    $sql = "update user_info set ".implode(",",$data)." where USER_NAME=".getTextValue($userName);
    $link->insertUpdate($sql);
}

function getPendingUserByUserId($link,$userId){
    $sql = "select USER_INTID from user_info where USER_NAME =".getTextValue($userId)." and VERIFING =".getTextValue('PENDING');
    $data = $link->getObjectDataFromQuery($sql);
    return $data; 
}

function updateUserPassword($link,$userId,$password){
    $sql = "update user_info SET PASSWORD=".getTextValue($password).",VERIFING =".getTextValue('VERIFIED')." where USER_NAME =".getTextValue($userId);
    $link->insertUpdate($sql);
}

function getCustomerListByIds($link,$ids){
    if(!empty($ids) ){
        $sql = "select * from company where COMPANY_ID in(".$ids.")";
    }else{
        $sql = "select * from company where COMPANY_TYPE =".getTextValue('CUSTOMER');
    }
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getUserPrivielegesByUserId($link,$userId){
    $sql = "select PRIVE_NAME from user_privileges where USER_ID =".$userId;
	$data = $link->getcolumnDataFromQuery($sql);
    return $data;

}

function getFullNameByUserIntId($link,$userIntId){
    $sql = 'select * from user_info where USER_INTID = '.$userIntId;
    $data =  $link->getRowDataFromQuery($sql);
    $FullName = $data['FIRST_NAME'].' '.$data['LAST_NAME'];
    return $FullName;
}

?>