<?php
    class OrderTableFormatter{
        
        var $link;
        var $userInfo;
        var $categoryIds = array();

        function OrderTableFormatter($link,$userInfo){
            $this->link = $link;
            $this->userInfo = $userInfo;
        }
        
        function setCategoryIds($categoryIds){
            $this->categoryIds = $categoryIds;
        }

        function formatters($id,$value,$data){
                    
             switch($id){
                case 'ORDER_NUM':
                    $formatter = '<a href="'.makeLocalUrl('orders/order_details.php','sec=ORDER&id='.$data['ORDER_ID']).'">'.$value.'</a>';
                break;
                case 'STATUS':
                    $formatter = OrdersStatusColorBox($value);
                break;
                case 'PRICE':
                case 'SPECIAL_PRICE':
                case 'SELL_PRICE':
                case 'DIS':
                    $formatter = formatCurrency($value);
                break;
                case 'ACTION':
                    if( in_array($data['RECORD_ID'],$this->categoryIds) ){
                        $formatter = '<span>'.getRawActionsIcon('cart','Alredy added to Order',false,true).'</span>';
                    }else{
                        $formatter = '<span onclick="addToCart(\''.$data['RECORD_ID'].'\',\''.$this->userInfo->cmpId.'\');">'.getRawActionsIcon('cart','Add To Cart').'</span>';
                    }
                    break;
                
                default:
                 $formatter = $value;
            }
            return $formatter;
        }








    }



?>