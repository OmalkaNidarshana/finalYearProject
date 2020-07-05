<?php

function getUserInfoByUserName($link,$email='*****'){
    $sql = "select * from user_info where USER_NAME = '".$email."'";
    $data = $link->getRowDataFromQuery($sql);
    return $data;

}

function getRecIdByUserName($link,$userName){
    $sql = "select USER_INTID from user_info where USER_NAME = ".getTextValue($userName);
    $data =  $link->getObjectDataFromQuery($sql);
    return $data;
}


?>