<?php

class Account{

    var $link;
    var $userInfo;
    var $cmpId;
    var $cmpType;
    var $roles = array(
                'SYS_OWNER'=> array('SYS_ADMIN'=>'SYS_ADMIN','SYS_MGR'=>'SYS_MGR'),
                'DISTRIBUTOR'=> array('ADMINISTRATOR'=>'ADMINISTRATOR','ACCOUNTANCE'=>'ACCOUNTANCE','SALES_REP'=>'SALES_REP'),
                'END_USER'=> array('MANAGER'=>'MANAGER','USER'=>'USER'),
                );
    function Account($link,$userInfo,$cmpId,$cmpType){
        $this->link = $link;
        $this->userInfo = $userInfo;
        $this->cmpId = $cmpId;
        $this->cmpType = $cmpType;
        $this->cmpData = getCompanyDataByCmpId($this->link,$this->cmpId);
    }

    function getUperAdminCompanyInfo(){
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
        if( $this->cmpType =='dist' || $this->userInfo->cmpType == 'DISTRIBUTOR'){
            $head .= ' &nbsp&nbsp|&nbsp&nbsp<span>'.getRawActionsIcon('edit','Edit Company').'</span>&nbsp';
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
            $html .='<tr><td>'.$i.'.&nbsp&nbsp<a href="'.makeLocalUrl('account/profile_script.php','cmpId='.$data['COMPANY_ID'].'&cmpType=dist').'">'.$data['COMAPNY_NAME'].'</a></td><tr>';
            $i++;
        }
        $html .= '</table>';
        $html .='</div>';
        $head =  'Distributor Company &nbsp&nbsp|&nbsp&nbsp';
        $head .= '<span>'.getRawActionsIcon('addCmp','Add Disrtibutor').'</span>&nbsp';
        return contentBorder($html,$head);
    }

    function getUserInformation(){
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
                    $html .= '<td>'.$data['USER_NAME'].'</td>';
                    $html .= '<td>'.$data['TITLE'].'</td>';
                    $html .= '<td>'.$data['USER_TYPE'].'</td>';
                    
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        $head =  'Users';
        if($this->cmpType == 'dist'){
           if($this->userInfo->cmpType == 'DISTRIBUTOR'){
                $head .= ' &nbsp&nbsp|&nbsp&nbsp<span data-toggle="modal" data-target="#ADD_USER">'.getRawActionsIcon('addUser','Add User').'</span>&nbsp';
           }
        }else{
            $head .= ' &nbsp&nbsp|&nbsp&nbsp<span data-toggle="modal" data-target="#ADD_USER">'.getRawActionsIcon('addUser','Add User').'</span>&nbsp';
        }
        return contentBorder($html,$head);
    }

    function getAddUserForm(){
        global $countryArray;
        foreach( $countryArray as $country){
            $countryName[$country['name']] = $country['name'];
            $countryCode[$country['code']] = '(+'.$country['code'].')';
        }
        $html = '';
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        $html .=HTML::formStart('','POST','ADD_USER_FORM');
        $html .=HTML::hiddenFeild('cmpId',$this->userInfo->cmpId,array('id'=>'cmpId'));
        $html .=HTML::hiddenFeild('cmpType',$this->userInfo->cmpType,array('id'=>'cmpType'));
        $html .=HTML::hiddenFeild('processPath',makeLocalUrl('account/acc_process.php','action=addUser'),array('id'=>'processPath'));
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('First Name : ',array("style"=>"padding:5px;") ).HTML::textFeild('FIRST_NAME','',array('style'=>'width:300px;','id'=>'FIRST_NAME'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('Last Name : ',array("style"=>"padding:5px;") ).HTML::textFeild('LST_NAME','',array('style'=>'width:300px;','id'=>'LST_NAME'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('User Name : ',array("style"=>"padding:5px;") ).HTML::textFeild('USER_NAME','',array('style'=>'width:300px;','id'=>'USER_NAME','placeholder'=>'This should be a valid email'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('Title : ',array("style"=>"padding:5px;") ).HTML::textFeild('TITLE','',array('style'=>'width:300px;'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('User Type : ',array("style"=>"padding:5px;") ).HTML::selectFeild('USER_TYPE','USER_TYPE',array(""=>"")+$this->roles[$this->userInfo->cmpType],'',array('style'=>'width:300px;'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('Addres 1 : ',array("style"=>"padding:5px;") ).HTML::textFeild('ADD_1','',array('style'=>'width:300px;'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('Addres 2 : ',array("style"=>"padding:5px;") ).HTML::textFeild('ADD_2','',array('style'=>'width:300px;'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('Country : ',array("style"=>"padding:5px;") ).HTML::selectFeild('CNTRY','CNTRY',array(""=>"")+$countryName,'',array('style'=>'width:300px;'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $html .= HTML::openCloseTr(true);
            $html .= HTML::openCloseTd(true,array("align"=>"right","width"=>"400px"));
                $html .= HTML::lblFeild('Phone : ',array("style"=>"padding:5px;") ).HTML::selectFeild('PHONE_CODE','PHONE_CODE',array(""=>"")+$countryCode,'',array('style'=>'width:70px;'));
                $html .= '&nbsp'.HTML::phoneFeild('PHONE_NUM','',array('style'=>'width:230px;','pattern'=>'[0-9]{2}-[0-9]{4}-[0-9]{3}','placeholder'=>'12-3456-789'));
            $html .= HTML::openCloseTd(false);
        $html .= HTML::openCloseTr(false);
        $btn = HTML::submitButtonFeild('save','Save',array('onclick'=>'addUser();'));
        $html .= HTML::formEnd();
        $html .= HTML::openCloseTable(false,false);
        $popUp = modalPopupBox('Add User','ADD_USER',$html,$btn);
        return $popUp;
    }
}






?>