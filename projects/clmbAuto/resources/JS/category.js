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

function loadCatEditPopUp(id){
    var url = $("#editCategoryProcess").val()+'?catId='+id+'&action=editCategory';
    alert(url);
     $.ajax({
         type: "POST",
         url: url,
         dataType: "JSON",
         success: function(data){
             //alert(data);
             $('#EDIT_CATEGORY_POPUP').modal('show');
             $('#editCatPopUp').html(data);
         }
         
      });
 }