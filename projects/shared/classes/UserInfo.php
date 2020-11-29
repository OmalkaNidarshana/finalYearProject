<?php

class UserInfo{

    var $firstName;
    var $LastName;
    var $intId;
    var $userName;
    var $role;
    var $cmpId;
    var $cmpType;
    var $assignCmp = array();
    var $privileges = array();
    var $link;
    var $assignCompny = array();
    var $userInfo;

    function UserInfo($link,$userName){
        $this->link = $link;
        $this->userName = $userName;
        $this->userInfo = getUserInfoByUserId($link,$userName);
        //print_r($this->userInfo);
                
    }

    function setFirstname($firstName){
        $this->firstName = $firstName;
    }

    function setLastname($LastName){
        $this->LastName = $LastName;
    }

    function setIntId($intId){
        $this->intId = $intId;
    }

    function setRole($role){
        $this->role = $role;
    }

    function setCmpId($cmpId){
        $this->cmpId = $cmpId;
    }

    function setCmpType($cmpType){
        $this->cmpType = $cmpType;
    }

    function setAssignCompny($assignCompny){
        $this->assignCompny = $assignCompny;
    }

   function userIsSalesRep(){
        $this->userRole = $this->userInfo['USER_TYPE'];
        if($this->userRole == 'SALES_REP')
            return true;
        else
            return false;
    }

    function userIsAdmistrtor(){
        if($this->userRole == 'ADMINISTRATOR')
            return true;
        else
            return false;

    }

    function userIsAccountManager(){
        if($this->userRole == 'ACCOUNT_MANAGER')
            return true;
        else
            return false;

    }

}



?>