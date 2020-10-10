<?php
function getLeftMainMenue($mainMenueArr,$subMenueArr,$menueIcon){
    $html = '<section class="sidebar sideBarHeight" id="sidebar" style="height: auto;">';
   $html .='<ul class="sidebar-menu tree" data-widget="tree">';
   foreach ($subMenueArr as $subMenueKey=>$link) {
       if( is_array($link) ) {
           $html .= '<li class="treeview">';
           $html .= '<a href="#"><i class="'.$menueIcon[$subMenueKey].'"></i><span>&nbsp;&nbsp;'.$mainMenueArr[$subMenueKey].'</span>';
           $html .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
           $html .= '</a>';
           $html .= '<ul class="treeview-menu">';
           foreach ($link as $subLbl=>$link)
               $html .= '<li><a href="'.$link.'"><i class="fa fa-caret-right"></i>'.$subLbl.'</a></li>';
           $html .= '</ul>';
           $html .= '</li>';
       }else{
           $html .= '<li>';
           $html .= '<a href="'.$link.'"><i class="'.$menueIcon[$subMenueKey].'"></i><span>&nbsp;&nbsp;'. $mainMenueArr[$subMenueKey] .'</span>';
           $html .= '</a></li>';
       }
   }
   $html .='</ul>';
   $html .='</section>';
   return $html;

}

function getHeaderProfileSect($userInfo){
    $html = '<li class="dropdown user user-menu">';
       $html .='<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
           $html .='<img src="http://'.IMG_ROOT.'userImg/user_omalka.jpg" class="user-image" alt="User Image">';
           $html .='<span class="hidden-xs">'.$userInfo->firstName.' '.$userInfo->LastName.'</span>';
       $html .='</a>';
       $html .='<ul class="dropdown-menu">';
           $html .='<li class="user-header">';
               $html .='<img src="http://'.IMG_ROOT.'userImg/user_omalka.jpg" class="img-circle" alt="User Image">';
               $html .='<p>'.$userInfo->firstName.' '.$userInfo->LastName.' - '.$userInfo->role.'<small>'.$userInfo->userName.'</small></p>';
           $html .='</li>';
           $html .='<li class="user-footer">';
                $html .='<div class="pull-left">';
                    $html .='<a href="#" class="btn btn-default btn-flat">Profile</a>';
                $html .='</div>';
                $html .='<div class="pull-right">';
                $html .= HTML::formStart(makeLocalUrl('main/login.php',''),'POST','');
                    $html .= HTML::submitButtonFeild('logOut','Sign Out',array('style'=>'height: 35px;width: 80px;border-radius: 0px;'));
                $html .= HTML::formEnd();
                 //$html .='<a href="#" class="btn btn-default btn-flat">Sign out</a>';
               $html .='</div>';
           $html .='</li>';
       $html .='</ul>';
   $html .='</li>';
   return $html;

}

function  getPageRecordNum($link,$table,$priKey,$fltr,$ordBy){
    $sql = "select count(".$priKey.") from ".$table;
        if( !empty($fltr) ){
            $sql .= " where ".implode(' and ',$fltr);
        }

        if( !empty($ordBy) ){
            $sql .= " ORDER BY ".$ordBy;
        }
    $data = $link->getObjectDataFromQuery($sql);
    return $data;
}

function  getPageRecordIds($link,$table,$priKey,$fltr,$ordBy){
    $sql = "select ".$priKey." from ".$table;
        if( !empty($fltr) ){
            $sql .= " where ".implode(' and ',$fltr);
        }

        if( !empty($ordBy) ){
            $sql .= " ORDER BY ".$ordBy;
        }
    $data = $link->getcolumnDataFromQuery($sql);
    return $data;
}

function getPageDataSet($link,$table,$priKey,$colList,$idList){
    if( !empty($idList) ){
        $sql = "select ".$colList." from ".$table." where ".$priKey." in (".implode(',',$idList).")";
        $data = $link->getRecordSetFromQuery($sql);
        return $data;
    }
 
}

function getChunckDataByPageSize($pageFullDataSet,$pageNum){
    $reletedPageIds = array();
    $dataSet = array_chunk($pageFullDataSet,$pageNum);
    $pageNumbers = count($dataSet);
    $i = 1;
    foreach ( $dataSet as $idSet){
        foreach( $idSet as $id){
            $reletedPageIds[$i][] = $id;
        }
        $i++;
    }
    return array($reletedPageIds,$pageNumbers);
}

?>