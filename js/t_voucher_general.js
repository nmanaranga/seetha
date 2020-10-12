$(document).ready(function(){
//  $("#id").attr("readonly","readonly");

  $("#btnSave").attr("disabled", true);

  $("#btnSave2").click(function(){
    $("#btnSave").attr("disabled", false);
  });

  $("#id").dblclick(function(){
    $("#id").attr("readonly",false);
  });

  $("#type").val("chaque");
  type = $("#type").val();
  $("#type_hid").val(type);
  get_type();

  if($("#type").val()=="chaque"){
   $(".acc_types").attr("readonly",true);
   $("#cash_acc").addClass("hid_value");
   $("#cash_acc").removeClass("input_txt");
   $("#cash_acc").val("");
   $("#cash_acc_des").val("");
   $("#balance").val("");
 }

 $("#btnReset").click(function(){
  location.href="index.php?action=t_voucher_general";
});

 $("#code").blur(function(){
  check_code();
});

 $("#type").change(function(){
   $("#type").val();
   type = $("#type").val();
   $("#type_hid").val(type);

   if($("#type").val()=="chaque"){          
     $(".acc_types").attr("readonly",true);
     $("#cash_acc").addClass("hid_value");
     $("#cash_acc").removeClass("input_txt");
     $("#cash_acc").val("");
     $("#cash_acc_des").val("");
     $("#balance").val("");
   }else{
    $(".acc_types").attr("readonly",false);
    $("#cash_acc").addClass("input_txt");
    $("#cash_acc").removeClass("hid_value");
    default_acc();
  }
});

 $("#showPayments").click(function(){
  var net_value=parseFloat($("#net").val());
  payment_opt('t_voucher',m_round(net_value));

});

 $("#tgrid").tableScroll({height:300});

 $("#cash_acc").keydown(function(e){
        /*if($("#type").val()=="cash"){          
            if(e.keyCode==112){
                $("#pop_search").val($("#cash_acc").val());
                load_data2();
                $("#serch_pop").center();
                $("#blocker").css("display", "block");
                setTimeout("select_search()", 100);     
            }
            $("#pop_search").keyup(function(e){
                load_data2();
            });
          }*/
        });

 $(".fo").keydown(function(e){
  set_cid($(this).attr("id"));
  if(e.keyCode==112){
    $("#pop_search").val($("#0_"+scid).val());
    load_items(scid);
    $("#serch_pop").center();
    $("#blocker").css("display", "block");
    setTimeout("select_search()", 100);     
  }
  if(e.keyCode==46){
    $("#h_"+scid).val("");
    $("#0_"+scid).val("");
    $("#n_"+scid).val("");
    $("#1_"+scid).val("");
    $("#2_"+scid).val("");
    gross_amount();
  }
  $("#pop_search").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
      load_items(scid);
    }
  });
});

 $("#sales_category").change(function() {
   get_group();
 });

 $("#type").change(function(){

   get_type();
 });

 $(".amount").blur(function(){

  gross_amount();
  var tot = $("#net").val();

  if($("#type").val() == "cash"){

    $("#cash").val(tot);
  }
  if($("#type").val() == "chaque"){

    $("#cheque_issue").val(tot);
  }


});

 $("#id").keydown(function(e){
  if(e.keyCode == 13){
    $(this).blur();
    load_data($(this).val());
  }
});

 $("#hid_nno").keydown(function(e){
  if(e.keyCode == 13){
    $(this).blur();
    load_data($(this).val(),'1');

  }
});

 $("#btnDelete").click(function(){
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
});

function default_acc(){
  $.post("index.php/main/load_data/t_voucher_general/get_default_acc", {
  }, function(r){
    if(r!=2){
      $("#cash_acc").val(r[0].code);
      $("#cash_acc_des").val(r[0].description);

      $.post("index.php/main/load_data/utility/get_account_balance_voucher",{
        code :r[0].code
      },   
      function(rs){
        $("#balance").val(rs); 
        input_active();
      },"json");
    }       
  }, "json");
}



