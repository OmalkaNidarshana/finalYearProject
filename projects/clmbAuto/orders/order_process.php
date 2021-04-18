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


}

?>