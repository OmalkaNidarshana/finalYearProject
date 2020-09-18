<?php
function getMainMenueArray(){
    $mainMenueArr['HOME'] = 'Home';
    $mainMenueArr['PROFILE'] = 'Profile';
    $mainMenueArr['ACC'] = 'Accounts';
    $mainMenueArr['CATEGORY'] = 'Category';
    $mainMenueArr['ORDERS'] = 'Orders';
    $mainMenueArr['PERIOD'] = 'Credit Period';
    return $mainMenueArr;
}

function getSubMenueArray(){
    $subMenueArr['HOME'] = makeLocalUrl('main/home_script.php','sec=HOME') ;
    $subMenueArr['PROFILE'] = makeLocalUrl('account/profile_script.php','sec=PROFILE');
    $subMenueArr['ACC']['Sales'] = 'www.google.com';
    $subMenueArr['ACC']['Incomes'] = 'www.google.com';
    $subMenueArr['ACC']['Expenses'] = 'www.google.com';
   
    $subMenueArr['ORDERS']['Orders'] = makeLocalUrl('orders/order_script.php','sec=ORDER');
    $subMenueArr['ORDERS']['Re-Orders'] = 'www.google.com';
    $subMenueArr['ORDERS']['Rejected Orders'] = 'www.google.com';
    $subMenueArr['CATEGORY'] =  makeLocalUrl('cat/category_script.php','sec=CAT') ;
    $subMenueArr['PERIOD'] = '';

    

    return $subMenueArr;
}

function getHeaderMenueIcons(){
    $MenueIcons['HOME'] = 'fa fa-home fa-lg';
    $MenueIcons['PROFILE'] = 'fa fa-user fa-lg';
    $MenueIcons['ACC'] = 'fa fa-credit-card fa-lg';
    $MenueIcons['CATEGORY'] = 'fa fa-sign-in fa-lg';
    $MenueIcons['ORDERS'] = 'fa fa-arrows-v fa-lg';
    $MenueIcons['PERIOD'] = 'fa fa-clock-o fa-lg';
    return $MenueIcons;
}

