$(document).ready(function(){
  var sup_cr_prd=0;
  $("#btnSave").attr("disabled","disabled");

  $("#btnResett").click(function(){
    location.href="?action=t_voucher";
  });

  $("#btnDelete5").click(function(){
   set_delete();
 });

  $("#btnPrint").click(function(){
    if($("#hid").val()=="0"){
      set_msg("Please load data before print","error");
      return false;
    }else{
      $("#print_pdf").submit();
    }
  });

  $("#supplier_id").keypress(function(e){
    if(e.keyCode == 112){
      $("#pop_search4").val($("#supplier_id").val());
      load_supf1();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);
    }

    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_supf1();

     }
   }); 

    if(e.keyCode == 46){
      $("#supplier_id").val("");
      $("#supplier").val("");
    }
  });

  $("#id").keypress(function(e){
    if(e.keyCode==13){
      load_data($(this).val());
      load_payment_option_data($(this).val(),"19");
      $("#btnSave").attr("disabled","disabled");
    }
  });

  $("#showPayments").click(function(){
    var net_value=parseFloat($("#net_val").val());
    var balance=parseFloat($("#balance2").val());
    payment_opt('t_voucher',m_round($("#net").val()));

    if($("#hid").val()=="" || $("#hid").val()=="0"){
      $("#cash").val(m_round($("#net").val()));        
    }

    $("#save_status").val("0");
    if($("#hid").val()=="0"){
     get_min_date();
   }



 });


  $("#is_multi_branch").click(function(){
    $("#load_details").click();
  });
  
  $("#tgrid").tableScroll({height:355});

  $("#supplier_id").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });
  

  $("#supplier_id").keypress(function(e){
    if(e.keyCode == 13){
      set_cus_values($(this));
    }
  });
  
  $("#supplier_id").blur(function(){
    set_cus_values($(this));
  });


  $("#load_details").click(function(){
    if($("#supplier_id").val()!="" || $("#supplier").val()!=""){
      if($("#is_multi_branch").is(':checked')){
        load_supplier_details_all();
        load_supplier_balance_all();
      }else{
        load_supplier_details();
        load_supplier_balance();
      } 

      setTimeout(function(){
       $("#total_amount").val(m_round(get_column_total('.pay','#4_')));
       $("#total_balance").val(m_round(get_column_total('.pay','#5_')));
     }, 1000);

    }else{
     set_msg("Please select supplier account");
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
        $("#total_payment").val(m_round(settlement)); 
        $("#net_val").val(m_round(amount)); 
        $("#balance2").val(m_round(actual_amount-settlement));  
      }else{
        $("#6_"+e).val(m_round(amount));
        settlement=settlement+parseFloat($("#6_"+e).val());
        $("#total_payment").val(m_round(settlement)); 
        $("#net_val").val(m_round(amount)); 
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
/*                  $(".fo").each(function(e){
                     $("#6_"+e).val("");
                   });*/

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
                        $("#total_payment").val(m_round(settlement)); 
                        $("#net_val").val(m_round(amount)); 
                        $("#balance2").val(m_round(actual_amount-settlement));
                        if(isNaN($("#balance2").val())){$("#balance2").val(0);}
                      }else{

                        $("#6_"+e).val(m_round(amount));
                        settlement=settlement+parseFloat($("#6_"+e).val());
                        $("#total_payment").val(m_round(settlement)); 
                        $("#net_val").val(m_round(amount)); 
                        $("#balance2").val(m_round(actual_amount-settlement));
                        if(isNaN($("#balance2").val())){$("#balance2").val(0);}
                        return false;
                      }
                    }             
                  });    
                  }   
                }); 

  $(".pay").keyup(function(){

    tot_payment();

  });
  $(".pay").blur(function(){

    //    if($("#net").val()!=""){
     set_cid($(this).attr("id"));
     var x=parseFloat($("#6_"+scid).val());
     var y=parseFloat($("#5_"+scid).val()); 
     if(isNaN(x)){x=0;}
     if(isNaN(y)){y=0;}   
     

     if(x<=y){
      var pay_amount=parseFloat($("#net").val());
      var tot_amount=parseFloat($("#total_payment").val());
      var bal = pay_amount - tot_amount;

      if(isNaN(bal))
      {
        bal=0;
        $("#balance2").val(m_round(bal));
      }else{
        $("#balance2").val(m_round(bal));
      }
    }else{
      set_msg("Balance is lower than you entered.","error");
      $("#6_"+scid).val("");
    }
     /*   }else{
        set_msg("Please enter payment value","error");
            $(".fo").each(function(e){
               $("#6_"+e).val("");
                $("#net_val").val("");
                  $("#balance2").val("");                     
          });
        } */
        
      });

  $("#net").keyup(function(){

    var pay_amount=parseFloat($("#net").val());
    var tot_amount=parseFloat($("#total_payment").val());
    var bal = pay_amount - tot_amount;
    $("#net_val").val(m_round(pay_amount));

    if(isNaN(bal))
    {
      bal=0;
      $("#balance2").val(m_round(bal));
    }
    if(pay_amount>tot_amount){
      $("#balance2").val(m_round(bal));
    }

  });
});

