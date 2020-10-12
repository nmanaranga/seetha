$(document).ready(function(){
  $("#code").blur(function(){
    check_code();
  });
  $("#chq_type").val("1");
  $("#chq_rf_type").val("1");
  $("#drn").attr("disabled",true);
  $("#unsettle").attr("disabled",true);
  $("#refu_rt").attr("disabled",true);
  $("#refu_pd").attr("disabled",true);

  $("#chq_return").click(function(){
    if($("#chq_return").is(":checked")){
      $("#chq_type").val("1");
      $("#drn").attr("disabled",true);
      $("#unsettle").attr("disabled",true);
      $("#drn").attr("checked",true);
      $("#refu_rt").attr("disabled",true);
      $("#refu_pd").attr("disabled",true);
      $("#refu_rt").attr("checked",true);
    }
  });

  $("#chq_refund").click(function(){
    if($("#chq_refund").is(":checked")){
      $("#chq_type").val("2");
      $("#drn").attr("disabled",false);
      $("#unsettle").attr("disabled",false);
      $("#refu_rt").attr("checked",true);
      $("#refu_pd").attr("disabled",false);
      $("#refu_rt").attr("disabled",false);
    }
  });

  $(".ref_chq").click(function(){
    if($("#refu_rt").is(":checked")){
      $("#chq_rf_type").val("1");
    }else{
     $("#chq_rf_type").val("2");
   }
 });

  $("#btnDelete").click(function(){
    set_delete($("#id").val());
  });

  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });

  $(".sc").tableScroll({height:355});

  $("#id").keydown(function(e){
    if(e.keyCode == 13){
      load_data($(this).val());
    }
  })

  $("#code").blur(function() {
    check_code();
  });
  $("#tabs").tabs();

  $("#cheque_no").keydown(function(e){

    if(e.keyCode == 112){
      $("#pop_search").val($("#cheque_no").val());
      make_delay('load_cheque();');
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search').focus();", 100);
    }
    $("#pop_search").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        make_delay('load_cheque();');
      }
    }); 
    if(e.keyCode == 46){
      $("#cheque_no").val("");
      $("#bank").val("");
      $("#bank_des").val("");
      $("#cheque_val_1").val("");
      $("#cheque_val_2").val("");
      $("#account").val("");
      $("#load_no").val("");
      $("#merchant_id").val("");
      empty_credit_sale_grid();
    }
  });

  $("#memo").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search10").val();
      load_chq_return(1);
      $("#serch_pop10").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search10').focus();", 100);
    }
    $("#pop_search10").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_chq_return(1);
      }
    }); 
    if(e.keyCode == 46){
      $("#memo").val("");
    }
  });

  $("#dr_acc").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search13").val($("#dr_acc").val());
      load_acc(1);
      $("#serch_pop13").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search13').focus();", 100);
    }
    $("#pop_search13").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_acc(1);
      }
    }); 
    if(e.keyCode == 46){
      $("#dr_acc").val("");
      $("#dr_acc_des").val("");
    }
  });

  $("#cr_acc").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search14").val($("#cr_acc").val());
      load_acc2(2);
      $("#serch_pop14").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search14').focus();", 100);
    }
    $("#pop_search14").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_acc2(2);
      }
    }); 
    if(e.keyCode == 46){
      $("#cr_acc").val("");
      $("#cr_acc_des").val("");
    }
  });

  $(".amt").keyup(function(){
   total_amounts()
 });




});


function load_chq_return(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"r_return_reason",
    field:"code",
    field2:"description",
    preview2:"Return Reason Name",
    add_query: " AND type='3'",
    search : $("#pop_search10").val() 
  }, 
  function(r){ 
    $("#sr10").html(r);
    settings_chq_return();            
  }, "text");
}

function settings_chq_return(){
  $("#item_list .cl").click(function(){        
    $("#memo").val($(this).children().eq(1).html());
    $("#pop_close10").click(); 
  })    
}

