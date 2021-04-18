<?php
    class InvoiceTableFormatter{
        
        var $link;
        var $userInfo;
        var $categoryIds = array();

        function InvoiceTableFormatter($link,$userInfo){
            $this->link = $link;
            $this->userInfo = $userInfo;
        }
        
        function setCategoryIds($categoryIds){
            $this->categoryIds = $categoryIds;
        }

        function formatters($id,$value,$data){
                    
             switch($id){
                case 'INV_NUM':
                    $formatter = '<a href="'.makeLocalUrl('invoice/invoice_details.php','sec=INVOICE&id='.$data['INV_ID']).'">'.$value.'</a>';
                break;
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
                case 'ADDITIONAL_DISSCOUNT':
                    $formatter = $value.'%';
                break;
                case 'AMMOUNT':
                case 'NET_AMMOUNT':
                case 'SELL_PRICE':
                case 'TOTAL':
                case 'DISCOUNT':
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