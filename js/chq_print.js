$(document).ready(function(){

  var p_code = window.location.search.split('=')[2];
  if(p_code != undefined){
    var no = p_code.split("|");
    var chq_no = no[1];
    var nno = no[0]; 
    var t_no = no[2];        

    load_voucher_data(nno,chq_no,t_no);

  }

  $("#btnReset").click(function(){
    location.href="index.php?action=chq_print";
  });

  $("#btnPrint").click(function(){
    if($("#hid").val()=="0"){
      set_msg("Please load data before print","error");
      return false;
    }else{
      $("#print_pdf").submit();
    }
  });

  $("#btndelete").click(function(){
    set_delete();
  });

  $("#id").keydown(function(e){
            //alert(e.keycode);
            if(e.keyCode ==13){
              load_data($(this).val());
            }
          });

  $("#cash_cheque_id").click(function(){
    if($("#cash_cheque_id").is(':checked')){
      $("#CF_Name_des").val("Cash");
      $("#Payee_No_des").val("Cash");
    }else{
      $("#CF_Name_des").val($("#payee_hid").val());
      $("#Payee_No_des").val($("#cf_hid").val());


    }
  });
});



function save(){
  var frm = $('#form_');
  $("#acc_code").val($("#acc_No").val());
  $("#qno").val($("#id").val());
  $("#p_amount").val($("#amount").val());
  loding();
  $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    success: function (pid){
      if(pid == 1){
        loding();
        setTimeout("$('#print_pdf').submit();", 100);          
          //location.href="index.php?action=chq_print";
        }else if(pid == 2){
          set_msg("No permission to add data.");
        }else if(pid == 3){
          set_msg("No permission to edit data.");
        }else{
          loding();
          set_msg("Error : \n"+pid);
        }
      }
    });
}



function validate(){   
  return true;
}

function set_delete(){
  var code = $("#hid").val();  
  if(confirm("Are you sure delete "+code+"?")){
    loding();
    $.post("index.php/main/delete/chq_print", {
      hid : code,
    }, function(res){
      if(res == 1){
        alert("Record Deleted Successfully");
        location.href="index.php?action=chq_print";
      }else if(res == 2){
        alert("No permission to delete data.");
      }else{
        set_msg(res);
      }
      loding();
    }, "text");
  }
}


function load_voucher_data(id,chq,t_no){

  loding();
  if(t_no=="19"){
    var path ="index.php/main/load_data/chq_print/load_voucher"
  }else if(t_no=="48"){
    var path ="index.php/main/load_data/chq_print/load_genaral_voucher"
  } else{
    var path ="index.php/main/load_data/chq_print/load_transfer_voucher"
  }
  $.post(path, {
    id : id,
    chq_no: chq
  }, function(r){
   loding(); 
   if(r!="2"){
     $("#date").removeClass('input_date_down_future');
     $("#bank_date").removeClass('input_date_down_future'); 

     $("#voucher_id").val(r[0].nno);
     $("#date").val(r[0].ddate);
     $("#acc_No").val(r[0].bank);
     $("#acc_No_des").val(r[0].description);
     $("#cheque_id").val(r[0].cheque_no);
     $("#bank_date").val(r[0].cheque_date);
     if(t_no=="19" || t_no=="131"){
       $("#Payee_No").val(r[0].acc_code);
     }
     $("#Payee_No_des").val(r[0].name);
     $("#CF_Name_des").val(r[0].name);
     $("#amount").val(m_round(r[0].payment));
     $("#payee_hid").val(r[0].name);
     $("#cf_hid").val(r[0].name);          
   }

 }, "json");
}



function load_data(id){
  loding();
  $.post("index.php/main/load_data/chq_print/get_data", {
    no : id,
  }, function(r){  
    if(r=="2"){
      loding();
      set_msg("No Records");
    }else{
      loding();
      $("#id").attr("readonly",true)
      $("#cash_cheque_id").attr("checked",false);
      $("#bank_date_id").attr("checked",false);
      $("#cross_cheq_id").attr("checked",false);

      $("#id").val(r[0].nno);
      $("#hid").val(r[0].nno);
      $("#qno").val(r[0].nno);
      $("#date").val(r[0].ddate);
      $("#voucher_id").val(r[0].voucher_no);
      $("#acc_No").val(r[0].acc_no);
      $("#acc_No_des").val(r[0].acc_name);
      $("#cheque_id").val(r[0].chq_no);
      $("#cross_word").val(r[0].cross_word);
      $("#bank_date").val(r[0].bank_date);
      $("#Payee_No").val(r[0].payee_id);
      $("#Payee_No_des").val(r[0].payee_name);
      $("#CF_Name_des").val(r[0].payee_name);            
      $("#description").val(r[0].description);
      $("#amount").val(r[0].amount);
      $("#acc_code").val(r[0].acc_no);
      $("#p_amount").val(r[0].amount);

      $("#payee_hid").val(r[0].payee_name);
      $("#cf_hid").val(r[0].payee_name);   


      if(r[0].is_cancel==1){
        $("#btndelete").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
      }

      if(r[0].is_cash_cheque==1){
        $("#cash_cheque_id").attr("checked",true);
      }
      if(r[0].is_bank_date==1){
        $("#bank_date_id").attr("checked",true);
      }
      if(r[0].is_cross_cheq==1){
        $("#cross_cheq_id").attr("checked",true);
      }

    } 

  }, "json");
}

