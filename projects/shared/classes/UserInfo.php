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
    

    function UserInfo($link,$userName){
        $this->link = $link;
        $this->userName = $userName;
                
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

   /* function getUserAssignCompany(){

    }

    function getUserPrivilegeByUserId(){


    }*/
}



?>