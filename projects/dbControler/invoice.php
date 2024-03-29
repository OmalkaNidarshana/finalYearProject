<?php

function getMaxRecIdFromInvoiveTable($link){
    $sql = 'select max(INV_ID) from invoice';
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}

function getInvoiceDetailsById($link,$id){
    $sql = 'select * from invoice where INV_ID ='.$id;
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}

function getCompletedInvoiceData($link){
    $sql = 'select * from invoice where STATUS ='.getTextValue('PAID');
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getOutstandingData($link){
    $sql = 'select * from outstanding_line';
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}
?>