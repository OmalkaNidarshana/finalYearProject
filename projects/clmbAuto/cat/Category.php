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
    var $tblColumns = array();
    var $fldDefinition = array();

    var $summaryFlds = array('BRAND','MODEL','VEHICAL_CODE','ENGINE','CC','BRISK','BRISK_CODE','DENSO','IRIDIUM',
                            'STOCK_NO','PRICE','DIS','SPECIAL_PRICE','SELL_PRICE');

    var $searchFlds = array('BRAND','MODEL','VEHICAL_CODE','ENGINE','CC','BRISK','BRISK_CODE','DENSO');

    function Category($link){
        $this->link = $link;
        $this->dataTable = new SortableTable($this->link);
        $this->formatter = new CategoryTableFomatter($this->link);
        $this->structure = getTableSchemaInformation($this->link,$this->table);
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
        foreach( $this->summaryFlds as $flds){
            $fldsDef = $this->fldDefinition[$flds];
            $dataTable->addColumn($flds,$fldsDef->lbl);
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

}



?>