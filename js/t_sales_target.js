$(document).ready(function(){

  $("#btnLoad").click(function(){
    loadTableData();
  });

  $("#btnPrint").click(function(){
            // if($("#hid").val()=="0"){
            //   set_msg("Please load data before print");
            //   return false;
            // }else{
              $("#print_pdf").submit();
            // }  
          });
  


});

$(document).on("click","input",function() {
  $(".fot").click(function(e){ 

   calculateTotl();
 });

  $(".fot").keydown(function(e){ 
   if(e.keyCode==13){
     var fid=$(this).attr('id').split("target_")[1];
     var v= m_round(parseFloat($("#archment_"+fid).val())- parseFloat($("#target_"+fid).val()));
     $("#var_"+fid).val(v);
     var vp=m_round((v/parseFloat($("#target_"+fid).val()))*100);
     $("#varPre_"+fid).val(vp);
   }
 });

  $(".fot").blur(function(e){ 
   var fid=$(this).attr('id').split("target_")[1];
   var v= m_round(parseFloat($("#archment_"+fid).val())- parseFloat($("#target_"+fid).val()));
   $("#var_"+fid).val(v);
   var vp=m_round((v/parseFloat($("#target_"+fid).val()))*100);
   $("#varPre_"+fid).val(vp);
 });
});



function validate(){
	return true;
}

function calculateTotl(){

  var y = $("#txtMonth").val().split("-")[0];
  var m = $("#txtMonth").val().split("-")[1];
  var dayCount = new Date(y,m,1,-1).getDate();

  var x=0;

  for (var i=1; i<=dayCount; i++) {
   x+=parseFloat($("#target_"+i).val());
 }


 $("#target_tot").val(x);
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
    $("#txtCumilativeTar").val(D.cumlTar);
    $("#txtCumilativeArch").val(D.cumlArch);

    $("#txtCumVarince").val(D.cumlVar);
    $("#txtCumVarincePre").val(D.cumlVarPre+" %");

  },"json");
 }else{
  alert("Please select Date");
}
}





function save(){
  var frm = $('#form_');

  var cshrcpt = {
    pmtCash: [] ,
    
  };
  for (var i = 0; i <= 1000; i++) {
    cshrcpt.pmtCash.push({ 
      code   : "0001"+i,
      name1   : "Nimesh Duminda hathurusinghe",
      name2   : "Nimesh Duminda hathurusinghe",
      name3   : "Nimesh Duminda hathurusinghe",
      name4   : "Nimesh Duminda hathurusinghe",
      name5   : "Nimesh Duminda hathurusinghe",
      name6   : "Nimesh Duminda hathurusinghe",
      name7   : "Nimesh Duminda hathurusinghe",
      name8   : "Nimesh Duminda hathurusinghe",
      name9   : "Nimesh Duminda hathurusinghe",
      name10   : "Nimesh Duminda hathurusinghe",
      amount   : "5500.00"
    });
  }



  var dataStringx = JSON.stringify(cshrcpt);
  $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: {myData : dataStringx },
    success: function (re){
      $('#trnsIndx').val(re);
      alert('Receipt Saved ...');

    }
  });

}



function savexxx(){
	var frm = $('#form_');
        // loding();
        $.ajax({
        	type: frm.attr('method'),
        	url: frm.attr('action'),
        	data: frm.serialize(),
        	success: function (pid){

        		// alert(pid);
          //        location.href='';
          sucess_msg();

        }
      });
      }






      $(function() {
       $('.monthYearPicker').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm'
      }).focus(function() {
        var thisCalendar = $(this);
        $('.ui-datepicker-calendar').detach();
        $('.ui-datepicker-close').click(function() {
         var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
         var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
         thisCalendar.datepicker('setDate', new Date(year, month, 1));
         $("#txtmMonth").val($("#txtMonth").val());

       });

      });
    });



