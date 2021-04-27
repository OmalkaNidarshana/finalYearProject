<?php

class Account{

    var $link;
    var $userInfo;
    var $cmpId;
    var $cmpType;
    var $sysRoles = array();
    var $role = array();
    var $sysPrivileges = array();
    var $companyPrivileges = array();
    var $customerCompanyList = array();
    var $userName = '';
    var $userId = '';
    var $userdata =array();
    var $userPrive = array();

    function Account($link,$userInfo,$cmpId){
        $this->link = $link;
        $this->userInfo = $userInfo;
        $this->cmpId = $cmpId;
        
        $this->cmpData = getCompanyDataByCmpId($this->link,$this->cmpId);

        $this->sysRoles = getSystemRoles($this->link);

        foreach($this->sysRoles as $role){
            $this->role[$role['ROLE_NAME']] = $role['ROLE_NAME'];
        }

        $this->sysPrivileges = getSystemPrivileges($this->link);
        $this->companyPrivileges = getCompanyPrivilegesByCmpId($this->link,$this->cmpId);
        $this->customerCompanyList = getCustomerListByDistId($this->link,$this->cmpId);
        $this->userdata = getUserInfoByUserId($this->link,$this->userName);
        
        if( !empty($this->userdata)){
            $this->userPrive = getUserPrivielegesByUserId($this->link,$this->userdata['USER_INTID']);
        }
        
        
        
    }
    
    function setUserName($userName){ $this->userName=$userName;}
    function setUserId($userId){ $this->userId=$userId;}