function load_supf1(){
  $.post("index.php/main/load_data/utility/f1_selection_list_supplier", {
    data_tbl:"m_supplier",
    field:"code",
    field2:"name",
    hid_field:"credit_period",
    preview2:"Supplier Name",
    add_query:" AND is_inactive='0' ",
    search : $("#pop_search4").val() 
  }, 
  function(r){
    $("#sr4").html(r);
    settings_supf1();            
  }, "text");
}

function settings_supf1(){
  $("#item_list .cl").click(
    function(){        
      $("#supplier_id").val($(this).children().eq(0).html());
      $("#supplier").val($(this).children().eq(1).html());
      sup_cr_prd = $(this).children().eq(2).find('input').val();
      $("#pop_close4").click();                
    })    
}


var save_status=1;
function set_cus_values(f){
  var v = f.val();
  v = v.split("-");
  if(v.length == 2){
    f.val(v[0]);
    $("#supplier").val(v[1]);
  }
}



function set_cus_values2(f){
  var v = f.val();
  v = v.split("|");
  if(v.length == 2){
    f.val(v[0]);
    $("#sales_rep2").val(v[1]);
    
  }
}


function formatItems(row){
  return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
  return row[0]+"|"+row[1];
}


function save(){
  $("#qno").val($("#id").val());
  $("#dt").val($("#date").val());
  $("#recivied").val($("#net_val").val());
  $("#supp_id").val($("#supplier_id").val());
  $("#is_duplicate").val("1");
  $('#form_').attr('action',$('#form_id').val()+"t_voucher");

  var voucher = {
    vou_details:[], 

  };
  var grid_tot=200;

  for(var x=0; x<grid_tot; x++){
    if($("#2_"+x).val()!="" && $("#4_"+x).val()!="" && $("#6_"+x).val()!="0.00" && $("#6_"+x).val()!=""){ 
     voucher.vou_details.push({ 
      sub_cl          : $("#cl0_"+x).val(), 
      sub_bc          : $("#bc0_"+x).val(), 
      trans_code      : $("#trans_code"+x).val(),
      trans_no        : $("#2_"+x).val(), 
      date            : $("#3_"+x).val(), 
      amount          : $("#4_"+x).val(), 
      description     : $("#descrip_"+x).val(), 
      balance         : $("#5_"+x).val(), 
      payment         : $("#6_"+x).val(),
      order_num       : x,
    })
   }
 }
 var dataStringx = JSON.stringify(voucher);


 loding();
 var frm = $('#form_');
 if(save_status!=0){

  $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    beforeSend:function(xhr, settings){
     settings.data += '&moreinfo='+dataStringx;
   },
   success: function (pid){
    loding();
    var sid=pid.split('@');
    if(pid == 0){
      set_msg("Transaction is not completed");
      location.href="";
    }else if(pid == 2){
      set_msg("No permission to add data.");
    }else if(pid == 3){
      set_msg("No permission to edit data.");
    }else if(sid[0]==1){
      $("#btnSave").attr("disabled",true);
      $("#showPayments").attr("disabled",true);
      save_status=0;
      $("#save_status").val("0");
              //sucess_msg();

              if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                  $("#qno").val(sid[1]);
                  $("#print_pdf").submit();
                }
                location.href="";
              }else{
                location.href="";
              }    
            }else{
              set_msg(pid,'error');
            }
            
          }
        });
}
}



