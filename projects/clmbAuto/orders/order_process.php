<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";
include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/Email.php";

include_once $sysPath."/orders/Order.php";
include_once $sysPath."/orders/OrderTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/fldsAtribute.php";
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
}elseif( $action =='editOrderLine' ){
    $orderLineData = getOrderLineDateByHeaderAndLineId($link,$ordId,$lineId);
    $updateData = array();
    $updateData['QUANTITY'] = $_REQUEST['QUANTITY'];
    $updateData['TOTAL'] = $_REQUEST['QUANTITY']*$orderLineData['SELL_PRICE'];
    $updateData['EXPECTED_DELIVERY_DATE'] = dateTimeValue($_REQUEST['EXPECTED_DELIVERY_DATE']);
    foreach($updateData as $key=>$value){
        $dataArr[] = $key.'='.$value;
    }
    $sql = 'update order_lines set '.implode(",",$dataArr).' where ORDER_HEADER_ID ='.$ordId.' and LINE_NUM ='.$lineId;
   
    $link->insertUpdate($sql);
}elseif($action =='deleteOrdLine'){
    print_rr($_REQUEST);
    $sql = 'delete from order_lines where ORDER_HEADER_ID ='.$ordId.' and LINE_NUM ='.$lineId;
    $link->insertUpdate($sql);

    
}

?>