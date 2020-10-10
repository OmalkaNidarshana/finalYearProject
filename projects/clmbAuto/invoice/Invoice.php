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
    var $summaryFlds = array('INV_NUM','ORDER_NUM','CUSTOMER_ID','AMMOUNT','PAYMENT_METHOD','STATUS','INVOICE_DATE','INVOICE_CLOSE_DATE','ISSUED_BY','DESCRIPTION');

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
            $this->total = getTotalFromOrderByorderHeaderId($this->link,$orderId);
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
        $head .= '<span data-toggle="modal" data-target="#ADD_INV_POPUP">'.getRawActionsIcon('add','Add Invoice').'</span>';
        return htmlTableBox($html,$head,'true');

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
                    $html .='<small class="pull-right">Date: 2/10/2014</small>';
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
                $html .= $this->cmpDetails['POSATL_CODE'].','.$this->cmpDetails['POSATL_CODE'].','.$this->cmpDetails['CITY'].'<br>';
                $html .='Phone: '.$this->cmpDetails['EMAIL'].'<br>';
                $html .='Email: '.$this->cmpDetails['PHONE'];
                $html .='</address>';
            $html .='</div>';
            $html .='<div class="col-sm-4 invoice-col">';
                $html .='To';
                $html .='<address>';
                $html .='<strong>'.$this->customerData['COMPANY_NAME'].'</strong><br>';
                $html .= $this->customerData['ADRESS'].'<br>';
                $html .= $this->customerData['POSATL_CODE'].','.$this->customerData['POSATL_CODE'].','.$this->customerData['CITY'].'<br>';
                $html .='Phone: '.$this->customerData['EMAIL'].'<br>';
                $html .='Email: '.$this->customerData['PHONE'].'';
                $html .='</address>';
            $html .='</div>';
                $html .='<div class="col-sm-4 invoice-col">';
                $html .='<b>Invoice '.$this->details['INV_NUM'].'</b><br>';
                $html .='<br>';
                $html .='<b>Order ID:</b> '.$this->details['ORDER_NUM'].'<br>';
                $html .='<b>Payment Due:</b> '.$this->details['INVOICE_CLOSE_DATE'].'<br>';
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
                    $html .='<th>Qty</th>';
                    $html .='<th>Product</th>';
                    $html .='<th>Description</th>';
                    $html .='<th>Subtotal</th>';
                $html .='</tr>';
                $html .='</thead>';
                $html .='<tbody>';
                foreach($this->orderLineData as $data){
                    $html .='<tr>';
                        $html .='<td>'.$data['QUANTITY'].'</td>';
                        $html .='<td>'.$data['CATEGORY'].'</td>';
                        $html .='<td>'.$data['DESCRIPTION'].'</td>';
                        $html .='<td>'.$this->formatter->formatters('TOTAL',$data['TOTAL'],'').'</td>';
                    $html .='</tr>';
                }
                $html .='</tbody>';
            $html .='</table>';
        $html .='</div>';
        $html .='</div>';
        return $html;
    }

    function getInvoiceAmmountDetails(){
        $html ='<div class="row">';
            $html .='<div class="col-xs-6">';
                $html .='<p class="lead">Amount Due 2/22/2014</p>';
                $html .='<div class="table-responsive">';
                    $html .='<table class="table">';
                    $html .='<tbody>';
                        $html .='<tr>';
                            $html .='<th>Total:</th>';
                            $html .='<td>'.$this->formatter->formatters('TOTAL',array_sum($this->total),'').'</td>';
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
                    $html .='<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">';
                    $html .='<i class="fa fa-download"></i> Generate PDF';
                    $html .='</button>';
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
}
?>