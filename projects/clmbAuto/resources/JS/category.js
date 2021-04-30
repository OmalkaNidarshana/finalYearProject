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

 function saveEditLine(){
    var url = $("#editCategoryProcess").val()+'?action=saveEditLine';
    var postdata = $("#EDIT_CATEGORY_FORM").serialize();
    $.ajax({
         type: "POST",
         url: url,
         data: postdata,
         //dataType: "JSON",
         success: function(data){
            //location.reload();
         }
         
    });
 }

 function addCategory(){
   var url = $("#addCategory").val();
   var postdata = $("#ADD_CATEGORY_FORM").serialize();
   $.ajax({
        type: "POST",
        url: url,
        data: postdata,
        dataType: "JSON",
        success: function(data){
           location.reload();
        }
        
   });

 }

 
 function deleteItem($id){
   if (confirm('Are you sure, Do you want to delete this Company')) { 
      var url = $("#editCategoryProcess").val()+'?action=deleteItem&catId='+$id;
      $.ajax({
         type: "POST",
         url: url,
         //data: postdata,
         dataType: "JSON",
         success: function(data){
            location.reload();
         }
         
      });
   }
 }

