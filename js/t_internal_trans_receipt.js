$(document).ready(function(){



  var p_code = String(window.location.search.split('=')[2]);
  var string = String(window.location.search.split('=')[3]);
  var amount = String(window.location.search.split('=')[4]);

  var bank = String(window.location.search.split('=')[5]);
  var branch = String(window.location.search.split('=')[6]);
  var account = String(window.location.search.split('=')[7]);
  var chq_no = String(window.location.search.split('=')[8]);
  var realize_date = String(window.location.search.split('=')[9]);

  if(string != 'undefined'){//26-10-2015-105
    var name = decodeURI(string);
    $("#acc_code").val(p_code);
    $("#acc_des").val(name);
    $("#net").val(amount);

    $("#pdbank9_0").val(bank);
    $("#pdbranch9_0").val(branch);
    $("#pdacc9_0").val(account);
    $("#pdcheque9_0").val(chq_no);
    $("#pdamount9_0").val(amount);
    $("#pddate9_0").val(realize_date);

    jQuery(function(){
      jQuery('#load_details').click();});
  }
  


  $("#btnSavee").css("display","none");
  $("#btnResett").click(function(){
    location.href="?action=t_internal_trans_receipt";
  });


  $("#acc_code").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search").val($("#acc_code").val());
      load_branch_acc();
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search').focus()", 100);   
    }
    $("#pop_search").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_branch_acc();
      }
    });
    if(e.keyCode==46){
     $("#acc_code").val("");
     $("#acc_des").val("");
   }  
 });


  $("#sales_rep").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search2").val($("#sales_rep").val());
      load_emp();
      $("#serch_pop2").center();
      $("#blocker2").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);   
    }
    $("#pop_search2").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_emp();
      }
    });
    if(e.keyCode==46){
     $("#sales_rep").val("");
     $("#sales_rep2").val("");
   }  
 });


  $("#popclose2").click(function(){
    $("#light3").css("display","none");
    $("#fade").css("display","none");
  });


  $("#popclose").click(function(){
    $("#light").css("display","none");
    $("#fade").css("display","none");
  });

  
  $("#btnSave").attr("disabled","disabled");
  $("#btnPrint").click(function(){
    if($("#hid").val()=="0"){
      set_msg("Please load data before print");
      return false;
    }else{
      $("#print_pdf").submit();
    }
  });


  $("#btnDelete5").click(function(){
    set_delete();
  });

  $("#showPayments").click(function(){
    var net_value=parseFloat($("#net_val").val());
    var balance=parseFloat($("#balance2").val());
    payment_opt('t_internal_trans_receipt',m_round($("#net").val()));
    if($("#hid").val()=="" || $("#hid").val()=="0"){
      $("#cash").val(m_round(net_value+balance));
    }
    $("#save_status").val("0");
  });


  $("#id").keydown(function(e){
    if(e.keyCode==13){
      load_data($(this).val());
      load_payment_option_data($(this).val(),"108");
      $("#btnSave").attr("disabled","disabled");
    }
  });



  $("#load_details").click(function(){
    var acc_id=$("#acc_code").val();
    var acc_name=$("#acc_des").val();
    if(acc_id!="" && acc_name!=""){
      load_branch_details(); 
      load_branch_balance();
      setTimeout(function(){
        $("#total_amount").val(m_round(get_column_total('.payss','#4_')));
        $("#total_balance").val(m_round(get_column_total('.payss','#5_')));
      },200);
    }else{
      set_msg("Please Select branch account");
    }    
  });

  $(".pay").blur(function(){

    if($("#net").val()!=""){
     set_cid($(this).attr("id"));
     var x=parseFloat($("#6_"+scid).val());
     var y=parseFloat($("#5_"+scid).val()); 
     if(isNaN(x)){x=0;}
     if(isNaN(y)){y=0;}   


     if(x<=y){
      var actual_amount=parseFloat($("#net").val());
      var amount=actual_amount;
      var total_payment=0;
      $(".fo").each(function(e){
        var payment=parseFloat($("#6_"+e).val());
        if(!isNaN(payment)){
          if((m_round(actual_amount)-(m_round(total_payment)+m_round(payment)))<0){
           $("#6_"+e).val("");
           $("#net_val").val(m_round(total_payment)); 
           $("#balance2").val(m_round(actual_amount-total_payment)); 
           set_msg("Please check the pay amount");
         }else{
           total_payment=total_payment+payment;
           $("#net_val").val(m_round(total_payment)); 
           $("#balance2").val(m_round(actual_amount-total_payment)); 
         }
       }
     });
    }else{
      set_msg("Balance is lower than you entered.","error");
      $("#6_"+scid).val("");
    }
  }else{
    set_msg("Please enter payment value","error");
    $(".fo").each(function(e){
     $("#6_"+e).val("");
     $("#net_val").val("");
     $("#balance2").val("");                     
   });
  }
});


  $("#auto_fill").click(function(){
    if($("#auto_fill").is(':checked')){
      var actual_amount=parseFloat($("#net").val());
      if(!isNaN($("#net").val())){
        amount=parseFloat($("#net").val());
      }else{
        amount=0;
      }
      var amount=parseFloat($("#net").val());
      var settlement=0;
      $(".fo").each(function(e){
        $("#6_"+e).attr("readonly",true);
        var balance=parseFloat($("#5_"+e).val());
        if(!isNaN(balance)){
         if(amount>balance){
          amount=amount-balance;
          $("#6_"+e).val(m_round(balance));
          settlement=settlement+parseFloat($("#6_"+e).val());
          $("#net_val").val(m_round(settlement)); 
          $("#balance2").val(m_round(actual_amount-settlement));  
        }else{
          $("#6_"+e).val(m_round(amount));
          settlement=settlement+parseFloat($("#6_"+e).val());
          $("#net_val").val(m_round(settlement)); 
          $("#balance2").val(m_round(actual_amount-settlement));  

          return false;
        }
      }
    });    
    }else{
      $(".fo").each(function(e){
        $("#6_"+e).removeAttr("readonly");
        $("#6_"+e).val("");
        $("#net_val").val("");
        $("#balance2").val("");                     
      });
    }              
  });

  $("#net").blur(function(){
    $(".fo").each(function(e){
      $("#6_"+e).val("");
    });

    if($("#auto_fill").is(':checked')){
      var actual_amount=parseFloat($("#net").val());
      var amount=parseFloat($("#net").val());
      var settlement=0;

      if(!isNaN($("#net").val())){
        amount=parseFloat($("#net").val());
      }else{
        amount=0;
      }

      $(".fo").each(function(e){

        var balance=parseFloat($("#5_"+e).val());
        if(!isNaN(balance)){
         if(amount>balance){
          amount=amount-balance;
          $("#6_"+e).val(m_round(balance));
          settlement=settlement+parseFloat($("#6_"+e).val());
          $("#net_val").val(m_round(settlement)); 
          $("#balance2").val(m_round(actual_amount-settlement)); 
        }else{

          $("#6_"+e).val(m_round(amount));
          settlement=settlement+parseFloat($("#6_"+e).val());
          $("#net_val").val(m_round(settlement)); 
          $("#balance2").val(m_round(actual_amount-settlement));

          return false;
        }
      }
    });    
    }    
  }); 


  $("#grid").tableScroll({height:355});
  $("#tgrid").tableScroll({height:355});


});

