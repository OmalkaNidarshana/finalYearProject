<?php

class Outstanding{
    var $link;
    var $userInfo;
    var $table = 'outstanding_header';
    var $lineTable = 'outstanding_line';
    var $primaryKey = 'RECORD_ID';
    var $categoryIds;
    var $errMsg;
    var $categoryIdsArr = array();
    var $fltr;
    var $ordBy;
    var $pageNum;
    var $colList = '*';
    var $formatter;
    var $dataTable;
    var $structure;
    var $tblColumns = array();
    var $fldDefinition = array();
    var $details;
    var $customerList;
    var $customerData;
    var $invNum;
    var $outstandingHistoryData = array();
    var $outstandingData = array();

    var $summaryFlds = array('INV_NUM','ORDER_NUM','CUSTOMER_ID','TOTAL_AMMOUNT','PAID_AMOUNT','DUE_AMOUNT','OUT_STANDING_DATE','STATUS',
                        'CLOSED_DATE','REMAIN_DAYS','ACTION');

    function Outstanding($link,$userInfo,$invNum=''){
        $this->link = $link;
        $this->invNum = $invNum;
        $this->userInfo = $userInfo;
        $this->dataTable = new SortableTable($this->link);
        $this->formatter = new OutstandingTableFormatter($this->link,$this->userInfo);
        $this->structure = getTableSchemaInformation($this->link,$this->table);
        $this->outstandingData = getOutstandingDataByInvNum($this->link,$this->invNum);
        $this->outstandingHistoryData = getOutstandingHistoryDataByInvNum($this->link,$this->invNum);
        
        $this->initiate();

    }

    function setCategoryIds($categoryIds){
        $this->categoryIds = $categoryIds;
    }

    function setErrMsg($errMsg){
        $this->errMsg = $errMsg;
    }

    function setFltrs($fltr){
        $this->fltr = $fltr;
    }

    function initiate(){
        $this->OrderTableDefinitions();

    }

    function OrderTableDefinitions(){
        foreach ($this->structure as $data){
          $fld = $data['COLUMN_NAME'];
          $type = $data['DATA_TYPE'];
          $lngth = $data['CHARACTER_MAXIMUM_LENGTH'];
          $lbl = buildFldsLablel($fld);
          $this->fldDefinition[$fld] = new fldsAtribute($lbl,$type,$lngth);
          $this->tblColumns[] = $fld ;
        }
    }

    function outstandingSummaryTable(){
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
        return htmlTableBox($html,'Outstanding Orders');

    }

    function getOutstandingHistory(){
       
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" >';
            $html .= '<tbody><tr>';
                $html .= '<th>Paid Ammount</th><th>Paid Date</th><th>Accepted By</th>';
            $html .= '</tr>';
            foreach( $this->outstandingHistoryData as $historyData){
                $html .= '<tr>';
                    $html .= '<td>'.formatCurrency($historyData['PAID_AMOUNT']).'</td>';
                    $html .= '<td>'.formatDate($historyData['PAID_DATE']).'</td>';
                    $html .= '<td>'.getFullNameByUserIntId($this->link,$historyData['CREATED_BY']).'</td>';
                    
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '</div>';
       return contentBox($html,'');
    }

    function getActionPanel(){
        $btn = '';
        $btn .= HTML::submitButtonFeild('add_ammount','Add Ammount',array('onclick'=>'cancleOrder();','style'=>'width: 99px;',
                                            'data-toggle'=>'modal','data-target'=>'#ADD_AMOUNT_POPUP'));
         return contentBox($btn);
    }

    function getAddAmountForm(){
        
        $html = '';
        $html .= HTML::formStart('','POST','ADD_AMOUNT');
        $html .= '<table>';
        
        $html .= HTML::hiddenFeild('INV_NUM',$this->outstandingData['INV_NUM'],array('id'=>'INV_NUM'));
        $html .= HTML::hiddenFeild('ORDER_NUM',$this->outstandingData['ORDER_NUM'],array('id'=>'ORDER_NUM'));
        $html .= HTML::hiddenFeild('TOTAL_AMMOUNT',$this->outstandingData['TOTAL_AMMOUNT'],array('id'=>'TOTAL_AMMOUNT'));
        $html .= HTML::hiddenFeild('PAID_AMOUNT',$this->outstandingData['PAID_AMOUNT'],array('id'=>'PAID_AMOUNT'));
        $html .= HTML::hiddenFeild('CUSTOMER_ID',$this->outstandingData['DUE_AMOUNT'],array('id'=>'DUE_AMOUNT'));
        $html .= HTML::hiddenFeild('outstandingProcessPath',makeLocalUrl('outstanding/outstanding_process.php',''),array('id'=>'outstandingProcessPath'));
        $html .= '<tbody>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('Invoice# : ').'</td><td>&nbsp;&nbsp;'.$this->outstandingData['INV_NUM'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('Order Number : ').'</td><td>&nbsp;&nbsp;'.$this->outstandingData['ORDER_NUM'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="right">'.HTML::lblFeild('Total Amount : ').'</td><td>&nbsp;&nbsp;'.formatCurrency($this->outstandingData['TOTAL_AMMOUNT']).'</td>';
            $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<tr>';
        $html .= '<td align="right">'.HTML::lblFeild('Paid Amount : ').'</td><td>&nbsp;&nbsp;'.formatCurrency($this->outstandingData['PAID_AMOUNT']).'</td>';
            $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('Due Ammount : ').'</td><td>&nbsp;&nbsp;'.formatCurrency($this->outstandingData['DUE_AMOUNT']).'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('New Amount &nbsp;&nbsp;: ').'</td><td>&nbsp;&nbsp;'.HTML::textFeild('NEW_AMOUNT','',$attr=array()).'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('Date of payment&nbsp;&nbsp;: ').'</td><td>&nbsp;&nbsp;'.HTML::dateFeild('PAYMENT_DATE','').'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= HTML::formEnd();
        $btn = HTML::submitButtonFeild('save','Save',array('onclick'=>'addAmount();'));
        $popUp = modalPopupBox('Add Amount','ADD_AMOUNT_POPUP',$html,$btn);
        return $popUp;
    }
}
?>