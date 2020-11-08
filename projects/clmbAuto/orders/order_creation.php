<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";
include_once $sysPath."/orders/Order.php";
include_once $sysPath."/orders/OrderTableFormatter.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";

include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
//include_once $projPath."/shared/classes/Email.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/dbControler/category.php";
include_once $projPath."/dbControler/order.php";


$csFiles[] = STYLE_ROOT."main.css";
$jsFiles[] = JS_ROOT."order.js";

$custId = isset($_REQUEST['custId'])?$_REQUEST['custId']:array();
$ids = $userInfo->assignCompny;

/*if( !in_array($custId,$ids) ){
     header("Location: ".makeLocalUrl('main/customer_restrict.php','') );
    exit;
}*/

$ord = new Order($link,$userInfo);

if( isset($_REQUEST['order_initiate']) ){
        $exptDlvDate = $_REQUEST['EXPTD_DLV_DATE'];
        $customerId = $_REQUEST['custId'];
        $ordrData['qty'] = $_REQUEST['qty'];
        $ordrData['desk'] = $_REQUEST['desk'];
        $ordNum = $_REQUEST['ORD_NUM'];
        $headerId = insertUpdateOrders($link,$userInfo,$ordNum,$exptDlvDate,$customerId,$ordrData);
        //$cartData = getPendingCartDataByUserId($link,$userInfo->userName);
       // $cartId = $cartData['CART_ID'];
       /* $sql = "update cart SET STATUS = 'COMPLETED'  where CART_ID=".$cartId;
        $link->insertUpdate($sql);*/
        header('Location: '.makeLocalUrl('orders/order_details.php','sec=ORDER&id='.$headerId));
        exit;
}


$page[] = $ord->getOrderCreationSubmit();

//$page[] = $ord->addItemPopup();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";
?>