<?php

function getSalesRepList($link){
    $sql = 'select * from user_info where USER_TYPE ="SALES_REP"';
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getCommissionDataByRepId($link,$repId){
    $sql = 'select * from commisions where SALES_REP_ID ='.$repId;
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getDistinctOrderFromCommissionByRepId($link,$repId){
    $sql = 'select distinct(ORDER_NUM) FROM commisions WHERE SALES_REP_ID ='.$repId;
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getTotalCommissionByRepId($link,$repId){
    $sql="select sum(COMMISION) from commisions where SALES_REP_ID = ".$repId;
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}
?>