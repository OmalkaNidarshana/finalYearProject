<?php

function insertUpdateCategoryIdIntoCart($link,$data){
    $sql = 'select CART_ID from cart where SESSION_ID ='.getTextValue($data['SESSION_ID']).' and STATUS='.getTextValue('APROVED');
    $id = $link->getObjectDataFromQuery($sql);
    print_rr($id);
}




?>