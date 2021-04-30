<?php

class Category{

    var $link;
    var $table = 'category';
    var $primaryKey = 'RECORD_ID';
    var $fltr;
    var $ordBy;
    var $pageNum;
    var $colList = '*';
    var $formatter;
    var $dataTable;
    var $structure;
    var $userInfo;
    var $tblColumns = array();
    var $fldDefinition = array();
    var $categoryIdsArr = array();

    var $summaryFlds = array('BRAND','MODEL','VEHICAL_CODE','ENGINE','CC','BRISK','BRISK_CODE','DENSO','IRIDIUM',
                            'STOCK_NO','STOCK','PRICE','DIS','SELL_PRICE','COMMISION','ACTION');

    var $searchFlds = array('BRAND','MODEL','VEHICAL_CODE','ENGINE','CC','BRISK','BRISK_CODE','DENSO');

    function Category($link,$userInfo){
        $this->link = $link;
        $this->userInfo = $userInfo;
        $this->dataTable = new SortableTable($this->link);
        $this->formatter = new CategoryTableFomatter($this->link,$this->userInfo);
        $this->structure = getTableSchemaInformation($this->link,$this->table);

        $cartData = getPendingCartDataByUserId($this->link,$this->userInfo->userName);
        if( !empty($cartData) ){
            $categoryIds = $cartData['CATEGORY_ID'];
            $this->categoryIdsArr = explode(',',$categoryIds);
            $this->formatter->setCategoryIds($this->categoryIdsArr);
        }

        $this->initiate();
    }

    function setFltrs($fltr){
        $this->fltr = $fltr;
    }
    
    function initiate(){
        $this->CategoryTableDefinitions();

    }

    function CategoryTableDefinitions(){
        foreach ($this->structure as $data){
          $fld = $data['COLUMN_NAME'];
          $type = $data['DATA_TYPE'];
          $lngth = $data['CHARACTER_MAXIMUM_LENGTH'];
          $lbl = buildFldsLablel($fld);
          $this->fldDefinition[$fld] = new fldsAtribute($lbl,$type,$lngth);
          $this->tblColumns[] = $fld ;
        }
    }

    
    function getCategorySummaryTable(){
        $dataTable = $this->dataTable;
        $dataTable->setTable($this->table);
        $dataTable->setFormatter($this->formatter);
        $dataTable->setPriKey($this->primaryKey);
        $dataTable->setColumList($this->colList);
        $dataTable->setFilters($this->fltr);
        $dataTable->loadPageData();
        //$data = $this->getHeaderLevelData();
        //$dataTable->setHeaderLevelData($data);
        foreach( $this->summaryFlds as $flds){
            if( $flds == 'ACTION'){
                $dataTable->addColumn($flds,'Actions');
            }else{
                $fldsDef = $this->fldDefinition[$flds];
                $dataTable->addColumn($flds,$fldsDef->lbl);
            }
        }

        $html = $dataTable->htmlTable();
        $header = 'Categories';
        $header .= '<span  data-toggle="modal" data-target="#ADD_CATEGORY">'.getRawActionsIcon('add','Add Item').'</span>';

        return htmlTableBox($html,$header);

    }

    /*function getRegulerSearchHtml(){
        $html = '';
        $html .= '<div class="col-md-6 regulersearch" id="REGULER_SEARCH" >';
        $html .= '<div class="box box-primary regulerSearchStyle">';
            $html .= '<div class="box-header with-border">';
                $html .= '<h3 class="box-title">Quick Example</h3>';
            $html .= '</div>';
            $html .= '<form role="form" method="post">';
                $html .= '<div class="box-body">';
                    foreach($this->searchFlds as $fld){
                        $fldsDef = $this->fldDefinition[$fld];
                        $html .= '<div class="form-group col-md-4">';
                            $html .= '<label for="exampleInputEmail1">'.$fldsDef->lbl.'</label>';
                            $html .= '<input type="text" class="form-control" id='.$fld.' name="reguler['.$fld.']">';
                        $html .= '</div>';
                    }
                $html .= '</div>';
                $html .= '<div class="box-footer" align="right" style="background-color: #f2f2f2;">';
                     $html .= '<button type="submit" class="btn btn-primary" name="reguler_search">Submit</button>';
                $html .= '</div>';
            $html .= '</form>';
        $html .= '</div>';
        $popUp = sideModalPopupBox('Category','REGULER_SEARCH',$html,$btn);
        return $popUp;
    }*/

    function getRegulerSearchHtml(){
        $html = '';
        $html .= HTML::formStart('','POST','ADD_USER');
        $html .= '<table>';
            foreach($this->searchFlds as $fld){
                $html .= '<tr>';
                $fldsDef = $this->fldDefinition[$fld];
                $html .= '<td align="right">'.HTML::lblFeild($fldsDef->lbl.' : ').'</td><td>&nbsp;&nbsp;'.HTML::textFeild('firstName','',$attr=array()).'</td>';
                $html .= '</tr>';
            }
            $html .= '<tr>';
                $html .= '<td></td><td align="right">'.HTML::submitButtonFeild('reguler_search','Submit',$attr=array()).'</td>';
            $html .= '</tr>';
        $html .= '</table>';
       
        $html .= HTML::formEnd();
        $popUp = sideModalPopupBox('Category','REGULER_SEARCH',$html,'');
        return $popUp;
    }

