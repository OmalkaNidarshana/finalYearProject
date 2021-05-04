<?php

class ReOrder{
    var $link;
    var $userInfo;
    var $table = 're_orders';
    //var $lineTable = 'order_lines';
    var $primaryKey = 'ORDER_ID';
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
    var $summaryFlds = array('ORDER_NUM','PARENT_ORDER_NUM','LINE_ITEM','CUSTOMER_ID','ORDER_TYPE','ORDER_DATE');

    function ReOrder($link,$userInfo,$id=''){
        $this->link = $link;
        $this->id = $id;
        $this->userInfo = $userInfo;
        $this->dataTable = new SortableTable($this->link);
        $this->formatter = new ReOrderTableFormatter($this->link,$this->userInfo);
        $this->structure = getTableSchemaInformation($this->link,$this->table);
        $this->customerData = getCustomerListByDistId($this->link,$this->userInfo->cmpId);
        foreach( $this->customerData as $customerList){
            $this->customerList[$customerList['COMPANY_ID']] = $customerList['COMPANY_NAME'];
        }
        
        if(!empty($this->id))
            $this->details = getOrderDetailsByOrderId($this->link,$this->id);
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

    function getRejectedOrderSummaryTable(){
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
        return htmlTableBox($html,'Rejected Orders');

    }

}
?>