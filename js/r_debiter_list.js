$(document).ready(function(){

 $("#cluster").val($("#d_cl").val());
 cl_change();


 var OrAction=$("#print_pdf").attr("action");
 $("#btnPrint").click(function(){     

  if($("#cus_bal").is(':checked')){
    if($("#cus_id").val()=="" || $("#cus_id").val()==0){
      set_msg("Please Select a Customer");
      return false;
      $("#cus_id").focus();
    }
  }   


  if($("input[type='radio']:checked").length == 0){
    alert("Please select report");
    return false;
  }else{
    $("#print_pdf").attr("action",OrAction+"/reports/generate");//1020-106
    $("#print_pdf").submit();//1020-106
    $("#print_pdf").attr("action",OrAction);//1020-106 
  }
});

$("#printExcel").click(function(){//1020-106
  if($("#cus_bal").is(':checked')){
    if($("#cus_id").val()=="" || $("#cus_id").val()==0){
      set_msg("Please Select a Customer");
      return false;
      $("#cus_id").focus();
    }
  }   
  $("#print_pdf").attr("action",OrAction+"/excelReports/generate");
  $(".printExcel").submit();
  $("#print_pdf").attr("action",OrAction);        
}); 

$('input:radio').click(function () {
  if ($(this).attr('excel')) {
   $('#printExcel').removeAttr("disabled")
 } else{
   $('#printExcel').attr("disabled","disabled")
 };

});




$("#cluster").change(function(){
  var path;
  if($("#cluster").val()!=0){
    path="index.php/main/load_data/r_stock_report/get_branch_name2";
  }else{
    path="index.php/main/load_data/r_stock_report/get_branch_name3";
  }
  $.post(path,{
    cl:$(this).val(),
  },function(res){
    $("#branch").html(res);
  },'text');  

});

$("#cus_id").keydown(function(e){ 
  if(e.keyCode==112){
    $("#pop_search2").val($("#cus_id").val());
    load_cus();
    $("#serch_pop2").center();
    $("#blocker2").css("display", "block");
    setTimeout("$('#pop_search2').focus()", 100);   
  }
  $("#pop_search2").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
      load_cus();
    }
  });
  if(e.keyCode==46){
   $("#cus_id").val("");
   $("#customer").val("");
   $("#cu_id").val("");
 }  
}); 

$("#area_code").keydown(function(e){ 
  if(e.keyCode==112){
    $("#pop_search2").val($("#area_code").val());
    load_area();
    $("#serch_pop2").center();
    $("#blocker2").css("display", "block");
    setTimeout("$('#pop_search2').focus()", 100);   
  }
  $("#pop_search2").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
      load_area();
    }
  });
  if(e.keyCode==46){
   $("#area_code").val("");
   $("#area").val(""); 
   $("#are_id").val("");
 }  
}); 
});

function load_cus(){
  var br=$('#branch').val();
  var cl=$('#cluster').val();
  if(cl!="0"){
    var query="AND cl='"+cl+"'";
  }if(br!="0"){
    var query="AND bc='"+br+"'";
  }
  if(cl!="0" && br!="0"){
    var query="AND cl='"+cl+"'AND bc='"+br+"'";
  }
  $.post("index.php/main/load_data/utility/f1_load_cus", {
    data_tbl:"m_customer",
    field:"code",
    field2:"nic",
    field3:"name",
    add_query:query,
    preview2:"Supplier Name",
    search : $("#pop_search2").val() 
  }, function(r){
    $("#sr2").html(r);
    settings_cus();     
  }, "text");
}

function settings_cus(){
  $("#item_list .cl").click(function(){        
    $("#cus_id").val($(this).children().eq(0).html());
    $("#customer").val($(this).children().eq(1).html());
    $("#cu_id").val($(this).children().eq(0).html());
    $("#pop_close2").click();                
  })    
}

function load_area(){
  $.post("index.php/main/load_data/utility/f1_load_area", {
    data_tbl:"r_area",
    field:"code",
    field2:"description",
    preview2:"Supplier Name",
    search : $("#pop_search2").val() 
  }, function(r){
    $("#sr2").html(r);
    settings_area();     
  }, "text");
}

function settings_area(){
  $("#item_list .cl").click(function(){        
    $("#area_code").val($(this).children().eq(0).html());
    $("#area").val($(this).children().eq(1).html());
    $("#are_id").val($(this).children().eq(0).html());
    $("#pop_close2").click();                
  })    
}


function cl_change(){
  $("#store").val("");

  var path;
  var path_store;

  if($("#cluster").val()!=0)
  {
    path="index.php/main/load_data/r_stock_report/get_branch_name2";
    path_store="index.php/main/load_data/r_stock_report/get_stores_cl";
  }
  else
  {
    path="index.php/main/load_data/r_stock_report/get_branch_name3";
    path_store="index.php/main/load_data/r_stock_report/get_stores_default";
  }


  $.post(path,{
    cl:$("#cluster").val(),
    bc:$("#d_bc").val()
  },function(res){
    $("#branch").html(res);
    $("#branch").val($("#d_bc").val());
  },'text');  


  $.post(path_store,{
    cl:$("#cluster").val(),
    bc:$("#d_bc").val()
  },function(res){
    $("#store").html(res);
    $("#branch").val($("#d_bc").val());
  },'text');  


}
