$(document).ready(function(){

  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });
  $("#grid").tableScroll({height:355});

  $("#pop_search15").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
     load_cus();
   }
 })
  $("#t_search").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search15").val();
      load_cus();
      $("#serch_pop15").center();
      $("#blocker").css("display","block");
      setTimeout("$('#pop_search15').focus()",100);
    }
    if(e.keyCode == 46){
      $("#t_search").val("");
      $("#cus_name").val("");

    }
  });

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
  $("#search").keyup(function(){
    search();
  })
  $("#branch").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search4").val();
      load_branch();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);
    }
    $("#pop_search4").keyup(function(e){            
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_branch();
      }
    }); 
    if(e.keyCode == 46){
      $("#branch").val("");
      $("#branch_des").val("");
    }
  });

  $("#find").click(function(){
    load_chq_details();
  });

  $(".rmks").click(function(){
    set_cid($(this).attr("id"));
    load_return_details();
  });

});

function load_return_details(){
  if($("#rtnno_"+scid).val()!=0){
    $("#pop_search14").css("display","none");
    load_rtn_detail_each_chq($("#rtnno_"+scid).val(),$("#rtndate_"+scid).val(),$("#rtnreason_"+scid).val());
    $("#serch_pop14").center();
    $("#blocker").css("display", "block");
    setTimeout("$('#pop_search14').focus()", 100);
  }else{
    set_msg("This cheque no not returned");
  }
}

function load_rtn_detail_each_chq(rtn_no,rtn_date,rtn_reason){
  var tbl="<table id='item_list' style='width : 100%'>";
  tbl+="<tr>";
  tbl+="<th class='tb_head_th'>Trans No</th>";
  tbl+="<th class='tb_head_th'>Return Date</th>";
  tbl+="<th class='tb_head_th'>Return Reason</th>";
  tbl+="<th class='tb_head_th'>Return Notice No</th>";
  tbl+="<th class='tb_head_th'>Return Notice Date No</th>";
  tbl+="<th class='tb_head_th'>Remarks</th>";
  tbl+="</tr>";
  tbl+="<tr><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td></tr>";
  tbl+="<tr class='cl'>";
  tbl+="<td style='text-align:right;'> "+rtn_no+" </td>";
  tbl+="<td style='text-align:left;'> "+rtn_date+" </td>";
  tbl+="<td style='text-align:left;'> "+rtn_reason+" </td>";
  tbl+="<td style='text-align:left;'>   </td>";
  tbl+="<td style='text-align:left;'>   </td>";
  tbl+="<td style='text-align:left;'>   </td>";
  tbl+="</tr>";
  tbl+="</table>";

  $("#sr14").html(tbl);


}


function load_cluster(){
  $.post("index.php/main/load_data/f_post_dated_chq_reg/load_cluster", {
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
  $.post("index.php/main/load_data/f_post_dated_chq_reg/load_branch", {
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


function load_chq_details(){
  $.post("index.php/main/load_data/f_post_dated_chq_reg/load_chq_details", {
    f_date : $("#f_date").val(),
    t_date: $("#t_date").val(),
    cl:$("#cluster").val(),
    bc:$("#branch").val(),
    status:$("#status").val(),
    customer:$("#t_search").val()
  }, function(r){
    empty_grid();
    if(r!=2){
      for(var x=0; x<r.length; x++){
        $("#dt_"+x).val(r[x].date);
        $("#rldt_"+x).val(r[x].realize_date);
        $("#c_"+x).val(r[x].cus_name);
        $("#ccode_"+x).val(r[x].customer);
        $("#chqn_"+x).val(r[x].cheque_no);
        $("#amnt_"+x).val(r[x].amount);
        $("#acc_"+x).val(r[x].account);
        $("#b_"+x).val(r[x].bank_name);
        $("#bcode_"+x).val(r[x].bank);
        $("#bb_"+x).val(r[x].branch_name);
        $("#bbcode_"+x).val(r[x].branch);
        $("#ack_"+x).val(r[x].ack_no);
        $("#rd_"+x).val(r[x].rcpt_date);
        $("#rn_"+x).val(r[x].rcpt_no);
        $("#bd_"+x).val(r[x].bank_date);
        $("#r_"+x).attr("disabled",false);
        $("#status_"+x).val(r[x].status);
        if(r[x].return_no !=0){
          $("#r_"+x).css('color', 'red');
          $("#r_"+x).css("font-weight","Bold");
          $("#r_"+x).val('RTN');
        }else{
          $("#r_"+x).css('color', 'green');
          $("#r_"+x).css("font-weight","Bold");
          $("#r_"+x).val('...');
        }

        $("#rtnno_"+x).val(r[x].return_no);
        $("#rtndate_"+x).val(r[x].return_date);
        $("#rtnreason_"+x).val(r[x].return_reason);

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
    $("#bb_"+x).val("");
    $("#bbcode_"+x).val("");
    $("#ack_"+x).val("");
    $("#rd_"+x).val("");
    $("#rn_"+x).val("");
    $("#bd_"+x).val("");
    $("#r_"+x).attr("disabled",true);
    $("#r_"+x).css('color', 'black');
    $("#r_"+x).val("...");
    $("#rtnno_"+x).val("");
    $("#rtndate_"+x).val("");
    $("#rtnreason_"+x).val("");

  }
}
function load_cus(){
  $.post("index.php/main/load_data/utility/f1_selection_list_customer",{
    data_tbl:"m_customer",
    field:"code",
    field2:"name",
    field3:"nic",
    
    search:$("#pop_search15").val()
  },function(r){
    $("#sr15").html(r);
    setting_cus();
  },"text");
}
function setting_cus(){
  $("#item_list .cl").click(function(){
    $("#t_search").val($(this).children().eq(0).html());
    $("#cus_name").val($(this).children().eq(1).html());
    $("#pop_close15").click();
  })
}
  //$("#branch").val($(this).children().eq(0).html());