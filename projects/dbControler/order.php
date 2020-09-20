<?php

function getMaxOrderId($link){
    $sql = 'select max(ORDER_ID) from orders';
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}

function insertUpdateOrders($link,$userInfo,$ordNum,$exptDlvDate,$ordrData){
   
    $insertData = array();
    $sql = 'select max(ORDER_ID) from orders where ORDER_NUM ='.getTextValue($ordNum);
    $id = $link->getObjectDataFromQuery($sql);
    
    if(empty($id)){
        $lineNum =1;
        $insertData['COMAPNY_ID'] = $userInfo->cmpId;
        $insertData ['ORDER_NUM'] = getTextValue($ordNum);
        $insertData['LINE_ITEM'] = count($ordrData);
        $insertData['ORDER_DATE'] = getCurrentDateTime();
        $insertData['EXPECTED_DELIVERY_DATE'] = dateTimeValue($exptDlvDate);
        $insertData['STATUS'] = getTextValue('NEW');
        $insertData['CREATED_BY'] = $userInfo->intId;
        $insertData['MODIFIED_BY'] = $userInfo->intId;
        $insertData['CREATED_DATE'] = getCurrentDateTime();
        $insertData['MODIFIED_DATE'] = getCurrentDateTime();

        $sql = 'insert into orders ('.implode(",",array_keys($insertData) ).') values ('.implode(",",array_values($insertData) ).')';
        $link->insertUpdate($sql);
        $headerId = getMaxOrderId($link);
        foreach( $ordrData as $categoryId=>$quantity ){
            $categoryData = getCategoryDataBycategoryId($link,$categoryId);
            insertUpdateOrderLines($link,$userInfo,$headerId,$lineNum,$categoryData,$exptDlvDate,$quantity);
            $lineNum++;
        }
    }
}

function insertUpdateOrderLines($link,$userInfo,$headerId,$lineNum,$data,$exptDlvDate,$quantity){
    $sql = 'select LINE_ID from order_lines where ORDER_HEADER_ID ='.$headerId.' and LINE_NUM='.$lineNum;
    $lineId = $link->getObjectDataFromQuery($sql);
    if( empty($lineId) ){
        $insertData['ORDER_HEADER_ID'] = $headerId;
        $insertData['LINE_NUM'] = $lineNum;
        $insertData['CATEGORY'] = getTextValue($data['VEHICAL_CODE']);
        $insertData['QUANTITY'] = $quantity;
        $insertData['SELL_PRICE'] = $data['SELL_PRICE'];
        $insertData['TOTAL'] = $quantity*$data['SELL_PRICE'];
        $insertData['ORDER_DATE'] = getCurrentDateTime();
        $insertData['EXPECTED_DELIVERY_DATE'] =  dateTimeValue($exptDlvDate);
        $insertData['STATUS'] = getTextValue('NEW');
        $insertData['CREATED_BY'] = $userInfo->intId;
        $insertData['MODIFIED_BY'] = $userInfo->intId;
        $insertData['CREATED_DATE'] = getCurrentDateTime();
        $insertData['MODIFIED_DATE'] = getCurrentDateTime();
        $sql = 'insert into order_lines ('.implode(",",array_keys($insertData) ).') values ('.implode(",",array_values($insertData) ).')';
        
        $link->insertUpdate($sql);
    }

}

function getOrderDetailsByOrderId($link,$id){
    $sql = 'select * from orders where ORDER_ID ='.$id;
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}

function geOrderLineByOrderHeaderId($link,$headerId){
    $sql ='select * from order_lines where ORDER_HEADER_ID ='.$headerId;
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getOrderLineDateByHeaderAndLineId($link,$headerId,$lineId){
    $sql ='select * from order_lines where ORDER_HEADER_ID ='.$headerId.' and LINE_NUM ='.$lineId;
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}

function getSubmittedOrders($link){
    $sql ='select ORDER_NUM from orders where STATUS ='.getTextValue('SUBMITTED');
    $data = $link->getcolumnDataFromQuery($sql);
    return $data;
}

function getOrderIdByOrderNum($link,$ordrNum){
    $sql ='select ORDER_ID from orders where ORDER_NUM ='.getTextValue($ordrNum);
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}

function getTotalFromOrderByorderHeaderId($link,$ordrId){
    $sql ='select TOTAL from order_lines where ORDER_HEADER_ID ='.getTextValue($ordrId);
    $data = $link->getcolumnDataFromQuery($sql);
    return $data;
}
?>