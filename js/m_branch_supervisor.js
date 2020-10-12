$(document).ready(function(){

 $("#btnPrint").click(function(){
                $("#print_pdf").submit();
        });
  
});




function validate(){
	return true;
}


function loadTableData(){

    if($("#txtMonth").val().length > 0){

       var y = $("#txtMonth").val().split("-")[0];
       var m = $("#txtMonth").val().split("-")[1];
       var dayCount = new Date(y,m,1,-1).getDate();

       $("#txtDayC").val(dayCount);
       $("#txtDate").val($("#txtMonth").val());

       $.post("index.php/main/get_data/t_sales_target",{
          year : y ,
          month : m ,
          dayCount : dayCount
      },function(D){
        $(".list_div").html(D.T);
        $("#txtStatus").val(D.ST);
        $("#txtSumid").val(D.SMID);

    },"json");
   }else{
    alert("Please select Date");
}
}

function save(){
    var frm = $('#form_');
  
    $.ajax({
  type: frm.attr('method'),
  url: frm.attr('action'),
  data: frm.serialize(),
  success: function (pid){
            sucess_msg();

          
        }
    });
}