var save_status=1;


function load_branch_acc(){
  $.post("index.php/main/load_data/t_internal_trans_receipt/branch_accounts", {
    search : $("#pop_search").val() 
  }, function(r){
    $("#sr").html("");
    $("#sr").html(r);
    settings_cus();            
  }, "text");
}

function settings_cus(){
  $("#item_list .cl").click(function(){        
    $("#acc_code").val($(this).children().eq(2).html());
    $("#customer").val($(this).children().eq(2).html());
    $("#acc_des").val($(this).children().eq(3).html());
    $("#pop_close").click();                
  })    
}

function load_emp(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"m_employee",
    field:"code",
    field2:"name",
    preview2:"Employee Name",
    search : $("#pop_search2").val() 
  }, function(r){
    $("#sr2").html(r);
    settings_emp();      
  }, "text");
}

function settings_emp(){
  $("#item_list .cl").click(function(){        
    $("#sales_rep").val($(this).children().eq(0).html());
    $("#sales_rep2").val($(this).children().eq(1).html());
    $("#pop_close2").click();                
  })    
}



function load_branch_details(){
  clear_grid();  
  $.post("index.php/main/load_data/t_internal_trans_receipt/load_branch_details", {
    acc_id:$("#acc_code").val(),
  }, function(r){
    if(r!=2){        
     for(var i=0; i<r.det.length;i++){
      if(r.det[i].trans_mode=="2"){
        $trans_mode=" - Return";
      }else if(r.det[i].trans_mode=="1"){
        $trans_mode=" - Stationary";
      }else{
        $trans_mode="";
      }

      $("#cl0_"+i).val(r.det[i].cl);
      $("#bc0_"+i).val(r.det[i].bc);
      $("#2_"+i).val(r.det[i].trans_no);
      $("#2r_"+i).val(r.det[i].sub_no);
      $("#descrip_"+i).val(r.det[i].des+$trans_mode);
      $("#trans_code"+i).val(r.det[i].trans_code);
      $("#refcl_"+i).val(r.det[i].sub_cl);
      $("#refbc_"+i).val(r.det[i].sub_bc);
      $("#3_"+i).val(r.det[i].ddate);
      $("#4_"+i).val(r.det[i].amount);
      $("#5_"+i).val(r.det[i].balance);
    }
    $("#net").blur();
  }else{
    set_msg("This Branch hasn't settlement balance");
  }
}, "json");


}

