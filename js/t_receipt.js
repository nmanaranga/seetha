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
    $("#customer").val(p_code);
    $("#customer_id").val(name);
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
    location.href="?action=t_receipt";
  });


  $(".bal_det").click(function(){
    set_cid($(this).attr("id"));

    $.post("index.php/main/load_data/t_receipt/install_payment_shedule", {
     hid:$("#hid").val(),
     cl:$("#cl0_"+scid).val(),
     bc:$("#bc0_"+scid).val(),
     no:$("#2_"+scid).val(),
     paid:$("#6_"+scid).val()
   }, function(r){
    $("#install_payment_det").html(r);

  }, "text");

    $("#light").css("display","block");
    $("#fade").css("display","block");
  });


  $("#customer").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search").val($("#customer").val());
      load_customer();
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search').focus()", 100);   
    }
    $("#pop_search").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_customer();
      }
    });
    if(e.keyCode==46){
     $("#customer").val("");
     $("#customer_id").val("");
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



  $(".penalty_det").click(function(){
    set_cid($(this).attr("id"));
    $("#light3").css("display","block");
    $("#fade").css("display","block");

    $.post("index.php/main/load_data/t_receipt/load_penalty_details", {
     cus:$("#customer").val(),
     paid:$("#6_"+scid).val(),
     hid:$("#hid").val()
   }, function(r){
    $("#penalty_payment_det").html(r);
  }, "text");
    
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
    payment_opt('t_receipt',m_round($("#net").val()));
    if($("#hid").val()=="" || $("#hid").val()=="0"){
      $("#cash").val(m_round(net_value+balance));
    }
    $("#save_status").val("0");
    if(string != 'undefined'){
      $("#tabs").tabs("select", "tabs-11" );  
      total_pd_values();
      set_opt_balance(); 
    }
  });


  $("#id").keydown(function(e){
    if(e.keyCode==13){
      load_data($(this).val());
      load_payment_option_data($(this).val(),"16");
      $("#btnSave").attr("disabled","disabled");
    }
  });

  $("#is_multi_branch").click(function(){
    $("#load_details").click();
  });

  $("#load_details").click(function(){
    var customer_id=$("#customer").val();
    var customer_name=$("#customer_id").val();
    if(customer_id!="" && customer_name!=""){
     if($("#is_multi_branch").is(':checked')){
       load_customer_details_all(); 
       load_customer_balance_all();
     }else{
      load_customer_details(); 
      load_customer_balance();
    }

    setTimeout(function(){
      $("#total_amount").val(m_round(get_column_total('.payss','#4_')));
      $("#total_balance").val(m_round(get_column_total('.payss','#5_')));
    },200);
  }else{
    set_msg("Please Select Customer");
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
          var bal=parseFloat(actual_amount)-(parseFloat(total_payment)+parseFloat(payment));
          if(m_round(bal)<0){
           $("#6_"+e).val("");
           $("#net_val").val(m_round(total_payment)); 
           $("#balance2").val(m_round(actual_amount-total_payment)); 
           set_msg("Please check the pay amount");
         }else{
           total_payment=parseFloat(total_payment)+parseFloat(payment);
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

  $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });

  $("#customer").keydown(function(e){
    if(e.keyCode == 13){
      set_cus_values($(this));

    }
  });

  $("#customer").blur(function(){
    set_cus_values($(this));

  });



  $("#sales_rep").autocomplete('index.php/main/load_data/m_employee/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });

  $("#sales_rep").keydown(function(e){
    if(e.keyCode == 13){
      set_cus_values2($(this));
    }
  });

  $("#sales_rep").blur(function(){
    set_cus_values2($(this));
  });


});

var save_status=1;

function set_cus_values2(f){
  var v = f.val();
  v = v.split("-");

  if(v.length == 2){
    f.val(v[0]);
    $("#sales_rep2").val(v[1]);



  }
}

/*function load_customer(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_customer",
      field:"code",
      field2:"name",
      preview2:"Customer Name",
      search : $("#pop_search").val() 
  }, function(r){
      $("#sr").html(r);
      settings_cus();      
 }, "text");
}*/

function load_customer(){
  $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
    data_tbl:"m_customer",
    field:"code",
    field2:"name",
    field3:"nic",
    preview1:"Customer ID",
    preview2:"Customer Name",
    preview3:"Customer NIC",
    search : $("#pop_search").val() 
  }, function(r){
    $("#sr").html("");
    $("#sr").html(r);
    settings_cus();            
  }, "text");
}