function save(){
  $("#p_hid_nno").val($("#hid_nno").val());
  $("#qno").val($("#id").val());
  $("#voucher_type").val($("#type_hid").val());
  $("#ddate").val($("#date").val());
  $("#acc_code").val($("#cash_acc").val());
  $("#acc_des").val($("#cash_acc_des").val());
  $("#is_duplicate").val("1");
  $("#tot").val($("#net").val());
  $('#form_').attr('action',$('#form_id').val()+"t_voucher_general");

  var frm = $('#form_');
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
               // sucess_msg();
               if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                        //$("#qno").val(sid[1]);
                        //alert(sid[1]);
                        $("#print_pdf").submit();
                      }
                      location.href="";
                    }else{
                      location.href="";
                    }


                  }else if(pid == 2){
                    alert("No permission to add data.");
                  }else if(pid == 3){
                    alert("No permission to edit data.");
                  }else{
                    set_msg(pid);
                  }
                  loding();
                }
              });
}

function get_data_table(){
  $.post("/index.php/main/load_data/t_voucher_general/get_data_table", {

  }, function(r){
    $("#grid_body").html(r);
  }, "text");
}


function check_code(){
  loding();
  var code = $("#code").val();
  $.post("index.php/main/load_data/t_voucher_general/check_code", {
    code : code
  }, function(res){
    if(res == 1){
      if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
        set_edit(code);
      }else{
        $("#code").val('');
        $("#code").attr("readonly", false);
      }
    }
    loding();
  }, "text");
}

function validate(){
  if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
    set_msg("Please enter description.");
    $("#description").focus();
    return false;
  }else if($("#description").val() === $("#code").val()){
    set_msg("Please enter deferent values for description & code.");
    $("#des").focus();
    return false;
  }else if($("#sales_category").val()=="0"){
    set_msg("Please select category");
    return false;  
  }else if($("#type_hid").val() == 'chaque' && parseFloat($("#cash").val())>0){
    set_msg("Please change type or add cheque amount.");
    return false;
  }else if($("#type_hid").val() == 'cash' && parseFloat($("#cheque_issue").val())>0){
    set_msg("Please change type or add cash amount.");
    return false;       
  }else{
    return true;
  }
}

function set_delete(){

  var code = $("#id").val();
  var type = $("#type_hid").val();

  if(confirm("Are you sure delete "+code+"?")){
    loding();
    $.post("index.php/main/delete/t_voucher_general", {
      code : code,
      type2 :type,
      hid :$("#hid_nno").val() 
    }, function(res){
      if(res == 1){
       delete_msg();
     }else if(res == 2){
      alert("No permission to delete data.");
    }else{
      alert("Item deleting fail.");
    }
    loding();
  }, "text");
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
  $.post("index.php/main/get_data/t_voucher_general", {
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

function get_group(){
  $.post("index.php/main/load_data/r_groups/select_by_category", {
    category_id : $("#sales_category").val()
  }, function(r){
   $("#groups").html(r);
 }, "text");

}

function load_data2(){

  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"m_account",
    field:"code",
    field2:"description",
    add_query : "AND control_acc NOT IN ((SELECT `acc_code` FROM `m_default_account` WHERE `code` = 'CREDITOR_CONTROL')  AND ((SELECT `acc_code` FROM `m_default_account` WHERE `code` = 'DEBTOR_CONTROL')))",
    search : $("#pop_search").val() 
  }, function(r){   
    $("#sr").html(r);
    settings();

  }, "text");
}

function load_items(scid){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"m_account",
    field:"code",
    field2:"description",
    search :$("#pop_search").val()
  }, function(r){
    $("#sr").html(r);
    settings2(scid);        
  }, "text");
}

function select_search(){
  $("#pop_search").focus();

}

function settings(){
  $("#item_list .cl").click(function(){        
    $("#cash_acc").val($(this).children().eq(0).html());
    $("#cash_acc_des").val($(this).children().eq(1).html());
    $("#pop_close").click();
    var acc_code = $(this).children().eq(0).html(); 
    $.post("index.php/main/load_data/utility/get_account_balance_voucher",{
      code : acc_code
    },   
    function(rs){
      $("#balance").val(rs); 
      input_active();
    },"json");

  });    
}

function settings2(scid){

  $("#item_list .cl").click(function(){
    if(check_item_exist($(this).children().eq(0).html())){        
      $("#0_"+scid).val($(this).children().eq(0).html());
      $("#h_"+scid).val($(this).children().eq(0).html());
      $("#n_"+scid).val($(this).children().eq(1).html());
      $("#pop_close").click();
    }else{
      alert("Account code "+$(this).children().eq(1).html()+" is already added.");
    }
  });    
}



