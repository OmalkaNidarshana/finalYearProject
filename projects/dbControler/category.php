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

function getCategoryDataBycategoryId($link,$catId){// get gategory data by category table by record Id
    $sql = 'select * from category where RECORD_ID ='.$catId;
    $data = $link->getRowDataFromQuery($sql);
    return $data;
}

function updateCategory($link,$cateData,$catId){// update category data from category table
    global $userInfo;
    $data = array();
    $data['BRAND'] = getTextValue($cateData['BRAND']);
    $data['MODEL'] = getTextValue($cateData['MODEL']);
    $data['VEHICAL_CODE'] = getTextValue($cateData['VEHICAL_CODE']);
    $data['ENGINE'] = getTextValue($cateData['ENGINE']);
    $data['CC'] = getTextValue($cateData['CC']);
    $data['BRISK'] = getTextValue($cateData['BRISK']);
    $data['BRISK_CODE'] = getTextValue($cateData['BRISK_CODE']);
    $data['DENSO'] = getTextValue($cateData['DENSO']);
    $data['IRIDIUM'] = getTextValue($cateData['IRIDIUM']);
    $data['STOCK_NO'] = getNumValue($cateData['STOCK_NO']);
    $data['STOCK'] = getNumValue($cateData['STOCK']);
    $data['PRICE'] = getNumValue($cateData['PRICE']);
    $data['DIS'] = getNumValue($cateData['DIS']);
    $data['SPECIAL_PRICE'] = getNumValue($cateData['SPECIAL_PRICE']);
    $data['SELL_PRICE'] = getNumValue($cateData['SELL_PRICE']);
    $data['COMMISION'] = getNumValue($cateData['COMMISION']);

    $data['MODIFIED_DATE'] = getCurrentDateTime();
    $data['MODIFIED_BY'] = $userInfo->intId;

    foreach($data as $k=>$v){
        $updateData[] = $k.'='.$v;
    }
    $sql = "update category set ".implode(",",$updateData)." where RECORD_ID=".$catId;
    $link->insertUpdate($sql);
}

function insertCategory($link,$cateData){// insert  category data into category table
    global $userInfo;
    $data = array();
    $data['BRAND'] = getTextValue($cateData['BRAND']);
    $data['MODEL'] = getTextValue($cateData['MODEL']);
    $data['VEHICAL_CODE'] = getTextValue($cateData['VEHICAL_CODE']);
    $data['ENGINE'] = getTextValue($cateData['ENGINE']);
    $data['CC'] = getTextValue($cateData['CC']);
    $data['BRISK'] = getTextValue($cateData['BRISK']);
    $data['BRISK_CODE'] = getTextValue($cateData['BRISK_CODE']);
    $data['DENSO'] = getTextValue($cateData['DENSO']);
    $data['IRIDIUM'] = getTextValue($cateData['IRIDIUM']);
    $data['STOCK_NO'] = getNumValue($cateData['STOCK_NO']);
    $data['STOCK'] = getNumValue($cateData['STOCK']);
    $data['PRICE'] = getNumValue($cateData['PRICE']);
    $data['DIS'] = getNumValue($cateData['DIS']);
    $data['SPECIAL_PRICE'] = getNumValue($cateData['SPECIAL_PRICE']);
    $data['SELL_PRICE'] = getNumValue($cateData['SELL_PRICE']);
    $data['COMMISION'] = getNumValue($cateData['COMMISION']);
    $data['CREATED_DATE'] = getCurrentDateTime();
    $data['CREATED_BY'] = getNumValue($userInfo->intId);
    $data['MODIFIED_DATE'] = getCurrentDateTime();
    $data['MODIFIED_BY'] = getNumValue($userInfo->intId);


    $sql = 'insert into category ('.implode(',',array_keys($data)).') values('.implode(',',array_values($data)).')';
    $link->insertUpdate($sql);
}
?>