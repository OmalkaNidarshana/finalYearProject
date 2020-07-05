<?php

class Main{
    var $link;

    function Main($link){
        $this->link = $link;
    }

    function getManufactuerLogos(){
        $html = '';
        $logos = array();
        $logos[] = '<img src="http://'.IMG_ROOT.'brand/toyota_main.png" class="mainManufLogo">';
        $logos[] = '<img src="http://'.IMG_ROOT.'brand/honda_main.png" class="mainManufLogo">';
        $logos[] = '<img src="http://'.IMG_ROOT.'brand/mitsubishi_main.png" class="mainManufLogo">';
        $logos[] = '<img src="http://'.IMG_ROOT.'brand/daihatsu_main.png" class="mainManufLogo">';
        

        foreach($logos as $logo){
            $html .= imgPanelBox($logo);
        }
        return contentBorder($html,'Brands');
    }

    function getCustomerLogos(){
        return contentBorder('No data','Our Partners');

    }


}

?>