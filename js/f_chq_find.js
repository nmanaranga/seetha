$(document).ready(function(){

  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });
  var wh=$("#mframe2").width()-20;
  $("#grid").tableScroll({height:300, width:wh});
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

  $("#chq_type").change(function(){
    if($("#chq_type").val()=="1"){
      $(".heading_top").html("Received Acc");
      $(".heading_date").html("Realize Date");
     }else if($("#chq_type").val()=="2"){
      $(".heading_top").html("Issued To Acc");
      $(".heading_date").html("Bank Date");
    }else if($("#chq_type").val()=="3"){
      $(".heading_date").html("Realize Date");
    }
  });

  $(".num_find").click(function() {
    $(".num_find").attr('checked', false);
    $(this).attr('checked', true);
  });

  $("#find").click(function(){
    if($("#chq_type").val()=="1"){
      load_chq_details_received();
    }else if($("#chq_type").val()=="2"){
      load_chq_details_issued();
    }else if($("#chq_type").val()=="3"){
      load_chq_acknowledge();
    }
  });

  $(".rmks").click(function(){
    set_cid($(this).attr("id"));
    load_return_details();
  });

});

function load_cluster(){
  $.post("index.php/main/load_data/f_chq_find/load_cluster", {
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
  $.post("index.php/main/load_data/f_chq_find/load_branch", {
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

function load_chq_acknowledge(){
  if($("#f_date").is(":checked")){date=1;}else{date=0;}
  if($("#f_racc").is(":checked")){r_acc=1;}else{r_acc=0;}
  if($("#f_chq").is(":checked")){chq=1;}else{chq=0;}
  if($("#f_amount").is(":checked")){amount=1;}else{amount=0;}
  if($("#f_rdate").is(":checked")){r_date=1;}else{r_date=0;}
  if($("#f_acc").is(":checked")){acc=1;}else{acc=0;}
  if($("#f_bank").is(":checked")){bank=1;}else{bank=0;}
  if($("#f_branch").is(":checked")){b_branch=1;}else{b_branch=0;}
  if($("#f_status").is(":checked")){status=1;}else{status=0;}

  $.post("index.php/main/load_data/f_chq_find/chq_acknowledge",{

    cl:$("#cluster").val(),
    bc:$("#branch").val(),
    find:$("#find_txt").val(),
    date:date,
    r_acc:r_acc,
    chq:chq,
    amount:amount,
    r_date:r_date,
    acc:acc,
    bank:bank,
    b_branch:b_branch,
    status:status
  },function(r){
    empty_grid();
    if(r.data==2){
      set_msg("No Records");
    }else{

     for(var x=0; x<r.data.length; x++){

      $("#cl_"+x).val(r.data[x].cl);
      $("#bc_"+x).val(r.data[x].bc);
      $("#dt_"+x).val(r.data[x].date);
      $("#chqn_"+x).val(r.data[x].cheque_no);
      $("#amnt_"+x).val(r.data[x].amount);
      $("#acc_"+x).val(r.data[x].account);
      $("#b_"+x).val(r.data[x].bank_name);
      $("#bcode_"+x).val(r.data[x].bank);
      $("#br_"+x).val(r.data[x].branch_name);
      $("#brcode_"+x).val(r.data[x].branch);
      $("#tr_"+x).val(r.data[x].description);

      $("#rdate_"+x).val(r.data[x].realize_date);
      var status ="";
      if(r.data[x].status=="P"){
        status="Pending";
      }else if(r.data[x].status=="D"){
        status="Deposit";
      }else if(r.data[x].status=="R"){
        status="Receipted";
      }
      $("#status_"+x).val(status);
    }
  }
},"json");

}
function load_chq_details_received(){
  if($("#f_date").is(":checked")){date=1;}else{date=0;}
  if($("#f_racc").is(":checked")){r_acc=1;}else{r_acc=0;}
  if($("#f_chq").is(":checked")){chq=1;}else{chq=0;}
  if($("#f_amount").is(":checked")){amount=1;}else{amount=0;}
  if($("#f_rdate").is(":checked")){r_date=1;}else{r_date=0;}
  if($("#f_acc").is(":checked")){acc=1;}else{acc=0;}
  if($("#f_bank").is(":checked")){bank=1;}else{bank=0;}
  if($("#f_branch").is(":checked")){b_branch=1;}else{b_branch=0;}
  if($("#f_status").is(":checked")){status=1;}else{status=0;}
  if($("#f_racc").is(":checked")){f_racc=1;}else{f_racc=0;}

  $.post("index.php/main/load_data/f_chq_find/load_chq_details_received", {
    cl:$("#cluster").val(),
    bc:$("#branch").val(),
    find:$("#find_txt").val(),
    date:date,
    r_acc:r_acc,
    f_racc:f_racc,
    chq:chq,
    amount:amount,
    r_date:r_date,
    acc:acc,
    bank:bank,
    b_branch:b_branch,
    status:status
  }, function(r){
    empty_grid();
    if(r!=2){
      for(var x=0; x<r.length; x++){
        $("#cl_"+x).val(r[x].cl);
        $("#bc_"+x).val(r[x].bc);
        $("#dt_"+x).val(r[x].ddate);
        $("#c_"+x).val(r[x].acc_name);
        $("#ccode_"+x).val(r[x].received_from_acc);
        $("#chqn_"+x).val(r[x].cheque_no);
        $("#amnt_"+x).val(r[x].amount);
        $("#acc_"+x).val(r[x].account);
        $("#b_"+x).val(r[x].bank_name);
        $("#bcode_"+x).val(r[x].bank);
        $("#br_"+x).val(r[x].branch_name);
        $("#brcode_"+x).val(r[x].branch);
        $("#tr_"+x).val(r[x].description);
        $("#trn_"+x).val(r[x].trans_no);
        $("#rdate_"+x).val(r[x].bank_date);
        var status ="";
        if(r[x].status=="P"){
          status="Pending";
        }else if(r[x].status=="D"){
          status="Deposit";
        }else if(r[x].status=="R"){
          status="Returned";
        }
        $("#status_"+x).val(status);
      }
    }else{
      set_msg("no records. ");
    }
  }, "json");
}

function load_chq_details_issued(){
  if($("#f_date").is(":checked")){date=1;}else{date=0;}
  if($("#f_racc").is(":checked")){r_acc=1;}else{r_acc=0;}
  if($("#f_chq").is(":checked")){chq=1;}else{chq=0;}
  if($("#f_amount").is(":checked")){amount=1;}else{amount=0;}
  if($("#f_rdate").is(":checked")){r_date=1;}else{r_date=0;}
  if($("#f_acc").is(":checked")){acc=1;}else{acc=0;}
  if($("#f_bank").is(":checked")){bank=1;}else{bank=0;}
  if($("#f_branch").is(":checked")){b_branch=1;}else{b_branch=0;}
  if($("#f_status").is(":checked")){status=1;}else{status=0;}
  if($("#f_racc").is(":checked")){f_racc=1;}else{f_racc=0;}
  $.post("index.php/main/load_data/f_chq_find/load_chq_details_issued", {
    cl:$("#cluster").val(),
    bc:$("#branch").val(),
    find:$("#find_txt").val(),
    date:date,
    r_acc:r_acc,
    f_racc:f_racc,
    chq:chq,
    amount:amount,
    r_date:r_date,
    acc:acc,
    bank:bank,
    b_branch:b_branch,
    status:status
  }, function(r){
    empty_grid();
    if(r!=2){
      for(var x=0; x<r.length; x++){
        $("#cl_"+x).val(r[x].cl);
        $("#bc_"+x).val(r[x].bc);
        $("#dt_"+x).val(r[x].ddate);
        $("#c_"+x).val(r[x].issued_acc);
        $("#ccode_"+x).val(r[x].issued_to_acc);
        $("#chqn_"+x).val(r[x].cheque_no);
        $("#amnt_"+x).val(r[x].amount);
        $("#acc_"+x).val(r[x].account);
        $("#b_"+x).val(r[x].bank_name);
        $("#bcode_"+x).val(r[x].bank);
          /*$("#br_"+x).val(r[x].branch_name);
          $("#brcode_"+x).val(r[x].branch);*/
          $("#tr_"+x).val(r[x].description);
          $("#trn_"+x).val(r[x].trans_no);
          $("#rdate_"+x).val(r[x].bank_date);
          var status ="";
          if(r[x].status=="P"){
            status="Pending";
          }else if(r[x].status=="D"){
            status="Deposit";
          }else if(r[x].status=="R"){
            status="Returned";
          }
          $("#status_"+x).val(status);
        }
      }else{
        set_msg("no records. ");
      }
    }, "json");
}


function empty_grid(){
  for(var x=0; x<50; x++){
    $("#cl_"+x).val("");
    $("#bc_"+x).val("");
    $("#dt_"+x).val("");
    $("#c_"+x).val("");
    $("#ccode_"+x).val("");
    $("#chqn_"+x).val("");
    $("#amnt_"+x).val("");
    $("#acc_"+x).val("");
    $("#b_"+x).val("");
    $("#bcode_"+x).val("");
    $("#br_"+x).val("");
    $("#brcode_"+x).val("");
    $("#tr_"+x).val("");
    $("#status_"+x).val("");
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

