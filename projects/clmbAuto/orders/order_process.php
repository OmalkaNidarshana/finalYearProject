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
    $sql = 'update orders set STATUS = '.getTextValue('SUBMITTED');
    $link->insertUpdate($sql);
}elseif( $action =='editOrderLine' ){
    $ord = new Order($link,$userInfo);
    $editLinePopup = $ord->getOrderLineEditForm($ordId,$lineId);
    echo json_encode($editLinePopup);
}

?>