function load_branch_balance(){
 $.post("index.php/main/load_data/t_internal_trans_receipt/load_branch_balance", {
  acc_id:$("#acc_code").val()
}, function(r){
  if(r>0){
    $("#balance").val(r);
  }else{
    $("#balance").val("0.00");
  }  
}, "text");
}


function clear_grid(){
  $("#hid").val("");  
  $("#balance").val("");   
  $("#sales_rep").val(""); 
  $("#sales_rep2").val(""); 
  $("#net_val").val(""); 
  $("#balance2").val(""); 
  $("#ref_no").val("");

  for(var i=0; i<50;i++){
    $("#btn_"+i).css("display","none");
    $("#cl0_"+i).val("");
    $("#bc0_"+i).val("");
    $("#1_"+i).val("");
    $("#2_"+i).val("");
    $("#2r_"+i).val("");
    $("#3_"+i).val("");
    $("#4_"+i).val("");
    $("#5_"+i).val("");
    $("#6_"+i).val("");
    $("#descrip_"+i).val("");
    $("#trans_code"+i).val("");
  }   
} 

function save(){
  $("#is_duplicate").val("1");

  $('#form_').attr('action',$('#form_id').val()+"t_internal_trans_receipt");
  var frm = $('#form_');
  if(save_status!=0){
   loding();
   $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    success: function (pid){
      var sid=pid.split('@');
      if(sid[0]==1){
        $("#btnSave").attr("disabled",true);
        $("#showPayments").attr("disabled",true);
        //$("#btnSavee").css("display","inline");
        save_status=0;
        $("#save_status").val("0");

        if(confirm("Save Completed, Do You Want A print?")){
          if($("#is_prnt").val()==1){
            $("#qno").val(sid[1]);
            $("#print_pdf").submit();
          }
          reload_form();
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
}

function reload_form(){
  setTimeout(function(){
    location.href = '';
  },50); 
}


function validate(){
  if($("#acc_code").val()==""){
   set_msg("Please Enter account code","error");
   $("#acc_code").focus();
   return false;
 }else if($("#net").val()=="" || $("#net").val()==0){
   set_msg("Please Enter Payment","error");
   $("#net").focus();
   return false;
 }else if($("#load_opt").val()==0 && $("#payment_option").is(':checked')){
   var net_value=parseFloat($("#net_val").val());
   var balance=parseFloat($("#balance2").val());
   payment_opt('t_internal_trans_receipt', m_round($("#net").val()));     
   return false;              
 }else{
  return true;
}
}




function set_delete(){
  var id = $("#hid").val();
  if(id != 0){
    if(confirm("Are you sure to delete this receipt ["+$("#id").val()+"]? ")){
      $.post("index.php/main/delete/t_internal_trans_receipt", {
        trans_no:id,
      },function(r){
        if(r != 1){
          set_msg(r);
        }else{
          delete_msg();
        }
      }, "text");
    }
  }else{
    set_msg("Please load record");
  }
}


function load_data(id){
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_internal_trans_receipt/", {
    id: id
  }, function(r){
    if(r=="2"){
      set_msg("No records");
    }else{
      $("#hid").val(id);    
      $("#qno").val(id);
      
      $("#acc_code").val(r.sum[0].acc_code);
      $("#customer").val(r.sum[0].acc_code);
      $("#acc_des").val(r.sum[0].acc_des);
      $("#balance").val(r.sum[0].balance);
      $("#memo").val(r.sum[0].memo);
      $("#net").val(r.sum[0].payment);
      $("#date").val(r.sum[0].ddate);
      $("#ref_no").val(r.sum[0].ref_no);
      $("#net_val").val(r.sum[0].settle_amount);
      $("#balance2").val(r.sum[0].settle_balance);      
      $("#hid_cash").val(r.sum[0].pay_cash);
      $("#hid_cheque_recieve").val(r.sum[0].pay_receive_chq);
      $("#hid_credit_card").val(r.sum[0].pay_ccard);
      $("#cash").val(r.sum[0].pay_cash);
      $("#cheque_recieve").val(r.sum[0].pay_receive_chq);
      $("#credit_card").val(r.sum[0].pay_ccard);
      $("#auto_fill").attr("disabled","disabled");
      $("#load_details").attr("disabled","disabled");
      $("#id").attr("readonly","readonly") 


      var settlement=0;
      var balance=parseFloat(r.sum[0].payment);
      for(var i=0; i<r.det.length;i++){
        $("#cl0_"+i).val(r.det[i].cl);
        $("#bc0_"+i).val(r.det[i].bc);
        $("#refcl_"+i).val(r.det[i].to_cl);
        $("#refbc_"+i).val(r.det[i].to_bc);
        $("#trans_code"+i).val(r.det[i].trans_code);
        $("#2_"+i).val(r.det[i].trans_no);
        $("#2r_"+i).val(r.det[i].sub_no);
        $("#3_"+i).val(r.det[i].date);
        $("#descrip_"+i).val(r.det[i].description);
        $("#4_"+i).val(r.det[i].amount);
        $("#5_"+i).val(r.det[i].balance);
        $("#6_"+i).val(r.det[i].payment);

        settlement=settlement+parseFloat(r.det[i].payment);
        $("#net_val").val(m_round(settlement));
        balance=balance-parseFloat(r.det[i].payment);
        $("#balance2").val(m_round(balance));
      }

      if(r.sum[0].is_cancel==1){
        $("#btnDelete5").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
      }

      setTimeout(function(){
        $("#total_amount").val(m_round(get_column_total('.payss','#4_')));
        $("#total_balance").val(m_round(get_column_total('.payss','#5_')));
      }, 500);
    }
    loding();
  }, "json");
}


function empty_grid(){
  for(var i=0; i<50; i++){
    $("#cl0_"+i).val("");
    $("#bc0_"+i).val("");
    $("#btn_"+i).css("display","none");
    $("#descrip_"+i).val(""); 
    $("#1_"+i).val("");
    $("#2_"+i).val("");
    $("#2r_"+i).val("");
    $("#3_"+i).val("");
    $("#4_"+i).val("");
    $("#5_"+i).val("");
    $("#6_"+i).val("");

  }
}