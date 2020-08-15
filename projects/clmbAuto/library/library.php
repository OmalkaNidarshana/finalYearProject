<?php

function htmlTableBox($table,$head,$isSearch=false){

   $html = '<div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">'.$head.'</h3>';
        if($isSearch){
            $html .='
            <div class="box-tools">
            <form method="post">
                <div class="input-group input-group-sm " style="width: 150px;">
                    <input type="text" name="random_search" class="form-control pull-right" placeholder="Search...">
                    <div class="input-group-btn">
                        <button type="submit" name="custom_search" class="btn btn-default"><i class="fa fa-search" title="Custome Search"></i></button>
                    </div>
                    <div class="input-group-btn">
                        <span class="btn btn-default" style="padding-top: 5px;" data-toggle="modal" data-target="#REGULER_SEARCH"><i class="fa fa-binoculars" title="Reguler Search"></i></span>
                    </div>
                
                </div>
            </form>
            </div>';
        }
        $html .='</div>
            <div class="box-body table-responsive ">
                '.$table.'
            </div>
      
        </div>
    </div>';
    return $html;
}

function contentBox($table){

    $html = '<div class="col-xs-12">
        <div class="box">
        <div class="box-body table-responsive ">
             '.$table.'
        </div>
       
        </div>
     </div>';
     return $html;
 }

 function imgPanelBox($table){

    $html = '<div class="col-sm-2">
     <div class="box">
       <div class="img-box-header">
         </div>
             <div class="box-body table-responsive">
                 '.$table.'
             </div>
       
         </div>
     </div>';
     return $html;
 }

 function getHtmlAlertBox($msg){
    $html = '<div class="alert alert-danger alert-dismissible">';
        $html .= $msg;
    $html .= '</div>';
    return $html;
 }
 
 function getPageContentArea($cntents=array()){
    $html = '<section class="content">';
        $html .='<div class="row">';
        foreach($cntents as $cntent){
            $html .=$cntent;
        }
        $html .='</div>';
    $html .= '</section>';
    echo $html;
 }

 function makeLocalUrl($directory,$params){
    $url = 'http://'.ROOT.$directory;
    if($params){
        $url .= "?".$params;
    } 
    return $url;
 }

 function getTextValue($str){
    $str = "'".$str."'";
    return $str;
 }

 function getCurrentDateTime(){
    return date("Y-m-d H:i:s");
 }

 function formatCurrency($val){
    $currencyVal = 'Rs.'.$val;
    return $currencyVal;
 }
 function buildFldsLablel($fld){
    $lbl = str_replace("_"," ",$fld);
    $fldLbl = ucfirst(strtolower($lbl));
    return $fldLbl;
 }

 function contentBorder($data,$title){
    $html ='<div class="col-xs-12">';
        $html .='<div class="box">';
            $html .='<div class="box-header">';
                $html .='<h3 class="box-title">'.$title.'</h3>';
                //$html .='<hr style="height:2px;border-width:0;background-color:LightGray">';
            $html .='</div>';
            $html .='<div class="box-body">';
                $html .= $data;
            $html .='</div>';
        $html .='</div>';
    $html .='</div>';
    return $html;
}

function getRawActionsIcon($type,$titile,$isanimated=true,$isDisable=false){
    
    switch($type){
        case "delete":
            $icon = 'fa-trash-o';
            $class = 'deleteIcon';
        break;
        case "edit":
            $icon = 'fa-pencil';
            $class = 'editIcon';
        break;
        case "cart":
            $icon = 'fa-shopping-cart';
            $class = 'cartIcon';
        break;
        case "store":
            $icon = 'fa-database';
            $class = 'stockIcon';
        break;
        case "addCmp":
            $icon = 'fa-building-o';
            $class = 'addCmpIcon';
        break;
        case "addUser":
            $icon = 'fa fa-user-plus';
            $class = 'addUser';
        break;
    }

    if($isanimated){
        $animated = 'animated-hover';
    }else{
        $animated = '';
       
    }

    if($isDisable){
        $class = 'disable';
    }

    $action = '<i class="fa '.$icon.' faa-shake '.$animated.' '.$class.' fa-lg" title="'.$titile.'"></i>';
    //print_rr($action )
    return $action;
}

function print_rr($data){
    echo '<pre>';
    if( empty($data)){
        echo 'False/Empty';
    }else{
        print_r($data);
    }
    echo '<pre>';
}

function modalPopupBox($title,$id,$data,$btn){
    $html ='<div class="modal fade in" id="'.$id.'" style="display: none; padding-right: 17px;">';
        $html .='<div class="modal-dialog">';
            $html .='<div class="modal-content">';
                $html .='<div class="modal-header">';
                    $html .='<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    $html .='<span aria-hidden="true">×</span></button>';
                    $html .='<h4 class="modal-title">'.$title.'</h4>';
                $html .='</div>';
                $html .='<div class="modal-body">';
                    $html .= $data;
                $html .='</div>';
                $html .='<div class="modal-footer">';
                    $html .= $btn;
                    $html .='<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>';
                $html .='</div>';
            $html .='</div>';
        $html .='</div>';
    $html .='</div>';
    return $html;
}

function sideModalPopupBox($title,$id,$data,$btn){
    $html ='<div class="modal right fade" id="'.$id.'" style="display: none; padding-right: 17px;">';
        $html .='<div class="modal-dialog">';
            $html .='<div class="modal-content">';
                $html .='<div class="modal-header">';
                    $html .='<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    $html .='<span aria-hidden="true">×</span></button>';
                    $html .='<h4 class="modal-title">'.$title.'</h4>';
                $html .='</div>';
                $html .='<div class="modal-body">';
                    $html .= $data;
                $html .='</div>';
                $html .='<div class="modal-footer">';
                    $html .= $btn;
                    $html .='<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>';
                $html .='</div>';
            $html .='</div>';
        $html .='</div>';
    $html .='</div>';
    return $html;
}
?>