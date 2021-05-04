<?php

include_once "../path.php";
include_once $sysPath."/auth/check_auth.php";
include_once $sysPath."/library/utill.php";
include_once $sysPath."/library/library.php";
include_once $sysPath."/cat/Category.php";
include_once $sysPath."/cat/CategoryTableFormatter.php";
include_once $projPath."/shared/classes/HTML.php";
include_once $projPath."/shared/classes/Script.php";
include_once $projPath."/shared/classes/SortableTable.php";
include_once $projPath."/shared/classes/MainChart.php";
include_once $projPath."/shared/classes/FldsAtribute.php";
include_once $projPath."/shared/classes/TableFormatter.php";
include_once $projPath."/dbControler/shared.php";
include_once $projPath."/dbControler/category.php";


include_once $projPath."/dbControler/category.php";




$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
$catId = isset($_REQUEST['catId'])?$_REQUEST['catId']:'';
$cmpId = isset($_REQUEST['cmpId'])?$_REQUEST['cmpId']:'';

if( $action=='addCart' ){
    $data = array();
    $data['SESSION_ID'] = $userInfo->userName;
    $data['CATEGORY_ID'] = $catId;
    $data['COMPANY_ID '] = $cmpId;
        
    insertUpdateCategoryIdIntoCart($link,$data);
}elseif( $action=='editCategory' ){
    $ord = new Category($link,$userInfo);
    $editLinePopup = $ord->getCategoryEditForm($catId);
    echo json_encode($editLinePopup);
}elseif( $action=='saveEditLine' ){
    $catId = $_REQUEST['categoryId'];
    $catData = $_REQUEST['catData'];
    updateCategory($link,$catData,$catId);
}elseif( $action=='addCategory' ){
   insertCategory($link,$_REQUEST['catData']);
}elseif( $action=='deleteItem' ){
   $sql = "delete from category where RECORD_ID=".$catId;
   $link->insertUpdate($sql);
}

?>