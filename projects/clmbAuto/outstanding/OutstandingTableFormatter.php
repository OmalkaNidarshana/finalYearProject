<?php
    class OutstandingTableFormatter{
        
        var $link;
        var $userInfo;
        var $categoryIds = array();

        function OutstandingTableFormatter($link,$userInfo){
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
                case 'TOTAL_AMMOUNT':
                case 'PAID_AMOUNT':
                case 'DUE_AMOUNT':
                    $formatter = formatCurrency($value);
                break;
                case 'ACTION':
                    $formatter = '<span><a href="'.makeLocalUrl('outstanding/outstanding_history.php','invNum='.$data['INV_NUM'].'&sec=OUT_STAND_HISTORY').'">'.getRawActionsIcon('view','View History',false,false).'</a></span>';
                break;
                default:
                 $formatter = $value;
            }
            return $formatter;
        }








    }



?>