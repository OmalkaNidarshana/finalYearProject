<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";
include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
//include_once $projPath."/shared/classes/Email.php";

include_once $sysPath."/orders/Order.php";
include_once $sysPath."/orders/OrderTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";

include_once $projPath."/dbControler/order.php";
include_once $projPath."/dbControler/category.php";

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$ordId = isset($_REQUEST['orderId'])?$_REQUEST['orderId']:'';
$lineId = isset($_REQUEST['lineId'])?$_REQUEST['lineId']:'';

if( $action =='orderSubmit' ){
    $sql = 'update orders set STATUS = '.getTextValue('SUBMITTED').'where ORDER_ID='.$ordId;
    $link->insertUpdate($sql);
    
}elseif( $action =='loadEditLineForm' ){
    $ord = new Order($link,$userInfo);
    $editLinePopup = $ord->getOrderLineEditForm($ordId,$lineId);
    echo json_encode($editLinePopup);
}elseif( $action =='loadRejecItemPopUp' ){
    $ord = new Order($link,$userInfo);
    $editLinePopup = $ord->getRejectItemForm($ordId,$lineId);
    echo json_encode($editLinePopup);
}elseif( $action =='editOrderLine' ){
    $orderLineData = getOrderLineDateByHeaderAndLineId($link,$ordId,$lineId);
    $updateData = array();

    $discountRate = $_REQUEST['diss'];
    $total = $_REQUEST['QUANTITY']*$orderLineData['SELL_PRICE'];

    $updateData['QUANTITY'] = $_REQUEST['QUANTITY'];
    $updateData['TOTAL'] = $total;
    $updateData['DISCOUNT'] = $total*($discountRate/100);
    $updateData['EXPECTED_DELIVERY_DATE'] = dateTimeValue($_REQUEST['EXPECTED_DELIVERY_DATE']);

    foreach($updateData as $key=>$value){
        $dataArr[] = $key.'='.$value;
    }
    $sql = 'update order_lines set '.implode(",",$dataArr).' where ORDER_HEADER_ID ='.$ordId.' and LINE_NUM ='.$lineId;
   
    $link->insertUpdate($sql);
}elseif($action =='deleteOrdLine'){
    
    $sql = 'delete from order_lines where ORDER_HEADER_ID ='.$ordId.' and LINE_NUM ='.$lineId;
    $link->insertUpdate($sql);
}elseif($action =='loadItemData'){
    
    $reletedItemData = getItemDataByBriskCodeAndModel($link,$_REQUEST['brisk'],$_REQUEST['model']);
    echo json_encode($reletedItemData);
}elseif($action =='loadModelList'){
    $briskCode = $_REQUEST['brsikCode'];
    $modelList = getModelListByBrickCode($link,$briskCode);
    foreach($modelList as $model){
        $modelListArr[$model] = $model;
    }
    echo json_encode($modelListArr);
}elseif($action =='cancleOrder'){
    $sql = 'update orders set STATUS = '.getTextValue('CANCELD').'where ORDER_ID='.$ordId;
    $link->insertUpdate($sql);

    $sql = 'update order_lines set STATUS = '.getTextValue('CANCELD').'where ORDER_HEADER_ID='.$ordId;
    $link->insertUpdate($sql);

}elseif($action =='verifyOrder'){
    $ordId = $_REQUEST['ordId'];
    $ordLineData = geOrderLineByOrderHeaderId($link,$ordId);
    foreach($ordLineData as $data){
        $catId = $data['CAT_ID'];
        $catrgorydata = getCategoryDataBycategoryId($link,$catId);
        $msg = 'Verifing Line Number '.$data['LINE_NUM'];
        $css = 'lead';
        $jsonData[$data['LINE_NUM']]['msg'] = $msg;
        $jsonData[$data['LINE_NUM']]['css'] = $css;

        if($data['QUANTITY']<=$catrgorydata['STOCK']){
            $qntryVerify = '--- Line quantity is matched.';
            $jsonData[$data['LINE_NUM']]['qty']['qntyVerify'] = $qntryVerify;
            $jsonData[$data['LINE_NUM']]['qty']['qntycss'] = 'text-green';
            $qtyErr = false;
        }else{
            $qntyVerify = '--- Line quantity exceed stock quantity.';
            $jsonData[$data['LINE_NUM']]['qty']['qntyVerify'] = $qntyVerify;
            $jsonData[$data['LINE_NUM']]['qty']['qntycss'] = 'text-red';
            $qtyErr = true;
        }

        if($data['SELL_PRICE']=$catrgorydata['SELL_PRICE']){
            $priceVeri = '--- Unit price is matched.';
            $jsonData[$data['LINE_NUM']]['price']['priceVeri'] = $priceVeri;
            $jsonData[$data['LINE_NUM']]['price']['pricecss'] = 'text-green';
            $priceErr = false;
        }else{
            $priceVeri = '--- Unit price is dose not matched.';
            $jsonData[$data['LINE_NUM']]['price']['priceVeri'] = $priceVeri;
            $jsonData[$data['LINE_NUM']]['price']['pricecss'] = 'text-red';
            $priceErr = true;
        }

        if( !$qtyErr && !$priceErr ){
            $sql = 'update order_lines set STATUS = "VERIFIED" where ORDER_HEADER_ID ='.$ordId.' and LINE_NUM ='.$data['LINE_NUM'];
            $link->insertUpdate($sql);

            $sql = 'update orders set STATUS = "VERIFIED" where ORDER_ID ='.$ordId;
            $link->insertUpdate($sql);

            $newStock = $catrgorydata['STOCK']-$data['QUANTITY'];
            $sql = 'update category set STOCK = '.$newStock.' where RECORD_ID ='.$catId;
            $link->insertUpdate($sql);

        }else{
            $sql = 'update order_lines set STATUS = "FAILD"  where  ORDER_HEADER_ID ='.$ordId.' and LINE_NUM ='.$data['LINE_NUM'];
            $link->insertUpdate($sql);

            $sql = 'update orders set STATUS = "FAILD" where ORDER_ID ='.$ordId;
            $link->insertUpdate($sql);
        }
    }
    echo json_encode($jsonData);
}elseif($action =='rejectItem'){
    
    $orderId = $_REQUEST['orderId'];
    $lineId = $_REQUEST['lineId'];
    $orderData = getOrderDetailsByOrderId($link,$orderId);
    $orderLineData = getOrderLineDateByHeaderAndLineId($link,$orderId,$lineId);

    $data['ORDER_NUM'] = getTextValue($orderData['ORDER_NUM']);
    $data['CUSTOMER_ID'] = getNumValue($orderData['CUSTOMER_ID']);
    $data['ORDER_DATE'] = dateTimeValue($orderData['ORDER_DATE']);
    
    $data['LINE_NUM'] = getNumValue($orderLineData['LINE_NUM']);
    $data['BRAND'] = getTextValue($orderLineData['BRAND']);
    $data['MODEL'] = getTextValue($orderLineData['MODEL']);
    $data['BRISK'] = getTextValue($orderLineData['BRISK']);
    $data['CATEGORY'] = getTextValue($orderLineData['CATEGORY']);

    $data['REJECTED_QTY'] = getNumValue($_REQUEST['REJECTED_QTY']);
    $data['REJECTED_REASON'] = getTextValue($_REQUEST['REJECT_REASON']);
    $data['REJECTED_DATE'] = dateTimeValue($_REQUEST['REJECTED_DATE']);

    $data['CREATED_BY'] = $userInfo->intId;
    $data['MODIFIED_BY'] = $userInfo->intId;
    $data['CREATED_DATE'] = getCurrentDateTime();
    $data['MODIFIED_DATE'] = getCurrentDateTime();

    $sql = 'insert into reject_orders ('.implode(",",array_keys($data)).') values ('.implode(",",array_values($data)).')';
    $link->insertUpdate($sql);

    if( $_REQUEST['REJECTED_QTY'] < $orderLineData['QUANTITY'] ){
       $description = 'Partialy quantity rejected';
    }elseif( $_REQUEST['REJECTED_QTY'] = $orderLineData['QUANTITY'] ){
        $description = 'Fully quantity rejected';
    }else{
        $description = '';
    }

    $sql = 'update order_lines set DESCRIPTION = '.getTextValue($description).'  where  ORDER_HEADER_ID ='.$orderId.' and LINE_NUM ='.$orderLineData['LINE_NUM'];
    $link->insertUpdate($sql);

}elseif($action =='reOrder'){
    $ordId =  $_REQUEST['orderId'];
    $maxId = getMaxOrderId($link);
    $newOrderId = $maxId+1;
    
    $orderData = getOrderDetailsByOrderId($link,$ordId);
    
    $newOrderData = $orderData;
    $newOrderData['ORDER_ID'] = $newOrderId;
    $newOrderData['ORDER_NUM'] = getTextValue('ORD-NUM-'.$newOrderId);
    $newOrderData['ORDER_DATE'] = getCurrentDateTime();
    $newOrderData['ORDER_TYPE'] = getTextValue('RE_ORDER');
    $newOrderData['STATUS'] = getTextValue('NEW');
    $newOrderData['CREATED_BY'] = $userInfo->intId;
    $newOrderData['MODIFIED_BY'] = $userInfo->intId;
    $newOrderData['CREATED_DATE'] = getCurrentDateTime();
    $newOrderData['MODIFIED_DATE'] = getCurrentDateTime();
    $newOrderData['DESCRIPTION'] = getTextValue('');
    $newOrderData['EXPECTED_DELIVERY_DATE'] = dateTimeValue($orderData['EXPECTED_DELIVERY_DATE']);
    $newOrderData['ACTUAL_DELIVERY_DATE'] = dateTimeValue($orderData['ACTUAL_DELIVERY_DATE']);

    $sql = 'insert into orders ('.implode(",",array_keys($newOrderData)).') values ('.implode(",",array_values($newOrderData)).')';
    $link->insertUpdate($sql);

    
    $ordrLineData = geOrderLineByOrderHeaderId($link,$ordId);
    foreach($ordrLineData as $lineData){
        
        unset($lineData['LINE_ID']);
        $lineData['ORDER_HEADER_ID'] = $newOrderId;
        $lineData['ORDER_DATE'] = getCurrentDateTime();
        $lineData['STATUS'] = getTextValue('NEW');
        $lineData['CREATED_BY'] = $userInfo->intId;
        $lineData['MODIFIED_BY'] = $userInfo->intId;
        $lineData['CREATED_DATE'] = getCurrentDateTime();
        $lineData['MODIFIED_DATE'] = getCurrentDateTime();
        $lineData['BRAND'] = getTextValue($lineData['BRAND']);
        $lineData['MODEL'] = getTextValue($lineData['MODEL']);
        $lineData['BRISK'] = getTextValue($lineData['BRISK']);
        $lineData['CATEGORY'] = getTextValue($lineData['CATEGORY']);
        $lineData['DESCRIPTION'] = getTextValue('');
        $lineData['EXPECTED_DELIVERY_DATE'] = dateTimeValue($lineData['EXPECTED_DELIVERY_DATE']);
        $lineData['ACTUAL_DELIVERY_DATE'] = dateTimeValue($lineData['ACTUAL_DELIVERY_DATE']);
        $lineData['REJECTED_DATE'] = dateTimeValue($lineData['REJECTED_DATE']);
        $lineData['REJECTED_REASON'] = dateTimeValue($lineData['REJECTED_REASON']);
       
        $sql = 'insert into order_lines ('.implode(",",array_keys($lineData)).') values ('.implode(",",array_values($lineData)).')';
        $link->insertUpdate($sql);

        
    }

    $reOrdData = $orderData;
    $reOrdData['PARENT_ORDER_ID'] = $orderData['ORDER_ID'];
    $reOrdData['REF_ORDER_ID'] = $newOrderId;
    $reOrdData['PARENT_ORDER_NUM'] = getTextValue($orderData['ORDER_NUM']);
    $reOrdData['ORDER_DATE'] = getCurrentDateTime();
    $reOrdData['ORDER_TYPE'] = getTextValue('RE_ORDER');
    $reOrdData['ORDER_NUM'] = getTextValue('ORD-NUM-'.$newOrderId);
    $reOrdData['STATUS'] = getTextValue('NEW');
    $reOrdData['DESCRIPTION'] = getTextValue('');
    $reOrdData['EXPECTED_DELIVERY_DATE'] = dateTimeValue($orderData['EXPECTED_DELIVERY_DATE']);
    $reOrdData['ACTUAL_DELIVERY_DATE'] = dateTimeValue($orderData['ACTUAL_DELIVERY_DATE']);
    $reOrdData['CREATED_BY'] = $userInfo->intId;
    $reOrdData['MODIFIED_BY'] = $userInfo->intId;
    $reOrdData['CREATED_DATE'] = getCurrentDateTime();
    $reOrdData['MODIFIED_DATE'] = getCurrentDateTime();
    
    $sql = 'insert into re_orders ('.implode(",",array_keys($reOrdData)).') values ('.implode(",",array_values($reOrdData)).')';
    $link->insertUpdate($sql);

    $loc = makeLocalUrl('orders/order_details.php','sec=ORDER&id='.$newOrderId);
    echo json_encode($loc);

}

?>