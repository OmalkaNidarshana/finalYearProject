function addAmount(){
    var url = $("#outstandingProcessPath").val()+'?action=addAmount';
    var postData = $('#ADD_AMOUNT').serialize();
    $.ajax({
        type: "POST",
        url: url,
        data:postData,
        success: function(data){
            location.reload();
        }
         
    });
 }