function total_amounts(){
  if($("#cqh_ret_charge").val()!=""){
    var chq_return = parseFloat($("#cqh_ret_charge").val());
  }else{
    var chq_return = parseFloat(0);
  }
  if($("#other_charge").val()!=""){
    var other = parseFloat($("#other_charge").val());
  }else{
    var other = parseFloat(0);
  }
  if($("#cheque_val_1").val()!=""){
    var chq_val = parseFloat($("#cheque_val_1").val());
  }else{
    var chq_val = parseFloat(0);
  }

  var ful_tot = parseFloat(0);

  ful_tot = chq_return+other+chq_val;

  $("#tot_charge").val(m_round(ful_tot));
}

function load_acc(no){

  if($("#chq_type").val()=="2"){
    var tbl = "m_customer";
    var col_name ="name";
    var p_name ="Name";
  }else{
    var tbl = "m_account";
    var col_name ="description";
    var p_name ="Description";
  }

  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:tbl,
    field:"code",
    field2:col_name,
    preview2:p_name,
    search : $("#pop_search13").val() 
  }, function(r){
    $("#sr13").html(r);
    settings_acc(no);            
  }, "text");
}


function load_acc2(no){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"m_account",
    field:"code",
    field2:"description",
    preview2:"Description",
    search : $("#pop_search14").val() 
  }, function(r){
    $("#sr14").html(r);
    settings_acc(no);            
  }, "text");
}

function settings_acc(no){
  if(no==1){
    $("#item_list .cl").click(function(){        
      $("#dr_acc").val($(this).children().eq(0).html());
      $("#dr_acc_des").val($(this).children().eq(1).html());
      $("#pop_close13").click();  
    })   
  }else{
    $("#item_list .cl").click(function(){        
      $("#cr_acc").val($(this).children().eq(0).html());
      $("#cr_acc_des").val($(this).children().eq(1).html());
      $("#pop_close14").click();  
    })  
  }
}

function load_cheque(){
  $.post("index.php/main/load_data/t_cheques/cheque_list", {
    search  : $("#pop_search").val(),
    type    : $("#type").val(),
    chk_type: $("#chq_type").val(),
    ref_type: $("#chq_rf_type").val(),
  }, function(r){
    $("#sr").html(r);
    settings_cheque();            
  }, "text");
}

function settings_cheque(){
 $("#item_list .cl").click(function(){        
  $("#cheque_no").val($(this).children().eq(0).html());
  $("#bank").val($(this).children().eq(1).html());
  $("#bank_des").val($(this).children().eq(2).html());
  $("#cheque_val_1").val($(this).children().eq(3).html());
  $("#account").val($(this).children().eq(4).html());
  $("#load_no").val($(this).children().eq(5).html());
  $("#memo").val($(this).children().eq(8).html());

  $("#cash_value").val($(this).children().eq(9).html());
  $("#cheque_value").val($(this).children().eq(3).html());
  $("#Trans_no").val($(this).children().eq(11).html());
  $("#trans_code").val($(this).children().eq(12).html());

  $("#realize_date").val($(this).children().eq(13).html());
  $("#customer").val($(this).children().eq(14).html());

  $("#trans_code_c").val($(this).children().eq(15).html());
  $("#pre_status").val($(this).children().eq(16).html());



  if($("#chq_return").is(":checked")){
    $("#cr_acc").val($(this).children().eq(6).html());
    $("#cr_acc_des").val($(this).children().eq(7).html());
    load_default_acc(1);
  }else{
    load_default_acc(2);
  }

  $(".chk_t").attr("disabled",true);
  $("#pop_close").click();  

    //if($("#chq_refund").is(":checked")){
      load_transactions($(this).children().eq(5).html());
    //}

    
    total_amounts();

  })   
}


function load_default_acc(chq_typ){
  if(chq_typ == 1){
    $.post("index.php/main/load_data/t_cheques/load_default_acc", {
    }, function(r){
      if(r==2){
      }else{
        $("#dr_acc").val(r.code);
        $("#dr_acc_des").val(r.des);
      }          
    }, "json");
  }else{
    $.post("index.php/main/load_data/t_cheques/load_default_acc", {
    }, function(r){
      if(r==2){
      }else{
        $("#cr_acc").val(r.code);
        $("#cr_acc_des").val(r.des);

        $("#dr_acc").val($("#customer").val());
      }          
    }, "json");
  }
}

