$(document).ready(function(){

  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });
  $("#grid").tableScroll({height:355,width:1190});

  $("#cluster").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search").val();
        load_cluster();
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search').focus()", 100);
      }
      $("#pop_search").keyup(function(e){            
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_cluster();
        }
      }); 
      if(e.keyCode == 46){
        $("#cluster").val("");
        $("#cluster_des").val("");
      }
  });

$("#cus_search").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search15").val();
      pop_cus();
      $("#serch_pop15").center();
      $("#blocker").css("display","block");
      setTimeout("$('#pop_search15').focus()",100);
    }
    $("#pop_search15").keyup(function(e){
      pop_cus()
    });
    if(e.keyCode == 46){
      $("#cus_search").val("");
      $("#cus_desc").val("");

    }
});

  $("#branch").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search4").val();
        load_branch();
        $("#serch_pop4").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search4').focus()", 100);
      }
      $("#pop_search4").keyup(function(e){            
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) { 
          load_branch();
        }
      }); 
      if(e.keyCode == 46){
        $("#branch").val("");
        $("#branch_des").val("");
      }
  });

  $(".tt").click(function(){
    if($("#cr_type").is(":checked")){
      $("#heading_top").html("Customer");
      $("#p_type").val("customer");
    }else{
      $("#heading_top").html("Supplier");
      $("#p_type").val("supplier");
    }
  })

  $("#find").click(function(){
    if($("#cr_type").is(":checked")){
      load_chq_details_r();
    }else{
      load_chq_details_p();
    }
  });

  $(".rmks").click(function(){
    set_cid($(this).attr("id"));
    load_return_details();
  });

});

function load_cluster(){
  $.post("index.php/main/load_data/f_return_chq_reg/load_cluster", {
    search : $("#pop_search").val(),
  }, function(r){
      $("#sr").html(r);
      setting_cluster();
  }, "text");
}

function setting_cluster(){
  $("#item_list .cl").click(function(){        
    $("#cluster").val($(this).children().eq(0).html());
    $("#cluster_des").val($(this).children().eq(1).html());
    $("#pop_close").click();                
  });    
}

function load_branch(){
  $.post("index.php/main/load_data/f_return_chq_reg/load_branch", {
    search : $("#pop_search").val(),
    cl: $("#cluster").val(),
  }, function(r){
      $("#sr4").html(r);
      setting_branch();
  }, "text");
}

function setting_branch(){
  $("#item_list .cl").click(function(){        
    $("#branch").val($(this).children().eq(0).html());
    $("#branch_des").val($(this).children().eq(1).html());
    $("#pop_close4").click();                
  });    
}


function load_chq_details_r(){
  $.post("index.php/main/load_data/f_return_chq_reg/load_chq_details_r", {
    f_date : $("#f_date").val(),
    t_date: $("#t_date").val(),
    cl:$("#cluster").val(),
    bc:$("#branch").val(),
    search:$("#cus_search").val(),
  }, function(r){
      empty_grid();
      if(r!=2){
        for(var x=0; x<r.length; x++){
          $("#dt_"+x).val(r[x].ddate);
          $("#c_"+x).val(r[x].name);
          $("#ccode_"+x).val(r[x].customer);
          $("#chqn_"+x).val(r[x].cheque_no);
          $("#amnt_"+x).val(r[x].amount);
          $("#acc_"+x).val(r[x].account);
          $("#b_"+x).val(r[x].bank_name);
          $("#bcode_"+x).val(r[x].bank);
          $("#tr_"+x).val(r[x].description);
          $("#trn_"+x).val(r[x].trans_no);
          $("#rdate_"+x).val(r[x].realize_date);
        }
      }else{
        set_msg("no records. ");
      }
  }, "json");
}


function load_chq_details_p(){
  $.post("index.php/main/load_data/f_return_chq_reg/load_chq_details_p", {
    f_date : $("#f_date").val(),
    t_date: $("#t_date").val(),
    cl:$("#cluster").val(),
    bc:$("#branch").val(),
    search:$("#cus_search").val(),
  }, function(r){
      empty_grid();
      if(r!=2){
        for(var x=0; x<r.length; x++){
          $("#dt_"+x).val(r[x].ddate);
          $("#c_"+x).val(r[x].name);
          $("#ccode_"+x).val(r[x].customer);
          $("#chqn_"+x).val(r[x].cheque_no);
          $("#amnt_"+x).val(r[x].amount);
          $("#acc_"+x).val(r[x].account);
          $("#b_"+x).val(r[x].bank_name);
          $("#bcode_"+x).val(r[x].bank);
          $("#tr_"+x).val(r[x].description);
          $("#trn_"+x).val(r[x].trans_no);
          $("#rdate_"+x).val(r[x].realize_date);
        }
      }else{
        set_msg("no records. ");
      }
  }, "json");
}

function empty_grid(){
  for(var x=0; x<50; x++){
    $("#dt_"+x).val("");
    $("#c_"+x).val("");
    $("#ccode_"+x).val("");
    $("#chqn_"+x).val("");
    $("#amnt_"+x).val("");
    $("#acc_"+x).val("");
    $("#b_"+x).val("");
    $("#bcode_"+x).val("");
    $("#tr_"+x).val("");
    $("#trn_"+x).val("");
    $("#rdate_"+x).val("");
  }
}

function pop_cus(){
  $.post("index.php/main/load_data/utility/f1_selection_list_customer",{
    data_tbl:"m_customer",
    field:"code",
    field2:"name",
    field3:"nic",
    search:$("#pop_search15").val()

  },function(r){
    $("#sr15").html(r);
    cus_sttings();
  },"text");
}
function cus_sttings(){
  $("#item_list .cl").click(function(){
    $("#cus_search").val($(this).children().eq(0).html());
    $("#cus_desc").val($(this).children().eq(1).html());
    $("#pop_close15").click();
  })

}

