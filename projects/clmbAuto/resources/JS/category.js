function addToCart(catId,cmpId){
    
   var url = $("#processPath").val()+'&catId='+catId+'&cmpId='+cmpId;
    $.ajax({
        type: "POST",
        url: url,
        success: function(data){
            //var f = document.getElementById('headerLvldata');
           
            $(headerLvldata).css({"box-shadow": "0px 0px  #6b8e23"}).delay(100);
            $(headerLvldata).css({"box-shadow":"2px 2px  15px #6b8e23"});
            
        }
        
     });
}