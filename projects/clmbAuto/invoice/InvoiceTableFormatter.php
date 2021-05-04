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
                case 'PAID_AMOUNT':   
                    $formatter = formatCurrency($value);
                break;
                case 'ACTION':
                    $formatter ='';
                    if($data['STATUS'] == 'PENDING' ){
                        $formatter = '<span>'.HTML::submitButtonFeild('invoice_paid','Paid',array('style'=>'margin-bottom:5px; width: 72px;','onclick'=>'paidInv('.$data['INV_ID'].');')).'</span>';
                        if($data['PAYMENT_METHOD'] == 'CASH'){
                            $formatter .= '<span>'.HTML::submitButtonFeild('invoice_outstanding','Outstanding',array('style'=>'width: 72px; background-color: maroon;','onclick'=>'loadOutstandingPopUp('.$data['INV_ID'].');')).'</span>';
                        }
                    }
                    break;
                
                default:
                 $formatter = $value;
            }
            return $formatter;
        }








    }



?>