<?php

function getMaxOrderId($link){
    $sql = 'select max(ORDER_ID) from orders';
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}

function insertUpdateOrders($link,$userInfo,$ordNum,$custId,$ordrData){
    $quantityData = $ordrData['qty'];
    $descriptionData = $ordrData['desk'];
    $disscountData = $ordrData['diss'];

    $insertData = array();
    $sql = 'select max(ORDER_ID) from orders where ORDER_NUM ='.getTextValue($ordNum);
    $id = $link->getObjectDataFromQuery($sql);
    if(empty($id)){
        $lineNum =1;
        $insertData['COMPANY_ID'] = $userInfo->cmpId;
        $insertData['CUSTOMER_ID'] = $custId;
        $insertData ['ORDER_NUM'] = getTextValue($ordNum);
        $insertData['LINE_ITEM'] = count($quantityData);
        $insertData['ORDER_DATE'] = getCurrentDateTime();
       // $insertData['EXPECTED_DELIVERY_DATE'] = dateTimeValue($exptDlvDate);
        $insertData['STATUS'] = getTextValue('NEW');
        $insertData['CREATED_BY'] = $userInfo->intId;
        $insertData['MODIFIED_BY'] = $userInfo->intId;
        $insertData['CREATED_DATE'] = getCurrentDateTime();
        $insertData['MODIFIED_DATE'] = getCurrentDateTime();

        $sql = 'insert into orders ('.implode(",",array_keys($insertData) ).') values ('.implode(",",array_values($insertData) ).')';
        $link->insertUpdate($sql);
        $headerId = getMaxOrderId($link);
        foreach( $quantityData as $categoryId=>$quantity ){
            $categoryData = getCategoryDataBycategoryId($link,$categoryId);
            $description = $descriptionData[$categoryId];
            $discount = $disscountData[$categoryId];
            insertUpdateOrderLines($link,$userInfo,$headerId,$lineNum,$categoryData,$discount,$quantity,$ordNum,$description);
            $lineNum++;
        }
    }
    return $headerId;
}

function insertUpdateOrderLines($link,$userInfo,$headerId,$lineNum,$data,$discount,$quantity,$ordNum,$description){
    $sql = 'select LINE_ID from order_lines where ORDER_HEADER_ID ='.$headerId.' and LINE_NUM='.$lineNum;
    $lineId = $link->getObjectDataFromQuery($sql);
    if( empty($lineId) ){
        $insertData['ORDER_HEADER_ID'] = $headerId;
        $insertData['LINE_NUM'] = $lineNum;
        
        
        $insertData['QUANTITY'] = $quantity;
        $insertData['CAT_ID'] = $data['RECORD_ID'];
        $insertData['BRAND'] = getTextValue($data['BRAND']);
        $insertData['MODEL'] = getTextValue($data['MODEL']);
        $insertData['BRISK'] = getTextValue($data['BRISK']);
        $insertData['CATEGORY'] = getTextValue($data['VEHICAL_CODE']);
        $insertData['SELL_PRICE'] = $data['SELL_PRICE'];
        $insertData['DISCOUNT_RATE'] = $discount;
        $insertData['TOTAL'] = $quantity*$data['SELL_PRICE'];

        $insertData['DISCOUNT'] = $insertData['TOTAL']*($discount/100);
        $insertData['ORDER_DATE'] = getCurrentDateTime();
        $insertData['STATUS'] = getTextValue('NEW');
        $insertData['DESCRIPTION'] = getTextValue($description);
        $insertData['CREATED_BY'] = $userInfo->intId;
        $insertData['MODIFIED_BY'] = $userInfo->intId;
        $insertData['CREATED_DATE'] = getCurrentDateTime();
        $insertData['MODIFIED_DATE'] = getCurrentDateTime();
        
        $sql = 'insert into order_lines ('.implode(",",array_keys($insertData) ).') values ('.implode(",",array_values($insertData) ).')';
        $link->insertUpdate($sql);
        insertCommission($link,$data['VEHICAL_CODE'],$insertData['TOTAL'],$data['SELL_PRICE'],$data['COMMISION'],$ordNum);
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

function getOrderDataByOrderNum($link,$ordrNum){
    $sql ='select * from orders where ORDER_NUM ='.getTextValue($ordrNum);
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}

function getTotalFromOrderByorderHeaderId($link,$ordrId){
    $sql ='select TOTAL from order_lines where ORDER_HEADER_ID ='.getTextValue($ordrId);
    $data = $link->getcolumnDataFromQuery($sql);
    return $data;
}

function insertCommission($link,$category,$total,$salesPrice,$rate,$ordNum){
    global $userInfo;
    $insert['SALES_REP_ID'] = $userInfo->intId;
    $insert['CATEGORY'] = getTextValue($category);
    $insert['ORDER_NUM'] = getTextValue($ordNum);
    $insert['TOTAL '] = $total;
    $insert['SALES_PRICE'] = $salesPrice;
    $insert['COMMISION_RATE'] = $rate;
    $insert['COMMISION'] = getCommisions($total,$rate);
    $insert['CREATED_DATE'] = getCurrentDateTime();
    $sql = 'insert into commisions ('.implode(",",array_keys($insert) ).') values ('.implode(",",array_values($insert) ).')';
    $link->insertUpdate($sql);
}

function getItemList($link){ //get all unique item name from table
    $sql = "select distinct(BRISK) from category";
    $data = $link->getColumnDataFromQuery($sql);
    return $data;
}

function getModelListByBrickCode($link,$briskCode){ //get all model list related to brisk code
    $sql = "select distinct(MODEL) from category where BRISK = ".getTextValue($briskCode);
    $data = $link->getColumnDataFromQuery($sql);
    return $data;
}

function getItemDataByBriskCodeAndModel($link,$briskCode,$model){ //get all item type releted to brisk number
    $sql = "SELECT * FROM category WHERE BRISK = ".getTextValue($briskCode)." and MODEL = ".getTextValue($model);
    $data = $link->getRecordSetFromQuery($sql);
    return $data;
}

function getTotalOrderByCustomerId($link,$custId){// get number of order for each customer
    $sql ='select count(ORDER_ID) from orders where CUSTOMER_ID ='.$custId;
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}

function getRejectedOrdrIdByHeaderAndLineId($link,$ordrNum,$lineId){
    $sql ='select * from reject_orders where ORDER_NUM ='.getTextValue($ordrNum).' and LINE_NUM ='.$lineId;
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}
?>