function settings_cus(){
  $("#item_list .cl").click(function(){        
    $("#customer").val($(this).children().eq(0).html());
    $("#customer_id").val($(this).children().eq(1).html());
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



function load_customer_details(){
  clear_grid();  
  $.post("index.php/main/load_data/t_receipt/load_customer_details", {
    customer_id:$("#customer").val(),
    inv_no:$("#inv_no").val()

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
      if(r.det[i].memo=="Installment Base"){
        $("#btn_"+i).css("display","block");
        $("#is_install_"+i).val(1);
        $("#is_install_"+i).attr('title','1');
      }

      if(r.det[i].type=="47"){
        $("#btn2_"+i).css("display","block");
        $("#is_penalty_"+i).val(1);
        $("#is_penalty_"+i).attr('title','1');
      }

    }

    $("#net").blur();

  }else{
    set_msg("This customer hasn't settlement balance");
  }
}, "json");


}

function load_customer_balance(){
 $.post("index.php/main/load_data/t_receipt/load_customer_balance", {
  customer_id:$("#customer").val()
}, function(r){

  if(r){
    $("#balance").val(r);
  }else{
    $("#balance").val("0.00");
  }  

}, "text");

}

function load_customer_balance_all(){
 $.post("index.php/main/load_data/t_receipt/load_customer_balance_all", {
  customer_id:$("#customer").val()
}, function(r){
  if(r){
    $("#balance").val(r);
  }else{
    $("#balance").val("0.00");
  }  
}, "text");
}

function load_customer_details_all(){
  clear_grid();  
  $.post("index.php/main/load_data/t_receipt/load_customer_details_all", {
    customer_id:$("#customer").val()
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
        if(r.det[i].memo=="Installment Base"){
          $("#btn_"+i).css("display","block");
          $("#is_install_"+i).val(1);
          $("#is_install_"+i).attr('title','1');
        }

      }
      $("#net").blur();

    }else{
      set_msg("This customer hasn't settlement balance");
    }
  }, "json");
}

