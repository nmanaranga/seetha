$(document).ready(function(){

  $("#btnReset").click(function(){
    location.href="";
  });

  $("#btnPrint").click(function(){
    if($("#hid").val()=="0"){
      set_msg("Please load data before print");
      return false;
    }else{
     $("#qno").val(0);
     $("#bal_date").val($("#stdate").val());
     $("#bal_area").val($("#area").val());
     $("#print_pdf").submit();
   }  
 });



  $("#load").click(function(){
    load_det();
  });

  $("#tbl_tbdy").css("overflow-y","scroll");
  $("#tbl_tbdy").css("height","550px");



  $("#cluster").change(function(){

    var clust=$("#cluster").val();
    $.post("index.php/main/load_data/t_loyalty_card_request_approve/select_branch",{
      cluster:clust,

    },function(res){             
     $("#branch").html(res);
   },"text"

   );     
  });

  

});



function load_det(){
  loding();
  $.post("index.php/main/load_data/t_loyalty_card_request_approve/load_details",{
    cl: $("#cluster").val(),
    bc: $("#branch").val(),
  }, function(r){
    if (r.det!=2) {
      loding();
      var item_det="<table class='cashTb' style='width:1000px;padding:5px 0px;' >";
      item_det+="<tr><td style='width:10%;color:#fff;background:#476bb2;text-align:center;'>From Cl</td><td style='width:15%;color:#fff;background:#476bb2;text-align:center;'>From BC</td><td style='width:10%;color:#fff;background:#476bb2;text-align:center;'>No</td><td style='width:30%;color:#fff;background:#476bb2;text-align:center;'>Customer</td><td style='width:30%;color:#fff;background:#476bb2;text-align:center;'>Comments</td><td style='width:3%;color:#fff;background:#476bb2;text-align:center;'>&nbsp;</td></tr>";
      for(var x=0; x<r.det.length; x++){

        item_det+="<td style='border:1px solid #aaa;padding:3px 5px;text-align:left;'><input type='text' class='input_active' style='width:100%' id='0_"+x+"' name='0_"+x+"' value='"+r.det[x].from_cl+"' title='"+r.det[x].from_cl+"' readonly></td>";
        item_det+="<td style='border:1px solid #aaa;padding:3px 5px;text-align:left;'><input type='hidden' class='input_active' style='width:100%;text-align:left;' id='11_"+x+"' name='11_"+x+"' value='"+r.det[x].from_bc+"' title='"+r.det[x].from_bc+"' readonly><input type='text' class='input_active' style='width:100%;text-align:left;' id='1_"+x+"' name='1_"+x+"' value='"+r.det[x].bc_name+"' title='"+r.det[x].bc_name+"' readonly></td>";
        item_det+="<td style='border:1px solid #aaa;padding:3px 5px;text-align:right;'><input type='text' class='input_active' style='width:100%;text-align:right;' id='2_"+x+"' name='2_"+x+"' value='"+r.det[x].nno+"' title='"+r.det[x].nno+"' readonly></td>";
        item_det+="<td style='border:1px solid #aaa;padding:3px 5px;text-align:left;'><input type='text' class='input_active' style='width:100%;text-align:left;' id='3_"+x+"' name='3_"+x+"' value='"+r.det[x].cus_code+"-"+r.det[x].name+"' title='"+r.det[x].cus_code+"-"+r.det[x].name+"' readonly></td>";
        item_det+="<td style='border:1px solid #aaa;padding:3px 5px;text-align:left;'><input type='text' class='input_active' style='width:100%;text-align:left;' id='4_"+x+"' name='4_"+x+"' value='"+r.det[x].comments+"' title='"+r.det[x].comments+"' readonly></td>";
        item_det+="<td style='border:1px solid #aaa;padding:3px 5px;text-align:right;'><input type='checkbox' class='input_active chk' style='width:100%;text-align:right;' id='chk_"+x+"' name='chk_"+x+"' value='' title='' checked></td>";
        item_det+="</tr>";
      }
      item_det+="<input type='hidden' class='input_txt' id='count' name='count' title='"+r.det.length+"' value='"+r.det.length+"' style='width: 100%; height:20px;' />";

      item_det+="</table>";
    }else{
     $("#tbl_tbdy").text("No Data");
   }


   $("#tbl_tbdy").html(item_det);

 }, "json");
}




function save(){
  var frm = $('#form_');
  $("#qno").val($("#id").val());
  loding();
  $.ajax({
   type: frm.attr('method'),
   url: frm.attr('action'),
   data: frm.serialize(),
   success: function (pid){
    if(pid == 1){
      $("#btnSave").attr("disabled",true);
      alert("Save Completed");
      /*if(confirm("Save Completed, Do You Want A print?")){
        $("#print_pdf").submit();
        reload_form();
      }else{*/
        location.href="";
     // }
   }else if(pid == 2){
    alert("No permission to add data.");
  }else if(pid == 3){
    alert("No permission to edit data.");
  }else{
    alert("Error : \n"+pid);
  }

}
});
}

function reload_form(){
  setTimeout(function(){
    location.href= '';
  },50); 
}



function validate(){

  return true;

}


function select_search(){
  $("#pop_search").focus();

}