function validate(){

  if($("#supplier_id").val()=="" || $("#supplier_id").attr("title")==$("#supplier_id").val()){
   set_msg("Please Enter Customer");
   $("#supplier_id").focus();
   return false;
 }else if($("#net").val()=="" || $("#net").val()==0){
  set_msg("Please Enter Payment");
  $("#net").focus();
  return false;
}else if($("#load_opt").val()==0 && $("#payment_option").is(':checked')){
  var net_value=parseFloat($("#net_val").val());
  var balance=parseFloat($("#balance2").val());
  payment_opt('t_voucher',m_round($("#net").val()));     
  return false;              
}else if($("#net").val() < $("#total_payment").val()){
 set_msg("Payment value can't be less than total","error");
 return false;            
}else{
  return true;
}
}





function load_supplier_details(){
  clear_grid();  
  $.post("index.php/main/load_data/t_voucher/load_supplier_details", {
    supplier_id:$("#supplier_id").val()
  }, function(r){

    if(r!=2){        
     for(var i=0; i<r.det.length;i++){
      $("#cl0_"+i).val(r.det[i].cl);
      $("#bc0_"+i).val(r.det[i].bc);
      $("#1_"+i).val(r.det[i].description);
      $("#trans_code"+i).val(r.det[i].type);
      $("#descrip_"+i).val(r.det[i].memo);
      
      $("#2_"+i).val(r.det[i].trans_no);
      $("#3_"+i).val(r.det[i].ddate);
      $("#4_"+i).val(r.det[i].amount);
      $("#5_"+i).val(r.det[i].balance);

      $("#6_"+i).attr('readonly', false);
    }

    $("#net").blur();
  }else{
    set_msg("This supplier hasn't settlement balance");
  }
}, "json");
}


function load_supplier_details_all(){
  clear_grid();  
  $.post("index.php/main/load_data/t_voucher/load_supplier_details_all", {
    supplier_id:$("#supplier_id").val()
  }, function(r){

    if(r!=2){  
      for(var i=0; i<r.det.length;i++){
        $("#cl0_"+i).val(r.det[i].cl);
        $("#bc0_"+i).val(r.det[i].bc);
        $("#1_"+i).val(r.det[i].description);
        $("#trans_code"+i).val(r.det[i].type);
        $("#descrip_"+i).val(r.det[i].memo);
        $("#2_"+i).val(r.det[i].trans_no);
        $("#3_"+i).val(r.det[i].ddate);
        $("#4_"+i).val(r.det[i].amount);
        $("#5_"+i).val(r.det[i].balance);

        $("#6_"+i).attr('readonly', false);
        
      }
      $("#net").blur();

    }else{
      set_msg("This supplier hasn't settlement balance","error");
    }
  }, "json");
}


function load_supplier_balance(){
 $.post("index.php/main/load_data/t_voucher/load_supplier_balance", {
  supplier_id:$("#supplier_id").val()
}, function(r){
  if(r){
    $("#balance").val(r);
  }else{
    $("#balance").val("0.00");
  }  
  
}, "text");

}

function load_supplier_balance_all(){
 $.post("index.php/main/load_data/t_voucher/load_supplier_balance_all", {
  supplier_id:$("#supplier_id").val()
}, function(r){
  if(r){
    $("#balance").val(r);
  }else{
    $("#balance").val("0.00");
  }  
  
}, "text");

}

