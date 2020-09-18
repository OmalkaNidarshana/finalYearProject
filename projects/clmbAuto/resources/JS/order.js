function submitOrder(id){
    
    var url = $("#orderProcessUrl").val()+'?orderId='+id+'&action=orderSubmit';
    
    $.ajax({
        type: "POST",
        url: url,
        success: function(data){
            location.reload();
        }
        
     });
}

function loadEditPopUp(id,lineId){
   var url = $("#orderProcessUrl").val()+'?orderId='+id+'&lineId='+lineId+'&action=editOrderLine';
    
    $.ajax({
        type: "POST",
        url: url,
        dataType: "JSON",
        success: function(data){
            //alert(data);
            $('#EDIT_LINE_POPUP').modal('show');
            $('#editLinePopUp').html(data);
        }
        
     });
}