function clear_grid(){
  $("#hid").val("");  
  $("#balance").val("");   
            //$("#net").val(""); 
            $("#sales_rep").val(""); 
            $("#sales_rep2").val(""); 
            $("#net_val").val(""); 
            $("#balance2").val(""); 
            $("#ref_no").val("");


            $.post("index.php/main/load_data/t_receipt/get_max_no", {
            }, function(r){
              $("#id").val(r);
            },"text");

            for(var i=0; i<300;i++){

              $("#btn_"+i).css("display","none");
              $("#cl0_"+i).val("");
              $("#bc0_"+i).val("");
              $("#1_"+i).val("");
              $("#2_"+i).val("");
              $("#3_"+i).val("");
              $("#4_"+i).val("");
              $("#5_"+i).val("");
              $("#6_"+i).val("");
              $("#descrip_"+i).val("");
              $("#trans_code"+i).val("");
            }   
          } 




          function set_cus_values(f){
            var v = f.val();
            v = v.split("-");
            
            if(v.length == 2){
                //$("#vehicle_no").val(v[0]);
                f.val(v[0]);
                $("#customer_id").val(v[1]);


              }
            }

            function formatItems(row){
              return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
            }

            function formatItemsResult(row){
              return row[0]+"-"+row[1];
            }





            function save(){
              $("#btnSave").attr("disabled",true);
              $("#recivied").val($("#net").val());
              $("#qno").val($("#id").val());
              $("#dt").val($("#date").val());
              $("#cus_id").val($("#customer").val());
              $("#is_duplicate").val("1");

              $('#form_').attr('action',$('#form_id').val()+"t_receipt");
              var frm = $('#form_');
              if(save_status!=0){
               loding();
               $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (pid){
                  var sid=pid.split('@');
                  if(pid == 0){
                    set_msg("Transaction is not completed");    
                  }else if(pid == 2){
                    set_msg("No permission to add data.");
                  }else if(pid == 3){
                    set_msg("No permission to edit data.");
                  }else if(sid[0]==1){
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
            if($("#customer_id").val()=="" || $("#customer_id").attr("title")==$("#customer_id").val()){
             set_msg("Please Enter Customer","error");
             $("#customer_id").focus();
             $("#btnSave").removeAttr("disabled");
             return false;
           }else if($("#sales_rep2").val()=="" || $("#sales_rep").val()==0){
             set_msg("Please Enter Sales Rep","error");
             $("#sales_rep").focus();
             $("#btnSave").removeAttr("disabled");
             return false;
           }else if($("#net").val()=="" || $("#net").val()==0){
             set_msg("Please Enter Payment","error");
             $("#net").focus();
             $("#btnSave").removeAttr("disabled");
             return false;
           }else if($("#load_opt").val()==0 && $("#payment_option").is(':checked')){
             var net_value=parseFloat($("#net_val").val());
             var balance=parseFloat($("#balance2").val());
             payment_opt('t_receipt', m_round($("#net").val()));  
             $("#btnSave").removeAttr("disabled");             
             return false;              
           }else{
            return true;
          }
        }




        function set_delete(){
          var id = $("#hid").val();
          if(id != 0){
            if(confirm("Are you sure to delete this receipt ["+$("#id").val()+"]? ")){
              $.post("index.php/main/delete/t_receipt", {
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

        function is_edit($mod)
        {
          $.post("index.php/main/is_edit/user_permissions/is_edit", {
            module : $mod

          }, function(r){
           if(r==1)
           {
             $("#btnSave").removeAttr("disabled", "disabled");
           }
           else{
             $("#btnSave").attr("disabled", "disabled");
           }

         }, "json");

        }

        function set_edit(code){
          loding();
          $.post("index.php/main/get_data/t_receipt", {
            code : code
          }, function(res){
            $("#code_").val(res.code);
            $("#code").val(res.code);
            $("#code").attr("readonly", true);
            $("#description").val(res.description);

            if(res.is_vehical == 1){
              $("#is_vehical").attr("checked", "checked");
            }else{
              $("#is_vehical").removeAttr("checked");
            }



       // is_edit('010');
       loding(); input_active();
     }, "json");
        }



        function show_pre_result(result){
          $("#points").val(m_round(result));
        }


        function load_data(id){
          empty_grid();
          loding();
          $.post("index.php/main/get_data/t_receipt/", {
            id: id
          }, function(r){

            if(r=="2"){
             set_msg("No records");
           }else{
            $("#hid").val(id);    
            $("#customer").val(r.sum[0].cus_acc);
            $("#customer_id").val(r.sum[0].cus_name);
            $("#memo").val(r.sum[0].memo);
            $("#sales_rep").val(r.sum[0].rep);
            $("#sales_rep2").val(r.sum[0].emp_name);
            $("#net").val(r.sum[0].payment);
            $("#ref_no").val(r.sum[0].ref_no);
            $("#date").val(r.sum[0].ddate);
            $("#dt").val(r.sum[0].ddate);
            $("#qno").val(id);
            $("#sub_no").val(r.sum[0].sub_no);
            $("#type").val(r.sum[0].type);
            $("#ddate").val(r.sum[0].inv_date);
            $("#balance").val(r.sum[0].balance);
            $("#recivied").val(r.sum[0].payment);
            $("#cus_id").val(r.sum[0].cus_acc);

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
            $("#pdchq").val(r.sum[0].pay_post_dated_chq);


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

            $("#is_multi_branch").attr("disabled","disabled");
            $("#load_details").attr("disabled","disabled");
            $("#auto_fill").attr("disabled","disabled");
            
            if(r.sum[0].multi_branch==1){
              $("#is_multi_branch").attr("checked", "checked");
            }else{
              $("#is_multi_branch").removeAttr("checked");
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

            $("#id").attr("readonly","readonly")            

            var settlement=0;
            var balance=parseFloat(r.sum[0].payment);
            for(var i=0; i<r.det.length;i++){
              $("#cl0_"+i).val(r.det[i].to_cl);
              $("#bc0_"+i).val(r.det[i].to_bc);
              $("#trans_code"+i).val(r.det[i].trans_code);
              $("#1_"+i).val(r.det[i].description);
              $("#2_"+i).val(r.det[i].trans_no);
              $("#3_"+i).val(r.det[i].date);
              $("#4_"+i).val(r.det[i].amount);
              $("#descrip_"+i).val(r.det[i].des1);
              $("#5_"+i).val(r.det[i].balance);
              $("#6_"+i).val(r.det[i].payment);
              $("#is_install_"+i).val(r.det[i].is_install);
              if(r.det[i].is_install==1){
                $('#btn_'+i).css("display","block");
              }


              if(r.det[i].is_penalty==1){
                $('#btn2_'+i).css("display","block");
              }


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
            }, 200);
          }
          loding();
        }, "json");
}

function empty_grid(){
 for(var i=0; i<300; i++){
  $("#cl0_"+i).val("");
  $("#bc0_"+i).val("");
  $("#btn_"+i).css("display","none");
  $("#descrip_"+i).val(""); 
  $("#1_"+i).val("");
  $("#2_"+i).val("");
  $("#3_"+i).val("");
  $("#4_"+i).val("");
  $("#5_"+i).val("");
  $("#6_"+i).val("");

}
}