function gross_amount(){

  var gross=loop=0;

  $(".amount").each(function(){
    var gs=parseFloat($("#1_"+loop).val());
    if(!isNaN(gs)){    
      gross=gross+gs;
    }    
    loop++;
  });
  $("#net").val(m_round(gross));

}

function empty_grid(){
  for(var i=0; i<25; i++){
    $("#h_"+i).val("");
    $("#0_"+i).val("");
    $("#n_"+i).val("");
    $("#1_"+i).val("");
    $("#2_"+i).val("");
  }
}

function load_data(id,i=0){
  empty_grid();
  $.post("index.php/main/load_data/t_voucher_general/get_data", {
    id : id,
    type:$("#type").val(),
    i:i
  }, function(r){



    $("#ref_no").val(r.sum.ref);
    $("#cash_acc").val(r.sum.paid_acc_code);
    $("#cash_acc_des").val(r.sum.paid_acc_des);
    $("#description").val(r.sum.note);
    $("#sales_category").val(r.sum.cat_code);
    $("#groups").val(r.sum.groups_code);
    $("#type").val(r.sum.type);
    $("#date").val(r.sum.ddate); 
    $("#cash").val(r.sum.cash_amount);
    $("#cheque_issue").val(r.sum.cheque_amount);
    $("#net").val(r.sum.tot); 
    $("#hid").val(r.sum.nno);
    $("#hid_nno").val(r.sum.hid_nno);
    $("#hx").val(r.sum.hid_nno);
    $("#p_hid_nno").val(r.sum.hid_nno);
    $("#id").val(r.sum.nno);
        //set value for hidden fields of pdf report
        $("#qno").val(r.sum.nno);
        $("#voucher_type").val(r.sum.type);
        $("#voucher_no").val(r.sum.nno);
        $("#category_id").val(r.sum.cat_code);
        $("#cat_des").val(r.sum.cat_des);
        $("#group_id").val(r.sum.groups_code);
        $("#group_des").val(r.sum.name);
        $("#ddate").val(r.sum.ddate);
        $("#acc_code").val(r.sum.paid_acc_code);
        $("#acc_des").val(r.sum.paid_acc_des);
        $("#vou_des").val(r.sum.note);
        $("#payee_name").val(r.sum.payee_name);

        if(r.sum.is_cancel==1){
         $("#btnDelete").attr("disabled", true);
         $("#showPayments").attr("disabled", true);
         $("#btnSave").attr("disabled", true);
         $("#mframe").css("background-image", "url('img/cancel.png')");
       }

       for(var i=0; i<r.det.length; i++){
        $("#h_"+i).val(r.det[i].acc_code);
        $("#0_"+i).val(r.det[i].acc_code);
        $("#n_"+i).val(r.det[i].description);
        $("#1_"+i).val(r.det[i].amount);
        $("#2_"+i).val(r.det[i].ref_no);

        gross_amount();
      }

      if(r.cheque.length>0){
        for(var i=0; i<r.cheque.length; i++){
          $("#bank7_"+i).val(r.cheque[i].bank);
          $("#des7_"+i).val(r.cheque[i].description);
          $("#chqu7_"+i).val(r.cheque[i].cheque_no);
          $("#amount7_"+i).val(r.cheque[i].amount);
          $("#cdate7_"+i).val(r.cheque[i].cheque_date);
        }
      }

      tot = $("#net").val();
      $("#tot").val(tot);

      var type = $("#type").val();
      $("#type_hid").val(type);

      $("#type").prop("disabled", true);
      load_payment_option_data($("#hid_nno").val(),"48");


    }, "json");
}

function get_type(){

  $.post("index.php/main/load_data/t_voucher_general/get_max_no_type", {
    table:'t_voucher_gl_sum',
    nno:'sub_no',
    type:$("#type").val(),
    hid:$("#hid").val(),
  }, function(res){
    $("#id").val(res);
              //empty_grid();
              //empty_all();
            },"text");
}

function check_item_exist(id){
  var v = true;
  $("input[type='hidden']").each(function(){
    if($(this).val() == id){
      v = false;
    }
  });

  return v;
}