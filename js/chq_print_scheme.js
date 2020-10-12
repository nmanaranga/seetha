var is_edit=0; 
$(document).ready(function(){

  $('#tabs').tabs();

  $("#code").blur(function(){
      check_code();
  });
    
  $("#btnSave1").click(function(){
    if(validate()){
      save();
    }  
  });

  $("#btnReset").click(function(){
   	location.href="?action=chq_print_scheme";
	});

   
  $("#btnPrint").click(function(){
      $("#print_pdf").submit();
  });

  $("#srchee").keyup(function(){  
  	$.post("index.php/main/load_data/utility/get_data_table", {
        code:$("#srchee").val(),
        tbl:"chq_print_scheme",
        tbl_fied_names:"Code-Name",
        fied_names:"code-name"

      }, function(r){
	        $("#grid_body").html(r);
  	  }, "text");
	});
	


});





function save(){
  var frm = $('#form_');
  loding();
  $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    success: function (pid){
      if(pid == 1){
          loding();
          sucess_msg();
      }else if(pid == 2){
          set_msg("No permission to add data.");
      }else if(pid == 3){
          set_msg("No permission to edit data.");
      }else{
          set_msg("Error : \n"+pid);
      }
    }
  });
}


function get_data_table(){
    $.post("index.php/main/load_data/m_customer/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}



function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/chq_print_scheme/check_code", {
        code : code
    }, function(res){
        if(res.data!= 2){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
            }
        }
    }, "json");
}




function validate(){
  if($("#code").val()=="" || $("#name").val()==""){
    set_msg("Please fill scheme code and name");
    return false;
  }else{
    return true;
  }  
}
    
function set_delete(code){
   if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/chq_print_scheme", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else{
                set_msg(res);
            }            
        }, "text");
    }
}



    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/chq_print_scheme", {
        code : code
    }, function(res){  
        $("#inactive").attr("checked",false);    
        $("#iscfprint").attr("checked",false);

        $("#code").val(res.data.code);
        $("#code_").val(res.data.code);
        $("#name").val(res.data.name);
        $("#bdata_x").val(res.data.bdata_x);
        $("#bdate_y").val(res.data.bdate_y);
        $("#bdate1_x").val(res.data.bdate1_x);
        $("#bdate2_x").val(res.data.bdate2_x);
        $("#bmonth1_x").val(res.data.bmonth1_x);
        $("#bmonth2_x").val(res.data.bmonth2_x);
        $("#byear1_x").val(res.data.byear1_x);
        $("#byear2_x").val(res.data.byear2_x);
        $("#byear3_x").val(res.data.byear3_x);
        $("#byear4_x").val(res.data.byear4_x);
        $("#payee_x").val(res.data.payee_x);
        $("#payee_y").val(res.data.payee_y);
        $("#paylength").val(res.data.paylength);
        $("#paygap").val(res.data.paygap);
        $("#crossing_x").val(res.data.crossing_x);
        $("#crossing_y").val(res.data.crossing_y);
        $("#crossonly_x").val(res.data.crossonly_x);
        $("#crossonly_y").val(res.data.crossonly_y);
        $("#amw_x").val(res.data.amw_x);
        $("#amw_y").val(res.data.amw_y);
        $("#amwlength").val(res.data.amwlength);
        $("#amountgap").val(res.data.amountgap);
        $("#starlength").val(res.data.starlength);
        $("#second_line_x").val(res.data.second_line_x);
        $("#second_line_y").val(res.data.second_line_y);
        $("#amt_x").val(res.data.amt_x);
        $("#amt_y").val(res.data.amt_y);
        $("#stamp_x").val(res.data.stamp_x);
        $("#stamp_y").val(res.data.stamp_y);
        $("#cfdate_x").val(res.data.cfdate_x);
        $("#cfdate_y").val(res.data.cfdate_y);
        $("#cfrealidate_x").val(res.data.cfrealidate_x);
        $("#cfrealidate_y").val(res.data.cfrealidate_y);
        $("#cfpay_x").val(res.data.cfpay_x);
        $("#cfpay_y").val(res.data.cfpay_y);
        $("#cfpaylength").val(res.data.cfpaylength);
        $("#cfnote_x").val(res.data.cfnote_x);
        $("#cfnote_y").val(res.data.cfnote_y);
        $("#cfnotelength").val(res.data.cfnotelength);
        $("#cfamount_x").val(res.data.cfamount_x);
        $("#cfamount_y").val(res.data.cfamount_y);

        $("#code").attr("readonly",true);
        if(res.data.inactive=="1"){
          $("#inactive").attr("checked",true);
        }
        if(res.data.iscfprint=="1"){
          $("#iscfprint").attr("checked",true);
        }
        loding(); 
    }, "json");
}


