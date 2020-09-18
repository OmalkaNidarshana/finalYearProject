<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";
include_once $sysPath."/orders/Order.php";
include_once $sysPath."/orders/OrderTableFormatter.php";
include_once $projPath."/shared/classes/fldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";

include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
include_once $projPath."/shared/classes/Email.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/dbControler/category.php";
include_once $projPath."/dbControler/order.php";

$csFiles[] = STYLE_ROOT."main.css";

$categoryIds = isset($_REQUEST['catIds'])?$_REQUEST['catIds']:array();

$ord = new Order($link,$userInfo);
$ord->setCategoryIds($categoryIds);

if( isset($_REQUEST['order_initiate']) ){
    if( empty($_REQUEST['EXPTD_DLV_DATE']) ){
        $ord->setErrMsg('Expected date cannot be empty');
    }else{
        $exptDlvDate = $_REQUEST['EXPTD_DLV_DATE'];
        $ordrData = $_REQUEST['qty'];
        $ordNum = $_REQUEST['ORD_NUM'];
        insertUpdateOrders($link,$userInfo,$ordNum,$exptDlvDate,$ordrData);
        $cartData = getPendingCartDataByUserId($link,$userInfo->userName);
        $cartId = $cartData['CART_ID'];
        $sql = "update cart SET STATUS = 'COMPLETED'  where CART_ID=".$cartId;
        $link->insertUpdate($sql);
    }
}


$page[] = $ord->getOrderCreationSubmit();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";
?>