$(document).ready(function(){

	$("#btnReset").click(function(){
		location.href="index.php?action=t_bankrec";
	});

  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });

  $("#btnDelete").click(function(){
    set_delete($("#id").val());
  });

  $("#btnSaveTemp").click(function(){
    $("#temp_save").val("1");
    save();
  });

  $("#un_deposit").click(function(){
    if($("#bank_id").val()!=""){
      window.open('?action=t_bankrec_additional_det&xxx='+$('#id').val()+'='+$('#date').val()+'='+$('#bank_id').val()+'',"_blank");  
    }else{
      set_msg("Please select bank");
    }
  });

  $("#er_bank").click(function(){
    if($("#bank_id").val()!=""){
      window.open('?action=t_bankrec_error_made_bank&xxx='+$('#id').val()+'='+$('#date').val()+'='+$('#bank_id').val()+'',"_blank");  
    }else{
      set_msg("Please select bank");
    }
  });

  $("#bank_chg").click(function(){
    if($("#bank_id").val()!=""){
      window.open('?action=t_bankrec_bank_chg&xxx='+$('#id').val()+'='+$('#date').val()+'='+$('#bank_id').val()+'',"_blank");  
    }else{
      set_msg("Please select bank");
    }
  });

  $("#code").blur(function(){
    check_code();
  });

  $("#id").keypress(function(e){
    if(e.keyCode==13){
      load_data($(this).val());
    }
  })   
  $("#tgrid").tableScroll({height:300,width:980});


  $("#bank_id").keypress(function(e){
    if(e.keyCode == 112){
      $("#pop_search6").val();
      load_bank();
      $("#serch_pop6").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search6').focus()", 100);
    }
    $("#pop_search6").keyup(function(e){            
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_bank();
      }
    }); 
    if(e.keyCode == 46){
      $("#bank_id").val("");
      $("#bank").val("");
    }
  });

  $("#load").click(function(){
    if($("#bank_id").val()!=""){
      load_grid();
      op_bal();
      
    }else{
      set_msg("Please Select Bank")
    }
  });

  $(".chk").click(function(){
    set_cid($(this).attr("id"));
    chk_items();
    get_selected(scid);
    /*cl_bal();*/
  });

    //reconz_0
    
  });

