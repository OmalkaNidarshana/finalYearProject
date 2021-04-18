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
$auth = new Authentication($link,$userInfo->userName,'');
$ord = new Order($link,$userInfo);

if( isset($_REQUEST['order_initiate']) ){
        $customerId = $_REQUEST['custId'];
        $ordrData['qty'] = $_REQUEST['qty'];
        $ordrData['desk'] = $_REQUEST['desk'];
        $ordrData['diss'] = $_REQUEST['diss'];
        $ordNum = $_REQUEST['ORD_NUM'];
        $headerId = insertUpdateOrders($link,$userInfo,$ordNum,$customerId,$ordrData);
        $customerData = getCompanyDataByCmpId($link,$customerId);
        $auth->sendorderCreationEmailContent($ordNum,$customerData['COMPANY_NAME'],$headerId,$userInfo);

        header('Location: '.makeLocalUrl('orders/order_details.php','sec=ORDER&id='.$headerId));
        exit;
}

//$page[] = $ord->getOrderCreationAction();
$page[] = $ord->getOrderCreationSubmit();

//$page[] = $ord->addItemPopup();

include_once $sysPath."/library/header.php";
    getPageContentArea($page);
include_once $sysPath."/library/footer.php";
?>