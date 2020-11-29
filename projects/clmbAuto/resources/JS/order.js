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

function loadModelList(){
    var brsikCode = $('#BRISK').val();
    var url = $("#loadItemDataUrl").val()+'?&action=loadModelList&brsikCode='+brsikCode;
    $.ajax({
        type: "POST",
        url: url,
        //data: postData,
        //dataType: "JSON",
        
        success: function(data){
            var option = ('<option value=""></option>');
            $.each( JSON.parse(data), function(key, value) { // or use data without  JSON.parse(data) 
               option+=('<option value="'+ value +'">'+ value +'</option>');
            });
            $('#MODEL').html(option);
        }
        
    });
}

function loadItemData(){
    var selectedModel = $('#MODEL').val();
    var brisk = $('#BRISK').val();
    var url = $("#loadItemDataUrl").val()+'?&action=loadItemData&model='+selectedModel+'&brisk='+brisk;
    
     $.ajax({
        type: "POST",
        url: url,
        //data: lectedItem,
        //dataType: "JSON",
        success: function(data){
            data = $.parseJSON(data);
            
            for(var i=0; i<data.length; i++){
                var recId = data[i]["RECORD_ID"];
                var rowIsexit = $('#ordrCreation').find('#row_'+recId).length;
                if( rowIsexit > 0 ){
                    alert('This item is already added');
                    $('#ordrCreation').find('#row_'+recId).addClass('blink');
                    setTimeout(function(){
                        $('#ordrCreation').find('#row_'+recId).removeClass('blink');
                     },1000);
                   
                }else{
                    var row = $("<tr id ='row_"+recId+"'/>");
                    row.append($("<td class='summarytable'>" + data[i]["BRAND"] + "</td>"));
                    row.append($("<td class='summarytable'>" + data[i]["MODEL"] + "</td>"));
                    row.append($("<td class='summarytable'>" + data[i]["VEHICAL_CODE"] + "</td>"));
                    row.append($("<td class='summarytable'>" + data[i]["CC"] + "</td>"));
                    row.append($("<td class='summarytable'>" + data[i]["BRISK"] + "</td>"));
                    row.append($("<td class='summarytable'>" + data[i]["BRISK_CODE"] + "</td>"));
                    row.append($("<td class='summarytable'>" + data[i]["DENSO"] + "</td>"));
                    row.append($("<td class='summarytable'>" + data[i]["IRIDIUM"] + "</td>"));
                    row.append($("<td class='summarytable'><textarea name='desk["+data[i]["RECORD_ID"]+"]'rows='1' cols='25' /></td>"));
                    row.append($("<td class='summarytable'><input type='number' name='qty["+recId+"]' value='1'/></td>"));
                    row.append($("<td class='summarytable deleteIcon' onclick='deleteInlineItem("+recId+");' title='Delete this line'>X<td>"));
                    $("#ordrCreation").append(row);
                }
            }
            
            //location.reload();

        }
        
    });
}

function deleteInlineItem(recId){
    $('#row_'+recId).remove();
}