<?php

function insertUpdateCategoryIdIntoCart($link,$data){
    global $userInfo;
    $insertData = array();
    $sql = 'select CART_ID from cart where SESSION_ID ='.getTextValue($data['SESSION_ID']).' and STATUS='.getTextValue('PENDING');
     $id = $link->getObjectDataFromQuery($sql);
    if( empty($id) ){
        $insertData['SESSION_ID'] = getTextValue($data['SESSION_ID']);
        $insertData['CATEGORY_ID'] = getTextValue($data['CATEGORY_ID']);
        $insertData['COMPANY_ID'] = $data['COMPANY_ID '];
        $insertData['STATUS'] =  getTextValue('PENDING');
        $insertData['CREATED_DATE'] = getCurrentDateTime();
        $insertData['CREATED_BY'] = $userInfo->intId;
        $insertData['MODIFY_DATE'] = getCurrentDateTime();
        $insertData['MODIFY_BY'] = $userInfo->intId;
        $sql = 'insert into cart ('.implode(',',array_keys($insertData)).') values('.implode(',',array_values($insertData)).')';
        $link->insertUpdate($sql);
    }else{
        $updateData = array();
        $dataArr = array();
        $cartData = getCategoryIdByCartId($link,$id);
        $existCatIds = $cartData['CATEGORY_ID'];
        $newCatIds = $existCatIds.','.$data['CATEGORY_ID'];
        $updateData['CATEGORY_ID'] = getTextValue($newCatIds);
        $updateData['MODIFY_BY'] = $userInfo->intId;
        $updateData['MODIFY_DATE'] = getCurrentDateTime();
        
        foreach( $updateData as $fld=>$value){
            $dataArr[] = $fld.' = '.$value;
        }
        $sql = 'update cart set '.implode(',',$dataArr).' where CART_ID ='.$id;
        $link->insertUpdate($sql);

    }
}

function getCategoryIdByCartId($link,$cartId){
    $sql = 'select * from cart where CART_ID ='.$cartId;
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}

function getPendingCartDataByUserId($link,$userId){
    $sql = 'select * from cart where SESSION_ID ='.getTextValue($userId).' and STATUS='.getTextValue('PENDING');
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}

function getCategoryDataBycategoryId($link,$catId){
    $sql = 'select * from category where RECORD_ID ='.$catId;
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}
?>