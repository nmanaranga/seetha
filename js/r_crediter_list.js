$(document).ready(function(){
   /*$("#btnPrint").click(function(){
     if($("input[type='radio']:checked").length == 0){
      alert("Please select report");
      return false;
     }
     else
     {
      $("#print_pdf").submit();
     }
    });
    */

    $("#cluster").val($("#d_cl").val());
    cl_change();


    var OrAction=$("#print_pdf").attr("action");
    $("#btnPrint").click(function(){  

      if($("#sup_bal").is(':checked')){
        if($("#supp").val()=="" || $("#supp").val()==0){
          set_msg("Please Select a Supplier");
          return false;
          $("#supp").focus();
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


  $("#supp").autocomplete("index.php/main/load_data/m_supplier/auto_com", {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });
  $("#supp").keydown(function(a) {
    if (13 == a.keyCode) set_cus_values5($(this));
  });
  $("#supp").blur(function() {
    set_cus_values5($(this));
  }); 

  $("#supp").keydown(function(e){ 
    if(e.keyCode==112){    
      $("#pop_search14").val($("#supp").val());
      load_supp();
      $("#serch_pop14").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search14').focus()", 100);   
    }
    $("#pop_search14").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_supp();
      }
    });
    if(e.keyCode==46){
      $("#supp").val("");
      $("#supp_des").val("");
    }  
  });


});

function load_supp() {
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"m_supplier",
    field:"code",
    field2:"name",
    preview2:"Supplier Name",
    search : $("#pop_search14").val() 
  }, function(r){
    $("#sr14").html(r);
    settings_supp();      
  }, "text");
}

function settings_supp(){
  $("#item_list .cl").click(function(){        
    $("#supp").val($(this).children().eq(0).html());
    $("#supp_des").val($(this).children().eq(1).html());
    $("#pop_close14").click();                
  })    
}


function set_cus_values5(a) {
  var b = a.val();
  b = b.split("|");
  if (2 == b.length) {
    a.val(b[0]);
    $("#supp_des").val(b[1]);
  }
}

function formatItems(a) {
  return "<strong> " + a[0] + "</strong> | <strong> " + a[1] + "</strong>";
}

function formatItemsResult(a) {
  return a[0] + "|" + a[1];
}