function load_transactions(no){
  empty_credit_sale_grid();
  $.post("index.php/main/load_data/t_cheques/load_transactions", {
    no  : no,
    type:$("#type").val()
  }, function(r){
    if(r==2){
      if($("#type").val()!="3"){
        set_msg("no records ! ! !"); 
      }
    }else{
      for(var x=0; x<r.length; x++){
        $("#date_"+x).val(r[x].ddate);
        $("#inv_"+x).val(r[x].nno);
        $("#amount_"+x).val(r[x].net_amount);
        $("#balance_"+x).val(r[x].balance);
        $("#paid_"+x).val(r[x].payment);
        $("#return_"+x).val(r[x].payment);
      }
      total();
    }          
  }, "json");
}

function total(){
  var tot_amont = tot_balance = tot_payemt = tot_return = parseFloat(0);

  for(var x=0; x<25; x++){
    if($("#amount_"+x).val()!=""){
      if(!isNaN($("#amount_"+x).val() && $("#balance_"+x).val() && $("#paid_"+x).val() && $("#return_"+x).val())){
        tot_amont   += parseFloat($("#amount_"+x).val());
        tot_balance += parseFloat($("#balance_"+x).val()); 
        tot_payemt  += parseFloat($("#paid_"+x).val());
        tot_return  += parseFloat($("#return_"+x).val());
      }
    }
  }

  $("#amount_tot").val(m_round(tot_amont));
  $("#balance_tot").val(m_round(tot_balance));
  $("#paid_tot").val(m_round(tot_payemt));
  $("#return_tot").val(m_round(tot_return));
}

function empty_credit_sale_grid(){
  for(var i=0; i<25; i++){
    $("#date_"+i).val("");
    $("#inv_"+i).val("");
    $("#amount_"+i).val("");
    $("#balance_"+i).val("");
    $("#paid_"+i).val("");
    $("#return_"+i).val("");
  }
}


function save(){
  $("#qno").val($("#id").val()); 
  var frm = $('#form_');
  loding();
  $.ajax({
  	type: frm.attr('method'),
  	url: frm.attr('action'),
  	data: frm.serialize(),
  	success: function (pid){
      if(pid == 1){
        $("#btnSave").attr("disabled",true);
        if(confirm("Save Completed, Do You Want A print?")){
          if($("#is_prnt").val()==1){
            $("#print_pdf").submit();
          }
          location.href="";
        }else{
          location.href="";
        }
      }else{
        set_msg(pid);
      }
      loding();
    }
  });
}



function validate(){
  if($("#des").val() == ""){
    set_msg("Please enter description.");
    $("#des").focus();
    return false;
  }else if($("#chq_type").val()=="2" && $("#chq_rf_type").val()=="1" && (!$("#drn").is(":checked") || $("#unsettle").is(":checked"))){
    set_msg("Please select settle type");
    return false;
  }else if($("#cheque_no").val()==""){
    set_msg("Please enter cheque no");
    return false;
  }else if($("#dr_acc").val()==""){
    set_msg("Please DR account");
    return false;    
  }else if($("#cr_acc").val()==""){
    set_msg("Please CR account");
    return false;  
  }else if($("#tot_charge").val()=="0.00"){
    set_msg("Total cann't be zero");
    return false;              
  }else{
    return true;
  }
}

function set_delete(code){
  if(confirm("Are You Sure Delete Cheque Return No "+code+"?")){
    loding();
    $.post("index.php/main/delete/t_cheques", {
      code : code,
      debit : $("#debit_no").val(),
      chq_pre : $("#pre_status").val(),
      chq_no:$("#cheque_no").val(),
      bank:$("#bank").val(),
      customer:$("#customer").val(),
      account:$("#account").val(),
    }, function(res){
      if(res == 1){
        location.href="";
      }else{
        set_msg(res);
      }
      loding();
    }, "text");
  }
}

