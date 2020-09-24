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
    $sql = 'select COMPANY_ID,COMAPNY_NAME from company where COMPANY_TYPE = '.getTextValue('DISTRIBUTOR');
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

function getCustIdByCustomerName($link,$customerName){
    $sql = "select COMPANY_ID  from company where COMAPNY_NAME =".getTextValue($customerName);
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}

?>