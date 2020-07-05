<?php
    class TableFormatter{
        
        var $link;

        function TableFormatter($link){
            $this->link = $link;

        }
        
        function formatters($id,$data){
            switch($id){
                case 'BRAND':
                    $formatter = '<span align="left">'.$data.'</span>';
                break;
                default:
                 $formatter = $data;
            }
            return $formatter;
        }








    }



?>