
function toggleRegulerSearch(){
    var e = document.getElementById('REGULER_SEARCH');
    if(e.style.display == 'block')
       e.style.display = 'none';
    else
       e.style.display = 'block';
    //document.getElementById('REGULER_SEARCH').style.display = "none";
}

function addUser(){

   var frstName = $("#FIRST_NAME").val();
   var lstName = $("#LST_NAME").val();
   var userName = $("#USER_NAME").val();
   var submit = true;

   if (frstName == null || frstName == "") {
      nameError = "First Name Cannot be empty.";
      $("#FIRST_NAME").attr('placeholder',nameError);
      $("#FIRST_NAME").addClass('red');
      $("#FIRST_NAME").css("border", "1px solid red");
      var submit = false;
  }
  if(lstName == null || lstName == ""){
      nameError = "Last Name Cannot be empty.";
      $("#LST_NAME").attr('placeholder',nameError); 
      $("#LST_NAME").addClass('red');
      $("#LST_NAME").css("border", "1px solid red");

      var submit = false;
  }
  if(userName == null || userName == ""){
      nameError = "User Name Cannot be empty.";
      $("#USER_NAME").attr('placeholder',nameError);
      $("#USER_NAME").addClass('red');
      $("#USER_NAME").css("border", "1px solid red");
      var submit = false;
  }
  
   var url = $("#processPath").val();
   var formData = $("#ADD_USER_FORM").serialize();

   if(submit == true){
      $.ajax({
         type: "POST",
         url: url,
         data: formData,
         dataType: 'json', 
         success: function(data){
            //data = $.parseJSON(data);
            var keys = Object.keys(data);
            if( keys == 'userName'){
               $('#USER_NAME').val("");
               $("#USER_NAME").attr('placeholder',data.userName);
               $("#USER_NAME").addClass('red');
               $("#USER_NAME").css("border", "1px solid red");
            }//else{
               location.reload(true);
           // }
            
         }
         
      });
   }

}