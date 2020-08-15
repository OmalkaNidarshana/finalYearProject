function addToCart(catId,cmpId){
    
   var url = $("#processPath").val()+'&catId='+catId+'&cmpId='+cmpId;
    $.ajax({
        type: "POST",
        url: url,
        success: function(data){
            location.reload();
        }
        
     });
}