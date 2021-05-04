<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";
include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
//include_once $projPath."/shared/classes/Email.php";

include_once $sysPath."/invoice/Invoice.php";
include_once $sysPath."/invoice/InvoiceTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/fldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";

include_once $projPath."/dbControler/order.php";
include_once $projPath."/dbControler/invoice.php";

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

if( $action =='addInvoice' ){
    $orderData = getOrderDataByOrderNum($link,$_REQUEST['ORDER_NUM']);
    
    $insertData = array();
    $paymentMethod = $_REQUEST['PAYMENT_METHOD'];
    $invoiceDate = strToTimeConverter($_REQUEST['INVOICE_DATE']);
    $inrementedDate = dateIncrementer("+1 MONTH",$invoiceDate);

    $insertData['INV_NUM'] = getTextValue($_REQUEST['INV_NUM']);
    $insertData['ORDER_NUM'] = getTextValue($_REQUEST['ORDER_NUM']);
    $insertData['CUSTOMER_ID'] = $orderData['CUSTOMER_ID'];
    $insertData['PAYMENT_METHOD'] = getTextValue($paymentMethod);
    $insertData['INVOICE_DATE'] = dateTimeValue($_REQUEST['INVOICE_DATE']);

    if( $paymentMethod == 'CASH'){
        $insertData['INVOICE_CLOSE_DATE'] = dateTimeValue($inrementedDate);
    }else{
        $insertData['INVOICE_CLOSE_DATE'] = dateTimeValue($_REQUEST['INVOICE_DATE']);
    }

    $firstName = $userInfo->firstName;
    $lstName = $userInfo->LastName;
    $insertData['ISSUED_BY'] = getTextValue($firstName.' '.$lstName);

    $ordrId = getOrderIdByOrderNum($link,$_REQUEST['ORDER_NUM']);
    $total = getTotalFromOrderByorderHeaderId($link,$ordrId);
    $insertData['AMMOUNT'] = array_sum($total);
    if( !empty($_REQUEST['ADDITIONAL_DISS']) )
        $insertData['ADDITIONAL_DISSCOUNT'] = $_REQUEST['ADDITIONAL_DISS'];
    $insertData['NET_AMMOUNT'] = $insertData['AMMOUNT'] - ($insertData['AMMOUNT']*($_REQUEST['ADDITIONAL_DISS']/100));
    $insertData['STATUS'] = getTextValue('PENDING');
    $insertData['CREATED_BY'] = $userInfo->intId;
    $insertData['MODIFIED_BY'] = $userInfo->intId;
    $insertData['CREATED_DATE'] = getCurrentDateTime();
    $insertData['MODIFIED_DATE'] = getCurrentDateTime();
    
    $sql = "insert into invoice (".implode(',',array_keys($insertData)).") values(".implode(',',array_values($insertData)).")";
    $link->insertUpdate($sql);
}elseif( $action =='paidInv' ){
    $invId = $_REQUEST['invId'];
    $sql = 'update invoice set STATUS='.getTextValue('PAID').' where INV_ID ='.$invId;
    $link->insertUpdate($sql);
}elseif( $action =='loadOutstandingPopUp' ){
    $invId = $_REQUEST['invId'];
    $inv = new Invoice($link,$userInfo);
    $outstandingForm = $inv->getOutstandingOrderForm($invId);
    echo json_encode($outstandingForm);
    
}elseif( $action =='saveOutstanding' ){
    $mainData =array();
    $subData =array();
    $invId = $_REQUEST['invId'];
    
    $totalAmmount = $_REQUEST['TOTAL_AMMOUNT'];
    $paidAmmount = $_REQUEST['OUTSATNDING_AMOUNT'];
    $duePayment = $totalAmmount-$paidAmmount;
    $strTime = strToTimeConverter($_REQUEST['OUT_STANDING_DATE']);
    $closeDate = dateIncrementer('+1 MONTH',$strTime);

    $mainData['INV_NUM'] =getTextValue($_REQUEST['INV_NUM']);
    $mainData['ORDER_NUM'] = getTextValue($_REQUEST['ORDER_NUM']);
    $mainData['CUSTOMER_ID'] = $_REQUEST['CUSTOMER_ID'];
    $mainData['TOTAL_AMMOUNT'] = $totalAmmount;
    $mainData['PAID_AMOUNT'] = $paidAmmount;
    $mainData['DUE_AMOUNT'] = $duePayment;
    $mainData['OUT_STANDING_DATE'] = dateTimeValue($_REQUEST['OUT_STANDING_DATE']);
    $mainData['STATUS'] = getTextValue('OPEN');
    $mainData['CLOSED_DATE'] = dateTimeValue($closeDate);
    $mainData['REMAIN_DAYS'] = dateDiffInDays($closeDate, $_REQUEST['OUT_STANDING_DATE']);
    $mainData['CREATED_BY'] = $userInfo->intId;
    $mainData['MODIFIED_BY'] = $userInfo->intId;
    $mainData['CREATED_DATE'] = getCurrentDateTime();
    $mainData['MODIFIED_DATE'] = getCurrentDateTime();
    
    $sql = "insert into outstanding_header (".implode(',',array_keys($mainData)).") values(".implode(',',array_values($mainData)).")";
    $link->insertUpdate($sql);
    

    $subData['INV_NUM'] =getTextValue($_REQUEST['INV_NUM']);
    $subData['ORDER_NUM'] = getTextValue($_REQUEST['ORDER_NUM']);
    $subData['PAID_DATE'] = dateTimeValue($_REQUEST['OUT_STANDING_DATE']);
    $subData['PAID_AMOUNT'] = $paidAmmount;
    $subData['CREATED_BY'] = $userInfo->intId;
    $subData['MODIFIED_BY'] = $userInfo->intId;
    $subData['CREATED_DATE'] = getCurrentDateTime();
    $subData['MODIFIED_DATE'] = getCurrentDateTime();

    $sql = "insert into outstanding_line (".implode(',',array_keys($subData)).") values(".implode(',',array_values($subData)).")";
    $link->insertUpdate($sql);

    $sql = 'update invoice set STATUS='.getTextValue('OUTSTANDING').' where INV_ID ='.$invId;
    $link->insertUpdate($sql);
    
}
?>

