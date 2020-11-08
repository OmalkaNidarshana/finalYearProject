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
    return $mainMenueArr;
}

function getSubMenueArray(){
    $subMenueArr['HOME'] = makeLocalUrl('main/home_script.php','sec=HOME') ;
    $subMenueArr['PROFILE'] = makeLocalUrl('account/profile_script.php','sec=PROFILE');
    $subMenueArr['ACC']['Sales'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['ACC']['Incomes'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['ACC']['Expenses'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['CUSTOMER'] = makeLocalUrl('account/customer_script.php','CUSTOMER') ;
    $subMenueArr['ORDERS']['Orders'] = makeLocalUrl('orders/order_script.php','sec=ORDER');
    $subMenueArr['ORDERS']['Re-Orders'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['ORDERS']['Rejected Orders'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['COMMISSION'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['CATEGORY'] =  makeLocalUrl('cat/category_script.php','sec=CAT') ;
    $subMenueArr['PERIOD'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['RECEIPT'] = makeLocalUrl('main/restricted.php','') ;
    $subMenueArr['INVOICE'] = makeLocalUrl('invoice/invoice_script.php','sec=INV') ;
    return $subMenueArr;
}

function getHeaderMenueIcons(){
    $MenueIcons['HOME'] = 'fa fa-home fa-lg';
    $MenueIcons['PROFILE'] = 'fa fa-user fa-lg';
    $MenueIcons['ACC'] = 'fa fa-credit-card fa-lg';
    $MenueIcons['CUSTOMER'] = 'fa fa-handshake-o';
    $MenueIcons['CATEGORY'] = 'fa fa-sign-in fa-lg';
    $MenueIcons['COMMISSION'] = 'fa fa-usd fa-lg';
    $MenueIcons['ORDERS'] = 'fa fa-arrows-v fa-lg';
    $MenueIcons['PERIOD'] = 'fa fa-clock-o fa-lg';
    $MenueIcons['RECEIPT'] = 'fa fa-file-text-o fa-lg';
    $MenueIcons['INVOICE'] = 'fa fa-cc-visa fa-lg';
    return $MenueIcons;
}

