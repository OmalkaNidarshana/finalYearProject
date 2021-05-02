<?php
    class RejectedOrderTableFormatter{
        
        var $link;
        var $userInfo;
        var $categoryIds = array();

        function RejectedOrderTableFormatter($link,$userInfo){
            $this->link = $link;
            $this->userInfo = $userInfo;
        }
        
        function setCategoryIds($categoryIds){
            $this->categoryIds = $categoryIds;
        }

        function formatters($id,$value,$data){
            
                    
             switch($id){
                case 'CUSTOMER_ID':
                    $customerData = getCompanyDataByCmpId($this->link,$data['CUSTOMER_ID']);
                    if( !empty($customerData) ){
                        $formatter = $customerData['COMPANY_NAME'];
                    }else{
                        $formatter = '';
                    }
                break;
                case 'STATUS':
                    $formatter = OrdersStatusColorBox($value);
                break;
                case 'PRICE':
                case 'SPECIAL_PRICE':
                case 'SELL_PRICE':
                case 'TOTAL':
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