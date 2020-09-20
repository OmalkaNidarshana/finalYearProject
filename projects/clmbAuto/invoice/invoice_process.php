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

if( $action =='addInvoice' ){
    $insertData = array();
    $paymentMethod = $_REQUEST['PAYMENT_METHOD'];
    $invoiceDate = strToTimeConverter($_REQUEST['INVOICE_DATE']);
    $inrementedDate = dateIncrementer("+1 MONTH",$invoiceDate);
    
    $insertData['INV_NUM'] = getTextValue($_REQUEST['INV_NUM']);
    $insertData['ORDER_NUM'] = getTextValue($_REQUEST['ORDER_NUM']);
    $insertData['PAYMENT_METHOD'] = getTextValue($paymentMethod);
    $insertData['INVOICE_DATE'] = dateTimeValue($invoiceDate);

    if( $paymentMethod == 'CASH'){
        $insertData['INVOICE_CLOSE_DATE'] = dateTimeValue($inrementedDate);
    }

    $firstName = $userInfo->firstName;
    $lstName = $userInfo->LastName;
    $insertData['ISSUED_BY'] = getTextValue($firstName.' '.$lstName);

    $ordrId = getOrderIdByOrderNum($link,$_REQUEST['ORDER_NUM']);
    $total = getTotalFromOrderByorderHeaderId($link,$ordrId);
    $insertData['AMMOUNT'] = array_sum($total);

    $insertData['STATUS'] = getTextValue('PENDING');
    $insertData['CREATED_BY'] = $userInfo->intId;
    $insertData['MODIFIED_BY'] = $userInfo->intId;
    $insertData['CREATED_DATE'] = getCurrentDateTime();
    $insertData['MODIFIED_DATE'] = getCurrentDateTime();
    print_rr($insertData);
    $sql = "insert into invoice (".implode(',',array_keys($insertData)).") values(".implode(',',array_values($insertData)).")";
    $link->insertUpdate($sql);
}

?>

