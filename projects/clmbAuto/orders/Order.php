<?php

class Order{
    var $link;
    var $userInfo;
    var $table = 'category';
    var $primaryKey = 'RECORD_ID';
    var $categoryIds;

    function Order($link,$userInfo){
        $this->link = $link;
        $this->userInfo = $userInfo;
        

    }

    function setCategoryIds($categoryIds){
        $this->categoryIds = $categoryIds;
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
                    $html .= '<td>'.HTML::textFeild('qty','1',array('style'=>'width:40px;height:15px;margin:0px;border-radius:3px;')).'</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
        $html .= '<div>';
        
        return contentBox($html);
    }

    function getOrderCreationSubmit(){
        $html = '';
        $submitHtml = HTML::formStart('','POST','ORD_CREATION');
        /*$submitHtml .= '<div class="row">';
            $submitHtml .= '<div class="col-sm-2">Expected Delivery Date</div><div class="col-sm-2">'.HTML::dateFeild('EXPTD_DLV_DATE','EXPTD_DLV_DATE',array()).'</div>';
        $submitHtml .= '</div>';*/

        $submitHtml .= '<table class="summarytable" width="100%">';
            $submitHtml .= '<tr>';
                $submitHtml .= '<td>'.HTML::submitButtonFeild('order_create','Create Order',array('height'=>'50px','width'=>'150px')).'<td>';
                $submitHtml .= '<td style="text-align: right;"><b>Expected Delivery Date :</b>&nbsp;'.HTML::dateFeild('EXPTD_DLV_DATE','EXPTD_DLV_DATE',array()).'</td>';
            $submitHtml .= '</tr>';
        $submitHtml .= '</table>';

        $html .= contentBox($submitHtml);
        $html .= $this->getOrderCreationHtml();
        $html .= HTML::formEnd();
        return $html;

    }
}
?>