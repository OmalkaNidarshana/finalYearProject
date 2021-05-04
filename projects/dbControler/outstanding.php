<?php

function getOutstandingHistoryDataByInvNum($link,$invNum){
    $sql = "select * from outstanding_line where INV_NUM =".getTextValue($invNum);
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getOutstandingDataByInvNum($link,$invNum){
    $sql = "select * from outstanding_header where INV_NUM =".getTextValue($invNum);
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}

?>