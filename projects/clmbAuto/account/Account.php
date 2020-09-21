<?php

class Account{

    var $link;
    var $userInfo;
    var $cmpId;
    var $cmpType;
    var $sysRoles = array();
    var $role = array();
    var $sysPrivileges = array();
   
    function Account($link,$userInfo,$cmpId){
        $this->link = $link;
        $this->userInfo = $userInfo;
        $this->cmpId = $cmpId;
        $this->cmpData = getCompanyDataByCmpId($this->link,$this->cmpId);

        $this->sysRoles = getSystemRoles($this->link);
        foreach($this->sysRoles as $role){
            $this->role[$role['ROLE_ID']] = $role['ROLE_NAME'];
        }
        $this->sysPrivileges = getSystemPrivileges($this->link);
    }

    function getCompanyInfo(){
        $html = '';
        $html .= '<div class="box-body table-responsive no-padding">';
              $html .= '<table class="table table-hover summarytable">';
                $html .= '<tr>';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Company Name : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['COMAPNY_NAME'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Company Type : &nbsp</span></td>';
                    $html .= '<td>'.$this->cmpData['COMAPNY_TYPE'].'</td><td style="height:40px;" width="150px;" align="right">';
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
        $head .= ' &nbsp&nbsp|&nbsp<span><a href="'.makeLocalUrl('account/company_edit.php','cmpId='.$this->cmpId).'">'.getRawActionsIcon('edit','Edit Company').'</a></span>&nbsp';
        
        return contentBorder($html,$head);

    }

    function getDistributorCompanyName(){
        $distributorData = getDistributorName($this->link);
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
        $i =1;
        $html .= '<table class="table table-hover summarytable">';
        foreach( $distributorData as $data){
            $html .='<tr><td>'.$i.'.&nbsp&nbsp<a href="'.makeLocalUrl('account/profile_script.php','cmpId='.$data['COMPANY_ID'].'&cmpType=dist').'">'.$data['COMAPNY_NAME'].'</a></td><tr>';
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
                    $html .= '<td><a href="'.makeLocalUrl('account/profile_script.php','sec=PROFILE&act=userInfo&userId='.$data['USER_INTID']).'">'.$data['USER_NAME'].'</a></td>';
                    $html .= '<td>'.$data['TITLE'].'</td>';
                    $html .= '<td>'.$data['USER_TYPE'].'</td>';
                    
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
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
                $html .='<td>'.HTML::lblFeild('City : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('CITY','',array('style'=>'width:300px;')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('Phone : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::selectFeild('PHONE_CODE','PHONE_CODE',array(""=>"")+$countryCode,'',array('style'=>'width:70px;')).'&nbsp'.HTML::phoneFeild('PHONE_NUM','',array('style'=>'width:230px;','pattern'=>'[0-9]{2}-[0-9]{4}-[0-9]{3}','placeholder'=>'12-3456-789')).'</td>';
            $html .='</tr>';
            $btn = HTML::submitButtonFeild('save','Save',array('onclick'=>'addUser();'));
        $html .= HTML::openCloseTable(false,false);
        $popUp = modalPopupBox('Add User','ADD_USER',$html,$btn);
        return $popUp;
    }

    function getUserInfo($userId){
        $userdata = getUserInfoByUserId($this->link,$userId);
        $html = '';
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
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Phone : &nbsp</span></td>';
                    $html .= '<td>'.$userdata['COMPANY_TYPE'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '</tr>';
            $html .= '</table>';
        $html .= '</div>';

        $head = 'User Informations';
        if( $this->cmpType =='dist' || $this->userInfo->cmpType == 'DISTRIBUTOR'){
            $head .= ' &nbsp&nbsp|&nbsp&nbsp<span>'.getRawActionsIcon('edit','Edit Company').'</span>&nbsp';
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
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        $html .=HTML::hiddenFeild('cmpId',$this->userInfo->cmpId,array('id'=>'cmpId'));
        $html .=HTML::hiddenFeild('cmpType',$this->userInfo->cmpType,array('id'=>'cmpType'));
        $html .=HTML::hiddenFeild('processPath',makeLocalUrl('account/acc_process.php','action=addUser'),array('id'=>'processPath'));
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Company Name : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('COMAPNY_NAME',$this->cmpData['COMAPNY_NAME'],array('style'=>'width:300px;','id'=>'COMAPNY_NAME')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Address :  ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('ADRESS',$this->cmpData['ADRESS'],array('style'=>'width:300px;','id'=>'ADRESS')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Postal Code :   ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('POSATL_CODE',$this->cmpData['POSATL_CODE'],array('style'=>'width:300px;','id'=>'POSATL_CODE')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('City : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('CITY ',$this->cmpData['CITY'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Country : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::selectFeild('COUNTRY','USER_TYPE',array(""=>"")+$countryName,$this->cmpData['COUNTRY'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Email : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('EMAIL  ',$this->cmpData['EMAIL'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Phone : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('PHONE  ',$this->cmpData['PHONE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td></td>';
            $html .='<td>'.HTML::submitButtonFeild('save','Save',array('onclick'=>'addUser();','style'=>'float: right;')).'</td>';
        $html .='</tr>';
        $html .= HTML::openCloseTable(false,false);
        return contentBorder($html,'Edit Company');
    }

    function getCompanyPrivileges(){
        $html = '';
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        $html .=HTML::hiddenFeild('cmpId',$this->userInfo->cmpId,array('id'=>'cmpId'));
        $html .=HTML::hiddenFeild('cmpType',$this->userInfo->cmpType,array('id'=>'cmpType'));
        $html .=HTML::hiddenFeild('processPath',makeLocalUrl('account/acc_process.php','action=addUser'),array('id'=>'processPath'));
        foreach( $this->sysPrivileges as $privData){
            $html .='<tr>';
                $html .='<td style="color:blue;">'.$privData['DETAILS'].'</td>';
                $html .='<td style="text-align:right;">'.HTML::lblFeild($privData['PRIVE_NAME'],array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::checkboxFeild('PRIVE_ID['.$privData['PRIVE_ID'].']','').'</td>';
            $html .='</tr>';
        }
        $html .= HTML::openCloseTable(false,false);
        return contentBorder($html,'Company Privileges');

    }
}






?>