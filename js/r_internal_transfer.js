$(document).ready(function(){
 var OrAction=$("#print_pdf").attr("action");
 $("#btnPrint").click(function(){     

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

  
  $("#cluster").val($("#d_cl").val());
  cl_change();

  $("#cluster").change(function(){
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
        cl:$(this).val(),
    },function(res){
        $("#branch").html(res);
    },'text');  


    $.post(path_store,{
        cl:$(this).val(),
    },function(res){
        $("#store").html(res);
    },'text');  

});

});

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
    },function(res){
        $("#branch").html(res);
        $("#branch").val($("#d_bc").val());
    },'text');  


    $.post(path_store,{
        cl:$("#cluster").val(),
    },function(res){
        $("#store").html(res);
        $("#branch").val($("#d_bc").val());
    },'text');  


}