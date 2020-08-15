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
                            'STOCK_NO','PRICE','DIS','SPECIAL_PRICE','SELL_PRICE','ACTION');

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
        $data = $this->getHeaderLevelData();
        $dataTable->setHeaderLevelData($data);
        foreach( $this->summaryFlds as $flds){
            if( $flds == 'ACTION'){
                $dataTable->addColumn($flds,'Actions');
            }else{
                $fldsDef = $this->fldDefinition[$flds];
                $dataTable->addColumn($flds,$fldsDef->lbl);
            }
        }

        $html = $dataTable->htmlTable();
        return htmlTableBox($html,'Categories','true');

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
        return $html;
    }*/

    function getRegulerSearchHtml(){
        $html = '';
        $html .= HTML::formStart('','POST','ADD_USER');
        $html .= HTML::lblFeild('First Name : ').HTML::textFeild('firstName','',$attr=array());
        $html .= HTML::lblFeild('Last Name : ').HTML::textFeild('lastName','',$attr=array());
        $html .= HTML::lblFeild('User Name : ').HTML::textFeild('userName','',$attr=array());
        $html .= HTML::lblFeild('Title : ').HTML::textFeild('title','',$attr=array());
        $btn = HTML::submitButtonFeild('reguler_search','Submit',$attr=array());
        $html .= HTML::formEnd();
        $popUp = sideModalPopupBox('Category','REGULER_SEARCH',$html,$btn);
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
            $html .= HTML::submitButtonFeild('order_create','Create Your Order',array('height'=>'50px','width'=>'150px'));
        }else{
            $html .= '<b>No Item Selected.</b>';
        }
        $html .= HTML::formEnd();
        return $html;
    }

}



?>