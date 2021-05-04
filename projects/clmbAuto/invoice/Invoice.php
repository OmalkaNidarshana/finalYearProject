<?php

class Invoice{
    var $link;
    var $userInfo;
    var $table = 'invoice';
    var $lineTable = '';
    var $primaryKey = 'INV_ID';
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
    var $orderLineData;
    var $customerData;
    var $total;
    var $summaryFlds = array('INV_NUM','ORDER_NUM','CUSTOMER_ID','ADDITIONAL_DISSCOUNT','AMMOUNT','NET_AMMOUNT','PAYMENT_METHOD','STATUS','INVOICE_DATE','INVOICE_CLOSE_DATE','ISSUED_BY','DESCRIPTION','ACTION');
    var $salesSummaryFlds = array('INV_NUM','ORDER_NUM','CUSTOMER_ID','ADDITIONAL_DISSCOUNT','AMMOUNT','NET_AMMOUNT','ISSUED_BY');

    var $searchFlds = array('INV_NUM','ORDER_NUM','CUSTOMER_ID','AMMOUNT','PAYMENT_METHOD','STATUS','INVOICE_DATE','ISSUED_BY','DESCRIPTION','CUSTOMER_ID');

    function Invoice($link,$userInfo,$id=''){
        $this->link = $link;
        $this->id = $id;
        $this->userInfo = $userInfo;
        $this->dataTable = new SortableTable($this->link);
        $this->formatter = new InvoiceTableFormatter($this->link,$this->userInfo);
        $this->structure = getTableSchemaInformation($this->link,$this->table);
        $this->cmpDetails = getCompanyDataByCmpId($this->link,$userInfo->cmpId);
        if( !empty($this->id) ){
            $this->details = getInvoiceDetailsById($this->link,$this->id);
            $this->customerData = getCompanyDataByCmpId($this->link,$this->details['CUSTOMER_ID']);
            $orderId = getOrderIdByOrderNum($link,$this->details['ORDER_NUM']);
            $this->orderLineData = geOrderLineByOrderHeaderId($this->link,$orderId);
            
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
        $this->tableDefinitions();

    }

    function tableDefinitions(){
        foreach ($this->structure as $data){
          $fld = $data['COLUMN_NAME'];
          $type = $data['DATA_TYPE'];
          $lngth = $data['CHARACTER_MAXIMUM_LENGTH'];
          $lbl = buildFldsLablel($fld);
          $this->fldDefinition[$fld] = new fldsAtribute($lbl,$type,$lngth);
          $this->tblColumns[] = $fld ;
        }
    }

    function getOrderCreationSubmit(){
        $html = '';
        $ordId = getMaxOrderId($this->link);
        $newOrdId = $ordId+1;
        $submitHtml = HTML::formStart('','POST','ORD_CREATION');
        $submitHtml .= HTML::hiddenFeild('ORD_NUM','ORD-NUM-'.$newOrdId);
        
        foreach($this->categoryIds as $id){
            $submitHtml .= HTML::hiddenFeild('catIds[]',$id);
        }
        $submitHtml .= '<table class="summarytable" width="100%">';
            $submitHtml .= '<tr>';
                $submitHtml .= '<td style="color: blue;"><span><b>Order Number : </b>ORD-NUM-'.$newOrdId.'</span>&nbsp;&nbsp;|&nbsp;&nbsp;';
                $submitHtml .= HTML::submitButtonFeild('order_initiate','Create Order',array('height'=>'60px','width'=>'150px')).'<td>';
                $submitHtml .= '<td style="text-align: right;">';
                    if(!empty($this->errMsg) )
                        $submitHtml .= '<span style="color:red;"><b>'.$this->errMsg.'&nbsp;&nbsp=></b></span>&nbsp;&nbsp';
                    $submitHtml .= '<b>Expected Delivery Date :</b>&nbsp;'.HTML::dateFeild('EXPTD_DLV_DATE','EXPTD_DLV_DATE',array('placeholder'=>'dsadasd'));
                $submitHtml .= '</td>';
            $submitHtml .= '</tr>';
        $submitHtml .= '</table>';

        $html .= contentBox($submitHtml);
        $html .= $this->getOrderCreationHtml();
        $html .= HTML::formEnd();
        return $html;

    }

    function getInvSummaryTable(){
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
        $head = 'Invoices';
        return htmlTableBox($html,$head);

    }

    function getSalesSummaryTable(){
        $dataTable = $this->dataTable;
        $dataTable->setTable($this->table);
        $dataTable->setFormatter($this->formatter);
        $dataTable->setPriKey($this->primaryKey);
        $dataTable->setColumList($this->colList);
        $dataTable->setFilters($this->fltr);
        $dataTable->loadPageData();
        //$data = $this->getHeaderLevelData();
        //$dataTable->setHeaderLevelData($data);
        foreach( $this->salesSummaryFlds as $flds){
            if( $flds == 'ACTION'){
                $dataTable->addColumn($flds,'Actions');
            }else{
                $fldsDef = $this->fldDefinition[$flds];
                $dataTable->addColumn($flds,$fldsDef->lbl);
            }
        }

        $html = $dataTable->htmlTable();
        $head = 'Sales';
        return htmlTableBox($html,$head);

    }

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

    function OrderLineItems(){
        $orderLIne = geOrderLineByOrderHeaderId($this->link,$this->id);
       
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" >';
            $html .= '<tbody><tr>';
                $html .= '<th>Line#</th><th>Category</th><th>Quantity</th><th>Order Date</th><th>Expected Delivery Date</th>
                            <th>Status</th><th>Action</th>';
            $html .= '</tr>';
            foreach( $orderLIne as $data){
                $html .= '<tr>';
                    $html .= '<td>'.$data['LINE_NUM'].'</td>';
                    $html .= '<td>'.$data['CATEGORY'].'</td>';
                    $html .= '<td>'.$data['QUANTITY'].'</td>';
                    $html .= '<td>'.$data['ORDER_DATE'].'</td>';
                    $html .= '<td>'.$data['EXPECTED_DELIVERY_DATE'].'</td>';
                    $html .= '<td>'.$this->formatter->formatters('STATUS',$data['STATUS'],'').'</td>';
                    $html .= '<td><span onclick="loadEditPopUp(\''.$this->id.'\',\''.$data['LINE_NUM'].'\')">'.getRawActionsIcon('edit','Edit Line').'</span>&nbsp;&nbsp;&nbsp;
                                <span onclick="deleteOrderLine(\''.$this->id.'\',\''.$data['LINE_NUM'].'\');">'.getRawActionsIcon('delete','Delete Line').'</span></td>';
                    
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        $head =  'Order Lines';
        return contentBorder($html,$head);
    }

    function getInvoiceAddingForm(){
        $html = '';
        $orderNumArray = array();
        $currentId = getMaxRecIdFromInvoiveTable($this->link);
        if( empty($currentId) ){
            $newId = 1;
        }else{
            $newId = $currentId+1;
        }
        $invNUm = 'INV-NUM-'.$newId;

        $paymentMethod = array('CASH'=>'Cash','CHEQUE'=>'Cheque');
        $orderArray = getSubmittedOrders($this->link);
        foreach( $orderArray as $orderNum){
            $orderNumArray[$orderNum] = $orderNum;
        }
        $html .= HTML::formStart('','POST','INV_ADD');
        $html .= HTML::hiddenFeild('invoiceProcessUrl',makeLocalUrl('invoice/invoice_process.php',''),array('id'=>'invoiceProcessUrl'));
        $html .= HTML::hiddenFeild('INV_NUM',$invNUm,array('id'=>'INV_NUM'));
        $html .= '<table>';
        $html .= '<tbody>';
        $html .= '<tr>';
            $html .= '<td>'.HTML::lblFeild('Invoice Number : ').'</td><td align="right" style="color:blue;">'.$invNUm.'</td>';
        $html .= '<tr>';
        $html .= '<tr>';
            $html .= '<td>'.HTML::lblFeild('Order Number : ').'</td><td align="right">'.HTML::selectFeild('ORDER_NUM','ORDER_NUM',array(""=>"")+$orderNumArray,array('style'=>'width:200px','disable'=>'true')).'</td>';
        $html .= '<tr>';
        $html .= '<tr>';
            $html .= '<td>'.HTML::lblFeild('Payment Method: ').'</td><td align="right">'.HTML::selectFeild('PAYMENT_METHOD','PAYMENT_METHOD',$paymentMethod,array('style'=>'width:200px')).'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td>'.HTML::lblFeild('Invoice Date : ').'</td><td align="right">'.HTML::dateFeild('INVOICE_DATE','',array('style'=>'width:150px')).'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $btn = HTML::submitButtonFeild('add_inv','Save',$attr=array('onclick'=>'addInvoice();'));
        $html .= HTML::formEnd();
        return modalPopupBox('Add Invoice','ADD_INV_POPUP',$html,$btn);;
    }

    function loadEditLinePopup(){
        $html = '<div id="editLinePopUp"></div>';
        $btn = HTML::submitButtonFeild('edit_line','Save',$attr=array('onclick'=>'saveEditLine('.$this->id.');'));
        $popUp = modalPopupBox('Edit Line','EDIT_LINE_POPUP',$html,$btn);
        return $popUp;

    }

    function getInvoiceHeader(){
        $html ='';
        $html .='<section class="invoice">';
            $html .='<div class="row">';
            $html .='<div class="col-xs-12">';
                $html .='<h2 class="page-header"><i class="fa fa-globe"></i> Brisk Lanka.';
                    $html .='<small class="pull-right">Date: '.formatDate($this->details['CREATED_DATE']).'</small>';
                $html .='</h2>';
            $html .='</div>';
        $html .='</div>';
        return $html;
    }

    function getInvoiceContactDetails(){
        $html ='';
        $html .='<div class="row invoice-info">';
            $html .='<div class="col-sm-4 invoice-col">';
                $html .='From';
                $html .='<address>';
                $html .='<strong>'.$this->cmpDetails['COMPANY_NAME'].'</strong><br>';
                $html .= $this->cmpDetails['ADRESS'].'<br>';
                $html .= $this->cmpDetails['POSATL_CODE'].','.$this->cmpDetails['CITY'].'<br>';
                $html .='Phone: '.$this->cmpDetails['PHONE'].'<br>';
                $html .='Email: '.$this->cmpDetails['EMAIL'];
                $html .='</address>';
            $html .='</div>';
            $html .='<div class="col-sm-4 invoice-col">';
                $html .='To';
                $html .='<address>';
                $html .='<strong>'.$this->customerData['COMPANY_NAME'].'</strong><br>';
                $html .= $this->customerData['ADRESS'].'<br>';
                $html .= $this->customerData['POSATL_CODE'].','.$this->customerData['CITY'].'<br>';
                $html .='Phone: '.$this->customerData['PHONE'].'<br>';
                $html .='Email: '.$this->customerData['EMAIL'];
                $html .='</address>';
            $html .='</div>';
                $html .='<div class="col-sm-4 invoice-col">';
                $html .='Details:';
                $html .='<address>';
                $html .='<b>Invoice Number: </b>'.$this->details['INV_NUM'].'</b>';
                $html .='<br>';
                $html .='<b>Order ID:</b> '.$this->details['ORDER_NUM'].'<br>';
                $html .='<b>Invoice Date:</b> '.formatDate($this->details['INVOICE_DATE']).'<br>';
                //$html .='<b>Account:</b> 968-34567';
            $html .='</div>';
        $html .='</div>';
        return $html;
    }

    function getInvoiceListtDetails(){
        $html ='<div class="row">';
        $html .='<div class="col-xs-12 table-responsive">';
            $html .='<table class="table table-striped"><thead>';
                $html .='<tr>';
                    
                    $html .='<th>Brand</th>';
                    $html .='<th>Model</th>';
                    $html .='<th>Brisk</th>';
                    $html .='<th>Category</th>';
                    $html .='<th>Qty</th>';
                    $html .='<th>Unit Price</th>';
                    $html .='<th>Total</th>';
                    $html .='<th>Discount(%)</th>';
                    $html .='<th>Net Price</th>';
                    $html .='<th>Sub Total</th>';
                $html .='</tr>';
                $html .='</thead>';
                $html .='<tbody>';
               // print_rr($this->orderLineData);
                foreach($this->orderLineData as $data){
                    $html .='<tr>';
                        $html .='<td>'.$data['BRAND'].'</td>';
                        $html .='<td>'.$data['MODEL'].'</td>';
                        $html .='<td>'.$data['BRISK'].'</td>';
                        $html .='<td>'.$data['CATEGORY'].'</td>';
                        $html .='<td>'.$data['QUANTITY'].'</td>';
                        $html .='<td>'.$this->formatter->formatters('SELL_PRICE',$data['SELL_PRICE'],'').'</td>';
                        $html .='<td>'.$this->formatter->formatters('TOTAL',$data['TOTAL'],'').'</td>';
                        $html .='<td>'.$data['DISCOUNT_RATE'].'</td>';
                        $html .='<td>'.$this->formatter->formatters('DISCOUNT',$data['DISCOUNT'],'').'</td>';
                        $netAmount = $data['TOTAL']-$data['DISCOUNT'];
                        $html .='<td>'.$this->formatter->formatters('DISCOUNT',$netAmount,'').'</td>';
                    $html .='</tr>';


                }
                $html .='</tbody>';
            $html .='</table>';
        $html .='</div>';
        $html .='</div>';
        return $html;
    }

    function getInvoiceAmmountDetails(){
        $netAmount = [];
        $additionalDiscount = 0;
        foreach($this->orderLineData as $data){
            $netAmount[] = $data['TOTAL']-$data['DISCOUNT'];
        }
        $netTotal = array_sum($netAmount);
       
        if( !empty($this->details['ADDITIONAL_DISSCOUNT']) && $this->details['ADDITIONAL_DISSCOUNT'] != 0.00){
            $additionalDiscountRate = $this->details['ADDITIONAL_DISSCOUNT'];
        }else{
            $additionalDiscountRate = '';
        }

        $html ='<div class="row">';
            $html .='<div class="col-xs-6">';
                $html .='<p class="lead">Payment Due '.formatDate($this->details['INVOICE_CLOSE_DATE']).'</p>';
                $html .='<div class="table-responsive">';
                    $html .='<table class="table">';
                    $html .='<tbody>';
                        $html .='<tr>';
                            $html .='<th>Total:</th>';
                            $html .='<td>'.$this->formatter->formatters('TOTAL',$netTotal,'').'</td>';
                        $html .='</tr>';
                        
                        if( !empty($additionalDiscountRate) ){
                            $html .='<tr>';
                                $html .='<th>Discount:</th>';
                                $additionalDiscount = $netTotal *($additionalDiscountRate/100);
                                $html .='<td>'.$this->formatter->formatters('TOTAL',$additionalDiscount,'').'</td>';
                            $html .='</tr>';
                        }
                        $html .='<tr>';
                            $html .='<th>Net Amount:</th>';
                            $additionalDiscount = $netTotal-$additionalDiscount;
                        $html .='<td>'.$this->formatter->formatters('TOTAL',$additionalDiscount,'').'</td>';
                    $html .='</tr>';
                    $html .='</tbody></table>';
                $html .='</div>';
            $html .='</div>';
            $html .='<div class="col-xs-6">';
                $html .='<p class="lead">Signature :</p>';
                $html .='<div class="table-responsive">';
                    $html .='</div>';
            $html .='</div>';
        $html .='</div>';
        return $html;
    }

    function getInvoiceActionSection(){
            $html='<div class="row no-print">';
                $html .='<div class="col-xs-12">';
                    $html .='<a href="'.makeLocalUrl('invoice/invoice_print.php','sec=INVOICE&id='.$this->id).'" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>';
                    $html .='</div>';
            $html .='</div>';
        $html .='</section>';
        return $html;
    }

    function getInvoiceDetails($isprint=false){
        $html = $this->getInvoiceHeader();
        $html .= $this->getInvoiceContactDetails();
        $html .= $this->getInvoiceListtDetails();
        $html .= $this->getInvoiceAmmountDetails();

        if(!$isprint)
            $html .= $this->getInvoiceActionSection();
        return $html;
    }

    function getOutstandingOrderForm($invId){
        $html = '';
        $invData = getInvoiceDetailsById($this->link,$invId);
        
        $html .= HTML::formStart('','POST','OUTSTANDING_DATA');
        $html .= '<table>';
        $html .= HTML::hiddenFeild('invId',$invData['INV_ID'],array('id'=>'INV_ID'));
        $html .= HTML::hiddenFeild('INV_NUM',$invData['INV_NUM'],array('id'=>'INV_NUM'));
        $html .= HTML::hiddenFeild('ORDER_NUM',$invData['ORDER_NUM'],array('id'=>'ORDER_NUM'));
        $html .= HTML::hiddenFeild('TOTAL_AMMOUNT',$invData['NET_AMMOUNT'],array('id'=>'NET_AMMOUNT'));
        $html .= HTML::hiddenFeild('CUSTOMER_ID',$invData['CUSTOMER_ID'],array('id'=>'CUSTOMER_ID'));
        $html .= '<tbody>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('Invoice# : ').'</td><td>&nbsp;&nbsp;'.$invData['INV_NUM'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('Order Number : ').'</td><td>&nbsp;&nbsp;'.$invData['ORDER_NUM'].'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td align="right">'.HTML::lblFeild('Total Amount : ').'</td><td>&nbsp;&nbsp;'.formatCurrency($invData['NET_AMMOUNT']).'</td>';
            $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('Ammount : ').'</td><td>&nbsp;&nbsp;'.HTML::textFeild('OUTSATNDING_AMOUNT','',$attr=array()).'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
            $html .= '<td align="right">'.HTML::lblFeild('Outstanding Date &nbsp;&nbsp;: ').'</td><td>&nbsp;&nbsp;'.HTML::dateFeild('OUT_STANDING_DATE','').'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= HTML::formEnd();
        return $html;
    }

    function loadOutsatandingForPopup(){
        $html = '<div id="outstandingPopUp"></div>';
        $btn = HTML::buttonFeild('reject_item','Save',$attr=array('onclick'=>'saveOutstanding();'));
        $popUp = modalPopupBox('Outstanding Order','OUTSTANDING_ORDER_POPUP',$html,$btn);
        return $popUp;
    }

    function getincomeSummaryTable(){
        $competedInvoiceData = getCompletedInvoiceData($this->link);
        $currentOutstandigData = getOutstandingData($this->link);
        
        foreach($competedInvoiceData as $k=>$invData){
            $dataInv[$k]['INV_NUM'] = $invData['INV_NUM'];
            $dataInv[$k]['ORDER_NUM'] = $invData['ORDER_NUM'];
            $dataInv[$k]['PAID_AMOUNT'] = $invData['NET_AMMOUNT'];
            $dataInv[$k]['SOURCE'] = 'PAID';
            $dataInv[$k]['PAID_DATE'] = $invData['MODIFIED_DATE'];
        }

        foreach($currentOutstandigData as $k=>$outstandData){
            $outInv[$k]['INV_NUM'] = $outstandData['INV_NUM'];
            $outInv[$k]['ORDER_NUM'] = $outstandData['ORDER_NUM'];
            $outInv[$k]['PAID_AMOUNT'] = $outstandData['PAID_AMOUNT'];
            $outInv[$k]['SOURCE'] = 'OUTSTANDING';
            $outInv[$k]['PAID_DATE'] = $outstandData['PAID_DATE'];
        }

        $salesOrderData = array_merge($dataInv,$outInv);
            $html = '';
            $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" id="sortableTable">';
            $html .= '<thead><tr>';
                $html .= '<th>Inv Num</th><th>Order Num</th><th>Amount</th><th>Source</th><th>Received Date</th>';                            ;
            $html .= '</tr></thead>';
            $html .= '<tbody>';
            foreach( $salesOrderData as $OrderData){
                $html .= '<tr>';
                    $html .= '<td>'.$OrderData['INV_NUM'].'</td>';
                    $html .= '<td>'.$OrderData['ORDER_NUM'].'</td>';
                    $html .= '<td>'.$this->formatter->formatters('PAID_AMOUNT',$OrderData['PAID_AMOUNT'],'').'</td>';
                    $html .= '<td>'.$this->formatter->formatters('STATUS',$OrderData['SOURCE'],'').'</td>';
                    $html .= '<td>'.$OrderData['PAID_DATE'].'</td>';
                    ;
                  $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        $head =  'Income';
        return contentBorder($html,$head);
    }
}
?>