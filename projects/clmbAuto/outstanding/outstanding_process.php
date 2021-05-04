<?php
include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/account/Account.php";
include_once $projPath."/shared/classes/Authentication.php";
include_once $projPath."/shared/classes/DbConnection.php";
//include_once $projPath."/shared/classes/Email.php";

include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";

include_once $projPath."/dbControler/order.php";
include_once $projPath."/dbControler/category.php";

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';

if( $action=='addAmount' ){
    $subData = array();
    $mainData= array();
    //print_rr($_REQUEST);
    $totalAmmount = $_REQUEST['TOTAL_AMMOUNT'];
    $paidAmmount = $_REQUEST['PAID_AMOUNT'];
    $newAmmount = $_REQUEST['NEW_AMOUNT'];

    $subData['INV_NUM'] =getTextValue($_REQUEST['INV_NUM']);
    $subData['ORDER_NUM'] = getTextValue($_REQUEST['ORDER_NUM']);
    $subData['PAID_DATE'] = dateTimeValue($_REQUEST['PAYMENT_DATE']);
    $subData['PAID_AMOUNT'] = $newAmmount;
    $subData['CREATED_BY'] = $userInfo->intId;
    $subData['MODIFIED_BY'] = $userInfo->intId;
    $subData['CREATED_DATE'] = getCurrentDateTime();
    $subData['MODIFIED_DATE'] = getCurrentDateTime();
    
    $sql = "insert into outstanding_line (".implode(',',array_keys($subData)).") values(".implode(',',array_values($subData)).")";
    $link->insertUpdate($sql);

    $newPaidAmmount = $paidAmmount+$newAmmount;
    $mainData['PAID_AMOUNT'] = $newPaidAmmount;
    $mainData['DUE_AMOUNT'] = $totalAmmount-$newPaidAmmount;
    $mainData['MODIFIED_DATE'] = getCurrentDateTime();
    $mainData['MODIFIED_BY'] = $userInfo->intId;
    foreach($mainData as $k=>$v){
        $data[] = $k.'='.$v;
    }
    $sql = 'update outstanding_header set '.implode(",",$data).' where INV_NUM='.getTextValue($_REQUEST['INV_NUM']);
    $link->insertUpdate($sql);

}
?>