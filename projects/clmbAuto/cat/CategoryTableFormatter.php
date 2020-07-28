<?php
    class CategoryTableFomatter{
        
        var $link;
        
        function CategoryTableFomatter($link){
            $this->link = $link;

        }
        
        function formatters($id,$value,$data){
             switch($id){
                case 'BRAND':
                    $formatter = '<span align="right">'.$value.'</span>';
                break;
                case 'PRICE':
                case 'SPECIAL_PRICE':
                case 'SELL_PRICE':
                case 'DIS':
                    $formatter = formatCurrency($value);
                break;
                case 'ACTION':
                    $formatter = '<span onclick="addToCart(\''.$data['RECORD_ID'].'\');">'.getRawActionsIcon('cart','Add To Cart').'</span>';
                break;
                
                default:
                 $formatter = $value;
            }
            return $formatter;
        }








    }



?>