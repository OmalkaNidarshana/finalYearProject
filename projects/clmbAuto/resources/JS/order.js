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
/*RECORD_ID":"47","BRAND":"DAIHATSU","MODEL":"HIJET","VEHICAL_CODE":"S200P","ENGINE":"EF-VE","CC":"660","BRISK":"A-LINE 11","BRISK_CODE":"DR15YCY-1",
"DENSO":"K20PR-U11","IRIDIUM":"","STOCK_NO":"27","PRICE":"364.00","DIS":"30","SPECIAL_PRICE":"520.00","SELL_PRICE":"550.00","COMMISION":"0.00"*/

function loadItemData(){
    var lectedItem = $('#BRISK').val();
    var url = $("#loadItemDataUrl").val()+'?&action=loadItemData&brisk='+lectedItem;
    
     $.ajax({
        type: "POST",
        url: url,
        //data: lectedItem,
        //dataType: "JSON",
        success: function(data){
            data = $.parseJSON(data);
            var row = $("<tr />")
            $("#ordrCreation").append(row);
            row.append($("<td class='summarytable'>" + data["BRAND"] + "</td>"));
            row.append($("<td class='summarytable'>" + data["MODEL"] + "</td>"));
            row.append($("<td class='summarytable'>" + data["VEHICAL_CODE"] + "</td>"));
            row.append($("<td class='summarytable'>" + data["CC"] + "</td>"));
            row.append($("<td class='summarytable'>" + data["BRISK"] + "</td>"));
            row.append($("<td class='summarytable'>" + data["BRISK_CODE"] + "</td>"));
            row.append($("<td class='summarytable'>" + data["DENSO"] + "</td>"));
            row.append($("<td class='summarytable'>" + data["IRIDIUM"] + "</td>"));
            row.append($("<td class='summarytable'><textarea name='desk["+data["RECORD_ID"]+"]'rows='1' cols='25' /></td>"));
            row.append($("<td class='summarytable'><input type='number' name='qty["+data["RECORD_ID"]+"]' value='1'/></td>"));
            
            //location.reload();

        }
        
    });
}