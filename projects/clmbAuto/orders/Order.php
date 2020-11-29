<?php

class Order{
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

    function Order($link,$userInfo,$id=''){
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

    function getOrderCreationHtml(){
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" id="ordrCreation" >';
            $html .= '<thead><tr>';
                $html .= '<th>Brand</th><th>Model</th><th>Vehical Code</th><th>CC</th><th>Brisk</th><th>Brisk Code</th><th>Denso
                        </th><th>IRIDIUM</th><th>Description</th><th>Quantity</th><th>Action</th>';
            $html .= '</tr></thead>';
            /*foreach( $this->categoryIds as $id){
                $data = getCategoryDataBycategoryId($this->link,$id);
                $html .= '<tr>';
                    $html .= '<td>'.$data['BRAND'].'</td>';
                    $html .= '<td>'.$data['MODEL'].'</td>';
                    $html .= '<td>'.$data['VEHICAL_CODE'].'</a></td>';
                    $html .= '<td>'.$data['CC'].'</td>';
                    $html .= '<td>'.$data['BRISK'].'</td>';
                    $html .= '<td>'.$data['BRISK_CODE'].'</td>';
                    $html .= '<td>'.$data['DENSO'].'</td>';
                    $html .= '<td>'.$data['IRIDIUM'].'</td>';
                    $html .= '<td>'.HTML::numberFeild('qty['.$id.']','1',array('style'=>'width:50px;height:15px;margin:0px;border-radius:3px;')).'</td>';
                $html .= '</tr>';
            }*/
            $html .= '</table>';
        $html .= '<div>';
        
        return contentBox($html);
    }

    function getOrderCreationSubmit(){
        $html = '';
        $ordId = getMaxOrderId($this->link);
        $briskList = getItemList($this->link);
        
       foreach($briskList as $brisk){
            $briskData[$brisk] = $brisk;
        }

        $newOrdId = $ordId+1;
        $html = HTML::formStart('','POST','ORD_CREATION');
        $html .= $this->getOrderCreationAction();
        $html .= HTML::hiddenFeild('loadItemDataUrl',makeLocalUrl('orders/order_process.php',''),array('id'=>'loadItemDataUrl'));
        $html .= HTML::hiddenFeild('ORD_NUM','ORD-NUM-'.$newOrdId,);
        $submitHtml = '<table class="summarytable" width="100%">';
            $submitHtml .= '<tr>';
                //$submitHtml .= '<td>'..'<td>';
                //$submitHtml .= HTML::submitButtonFeild('order_initiate','Create Order',array('style'=>'width:100px; height:20px;')).'<td>';
                $submitHtml .='<td style="text-align: left;width: 100px;"><b>Brisk Code : </b>&nbsp;</td><td style="width: 150px;">'.HTML::selectFeild('BRISK','BRISK',array(""=>"")+$briskData,'',false,array("style"=>"height: 25px;width: 100px;","onChange"=>"loadModelList();")).'</td>';
                $submitHtml .='<td style="text-align: left;width: 100px;"><b>Model : </b>&nbsp;</td><td>'.HTML::selectFeild('MODEL','MODEL',array(""=>""),'',false,array("style"=>"height: 25px;width: 100px;","onChange"=>"loadItemData();")).'</td>';
                /*$submitHtml .= '<td style="text-align: right;">';
                $submitHtml .= '<b>Expected Delivery Date : </b>&nbsp;'.HTML::dateFeild('EXPTD_DLV_DATE','EXPTD_DLV_DATE',array('placeholder'=>'dsadasd'));
                $submitHtml .= '</td>';*/
            $submitHtml .= '</tr>';
        $submitHtml .= '</table>';
        $headr = '<b>Add Item';
        $html .= htmlTableBox($submitHtml,$headr);
        $html .= $this->getOrderCreationHtml();
        $html .= HTML::formEnd();
        return $html;

    }

    function getOrderSummaryTable(){
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
        return htmlTableBox($html,'Orders');

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

    function getOrderHeaderDetails(){
        $html = '';
        $html .= HTML::formStart('','POST','SUBMIT_ORDER');
        $html .= HTML::hiddenFeild('orderProcessUrl',makeLocalUrl('orders/order_process.php',''),array('id'=>'orderProcessUrl'));
        $html .= '<div class="box-body table-responsive no-padding">';
              $html .= '<table class="table table-hover summarytable">';
                $html .= '<tr>';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Order Num : &nbsp</span></td>';
                    $html .= '<td>'.$this->details['ORDER_NUM'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Line Item : &nbsp</span></td>';
                    $html .= '<td>'.$this->details['LINE_ITEM'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '<tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Order Date : &nbsp</span></td>';
                    $html .= '<td>'.$this->details['ORDER_DATE'].'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Expected Delivery Date : &nbsp</span></td>';
                    $html .= '<td>'.$this->details['EXPECTED_DELIVERY_DATE'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '<tr>';
                $html .= '<tr>';    
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Status : &nbsp</span></td>';
                    $html .= '<td>'.$this->formatter->formatters('STATUS',$this->details['STATUS'],'').'</td><td style="height:40px;" width="150px;" align="right">';
                    $html .= '<td style="height:40px;" width="150px;" align="right"><span class="detailsHeader">Description : &nbsp</span></td>';
                    $html .= '<td>'.$this->details['DESCRIPTION'].'</td><td style="height:40px;" width="150px;" align="right">';
                $html .= '<tr>';
            $html .= '</table>';
        $html .= '</div>';
        $html .= HTML::formEnd();

        $head = 'Order Details : '.$this->details['ORDER_NUM'];
        
        return contentBorder($html,$head);

    }

    function OrderLineItems(){
        $orderLIne = geOrderLineByOrderHeaderId($this->link,$this->id);
        $html ='';
        $html .= '<div class="box-body table-responsive no-padding">';
            $html .= '<table class="table table-hover summarytable" >';
            $html .= '<tbody><tr>';
                $html .= '<th>Line#</th><th>Category</th><th>Quantity</th><th>Unit Price (Rs.)</th><th>Total (Rs.)</th><th>Discount (Rs.)</th><th>Order Date</th><th>Description</th><th>Expected Delivery Date</th>
                            <th>Status</th>';
                    if($this->details['STATUS'] !== 'SUBMITTED'){
                        $html .='<th>Action</th>';
                    }
            $html .= '</tr>';
            foreach( $orderLIne as $data){
                $html .= '<tr>';
                    $html .= '<td>'.$data['LINE_NUM'].'</td>';
                    $html .= '<td>'.$data['CATEGORY'].'</td>';
                    $html .= '<td>'.$data['QUANTITY'].'</td>';
                    $html .= '<td>'.$this->formatter->formatters('SELL_PRICE',$data['SELL_PRICE'],'').'</td>';
                    $html .= '<td>'.$this->formatter->formatters('TOTAL',$data['TOTAL'],'').'</td>';
                    $html .= '<td>'.$this->formatter->formatters('TOTAL',$data['DISCOUNT'],'').'</td>';
                    $html .= '<td>'.$data['ORDER_DATE'].'</td>';
                    $html .= '<td>'.$data['DESCRIPTION'].'</td>';
                    $html .= '<td>'.$data['EXPECTED_DELIVERY_DATE'].'</td>';
                    $html .= '<td>'.$this->formatter->formatters('STATUS',$data['STATUS'],'').'</td>';
                    if($this->details['STATUS'] !== 'SUBMITTED'){
                        $html .= '<td><span onclick="loadEditPopUp(\''.$this->id.'\',\''.$data['LINE_NUM'].'\')">'.getRawActionsIcon('edit','Edit Line').'</span>&nbsp;&nbsp;&nbsp;
                                    <span onclick="deleteOrderLine(\''.$this->id.'\',\''.$data['LINE_NUM'].'\');">'.getRawActionsIcon('delete','Delete Line').'</span></td>';
                    }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        $head =  'Order Lines';
        return contentBorder($html,$head);
    }

    function getTotalAmoutPanel(){
        $orderLIne = geOrderLineByOrderHeaderId($this->link,$this->id);
        
        foreach($orderLIne as $data){
            $totalArr[] = $data['TOTAL'];
            $dissArr[] = $data['DISCOUNT'];
        }

        $totalAmount = array_sum($totalArr);
        $totalDiscount = array_sum($dissArr);
        $netAmount = $totalAmount-$totalDiscount;
        $html = '<table class="table table-hover summarytable" >';
            $html .= '<tbody>';
                $html .= '<tr>';
                $html .= '<td><b>Total Amount :</b>&nbsp;&nbsp;Rs.'.$totalAmount.'</td><td align=""><b>Total Disount :</b>&nbsp;&nbsp;Rs.'.$totalDiscount.'</td><td align=""><b>Net Amount :</b>&nbsp;&nbsp;Rs.'.$netAmount.'</td>';
                $html .= '</tr>';
            $html .= '</tbody>';
        $html .= '</table>';
        return $html;

    }
    function getOrderLineEditForm($orderId,$lineId){
        $lineData = getOrderLineDateByHeaderAndLineId($this->link,$orderId,$lineId);

        
        $html = '';
        $html .= HTML::formStart('','POST','EDIT_LINE');
        $html .= '<table>';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<td>'.HTML::lblFeild('Line# : ').'</td><td>'.$lineId.'</td>';
        $html .= '<tr>';
        
        $html .= HTML::hiddenFeild('orderProcessUrl',makeLocalUrl('orders/order_process.php',''),array('id'=>'orderProcessUrl'));
        $html .= HTML::hiddenFeild('lineId',$lineId,array('id'=>'lineId'));
        $html .= '<tr>';
            $html .= '<td>'.HTML::lblFeild('Quantity : ').'</td><td align="right">'.HTML::numberFeild('QUANTITY',$lineData['QUANTITY']).'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>'.HTML::lblFeild('Expected Delivery Date : ').'</td><td align="right">'.HTML::dateFeild('EXPECTED_DELIVERY_DATE',$lineData['EXPECTED_DELIVERY_DATE']).'</td>';
        $html .= '<tr>';
        $html .= '</table>';
        $html .= HTML::formEnd();
        return $html;
    }

    function loadEditLinePopup(){
        $html = '<div id="editLinePopUp"></div>';
        $btn = HTML::submitButtonFeild('edit_line','Save',$attr=array('onclick'=>'saveEditLine('.$this->id.');'));
        $popUp = modalPopupBox('Edit Line','EDIT_LINE_POPUP',$html,$btn);
        return $popUp;

    }

    function addItemPopup(){ // The popUp of item adding form
        global $countryArray;
        foreach( $countryArray as $country){
            $countryName[$country['name']] = $country['name'];
            $countryCode[$country['code']] = $country['code'];
        }
        $html = '';
        $html .= HTML::formStart('','POST','ADD_ITEM_FORM');
        $html .= HTML::openCloseTable(true,false,array("style"=>"font-size:12px;"));
        $html .=HTML::hiddenFeild('cmpId',$this->userInfo->cmpId,array('id'=>'cmpId'));
        $html .=HTML::hiddenFeild('cmpType',$this->userInfo->cmpType,array('id'=>'cmpType'));
        $html .=HTML::hiddenFeild('processPath',makeLocalUrl('account/acc_process.php','action=addUser'),array('id'=>'processPath'));
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('Item List : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('ITEM_LIST','',array('style'=>'width:300px;','id'=>'FIRST_NAME')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
                $html .='<td>'.HTML::lblFeild('Last Name : ',array("style"=>"padding:5px;") ).'</td>';
                $html .='<td>'.HTML::textFeild('LST_NAME','',array('style'=>'width:300px;','id'=>'LST_NAME')).'</td>';
            $html .='</tr>';
            $html .='<tr>';
            $btn = HTML::submitButtonFeild('save','Save',array('onclick'=>'addUser();'));
        $html .= HTML::openCloseTable(false,false);
        $html .= HTML::formEnd();
        $popUp = modalPopupBox('Add Item','ADD_ITEM',$html,$btn);
        return $popUp;


    }

    function getInvoiceAddingForm(){
        $html = '';
       
        $currentId = getMaxRecIdFromInvoiveTable($this->link);
        if( empty($currentId) ){
            $newId = 1;
        }else{
            $newId = $currentId+1;
        }
        $invNUm = 'INV-NUM-'.$newId;

        $paymentMethod = array('CASH'=>'Cash','CHEQUE'=>'Cheque');
        $orderArray = getSubmittedOrders($this->link);
        $ordNum = 'ORD-NUM-'.$this->id;
        $html .= HTML::formStart('','POST','INV_ADD');
        $html .= HTML::hiddenFeild('invoiceProcessUrl',makeLocalUrl('invoice/invoice_process.php',''),array('id'=>'invoiceProcessUrl'));
        $html .= HTML::hiddenFeild('INV_NUM',$invNUm,array('id'=>'INV_NUM'));
        $html .= HTML::hiddenFeild('ORDER_NUM',$ordNum,array('id'=>'ORDER_NUM'));
        $html .= '<table>';
        $html .= '<tbody>';
        $html .= '<tr>';
            $html .= '<td>'.HTML::lblFeild('Invoice Number : ').'</td><td align="right" style="color:blue;">'.$invNUm.'</td>';
        $html .= '<tr>';
        $html .= '<tr>';
            $html .= '<td>'.HTML::lblFeild('Order Number : ').'</td><td align="right">'.$ordNum.'</td>';
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

    function getActionPanel(){
        $btn = '';
        HTML::submitButtonFeild('order_initiate','Create Order',array('style'=>'width:100px; height:20px;'));
        if( $this->details['STATUS'] == 'SUBMITTED'){
            $btn .= HTML::submitButtonFeild('re_open','Re Open',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:brown"));
            $btn .= '<span data-toggle="modal" data-target="#ADD_INV_POPUP">'.HTML::submitButtonFeild('create_invoce','Create Invoice',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:orange")).'</span>';
        }else{
            $btn .= HTML::submitButtonFeild('order_submit','Submit',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px;",'onclick'=>'submitOrder('.$this->id.');'));
        }
        $btn .= HTML::submitButtonFeild('order_submit','Cancel',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px; background-color:red",'onclick'=>'submitOrder('.$this->id.');'));
        return contentBox($btn);
    }

    function getOrderCreationAction(){
        $btn = HTML::submitButtonFeild('order_initiate','Create Order',array('style'=>"width: 100px;height: 30px; padding-left: 5px; margin:5px;"));
        return contentBox($btn);
    }
}
?>