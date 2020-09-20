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
   var url = $("#orderProcessUrl").val()+'?orderId='+id+'&lineId='+lineId+'&action=loadEditLineForm';
    
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

function saveEditLine(id){
    
    var lineId = $('#lineId').val();
    var url = $("#orderProcessUrl").val()+'?orderId='+id+'&lineId='+lineId+'&action=editOrderLine';
    var postData = $('#EDIT_LINE').serialize();
    $.ajax({
        type: "POST",
        url: url,
        data: postData,
        //dataType: "JSON",
        success: function(data){
            location.reload();

        }
        
     });
}
function deleteOrderLine(id,lineId){
    if (confirm('Are you sure, do you want to delete this line')) {   
        var url = $("#orderProcessUrl").val()+'?orderId='+id+'&lineId='+lineId+'&action=deleteOrdLine';
        $.ajax({
            type: "POST",
            url: url,
            //data: postData,
            //dataType: "JSON",
            success: function(data){
                location.reload();

            }
            
        });
    }
}