    function getHeaderLevelData(){
        $html ='';
        $count = count($this->categoryIdsArr);
        $action = makeLocalUrl('orders/order_creation.php','sec=ORD_CREATION');
        $html .= HTML::formStart($action,'POST','ADD_CART');
        foreach($this->categoryIdsArr as $id){
            $html .= HTML::hiddenFeild('catIds[]',$id);
        }
        $html .= HTML::hiddenFeild('processPath',makeLocalUrl('cat/category_process.php','action=addCart'),array('id'=>'processPath'));
        if( !empty($count) ){
            if($count == 1){
                $items = 'item';
            }else{
                $items = 'items';
            }
            $html .='<b>'.$count.'&nbsp'.$items.' in your Order</b>&nbsp;';
            $html .= '<span class="cartIcon">'.getRawActionsIcon('cart','',false).'<b> &nbsp;|&nbsp;&nbsp;</span></b> ';
            $html .= HTML::submitButtonFeild('order_create','Create Your Order',array('style'=>'width:130px; height:30px;'));
        }else{
            $html .= '<b>No Item Selected.</b>';
        }
        $html .= HTML::formEnd();
        return $html;
    }

    function getCategoryAddForm(){
        $html = '';
        $html .= HTML::formStart('','POST','ADD_CATEGORY_FORM');
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
             
        $html .=HTML::hiddenFeild('addCategory',makeLocalUrl('cat/category_process.php','action=addCategory'),array('id'=>'addCategory'));

        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Brand : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[BRAND]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Model :  ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[MODEL]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Vehical Code :   ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[VEHICAL_CODE]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Engine : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[ENGINE]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('CC : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[CC]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Brisk : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[BRISK]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Brisk Code : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[BRISK_CODE]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Denso : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[DENSO]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('IRIDIUM : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[IRIDIUM]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Stock No : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[STOCK_NO]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Stock : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[STOCK]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Price : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[PRICE]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Dis : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[DIS]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Special Price : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[SPECIAL_PRICE]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Sell Price : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[SELL_PRICE]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Commision : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[COMMISION]','',array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td></td>';
            $html .='<td></td>';
        $html .='</tr>';
        
        $btn = HTML::buttonFeild('save','Save',array('onclick'=>'addCategory();','style'=>'float: right;'));
       
        $html .= HTML::openCloseTable(false,false);
        $html .= HTML::formEnd();
        $popUp = modalPopupBox('Add Item','ADD_CATEGORY',$html,$btn);
        return $popUp;
    }

    function getCategoryEditForm($catId){
        $categoryData = getCategoryDataBycategoryId($this->link,$catId);
        //print_rr($categoryData);

        $html = '';
        $html .= HTML::formStart('','POST','EDIT_CATEGORY_FORM');
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        $html .=HTML::hiddenFeild('categoryId',$categoryData['RECORD_ID'],array('id'=>'categoryId'));
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Brand : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[BRAND]',$categoryData['BRAND'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Model :  ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[MODEL]',$categoryData['MODEL'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Vehical Code :   ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[VEHICAL_CODE]',$categoryData['VEHICAL_CODE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Engine : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[ENGINE] ',$categoryData['ENGINE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('CC : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[CC]',$categoryData['CC'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Brisk : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[BRISK]',$categoryData['BRISK'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Brisk Code : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[BRISK_CODE]',$categoryData['BRISK_CODE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Denso : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[DENSO]',$categoryData['DENSO'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('IRIDIUM : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[IRIDIUM]',$categoryData['IRIDIUM'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Stock No : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[STOCK_NO]',$categoryData['STOCK_NO'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Stock : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[STOCK]',$categoryData['STOCK'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Price : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[PRICE]',$categoryData['PRICE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Dis : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[DIS]',$categoryData['DIS'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Special Price : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[SPECIAL_PRICE]',$categoryData['SPECIAL_PRICE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Sell Price : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[SELL_PRICE]',$categoryData['SELL_PRICE'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td>'.HTML::lblFeild('Commision : ',array("style"=>"padding:5px;") ).'</td>';
            $html .='<td>'.HTML::textFeild('catData[COMMISION]',$categoryData['COMMISION'],array('style'=>'width:300px;')).'</td>';
        $html .='</tr>';
        $html .='<tr>';
            $html .='<td></td>';
            $html .='<td></td>';
        $html .='</tr>';
        $html .= HTML::openCloseTable(false,false);
        $html .= HTML::formEnd();
        
        return $html;
    }

    function loadEdiCatPopup(){
        $html = '<div id="editCatPopUp"></div>';
        $html .=HTML::hiddenFeild('editCategoryProcess',makeLocalUrl('cat/category_process.php',''),array('id'=>'editCategoryProcess') );
        $btn = HTML::buttonFeild('edit_cat','Save',$attr=array('onclick'=>'saveEditLine();'));
        $popUp = modalPopupBox('Edit Line','EDIT_CATEGORY_POPUP',$html,$btn);
        return $popUp;

    }
}



?>