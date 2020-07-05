<?php
function getMainMenueArray(){
    $mainMenueArr['HOME'] = 'Home';
    $mainMenueArr['PROFILE'] = 'Profile';
    $mainMenueArr['ACC'] = 'Accounts';
    $mainMenueArr['CATEGORY'] = 'Category';
    $mainMenueArr['ORDERS'] = 'Orders';
    return $mainMenueArr;
}

function getSubMenueArray(){
    $subMenueArr['HOME'] = makeLocalUrl('main/home_script.php','sec=HOME') ;
    $subMenueArr['PROFILE'] = makeLocalUrl('account/profile_script.php','sec=PROFILE');
    $subMenueArr['ACC'] = 'www.facebook.com';
    $subMenueArr['CATEGORY'] =  makeLocalUrl('cat/category_script.php','sec=CAT') ;
    $subMenueArr['ORDERS']['Orders'] = 'www.google.com';
    $subMenueArr['ORDERS']['Re-Orders'] = 'www.google.com';
    $subMenueArr['ORDERS']['Rejected Orders'] = 'www.google.com';

    return $subMenueArr;
}

function getHeaderMenueIcons(){
    $MenueIcons['HOME'] = 'fa fa-home fa-lg';
    $MenueIcons['PROFILE'] = 'fa fa-user fa-lg';
    $MenueIcons['ACC'] = 'fa fa-arrows-v fa-lg';
    $MenueIcons['CATEGORY'] = 'fa fa-sign-in fa-lg';
    $MenueIcons['ORDERS'] = 'fa fa-arrows-v fa-lg';
    return $MenueIcons;
}

