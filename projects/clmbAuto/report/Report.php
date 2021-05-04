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
    var $fltrs = array();
    var $dateFltrs;
    var $ordBy;
    var $pageNum;
    var $colList = '*';
    var $formatter;
    var $dataTable;
    var $structure;
    var $dateLbl = 'All Data';
    var $dateMode;
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

    function setFltrs($fltrs){
        $this->fltrs = $fltrs;
    }

    function setDateFltrs($dateFltrs){
        $this->dateFltrs = $dateFltrs;
    }

    function setDateMode($dateMode){
        $this->dateMode = $dateMode;
    }

    function setDateLbl($dateLbl){
        $this->dateLbl = $dateLbl;
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
        $itemData = array();
        $ordIds = getSubmittedOrderIds($this->link,$this->dateFltrs);
        if( !empty($ordIds) )
            $itemData = getSubmittedOrdersItemByOrdIds($this->link,$ordIds);
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" id="orderReportTable">';
            $html .= '<thead>';
            $html .= '<tr>';
                $html .= '<th>Brand</th><th>Model</th><th>Brisk</th><th>Category</th><th>Quantity</th><th>Unit Price (Rs.)</th>
                            <th>Total (Rs.)</th><th>Discount (Rs.)</th><th>Discount Rate(%)</th><th>Order Date</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            foreach( $itemData as $data){
                $html .= '<tr>';
                    $html .= '<td>'.$data['BRAND'].'</td>';
                    $html .= '<td>'.$data['MODEL'].'</td>';
                    $html .= '<td>'.$data['BRISK'].'</td>';
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
            $fileTitle = 'Colombo Auto Supllier Order Report on :'.$this->dateLbl;
                     $html .= '<script>$(document).ready( function () {
                        $(\'#orderReportTable\').DataTable({
                            dom: \'Bfrtip\',
                            buttons: [
                                {
                                    extend:    \'csvHtml5\',
                                    text:      \'<i class="fa fa-file-text-o"></i>\',
                                    titleAttr: \'Download CSV\',
                                    title:      \''.$fileTitle.'\'
                                },
                                {
                                    extend:    \'pdfHtml5\',
                                    text:      \'<i class="fa fa-file-pdf-o"></i>\',
                                    titleAttr: \'Dowanload PDF\',
                                    title:      \''.$fileTitle.'\'
                                }
                            ]
                        });
                        } );
                    </script>';
        $html .= '<div>';
        if(!empty($itemData) ){
            $cnt = count($itemData);
        }else{
            $cnt = 0;
        }
        $head =  'Order Report &nbsp;|&nbsp; <span style="color:blue">Window :&nbsp;'.$this->dateLbl.'</span><span style="color:red">&nbsp;&nbsp;[&nbsp;'.$cnt.'&nbsp; Records Founds&nbsp]</span>';
        return contentBorder($html,$head);
    }
    
    
    function getSalesReportSummaryTable(){
        $invData = getPaidInvoiceByOrdIds($this->link,$this->dateFltrs);
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" id="salesRepotTable">';
            $html .= '<thead>';
            $html .= '<tr>';
                $html .= '<th>Invoice Number</th><th>Order Number</th><th>Ammount (Rs.)</th><th>Net Ammount (Rs.)</th><th>Discount (%)</th><th>Invoice Date</th><th>Issue By</th>';
            $html .= '</tr>';
            $html .= '</thead>';
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
            $fileTitle = 'Colombo Auto Supllier Sales Order Report on :'.$this->dateLbl;
                     $html .= '<script>$(document).ready( function () {
                        $(\'#salesRepotTable\').DataTable({
                            dom: \'Bfrtip\',
                            buttons: [
                                {
                                    extend:    \'csvHtml5\',
                                    text:      \'<i class="fa fa-file-text-o"></i>\',
                                    titleAttr: \'Download CSV\',
                                    title:      \''.$fileTitle.'\'
                                },
                                {
                                    extend:    \'pdfHtml5\',
                                    text:      \'<i class="fa fa-file-pdf-o"></i>\',
                                    titleAttr: \'Dowanload PDF\',
                                    title:      \''.$fileTitle.'\'
                                }
                            ]
                        });
                        } );
                    </script>';
           
        $html .= '<div>';
        if(!empty($invData) ){
            $cnt = count($invData);
        }else{
            $cnt = 0;
        }
        $head =  'Sales Report &nbsp;|&nbsp; <span style="color:blue">Window :&nbsp;'.$this->dateLbl.'</span><span style="color:red">&nbsp;&nbsp;[&nbsp;'.$cnt.'&nbsp; Records Founds&nbsp]</span>';
        return contentBorder($html,$head);
    }

    function getRejectOrderReportSummaryTable(){
        $rejectedOrderData = getRejectedOrderData($this->link,$this->dateFltrs);
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" id="RejectOrderReportTable">';
            $html .= '<thead>';
            $html .= '<tr>';
                $html .= '<th>Order Num</th><th>Line Num</th><th>Brand</th><th>Model</th><th>Brisk</th><th>Category</th><th>Rejected Quantity</th><th>Reject Resaon</th>
                            <th>Reject Date</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            foreach( $rejectedOrderData as $data){
                $html .= '<tr>';
                    $html .= '<td>'.$data['ORDER_NUM'].'</td>';
                    $html .= '<td>'.$data['LINE_NUM'].'</td>';
                    $html .= '<td>'.$data['BRAND'].'</td>';
                    $html .= '<td>'.$data['MODEL'].'</td>';
                    $html .= '<td>'.$data['BRISK'].'</td>';
                    $html .= '<td>'.$data['CATEGORY'].'</td>';
                    $html .= '<td>'.$data['REJECTED_QTY'].'</td>';
                    $html .= '<td>'.$data['REJECTED_REASON'].'</td>';
                    $html .= '<td>'.$data['REJECTED_DATE'].'</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
            $fileTitle = 'Colombo Auto Supllier Rejected Order Report on :'.$this->dateLbl;
                     $html .= '<script>$(document).ready( function () {
                        $(\'#RejectOrderReportTable\').DataTable({
                            dom: \'Bfrtip\',
                            buttons: [
                                {
                                    extend:    \'csvHtml5\',
                                    text:      \'<i class="fa fa-file-text-o"></i>\',
                                    titleAttr: \'Download CSV\',
                                    title:      \''.$fileTitle.'\'
                                },
                                {
                                    extend:    \'pdfHtml5\',
                                    text:      \'<i class="fa fa-file-pdf-o"></i>\',
                                    titleAttr: \'Dowanload PDF\',
                                    title:      \''.$fileTitle.'\'
                                }
                            ]
                        });
                        } );
                    </script>';
        $html .= '<div>';
        if(!empty($itemData) ){
            $cnt = count($itemData);
        }else{
            $cnt = 0;
        }
        $head =  'Rejected Order Report &nbsp;|&nbsp; <span style="color:blue">Window :&nbsp;'.$this->dateLbl.'</span><span style="color:red">&nbsp;&nbsp;[&nbsp;'.$cnt.'&nbsp; Records Founds&nbsp]</span>';
        return contentBorder($html,$head);
    }

    function getReOrderSummaryTable(){
        $itemData = array();
        $ordIds = getReOrderIds($this->link,$this->dateFltrs);
        
        if( !empty($ordIds) )
            $itemData = getSubmittedOrdersItemByOrdIds($this->link,$ordIds);
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" id="orderReportTable">';
            $html .= '<thead>';
            $html .= '<tr>';
                $html .= '<th>Brand</th><th>Model</th><th>Brisk</th><th>Category</th><th>Quantity</th><th>Unit Price (Rs.)</th>
                            <th>Total (Rs.)</th><th>Discount (Rs.)</th><th>Discount Rate(%)</th><th>Order Date</th>';
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';
            foreach( $itemData as $data){
                $html .= '<tr>';
                    $html .= '<td>'.$data['BRAND'].'</td>';
                    $html .= '<td>'.$data['MODEL'].'</td>';
                    $html .= '<td>'.$data['BRISK'].'</td>';
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
            $fileTitle = 'Colombo Auto Supllier Order Report on :'.$this->dateLbl;
                     $html .= '<script>$(document).ready( function () {
                        $(\'#orderReportTable\').DataTable({
                            dom: \'Bfrtip\',
                            buttons: [
                                {
                                    extend:    \'csvHtml5\',
                                    text:      \'<i class="fa fa-file-text-o"></i>\',
                                    titleAttr: \'Download CSV\',
                                    title:      \''.$fileTitle.'\'
                                },
                                {
                                    extend:    \'pdfHtml5\',
                                    text:      \'<i class="fa fa-file-pdf-o"></i>\',
                                    titleAttr: \'Dowanload PDF\',
                                    title:      \''.$fileTitle.'\'
                                }
                            ]
                        });
                        } );
                    </script>';
        $html .= '<div>';
        if(!empty($itemData) ){
            $cnt = count($itemData);
        }else{
            $cnt = 0;
        }
        $head =  'Re Order Report &nbsp;|&nbsp; <span style="color:blue">Window :&nbsp;'.$this->dateLbl.'</span><span style="color:red">&nbsp;&nbsp;[&nbsp;'.$cnt.'&nbsp; Records Founds&nbsp]</span>';
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
        $html .= HTML::formEnd();
       
        return contentBox($html);
    }
    
}
?>