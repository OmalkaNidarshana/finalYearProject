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
    var $cmpPrivileges;
    var $userPrivileges;

    function UserInfo($link,$userName){
        $this->link = $link;
        $this->userName = $userName;
        $this->userInfo = getUserInfoByUserId($link,$userName);

             
    }

    function init(){
        $this->userPrivileges = getUserPrivielegesByUserId($this->link,$this->intId);
        $this->cmpPrivileges = getCompanyPrivilegesByCmpId($this->link,$this->cmpId);
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
        if($this->role == 'SALES_REP')
            return true;
        else
            return false;
    }

    function userIsAdmistrtor(){
        if($this->role == 'ADMINISTRATOR')
            return true;
        else
            return false;

    }

    function userIsAccountManager(){
        if($this->role == 'ACCOUNT_MANAGER')
            return true;
        else
            return false;

    }

    function isUserHasAccountPriv(){
        $priv = 'ACCOUNT';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasOrdrPriv(){
        $priv = 'ORDERS';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasCategorytPriv(){
        $priv = 'CATEGORY';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasInvoicePriv(){
        $priv = 'INVOICE';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasAddUsertPriv(){
        $priv = 'ADD_USER';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasAddCustomerPriv(){
        $priv = 'ADD_CUSTOMER';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasAssignPriv(){
        $priv = 'ASSIGN_PRIVILEGES';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasCommissionPriv(){
        $priv = 'COMMISSION';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasAssignCustPriv(){
        $priv = 'ASSIGN_CUSTOMER';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isUserHasReportPriv(){
        $priv = 'REPORT';
        if( in_array($priv,$this->cmpPrivileges) ){
            if( in_array($priv,$this->userPrivileges) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}



?>