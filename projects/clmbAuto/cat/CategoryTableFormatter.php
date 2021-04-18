<?php
    class CategoryTableFomatter{
        
        var $link;
        var $userInfo;
        var $categoryIds = array();

        function CategoryTableFomatter($link,$userInfo){
            $this->link = $link;
            $this->userInfo = $userInfo;
        }
        
        function setCategoryIds($categoryIds){
            $this->categoryIds = $categoryIds;
        }

        
        function formatters($id,$value,$data){
                    
             switch($id){
                case 'BRAND':
                    $formatter = '<span align="right">'.$value.'</span>';
                break;
                case 'PRICE':
                case 'SPECIAL_PRICE':
                case 'SELL_PRICE':
                    $formatter = formatCurrency($value);
                break;
                case 'COMMISION':
                    $formatter = $value.'%';
                break;
                case 'ACTION':
                    $formatter = '<span onclick="loadCatEditPopUp('.$data['RECORD_ID'].')">'.getRawActionsIcon('edit','Edit Item').'</span>';
                    $formatter .= '<span>'.getRawActionsIcon('delete','Delete Item').'</span>';
                break;
                
                default:
                 $formatter = $value;
            }
            return $formatter;
        }








    }



?>