function empty_g(){ 
  $("#ref_no").val("");
  $("#trans_code_c").val("");
  $("#trans_code").val("");
  $("#Trans_no").val("");
  $("#cheque_value").val("");
  $("#des").val("");
  $("#dr_acc").val("");
  $("#dr_acc_des").val("");
  $("#cr_acc").val("");
  $("#cr_acc_des").val("");
  $("#cqh_ret_charge").val("");
  $("#other_charge").val("");
  $("#tot_charge").val("");
  $("#type").val("");
  $("#chq_type").val("");
  $("#cheque_no").val("");
  $("#customer").val("");
  $("#new_ddate").val("");
  $("#debit_no").val("");
  $("#bank").val("");
  $("#bank_des").val("");
  $("#cheque_val_1").val("");
  $("#realize_date").val("");
  $("#account").val("");
  $("#memo").val("");
  $("#pre_status").val("");
  $("#drn").attr("checked",false);
  $("#unsettle").attr("checked",false);
  $("#chq_return").attr("checked",false);
  $("#chq_refund").attr("checked",false);
}

function load_data(code){
  empty_g();
  loding();
  $.post("index.php/main/get_data/t_cheques", {
    code : code
  }, function(res){
    if(res != 2){

      $("#id").val(res[0].no); 
      $("#qno").val(res[0].no); 
      $("#hid").val(res[0].no);
      $("#ddate").val(res[0].ddate);
      $("#ref_no").val(res[0].ref_no);
      $("#trans_code_c").val(res[0].trans_code);
      $("#trans_code").val(res[0].t_name);
      $("#Trans_no").val(res[0].trans_no);
      $("#cheque_value").val(res[0].chq_amount);
      $("#des").val(res[0].description);
      $("#dr_acc").val(res[0].dr_acc);
      $("#dr_acc_des").val(res[0].dr_des);
      $("#cr_acc").val(res[0].cr_acc);
      $("#cr_acc_des").val(res[0].cr_des);
      $("#cqh_ret_charge").val(res[0].chq_return_amount);
      $("#other_charge").val(res[0].other_amount);
      $("#tot_charge").val(res[0].amount);
      $("#type").val(res[0].type);
      $("#pre_status").val(res[0].previous_chq_status);

      if(res[0].chq_type=="1"){
        $("#chq_return").attr("checked",true);
      }else if(res[0].chq_type=="2"){
        $("#chq_refund").attr("checked",true);
      }
      if(res[0].refund_type=="1"){
        $("#refu_rt").attr("checked",true);
      }else if(res[0].refund_type=="2"){
        $("#refu_pd").attr("checked",true);
      }
      $("#chq_type").val(res[0].chq_type);
      $("#cheque_no").val(res[0].cheque_no);
      $("#customer").val(res[0].customer);
      $("#new_ddate").val(res[0].new_bank_date);

      if(res[0].settle_type=="1"){
        $("#drn").attr("checked",true);
        $("#drn").attr("disabled",false);
        $("#unsettle").attr("disabled",false);
        $("#refu_rt").attr("checked",true);
        $("#refu_rt").attr("disabled",false);
        $("#refu_pd").attr("disabled",false);

      }else if(res[0].settle_type=="2"){
        $("#unsettle").attr("checked",true);
        $("#unsettle").attr("disabled",false);
        $("#drn").attr("disabled",false);
        $("#refu_pd").attr("checked",true);
        $("#refu_pd").attr("disabled",false);
        $("#refu_rt").attr("disabled",false);
      }
      $(".chk_t").attr("disabled",true);

      $("#debit_no").val(res[0].debit_note_no);
      $("#bank").val(res[0].bank);
      $("#bank_des").val(res[0].bank_name);
      $("#cheque_val_1").val(res[0].chq_amount);
      $("#realize_date").val(res[0].realize_date);
      $("#account").val(res[0].account);
      $("#memo").val(res[0].memo);

      if(res[0].is_cancel==1){
        $("#btnDelete").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
      }

    }else{
      set_msg("No records");
    }
    loding(); 
  }, "json");
}