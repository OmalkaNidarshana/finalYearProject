<?php
    class CategoryTableFomatter{
        
        var $link;
        
        function CategoryTableFomatter($link){
            $this->link = $link;

        }
        
        function formatters($id,$data){
            switch($id){
                case 'BRAND':
                    $formatter = '<span align="right">'.$data.'</span>';
                break;
                default:
                 $formatter = $data;
            }
            return $formatter;
        }








    }



?>