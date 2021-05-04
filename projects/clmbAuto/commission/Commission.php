<?php

class Commission{
    var $link;
    var $userInfo;
    var $table = 'commisions';
    //var $lineTable = 'outstanding_line';
    var $primaryKey = 'COMMISION_ID';
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
    var $orderNums;
    var $totalCommission;
    var $repId;
    var $outstandingHistoryData = array();
    var $outstandingData = array();

    var $summaryFlds = array('ORDER_NUM','TOTAL','COMMISION_RATE','COMMISION','CREATED_DATE');

    function Commission($link,$userInfo,$repId=''){
        $this->link = $link;
        $this->repId = $repId;
        $this->userInfo = $userInfo;
        $this->dataTable = new SortableTable($this->link);
        $this->formatter = new OutstandingTableFormatter($this->link,$this->userInfo);
        $this->structure = getTableSchemaInformation($this->link,$this->table);
        if(!empty($this->repId)){
            $this->orderNums = getDistinctOrderFromCommissionByRepId($this->link,$this->repId);
            $this->totalCommission = getTotalCommissionByRepId($this->link,$this->repId);
        }
       
        
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

    function getCommissionSummaryTable(){
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
        return htmlTableBox($html,'Commissions');

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

    function getSalesRepList(){
        $salesRepData = getSalesRepList($this->link);
        $html ='';
        //$html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-striped projects">';
            foreach( $salesRepData as $data){
                $name = $data['FIRST_NAME'].' '.$data['LAST_NAME'];
                $html .= '<tr>';
                    $html .= '<td width="80%">'.getWidgetsBox('blueIcon','fa fa-user','Name',$name).'</td>';
                    $html .= '<td width="10%" style="vertical-align: middle;"><a href="'.makeLocalUrl('orders/order_script.php','sec=ORDER&repId='.$data['USER_INTID']).'"'.getIconButton('fa fa-folder','View Orders','Style="background-color: darkslateblue;color:white;border-color: darkslateblue;"','').'</a></td>';
                    $html .= '<td width="10%" style="vertical-align: middle;"><a href="'.makeLocalUrl('commission/commission_detail.php','sec=COMMIS&repId='.$data['USER_INTID']).'"'.getIconButton('fa fa-percent','Commission','Style="background-color: olive;color:white;border-color: olive;"','').'</td>';
                    
                $html .= '</tr>';
            }
            $html .= '</table>';
        //$html .= '</div>';
        return contentBox($html);
    }

    function getTopDataPanel(){

        $html = '';
        $html .= getWidgetsBox('blueIcon','fa fa-folder','Total Order',count($this->orderNums));
        $html .=getWidgetsBox('greenIcon','fa fa-percent','Total Commission',formatCurrency($this->totalCommission));
    
        return $html;

    }
}
?>