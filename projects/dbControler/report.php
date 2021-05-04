<?php

function getSubmittedOrderIds($link,$fltr){
    $sql = "select ORDER_ID from orders where STATUS =".getTextValue('SUBMITTED');
    if(!empty($fltr)){
        $sql .=" and ".$fltr;
    }

    $data = $link->getcolumnDataFromQuery($sql);
    return $data;
}

function getSubmittedOrdersItemByOrdIds($link,$ordIdsArr){
    $sql = "select * from order_lines where ORDER_HEADER_ID in(".implode(",",$ordIdsArr).") order by ORDER_DATE desc";
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getPaidInvoiceByOrdIds($link,$fltr){
    $sql = "select * from invoice where STATUS !=".getTextValue('PENDING');
    if(!empty($fltr)){
        $sql .=" and ".$fltr;
    }
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getRejectedOrderData($link,$fltr){
    $sql = "select * from reject_orders";
    if(!empty($fltr)){
        $sql .=" where ".$fltr;
    }
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getReOrderIds($link,$fltr){
    $sql = "select REF_ORDER_ID from re_orders";
    if(!empty($fltr)){
        $sql .=" where ".$fltr;
    }

    $data = $link->getcolumnDataFromQuery($sql);
    return $data;
}
?>