function clear_grid(){
  $("#hid").val(""); 
  $("#balance").val("");   
  $("#net").val(""); 
  $("#memo").val("");
  $("#ref_no").val(""); 
  $("#net_val").val(""); 
  $("#balance2").val(""); 
  $('#multi_branch').attr('checked', false); 
  $('#payment_option').attr('checked', false); 
  $('#auto_fill').attr('checked', false); 

  $("#total_payment").val("");
  


  $.post("index.php/main/load_data/t_voucher/get_max_no", {
  }, function(r){
    $("#id").val(r);
  },"text");


  for(var i=0; i<100;i++){
    $("#cl0_"+i).val("");
    $("#bc0_"+i).val("");
    $("#1_"+i).val("");
    $("#2_"+i).val("");
    $("#3_"+i).val("");
    $("#4_"+i).val("");
    $("#5_"+i).val("");
    $("#6_"+i).val("");
    $("#6_"+i).attr('readonly', true);

    $("#descrip_"+i).val("");

  }   
} 






function show_pre_result(result){
  $("#points").val(m_round(result));
}


function set_delete(){
  var id = $("#hid").val();
  var ddate = $("#date").val();
  if(id != 0){
    if(confirm("Are you sure to delete this receipt ["+$("#id").val()+"]? ")){
      loding();
      $.post("index.php/main/delete/t_voucher", {
        trans_no:id,
        ddate:ddate
      },function(r){
        loding();
        if(r != 1){
          set_msg(r,"error");
        }else{
          delete_msg();
        }
      }, "text");
    }
  }else{
    set_msg("Please load record","error");
  }
}

function load_data(id){
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_voucher/", {
    id: id
  }, function(r){

    if(r=="2"){
     set_msg("No records","error");
   }else{
    $("#hid").val(id);    
    $("#supplier_id").val(r.sum[0].acc_code);
    $("#supplier").val(r.sum[0].supplier_name);
    $("#memo").val(r.sum[0].memo);
    $("#net").val(r.sum[0].payment);
    $("#net_val").val(r.sum[0].payment);
    $("#ref_no").val(r.sum[0].ref_no);
    $("#date").val(r.sum[0].ddate);

    $("#balance").val(r.sum[0].balance);
    $("#balance2").val(r.sum[0].settle_balance);


    $("#qno").val(id);
    $("#dt").val(r.sum[0].ddate);
    $("#supp_id").val(r.sum[0].acc_code);
    $("#recivied").val(r.sum[0].payment);
    
    $("#load_opt").val("");    
    $("#hid_cash").val(r.sum[0].pay_cash);
    $("#hid_cheque_issue").val(r.sum[0].pay_issue_chq);
    $("#hid_credit_card").val(r.sum[0].pay_ccard);
    $("#hid_credit_note").val(r.sum[0].pay_cnote);
    $("#hid_debit_note").val(r.sum[0].previlliage_card_no);
    $("#hid_bank_debit").val(r.sum[0].pay_bank_debit);
    $("#hid_discount").val(r.sum[0].previlliage_card_no);
    $("#hid_advance").val(r.sum[0].pay_advance);
    $("#hid_gv").val(r.sum[0].pay_gift_voucher);
    $("#hid_credit").val(r.sum[0].pay_credit);
    $("#hid_pc").val(r.sum[0].previlliage_card_no);
    $("#hid_pc_type").val(r.sum[0].previlliage_card_no);  
    $("#hid_priv_card").val(r.sum[0].pay_privi_card);


    $("#cash").val(r.sum[0].pay_cash);
    $("#cheque_issue").val(r.sum[0].pay_issue_chq);
    $("#cheque_recieve").val(r.sum[0].pay_receive_chq);
    $("#credit_card").val(r.sum[0].pay_ccard);
    $("#credit_note").val(r.sum[0].pay_cnote);
    $("#debit_note").val(r.sum[0].pay_dnote);
    $("#bank_debit").val(r.sum[0].pay_bank_debit);
    $("#discount").val(r.sum[0].pay_discount);
    $("#advance").val(r.sum[0].pay_advance);
    $("#gv").val(r.sum[0].pay_gift_voucher);
    $("#credit").val(r.sum[0].pay_credit);
    $("#pc").val(r.sum[0].pay_privi_card);

    if(r.sum[0].is_cancel==1){
      $("#btnDelete5").attr("disabled", "disabled");
      $("#btnSave").attr("disabled", "disabled");
      $("#mframe").css("background-image", "url('img/cancel.png')");
    }
    

    if(r.sum[0].is_multi_payment==1){
      $("#payment_option").attr("checked", "checked");
      $("#payment_option").css("display","none");
      $("#payment_option_shadow").attr("checked", "checked");
      $("#payment_option_shadow").css("visibility","visible");
      $("#payment_option_shadow").attr("disabled","disabled");

      
    }else{
      $("#payment_option").removeAttr("checked");
    }

    $("#is_multi_branch").attr("disabled","disabled");
    $("#load_details").attr("disabled","disabled");
    $("#auto_fill").attr("disabled","disabled");

    $("#id").attr("readonly","readonly")            
    

    var ntTot=0;
    var settlement=0;
    var balance=parseFloat(r.sum[0].payment);
    for(var i=0; i<r.det.length;i++){
      $("#cl0_"+i).val(r.det[i].sub_cl);
      $("#bc0_"+i).val(r.det[i].sub_bc);
      $("#1_"+i).val(r.det[i].description);
      $("#trans_code"+i).val(r.det[i].trans_code);
      $("#2_"+i).val(r.det[i].trans_no);
      $("#3_"+i).val(r.det[i].date);
      $("#4_"+i).val(r.det[i].amount);
      $("#5_"+i).val(r.det[i].balance);
      $("#descrip_"+i).val(r.det[i].des1);
      $("#6_"+i).val(r.det[i].payment);
      $("#6_"+i).attr('readonly', false);

      settlement=settlement+parseFloat(r.det[i].payment);
      $("#net_val").val(m_round(settlement));
      balance=balance-parseFloat(r.det[i].payment);
      $("#balance2").val(m_round(balance));


      tot_payment();

    }
    setTimeout(function(){
     $("#total_amount").val(m_round(get_column_total('.pay','#4_')));
     $("#total_balance").val(m_round(get_column_total('.pay','#5_')));
   }, 1000);
    
  }
  loding();
}, "json");
}  