function save(){
  loding();
  $("#qno").val($("#id").val());
  var frm = $('#form_');
  loding();
  $.ajax({
   type: frm.attr('method'),
   url: frm.attr('action'),
   data: frm.serialize(),
   success: function (pid){
    if(pid == 1){
      if(confirm("Save Completed, Do You Want A print?")){
        if($("#is_prnt").val()==1){
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

function reload_form(){
  setTimeout(function(){
    location.href= '';
  },50); 
}

function load_data(id){
  loding();
  $.post("index.php/main/load_data/t_bankrec/load", {
    code : id
  },function(res){
    loding();
    if(res != 2){
      empty_grid();
      $("#load").attr("disabled",true);
      $("#hid").val(res.sum[0].nno);
      $("#qno").val(res.sum[0].nno);
      $("#id").val(res.sum[0].nno);
      $("#bank_id").val(res.sum[0].account_id);
      $("#bank").val(res.sum[0].acc_des);
      $("#date_from").val(res.sum[0].date_from);
      $("#date_to").val(res.sum[0].date_to);
      $("#date").val(res.sum[0].ddate);
      $("#op_balance").val(res.sum[0].opening_bal);
      $("#cl_balance").val(res.sum[0].closing_bal);
      var tot_tick=tot_n_tick=tot_dr=tot_cr=0;
      for(var x=0; x<res.det.length; x++){
        $("#date_"+x).val(res.det[x].trans_date);
        $("#description_"+x).val(res.det[x].description);
        $("#tc_"+x).val(res.det[x].trans_code);
        $("#tcd_"+x).val(res.det[x].t_des);
        $("#no_"+x).val(res.det[x].trans_no);
        $("#dr_"+x).val(res.det[x].dr);
        $("#cr_"+x).val(res.det[x].cr);
        $("#auto_"+x).val(res.det[x].auto);
        if(res.det[x].is_reconcil==1){
          $("#reconz_"+x).attr("checked",true);
          tot_tick++;
        }else{
          $("#reconz_"+x).attr("checked",false);  
          tot_n_tick++;
        }  
        tot_dr+=parseFloat(res.det[x].dr);
        tot_cr+=parseFloat(res.det[x].cr);

        get_selected(x);          
      }
      $("#total_no").val(res.det.length);
      $("#chk_no").val(tot_tick);
      $("#unchk_no").val(tot_n_tick);
      $("#total_dr").val(m_round(tot_dr));
      $("#total_cr").val(m_round(tot_cr));

      if(res.sum[0].is_cancel==1){
        $("#btnDelete").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
      }
    }
  }, "json");
}



function get_data_table(){
  $.post("/index.php/main/load_data/t_bankrec/get_data_table", {

  }, function(r){
    $("#grid_body").html(r);
  }, "text");
}


function check_code(){
  loding();
  var code = $("#code").val();
  $.post("index.php/main/load_data/t_bankrec/check_code", {
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
  /*  if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
        alert("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
        alert("Please enter description.");
        $("#description").focus();
        return false;
    }else if($("#description").val() === $("#code").val()){
        alert("Please enter deferent values for description & code.");
        $("#des").focus();
        return false;
    }else{
       
    } */return true;
  }

  function set_delete(code){
    if(confirm("Are you sure delete no "+code+"?")){
      loding();
      $.post("index.php/main/delete/t_bankrec", {
        code : code
      }, function(res){
        if(res == 1){
          delete_msg();
        }else{
          set_msg(res);
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
    $.post("index.php/main/get_data/t_bankrec", {
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

  function load_bank(){
    $.post("index.php/main/load_data/t_bankrec/select_bank", {
      search : $("#pop_search6").val() 
    }, function(r){
      $("#sr6").html(r);
      settings_banks();            
    }, "text");
  }

  function settings_banks(){
    $("#item_list .cl").click(function(){        
      $("#bank_id").val($(this).children().eq(0).html());
      $("#bank").val($(this).children().eq(1).html());
      $("#pop_close6").click(); 
    })    
  }

  function load_grid(){
   $.post("index.php/main/load_data/t_bankrec/load_grid", {
    acc_code :$("#bank_id").val(),
    date_from :$("#date_from").val(),
    date_to :$("#date_to").val()

  }, function(r){
    if(r==2){
      set_msg("No Records");
    }else{
      empty_grid();
      var tot_dr=0;
      var tot_cr=0;

      v=0;
      for(var x=0; x<r.det.length; x++){

        $("#date_"+x).val(r.det[x].ddate);
        $("#description_"+x).val(r.det[x].description);             
        $("#tc_"+x).val(r.det[x].trans_code);
        $("#tcd_"+x).val(r.det[x].trans);
        $("#no_"+x).val(r.det[x].trans_no);
        $("#dr_"+x).val(r.det[x].dr_amount);
        $("#cr_"+x).val(r.det[x].cr_amount);
        $("#auto_"+x).val(r.det[x].auto_no);

        v++;

        $("#total_no").val(v);

        if(r.det[x].dr_amount!=""){
         tot_dr+=parseFloat(r.det[x].dr_amount);
         $("#total_dr").val(m_round(tot_dr));
       }
       if(r.det[x].cr_amount!=""){
         tot_cr+=parseFloat(r.det[x].cr_amount);
         $("#total_cr").val(m_round(tot_cr));
       }

       if(r.det[x].reconcile=='1'){
        $("#reconz_"+x).attr("checked",true);
      }

    }
  } 
}, "json");
 } 

 function chk_items(){
  v=0;
  z=0;
  for(var x=0; x<200; x++){
    if($("#reconz_"+x).is(":checked")){
      v++;
    }
    if(!$("#reconz_"+x).is(":checked")){
      z++;
    }
  }
  $("#chk_no").val(v);
  $("#unchk_no").val(z);
}

function get_selected(scid){
  var chk_dr_v =parseFloat(0);
  var chk_cr_v = parseFloat(0); 
  var unchk_dr_v = parseFloat(0); 
  var unchk_dr_V =parseFloat(0);
  var cl_bal_v =parseFloat(0);

  $(".bl").each(function(e){

    if($("#dr_"+e).val()!="" && $("#reconz_"+e).is(":checked")){            

      chk_dr_v+=parseFloat($("#dr_"+e).val());
    }
    if($("#cr_"+e).val()!="" && $("#reconz_"+e).is(":checked")){            

      chk_cr_v+=parseFloat($("#cr_"+e).val());
    }
    if($("#dr_"+e).val()!="" && !$("#reconz_"+e).is(":checked")){            

      unchk_dr_v+=parseFloat($("#dr_"+e).val());
    }
    if($("#cr_"+e).val()!="" && !$("#reconz_"+e).is(":checked")){            

      unchk_dr_V+=parseFloat($("#cr_"+e).val());
    }
    ;
/*
        if($("cl_balance").val()!="" && !$("#reconz_"+e).is(":checked")){            
            
                cl_bal_v+=parseFloat($("#cr_"+e).val());
                alert(cl_bal_v);
              }*/

            });

  $("#chk_dr").val(m_round(chk_dr_v));  
  $("#chk_cr").val(m_round(chk_cr_v));   
  $("#unchk_dr").val(m_round(unchk_dr_v));  
  $("#unchk_cr").val(m_round(unchk_dr_V)); 

  var tot_dr=$("#chk_dr").val();
  var tot_cr=$("#chk_cr").val();
  var opn_bal=$("#op_balance").val();

  cl_bal_v=parseFloat(opn_bal)+parseFloat(tot_dr)-parseFloat(tot_cr);
  
    //alert(cl_bal_v);  
    $("#cl_balance").val(m_round(cl_bal_v)); 

  }

  function op_bal(){
    $.post("index.php/main/load_data/t_bankrec/op_bal", {
      acc_code :$("#bank_id").val(),
      date_from :$("#date_from").val(),
      date_to :$("#date_to").val()

    }, function(r){
      if(r==2){
        set_msg("No Records");
      }else{
        var op_bal=parseFloat(r.op[0].bal);
        $("#op_balance").val(m_round(op_bal));  
      }

    },"json");

  }

  function empty_grid(){
    for(var x=0; x<200; x++){
      $("#code_"+x).val("");
      $("#date_"+x).val("");
      $("#description_"+x).val("");
      $("#tc_"+x).val("");
      $("#tcd_"+x).val("");
      $("#no_"+x).val("");
      $("#dr_"+x).val("");
      $("#cr_"+x).val("");
      $("#reconz_"+x).attr("checked",false);
    }
  }
