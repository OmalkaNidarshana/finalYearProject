<?php

class Script{
    
    var $link;
    var $regulerSearch = array();
    var $customSearch;
    var $srchCriteria;
    var $srchDataArr = array();
    var $srchFlds;
    var $fldDefinition;

    function Script($link,$columns,$fldDefinition){
        $this->link = $link;
        $this->columns = $columns;
        $this->fldDefinition = $fldDefinition;

    }

    function setRegulerSearch($regulerSearch){
        $this->regulerSearch = $regulerSearch;
    }

    function setCustomSearch($customSearch){
        $this->customSearch = $customSearch;
    }

    function analysRegulerSearch(){
        if( !empty($this->regulerSearch) ){
            foreach($this->regulerSearch as $srchFlds=>$srchVal){
                if( empty($srchVal) )
                    continue;
                $this->srchCriteria[] = $srchFlds."=".getTextValue($srchVal);
            }
        }
        
        return $this->srchCriteria;
    }

    function analysCustomSearch(){
        if( !empty($this->customSearch) ){
            foreach($this->columns as $col){
                $srchdata[] = $col." = ".getTextValue($this->customSearch);
            }
            $this->srchCriteria[] = implode(" or ",$srchdata);
        }
        return $this->srchCriteria;
    }

    

}







?>