function empty_grid(){
 for(var i=0; i<100; i++){
  $("#cl0_"+i).val("");
  $("#bc0_"+i).val("");
  $("#descrip_"+i).val("");
  $("#1_"+i).val("");
  $("#2_"+i).val("");
  $("#3_"+i).val("");
  $("#4_"+i).val("");
  $("#5_"+i).val("");
  $("#6_"+i).val("");
  $("#6_"+i).attr('readonly', true);        
}
} 


function tot_payment(){
  var ntTot=0;  
  $(".pay").each(function(e){
    var tot=parseFloat($(this).val());
    if(isNaN(tot)){tot=0;}
    if(tot!=""){
      ntTot+=tot;
    }
  });

  if(isNaN(ntTot)){ntTot=0;} 
  $("#total_payment").val(ntTot.toFixed(2));

  if(isNaN($("#balance2").val())){$("#balance2").val(0);}
}


/*function get_min_date(){
  var a_dates = parseInt(sup_cr_prd);
  var arr_date=[];
  $(".pay").each(function(e){
    if($("#3_"+e).val()!="" && parseFloat($(this).val())>0){  
      arr_date.push(new Date($("#3_"+e).val()));
    }
  });
  var m_date=new Date(Math.min.apply(null,arr_date));
  if(m_date != "Invalid Date"){
    m_date.setDate(m_date.getDate() + a_dates);
    var dd = m_date.getDate();
    var mm = m_date.getMonth() + 1;
    var y = m_date.getFullYear();
    var minimum_date = y + '-' + mm + '-' + dd;

    $("#cdate7_0").val(minimum_date);
      
  }


}*/

function get_min_date(){
  var m_date=new Date();
  if(m_date != "Invalid Date"){
    var dd = m_date.getDate();
    var mm = m_date.getMonth() + 1;
    var y = m_date.getFullYear();
    var minimum_date = y + '-' + mm + '-' + dd;
    $("#cdate7_0").val(minimum_date);
  }
}