    function getCompanyInfo(){
        $html = '';
        $html .=HTML::hiddenFeild('editCompanyProcessPath',makeLocalUrl('account/acc_process.php','action=deleteCompany&cmpId='.$this->cmpId),array('id'=>'editCompanyProcessPath'));
        $html .= '<div class="box-body table-responsive no-padding">';
              $html .= '<table class="table table-hover summarytable">';
                $html .= '<tr>';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Company Name : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['COMPANY_NAME'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Company Type : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['COMPANY_TYPE'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '<tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Address : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['ADRESS'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Postal Code : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['POSATL_CODE'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '<tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">City : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['CITY'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Country : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['COUNTRY'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '<tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Email : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['EMAIL'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Phone : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['PHONE'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '<tr>';
            $html .= '</table>';
        $html .= '</div>';

        $head = 'Company Informations';
        if( isset($_REQUEST['act']) && $_REQUEST['act'] == 'custDetail' ){
            $head .= ' &nbsp&nbsp|&nbsp<span><a href="'.makeLocalUrl('account/company_edit.php','sec=PROFILE&act=custDetail&cmpId='.$this->cmpId).'">'.getRawActionsIcon('edit','Edit Company').'</a></span>';
            $head .= '<span onclick="deletelCompany();">'.getRawActionsIcon('delete','Delete Company').'</span>';
            
        }else{
            $head .= ' &nbsp&nbsp|&nbsp<span><a href="'.makeLocalUrl('account/company_edit.php','cmpId='.$this->cmpId).'">'.getRawActionsIcon('edit','Edit Company').'</a></span>&nbsp';
        }
        return contentBorder($html,$head);

    }

    function getDistributorCompanyName(){
        $distributorData = getDistributorName($this->link);
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
        $i =1;
        $html .= '<table class="table table-hover summarytable">';
        foreach( $distributorData as $data){
            $html .='<tr><td>'.$i.'.&nbsp&nbsp<a href="'.makeLocalUrl('account/profile_script.php','cmpId='.$data['COMPANY_ID'].'&cmpType=dist').'">'.$data['COMPANY_NAME'].'</a></td><tr>';
            $i++;
        }
        $html .= '</table>';
        $html .='</div>';
        $head =  'Distributor Company &nbsp&nbsp|&nbsp&nbsp';
        $head .= '<span>'.getRawActionsIcon('addCmp','Add Disrtibutor').'</span>&nbsp';
        return contentBorder($html,$head);
    }

    function getUserList(){
        $userData = geUserInformationByCmpId($this->link,$this->cmpId);
        
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" >';
            $html .= '<tbody><tr>';
                $html .= '<th>First Name</th><th>Last Name</th><th>User Name</th><th>Title</th><th>Role</th>';
            $html .= '</tr>';
            foreach( $userData as $data){
                $html .= '<tr>';
                    $html .= '<td>'.$data['FIRST_NAME'].'</td>';
                    $html .= '<td>'.$data['LAST_NAME'].'</td>';
                    $html .= '<td><a href="'.makeLocalUrl('account/profile_script.php','sec=PROFILE&act=userInfo&userId='.$data['USER_NAME']).'">'.$data['USER_NAME'].'</a></td>';
                    $html .= '<td>'.$data['TITLE'].'</td>';
                    $html .= '<td>'.$data['USER_TYPE'].'</td>';
                    
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '</div>';
        $head =  'Users';
            $head .= ' &nbsp&nbsp|&nbsp&nbsp<span data-toggle="modal" data-target="#ADD_USER">'.getRawActionsIcon('addUser','Add User').'</span>&nbsp';
      
        return contentBorder($html,$head);
    }

    function getAddUserForm(){
        global $countryArray;
        foreach( $countryArray as $country){
            $countryName[$country['name']] = $country['name'];
            $countryCode[$country['code']] = $country['code'];
        }
        $html = '';
        $html .= HTML::formStart('','POST','ADD_USER_FORM');
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        $html .=HTML::hiddenFeild('cmpId',$this->userInfo->cmpId,array('id'=>'cmpId'));
        $html .=HTML::hiddenFeild('cmpType',$this->userInfo->cmpType,array('id'=>'cmpType'));
        $html .=HTML::hiddenFeild('processPath',makeLocalUrl('account/acc_process.php','action=addUser'),array('id'=>'processPath'));
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('First Name : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('FIRST_NAME','',array('style'=>'width:300px;','id'=>'FIRST_NAME')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('Last Name : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('LST_NAME','',array('style'=>'width:300px;','id'=>'LST_NAME')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('User Name : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('USER_NAME','',array('style'=>'width:300px;','id'=>'USER_NAME','placeholder'=>'This should be a valid email')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('Title : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('TITLE','',array('style'=>'width:300px;')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('User Type : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::selectFeild('USER_TYPE','USER_TYPE',array(""=>"")+$this->role,'',array('style'=>'width:300px;')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('Addres 1 : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('ADD_1','',array('style'=>'width:300px;')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
            
                $html .='<td>'.HTML::lblFeild('Addres 2 : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('ADD_2','',array('style'=>'width:300px;')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('City : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('CITY','',array('style'=>'width:300px;')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('Country : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::selectFeild('CNTRY','CNTRY',array(""=>"")+$countryName,'',array('style'=>'width:300px;')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('Phone : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::selectFeild('PHONE_CODE','PHONE_CODE',array(""=>"")+$countryCode,'',array('style'=>'width:70px;')).'&nbsp'.HTML::phoneFeild('PHONE_NUM','',array('style'=>'width:230px;','pattern'=>'[0-9]{2}-[0-9]{4}-[0-9]{3}','placeholder'=>'12-3456-789')).'</td>';
            $html .='</tr>';
            $btn = HTML::submitButtonFeild('save','Save',array('onclick'=>'addUser();'));
        $html .= HTML::openCloseTable(false,false);
        $html .= HTML::formEnd();
        $popUp = modalPopupBox('Add User','ADD_USER',$html,$btn);
        return $popUp;
    }

    function getUserInfo(){
        $userdata = getUserInfoByUserId($this->link,$this->userName);
        $html = '';
        $html .=HTML::hiddenFeild('deletUserProcessPath',makeLocalUrl('account/acc_process.php','action=deleteUser&userName='.$this->userName),array('id'=>'deletUserProcessPath'));
        $html .= '<div class="box-body table-responsive no-padding">';
              $html .= '<table class="table table-hover summarytable">';
                $html .= '<tr>';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">User Name : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['USER_NAME'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Title : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['TITLE'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '</tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">First Name : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['FIRST_NAME'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Last Name : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['LAST_NAME'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '</tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Adress 1 : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['ADDRESS_1'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Adress 2 : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['ADDRESS_2'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '</tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">City : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['CITY'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Country : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['COUNTRY'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '</tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Phone : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['PHONE'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"></td>';
                    $html .= '<td></td><td style="height:40px;" width="150px;" align="right">';
                $html .= '</tr>';
            $html .= '</table>';
        $html .= '</div>';

        $head = 'User Informations';
        if( $this->userName == $this->userInfo->userName || $this->userInfo->role == 'ADMINISTRATOR'){
            $head .= ' &nbsp&nbsp|&nbsp&nbsp<span><a href="'.makeLocalUrl('account/user_edit.php','sec=PROFILE&act=useredit&userId='.$userdata['USER_NAME']).'">'.getRawActionsIcon('edit','Edit User').'</a></span>&nbsp';
            if($this->userInfo->role == 'ADMINISTRATOR')
                $head .= '<span onclick="deleteUser();">'.getRawActionsIcon('delete','Delete User').'</span>';
        }
            return contentBorder($html,$head);
    }

    function getCompanyEditForm(){
        $html = '';
        global $countryArray;
        foreach( $countryArray as $country){
            $countryName[$country['name']] = $country['name'];
            $countryCode[$country['code']] = $country['code'];
        }
        $html .= HTML::formStart('','POST','EDIT_COMPANY');
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
       
        $html .=HTML::hiddenFeild('editCompanyProcessPath',makeLocalUrl('account/acc_process.php','action=editCompany&cmpId='.$this->cmpId),array('id'=>'editCompanyProcessPath'));
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Company Name : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('cmpdata[COMPANY_NAME]',$this->cmpData['COMPANY_NAME'],array('style'=>'width:300px;','id'=>'COMPANY_NAME')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Address :  ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('cmpdata[ADRESS]',$this->cmpData['ADRESS'],array('style'=>'width:300px;','id'=>'ADRESS')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Postal Code :   ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('cmpdata[POSATL_CODE]',$this->cmpData['POSATL_CODE'],array('style'=>'width:300px;','id'=>'POSATL_CODE')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('City : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('cmpdata[CITY]',$this->cmpData['CITY'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Country : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::selectFeild('cmpdata[COUNTRY]','COUNTRY',array(""=>"")+$countryName,$this->cmpData['COUNTRY'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Email : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('cmpdata[EMAIL]',$this->cmpData['EMAIL'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Phone : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('cmpdata[PHONE]',$this->cmpData['PHONE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td></td>';
            $html .='<td></td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td></td>';
            $html .='<td>'.HTML::buttonFeild('editCustomer','Save',array('style'=>'float: right;','onClick'=>'editCompany();')).'</td>';
        $html .='</tr>';
        $html .= HTML::openCloseTable(false,false);
        $html .= HTML::formEnd();
        return contentBorder($html,'Edit Company');
    }

    function getCompanyPrivileges(){
        $html = '';
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;","class"=>"table table-hover summarytable"));
        $html .=HTML::hiddenFeild('cmpId',$this->userInfo->cmpId,array('id'=>'cmpId'));
        $html .=HTML::hiddenFeild('cmpType',$this->userInfo->cmpType,array('id'=>'cmpType'));
        $html .=HTML::hiddenFeild('processPath',makeLocalUrl('account/acc_process.php','action=addUser'),array('id'=>'processPath'));
        foreach( $this->sysPrivileges as $privData){
            $html .='<tr>';
            $html .='<td>'.HTML::lblFeild($privData['PRIVE_NAME'],array("style"=>"padding:5px;") ).'</td>';
                if( in_array($privData['PRIVE_NAME'],$this->companyPrivileges) ){
                    $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','',true).'</td>';
                }else{
                    $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','').'</td>';
                }
                $html .='<td style="color:blue;">'.$privData['DETAILS'].'</td>';
            $html .='</tr>';
        }
        $html .= HTML::openCloseTable(false,false);
        return contentBorder($html,'Company Privileges');

    }

    function getUserPrivileges(){
        $html = '';
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        foreach( $this->sysPrivileges as $privData){
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild($privData['PRIVE_NAME'],array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','').'</td>';
                $html .='<td></td>';
            $html .='</tr>';
        }
        $html .= HTML::openCloseTable(false,false);
        return contentBorder($html,'User Privileges');

    }

    function getUserAssignedPrive(){
                        
        $html = '';
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        foreach( $this->userPrive as $priv){
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild($priv,array("style"=>"padding:5px;") ).'</td>';
                /*if( in_array($privData['PRIVE_NAME'],$userPrive) ){
                    $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','',true).'</td>';
                }else{
                    $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','').'</td>';
                }
                $html .='<td style="color:blue;"></td>';*/
            $html .='</tr>';
        }
   
        //$html .='<td>'.HTML::submitButtonFeild('save','Save',array('onclick'=>'addUser();','style'=>'float: right;')).'</td>';
        
        $html .= HTML::openCloseTable(false,false);
        return contentBorder($html,'Assigned User Privileges');
    }

    function getUserAssignedCustomer(){
        $userdata = getUserInfoByUserId($this->link,$this->userName);
        $userPrive = getUserPrivielegesByUserId($this->link,$userdata['USER_INTID']);
                    
        $html = '';
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        foreach( $userPrive as $priv){
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild($priv,array("style"=>"padding:5px;") ).'</td>';
                /*if( in_array($privData['PRIVE_NAME'],$userPrive) ){
                    $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','',true).'</td>';
                }else{
                    $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','').'</td>';
                }
                $html .='<td style="color:blue;"></td>';*/
            $html .='</tr>';
        }
   
        //$html .='<td>'.HTML::submitButtonFeild('save','Save',array('onclick'=>'addUser();','style'=>'float: right;')).'</td>';
        
        $html .= HTML::openCloseTable(false,false);
        return contentBorder($html,'Assigned Customer');
    }

    function getEditUserPrivileges(){
        
        $userdata = getUserInfoByUserId($this->link,$this->userName);
        $userPrive = getUserPrivilegesByCmpId($this->link,$userdata['USER_INTID']);
               
        $html = '';
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        foreach( $this->sysPrivileges as $privData){
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild($privData['PRIVE_NAME'],array("style"=>"padding:5px;") ).'</td>';
                if( in_array($privData['PRIVE_NAME'],$userPrive) ){
                    $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','',true).'</td>';
                }else{
                    $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','').'</td>';
                }
                $html .='<td style="color:blue;"></td>';
            $html .='</tr>';
        }
   
        $html .='<td>'.HTML::submitButtonFeild('save','Save',array('onclick'=>'addUser();','style'=>'float: right;')).'</td>';
        
        $html .= HTML::openCloseTable(false,false);
        return contentBorder($html,'Edit User Privileges');
    }

    function getAssignCustomer(){
        $customerList = getCustomerList($this->link);
        $userData = getUserInfoByUserName($this->link,$this->userName);
       
        $assignCompany = explode(",",$userData['ASSIGN_COMPANY']);
        $html = '';
        //$html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        //$html .='<div class ="container">';
        $html .= HTML::formStart('','POST','ASIGN_CUSTOMER');
        $html .=HTML::hiddenFeild('customerAssign',makeLocalUrl('account/acc_process.php','action=customerAssign'),array('id'=>'customerAssign'));
        $html .=HTML::hiddenFeild('userId',$this->userId);
        $html .='<div class ="row">';
        //print_rr($customerList);
        foreach( $customerList as $customerData){
                if( in_array($customerData['COMPANY_ID'],$assignCompany) ){
                    $html .='<div ><div class="col-sm-2">'.$customerData['COMPANY_NAME'].'</div><div class="col-sm-2">'.HTML::checkboxFeild('ASSIGN_CMP['.$customerData['COMPANY_ID'].']',$customerData['COMPANY_ID'],true).'</div></div>';
                }else{
                    $html .='<div ><div class="col-sm-2">'.$customerData['COMPANY_NAME'].'</div><div class="col-sm-2">'.HTML::checkboxFeild('ASSIGN_CMP['.$customerData['COMPANY_ID'].']',$customerData['COMPANY_ID']).'</div></div>';
                }
            
        }
        $html .='</div>';
        $html .= HTML::formEnd();
        $btn = '<span>'.HTML::submitButtonFeild('asignCustomer','Save',array('style'=>'margin-left: 10px;',"onclick"=>"assignCustomer();")).'</span>';
        //$html .='</div>';
        //$html .= HTML::openCloseTable(false,false);
        return contentBorder($html,'Assign Customer'.$btn);
    }

    function getUserEditForm(){
        $userdata = getUserInfoByUserId($this->link,$this->userName);
        $html = '';
        global $countryArray;
        foreach( $countryArray as $country){
            $countryName[$country['name']] = $country['name'];
            $countryCode[$country['code']] = $country['code'];
        }
        $html .= HTML::formStart('','POST','EDIT_COMPANY',array("enctype"=>"multipart/form-data"));
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));

        $html .=HTML::hiddenFeild('processPath',makeLocalUrl('account/acc_process.php','action=addUser'),array('id'=>'processPath'));
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('First Name : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('editUser[FIRST_NAME]',$userdata['FIRST_NAME'],array('style'=>'width:300px;','id'=>'FIRST_NAME')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Last Name :  ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('editUser[LAST_NAME]',$userdata['LAST_NAME'],array('style'=>'width:300px;','id'=>'LAST_NAME')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('User Name :   ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('editUser[USER_NAME]',$userdata['USER_NAME'],array('style'=>'width:300px;','id'=>'USER_NAME')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Title : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('editUser[TITLE]',$userdata['TITLE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Address 1 : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('editUser[ADDRESS_1]',$userdata['ADDRESS_1'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Address 2 : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('editUser[ADDRESS_2]',$userdata['ADDRESS_2'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('City : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('editUser[CITY]',$userdata['CITY'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Country : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::selectFeild('editUser[COUNTRY]','USER_TYPE',array(""=>"")+$countryName,$userdata['COUNTRY'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Phone : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('editUser[PHONE]',$userdata['PHONE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Image : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::fileUplodeFeild('USER_IMAGE','').'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td></td>';
            $html .='<td>'.HTML::submitButtonFeild('editUserData','Save',array('onclick'=>'addUser();','style'=>'float: right;')).'</td>';
        $html .='</tr>';
        $html .= HTML::openCloseTable(false,false);
        $html .= HTML::formEnd();
        return contentBorder($html,'Edit User');

    }

    function getCompanyCustomerList(){
        $html = '<table class="table table-hover summarytable" >';
            foreach( $this->customerCompanyList as $customerData){
                $html .= '<tr>';
                    $html .= '<td><a href="'.makeLocalUrl('account/profile_script.php','sec=PROFILE&act=custDetail&cmpId='.$customerData['COMPANY_ID']).'">'.$customerData['COMPANY_NAME'].'</a></td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $head =  'Customers';
            $head .= ' &nbsp&nbsp|&nbsp&nbsp<span data-toggle="modal" data-target="#ADD_CUSTOMER">'.getRawActionsIcon('addCmp','Add Customer').'</span>&nbsp';
        return contentBorder($html,$head);
    }
    
    function getCustomerList($customerList){
              
        $html ='';
        //$html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-striped projects">';
            foreach( $customerList as $customerData){
                $totalOrders = getTotalOrderByCustomerId($this->link,$customerData['COMPANY_ID']);
                $html .= '<tr>';
                    $html .= '<td><a href="'.makeLocalUrl('account/profile_script.php','sec=PROFILE&act=custDetail&cmpId='.$customerData['COMPANY_ID']).'">'.$customerData['COMPANY_NAME'].'</a></td>';
                    $html .= '<td align="right">';
                        $html .= '<span><a href="'.makeLocalUrl('orders/order_script.php','sec=ORDER&custId='.$customerData['COMPANY_ID']).'"'.getIconButton('fa fa-folder','View Orders','Style="background-color: #0069d9;color:white;border-color: #0062cc;"',$totalOrders).'</a></span>';
                        if( $this->userInfo->userIsSalesRep() ){
                            $html .= '<span><a href="'.makeLocalUrl('orders/order_creation.php','sec=ORD_CREATION&custId='.$customerData['COMPANY_ID']).'"'.getIconButton('fa fa-cart-plus"','Add Order','Style="background-color: #117a8b;color:white;border-color: #117a8b;"').'</a></span>';
                        }
                    $html .= '</td>';
                }
            $html .= '</table>';
        //$html .= '</div>';
        return contentBox($html);
    }

    function getCompanyAddForm(){
        $html = '';
        global $countryArray;
        foreach( $countryArray as $country){
            $countryName[$country['name']] = $country['name'];
            $countryCode[$country['code']] = $country['code'];
        }
        $html .= HTML::formStart('','POST','ADD_CUSTOMER_FORM');
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        $html .=HTML::hiddenFeild('cmpId',$this->userInfo->cmpId,array('id'=>'cmpId'));
        $html .=HTML::hiddenFeild('cmpType',$this->userInfo->cmpType,array('id'=>'cmpType'));
        $html .=HTML::hiddenFeild('customerAddProcessPath',makeLocalUrl('account/acc_process.php','action=addCustomer'),array('id'=>'customerAddProcessPath'));

        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Company Name : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('COMPANY_NAME','',array('style'=>'width:300px;','id'=>'COMPANY_NAME')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Address :  ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('ADRESS','',array('style'=>'width:300px;','id'=>'ADRESS')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Postal Code :   ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('POSATL_CODE','',array('style'=>'width:300px;','id'=>'POSATL_CODE')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('City : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('CITY ','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Country : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::selectFeild('COUNTRY','COUNTRY',array(""=>"")+$countryName,'',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Email : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('EMAIL  ','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Phone : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::selectFeild('PHONE_CODE','PHONE_CODE',array(""=>"")+$countryCode,'',array('style'=>'width:70px;')).'&nbsp'.HTML::phoneFeild('PHONE_NUM','',array('style'=>'width:230px;','pattern'=>'[0-9]{2}-[0-9]{4}-[0-9]{3}','placeholder'=>'12-3456-789')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td></td>';
            $html .='<td></td>';
        $html .='</tr>';
        $html .= HTML::formEnd();
        $btn = HTML::submitButtonFeild('save','Save',array('onclick'=>'addCompany();','style'=>'float: right;'));
       
        $html .= HTML::openCloseTable(false,false);
        $popUp = modalPopupBox('Add Customer','ADD_CUSTOMER',$html,$btn);
        return $popUp;
    }
}
?>