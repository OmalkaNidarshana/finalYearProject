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
    $sql = 'select COMPANY_ID,COMAPNY_NAME from company where COMAPNY_TYPE = '.getTextValue('DISTRIBUTOR');
    $data =  $link->getRecordSetFromQuery($sql);
    return $data;
}

function geUserInformationByCmpId($link,$cmpId){
    $sql = 'select * from user_info where COMPANY_ID = '.$cmpId;
    $data =  $link->getRecordSetFromQuery($sql);
    return $data;
}

function getUserInfoByUserId($link,$userId){
    $sql = 'select * from user_info where USER_INTID = '.$userId;
    $data =  $link->getRowDataFromQuery($sql);
    return $data;
}
?>