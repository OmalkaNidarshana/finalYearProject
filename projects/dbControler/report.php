<?php

function getSubmittedOrderIds($link){
    $sql = "select ORDER_ID from orders where STATUS =".getTextValue('SUBMITTED');
    $data = $link->getcolumnDataFromQuery($sql);
    return $data;
}

function getSubmittedOrdersItemByOrdIds($link,$ordIdsArr){
    $sql = "select * from order_lines where ORDER_HEADER_ID in(".implode(",",$ordIdsArr).") order by ORDER_DATE desc";
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getPaidInvoiceByOrdIds($link){
    $sql = "select * from invoice where STATUS =".getTextValue('PAID');
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

?>