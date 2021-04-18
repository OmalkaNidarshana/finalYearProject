<?php

class Report{
    var $link;
    var $userInfo;
    var $table = 'orders';
    var $lineTable = 'order_lines';
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
    var $summaryFlds = array('ORDER_NUM','LINE_ITEM','ORDER_DATE','STATUS','CUSTOMER_ID','EXPECTED_DELIVERY_DATE');

    var $searchFlds = array('ORDER_NUM','LINE_ITEM','ORDER_DATE','STATUS','ACTUAL_DELIVERY_DATE','STATUS');

    function Report($link,$userInfo,$id=''){
        $this->link = $link;
        $this->id = $id;
        $this->userInfo = $userInfo;
        $this->dataTable = new SortableTable($this->link);
        $this->formatter = new OrderTableFormatter($this->link,$this->userInfo);
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

    function getReportSummaryTable(){
        $ordIds = getSubmittedOrderIds($this->link);
        $itemData = getSubmittedOrdersItemByOrdIds($this->link,$ordIds);
       /*print_rr($itemData);
        exit;*/
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" id="sortableTable">';
            $html .= '<tr>';
                $html .= '<th>Category</th><th>Quantity</th><th>Unit Price (Rs.)</th><th>Total (Rs.)</th><th>Discount (Rs.)</th><th>Discount Rate(%)</th><th>Order Date</th>';
            $html .= '</tr>';
            $html .= '<tbody>';
            foreach( $itemData as $data){
                $html .= '<tr>';
                    
                    $html .= '<td>'.$data['CATEGORY'].'</td>';
                    $html .= '<td>'.$data['QUANTITY'].'</td>';
                    $html .= '<td>'.$this->formatter->formatters('SELL_PRICE',$data['SELL_PRICE'],'').'</td>';
                    $html .= '<td>'.$this->formatter->formatters('TOTAL',$data['TOTAL'],'').'</td>';
                    $html .= '<td>'.$this->formatter->formatters('TOTAL',$data['DISCOUNT'],'').'</td>';
                    $html .= '<td>'.$this->formatter->formatters('',$data['DISCOUNT_RATE'],'').'</td>';
                    $html .= '<td>'.$data['ORDER_DATE'].'</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        $head =  'Order Report';
        return contentBorder($html,$head);
    }
    
    
    function getSalesReportSummaryTable(){
        $invData = getPaidInvoiceByOrdIds($this->link);
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" id="sortableTable">';
            $html .= '<tr>';
                $html .= '<th>Invoice Number</th><th>Order Number</th><th>Ammount (Rs.)</th><th>Net Ammount (Rs.)</th><th>Discount (%)</th><th>Invoice Date</th><th>Issue By</th>';
            $html .= '</tr>';
            $html .= '<tbody>';
            foreach( $invData as $data){
                $html .= '<tr>';
                    
                    $html .= '<td>'.$data['INV_NUM'].'</td>';
                    $html .= '<td>'.$data['ORDER_NUM'].'</td>';
                    $html .= '<td>'.$this->formatter->formatters('AMMOUNT',$data['AMMOUNT'],'').'</td>';
                    $html .= '<td>'.$this->formatter->formatters('NET_AMMOUNT',$data['NET_AMMOUNT'],'').'</td>';
                    $html .= '<td>'.$this->formatter->formatters('TOTAL',$data['ADDITIONAL_DISSCOUNT'],'').'</td>';
                    $html .= '<td>'.$data['INVOICE_DATE'].'</td>';
                    $html .= '<td>'.$this->formatter->formatters('',$data['ISSUED_BY'],'').'</td>';
                    
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        $head =  'Sales Report';
        return contentBorder($html,$head);
    }

    function getActionPanel(){
        $html = '';
        $html .= HTML::formStart('','POST','INV_ADD');
        $html .= HTML::submitButtonFeild('date_range[today]','Daily',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:forestgreen"));
        $html .= HTML::submitButtonFeild('date_range[thisMonth]','This Month',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:forestgreen"));
        $html .= HTML::submitButtonFeild('date_range[thisQater]','This Quater',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:forestgreen"));
        $html .= HTML::submitButtonFeild('date_range[thisYear]','This Year',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:forestgreen"));
        $html .= HTML::submitButtonFeild('date_range[lastYear]','Last Year',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:forestgreen"));
        $html .= HTML::submitButtonFeild('date_range[all]','All',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:forestgreen"));
        $html .= HTML::formEnd();
       
        return contentBox($html);
    }
    
}
?>