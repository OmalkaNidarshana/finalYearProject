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
    var $summaryFlds = array('ORDER_NUM','LINE_ITEM','ORDER_DATE','STATUS','EXPECTED_DELIVERY_DATE');

    var $searchFlds = array('ORDER_NUM','LINE_ITEM','ORDER_DATE','STATUS','ACTUAL_DELIVERY_DATE','STATUS');

    function Order($link,$userInfo,$id=''){
        $this->link = $link;
        $this->id = $id;
        $this->userInfo = $userInfo;
        $this->dataTable = new SortableTable($this->link);
        $this->formatter = new OrderTableFormatter($this->link,$this->userInfo);
        $this->structure = getTableSchemaInformation($this->link,$this->table);
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
            $html .= '<table class="table table-hover summarytable" >';
            $html .= '<tbody><tr>';
                $html .= '<th>Brand</th><th>Model</th><th>Vehical Code</th><th>CC</th><th>Brisk</th><th>Brisk Code</th><th>Denso
                        </th><th>IRIDIUM</th><th>Quantity</th>';
            $html .= '</tr>';
            foreach( $this->categoryIds as $id){
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
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        
        return contentBox($html);
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
        return htmlTableBox($html,'Orders','true');

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
        $head .= '&nbsp;&nbsp;|&nbsp;&nbsp;'.HTML::submitButtonFeild('order_submit','Submit',array('style'=>"width: 50px;height: 20px; padding-left: 5px;",'onclick'=>'submitOrder('.$this->id.');'));
        
        return contentBorder($html,$head);

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
                                <span>'.getRawActionsIcon('delete','Delete Line').'</span></td>';
                    
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        $head =  'Order Lines';
        return contentBorder($html,$head);
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
        $html .= '<tr>';
            $html .= '<td>'.HTML::lblFeild('Quantity : ').'</td><td align="right">'.HTML::textFeild('QUANTITY',$lineData['QUANTITY']).'</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>'.HTML::lblFeild('Expected Delivery Date : ').'</td><td align="right">'.HTML::dateFeild('EXPECTED_DELIVERY_DATE',$lineData['EXPECTED_DELIVERY_DATE'],array('style'=>'width:200px')).'</td>';
        $html .= '<tr>';
        $html .= '</table>';
        $html .= HTML::formEnd();
        return $html;
    }

    function loadEditLinePopup(){
        $html = '<div id="editLinePopUp"></div>';
        $btn = HTML::submitButtonFeild('edit_line','Submit',$attr=array());
        $popUp = modalPopupBox('Edit Line','EDIT_LINE_POPUP',$html,$btn);
        return $popUp;

    }
}
?>