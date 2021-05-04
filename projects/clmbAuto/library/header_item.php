<?php
function getMainMenueArray(){
    $mainMenueArr['HOME'] = 'Home';
    $mainMenueArr['PROFILE'] = 'Profile';
    $mainMenueArr['ACC'] = 'Accounts';
    $mainMenueArr['CUSTOMER'] = 'Customers';
    $mainMenueArr['CATEGORY'] = 'Category';
    $mainMenueArr['ORDERS'] = 'Orders';
    $mainMenueArr['COMMISSION'] = 'Commission';
    $mainMenueArr['PERIOD'] = 'Credit Period';
    $mainMenueArr['RECEIPT'] = 'Receipt';
    $mainMenueArr['INVOICE'] = 'Invoice';
    $mainMenueArr['REPORT'] = 'Report';

    return $mainMenueArr;
}

function getSubMenueArray(){
    $subMenueArr['HOME'] = makeLocalUrl('main/home_script.php','sec=HOME') ;
    $subMenueArr['PROFILE'] = makeLocalUrl('account/profile_script.php','sec=PROFILE');
    $subMenueArr['ACC']['Sales'] = makeLocalUrl('orders/sales_orders_script.php','sec=SALES') ;
    $subMenueArr['ACC']['Incomes'] = makeLocalUrl('orders/income_script.php','') ;
    //$subMenueArr['ACC']['Expenses'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['ACC']['Out Standing'] = makeLocalUrl('outstanding/outstanding_script.php','sec=OUTSTAND') ;
    $subMenueArr['CUSTOMER'] = makeLocalUrl('account/customer_script.php','sec=CUSTOMER') ;
    $subMenueArr['ORDERS']['Orders'] = makeLocalUrl('orders/order_script.php','sec=ORDER');
    $subMenueArr['ORDERS']['Re-Orders'] = makeLocalUrl('orders/order_script.php','sec=ORDER') ;
    $subMenueArr['ORDERS']['Rejected Orders'] = makeLocalUrl('orders/rejected_orders_script.php','sec=RECTED_ORDER') ;
    $subMenueArr['COMMISSION'] = makeLocalUrl('commission/commission_script.php','') ;
    $subMenueArr['CATEGORY'] =  makeLocalUrl('cat/category_script.php','sec=CAT') ;
    $subMenueArr['PERIOD'] = makeLocalUrl('main/restricted.php','') ;
    //$subMenueArr['RECEIPT'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['INVOICE'] = makeLocalUrl('invoice/invoice_script.php','sec=INV') ;
    $subMenueArr['REPORT']['Orders'] = makeLocalUrl('report/order_report_script.php','sec=REP_ORDER') ;
    $subMenueArr['REPORT']['Re-Orders'] = makeLocalUrl('report/re_order_report_script.php','sec=REP_RE_ORDER') ;
    $subMenueArr['REPORT']['Rejected Orders'] = makeLocalUrl('report/rejected_order_report_script.php','sec=REP_REJCT_ORDER') ;
    $subMenueArr['REPORT']['Sales'] = makeLocalUrl('report/sales_report_script.php','sec=REP_SALES') ;
    return $subMenueArr;
}

function getHeaderMenueIcons(){
    $MenueIcons['HOME'] = 'fa fa-home';
    $MenueIcons['PROFILE'] = 'fa fa-user';
    $MenueIcons['ACC'] = 'fa fa-credit-card';
    $MenueIcons['CUSTOMER'] = 'fa fa-handshake-o';
    $MenueIcons['CATEGORY'] = 'fa fa-sign-in';
    $MenueIcons['COMMISSION'] = 'fa fa-usd';
    $MenueIcons['ORDERS'] = 'fa fa-arrows-v';
    $MenueIcons['PERIOD'] = 'fa fa-clock-o';
    $MenueIcons['RECEIPT'] = 'fa fa-receipt	';
    $MenueIcons['INVOICE'] = 'fa fa-cc-visa ';
    $MenueIcons['INVOICE'] = 'fa fa-cc-visa ';
    $MenueIcons['REPORT'] = 'fa fa-file-text-o';
    return $MenueIcons;
}

