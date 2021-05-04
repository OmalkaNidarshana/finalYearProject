function addInvoice(){
    var url = $("#invoiceProcessUrl").val()+'?action=addInvoice';
    var postData = $('#INV_ADD').serialize();
    $.ajax({
        type: "POST",
        url: url,
        data:postData,
        success: function(data){
            location.reload();
        }
         
    });
 }

 function paidInv(invId){
    var url = $("#invoiceProcessUrl").val()+'?action=paidInv&invId='+invId;
    $.ajax({
        type: "POST",
        url: url,
        //data:postData,
        success: function(data){
            location.reload();
        }
        
    });
 }

 function loadOutstandingPopUp(id){
    var url = $("#invoiceProcessUrl").val()+'?invId='+id+'&action=loadOutstandingPopUp';
     
     $.ajax({
         type: "POST",
         url: url,
         dataType: "JSON",
         success: function(data){
             //alert(data);
             $('#OUTSTANDING_ORDER_POPUP').modal('show');
             $('#outstandingPopUp').html(data);
         }
         
      });
 }

 function saveOutstanding(){
    var url = $("#invoiceProcessUrl").val()+'?action=saveOutstanding';
    var postData = $('#OUTSTANDING_DATA').serialize();
    $.ajax({
        type: "POST",
        url: url,
        data:postData,
        success: function(data){
            location.reload();
        }
         
    });
 }
 