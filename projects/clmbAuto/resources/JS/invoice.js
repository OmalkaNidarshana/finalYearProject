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