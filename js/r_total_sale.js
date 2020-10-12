$(document).ready(function(){

    $(".report").click(function(){

       if($("input[type='radio']:checked").val()=='r_total_sale'){
        $("#btnPrint").attr("disabled","disabled");
        $("#printExcel").attr("disabled","disabled");
    }
});


    $("#cluster").val($("#d_cl").val());
    cl_change();

    var OrAction=$("#print_pdf").attr("action");
    $("#btnPrint").click(function(){

     if($("input[type='radio']:checked").length == 0)
     {
      alert("Please select report");
      return false;
  }
  else
  {


    var date1 = new Date($("#to").val());
    var date2 = new Date($("#from").val());
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
    if($("input[type='radio']:checked").val()=='r_total_sale' || $("input[type='radio']:checked").val()=='r_total_sale_emp'){
      //  if(diffDays<=31){
            $("#print_pdf").attr("action",OrAction+"/reports/generate");//1020-106
            $("#print_pdf").submit();//1020-106
            $("#print_pdf").attr("action",OrAction);//1020-106 
       /* }else{
            alert('This report can not be selected for more than a month.');
        }*/
    }else{
        $("#print_pdf").attr("action",OrAction+"/reports/generate");//1020-106
            $("#print_pdf").submit();//1020-106
            $("#print_pdf").attr("action",OrAction);//1020-106 
        }
    }
    
});

    $("#printExcel").click(function(){//1020-106
       var date1 = new Date($("#to").val());
       var date2 = new Date($("#from").val());
       var timeDiff = Math.abs(date2.getTime() - date1.getTime());
       var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
       if($("input[type='radio']:checked").val()=='r_total_sale' || $("input[type='radio']:checked").val()=='r_total_sale_emp'){
           if(diffDays<=31){
            $("#print_pdf").attr("action",OrAction+"/excelReports/generate");
            $(".printExcel").submit();
            $("#print_pdf").attr("action",OrAction);      
        }else{
            alert('This report can not be selected for more than a month.');
        }  
    }else{
       $("#print_pdf").attr("action",OrAction+"/excelReports/generate");
       $(".printExcel").submit();
       $("#print_pdf").attr("action",OrAction); 
   }
}); 

    $("#cluster").change(function(){
        var path;
        var path_store;

        if($("#cluster").val()!=0)
        {
            path="index.php/main/load_data/r_total_sale/get_branch_name2";
        }
        else
        {
            path="index.php/main/load_data/r_total_sale/get_branch_name3";
        }

        $.post(path,{
            cl:$(this).val(),
        },function(res){
            $("#branch").html(res);
        },'text');  
    });

    $("#r_customer").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search2").val($("#r_customer").val());
            load_data9();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search2').focus()", 100);
        }

        $("#pop_search2").keyup(function(e){

            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_data9();
         }
     }); 

        if(e.keyCode == 46){
            $("#r_customer").val("");
            $("#r_customer_des").val("");
        }
    });


    $("#emp").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search4").val($("#emp").val());
            load_data_emp();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }
        $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_data_emp()
            }
        }); 
        if(e.keyCode == 46){
            $("#emp").val("");
            $("#emp_des").val("");
        }
    });

    $('input:radio').click(function (){
        if ($(this).attr('excel')) {
          if($("input[type='radio']:checked").val()!='r_total_sale'){
              $('#printExcel').removeAttr("disabled");
          }
      }else{
          $('#printExcel').attr("disabled","disabled");
      }
  });

    $("#btnprocess").click(function(){
        process_total_sale_stage1();
    });


});


function load_data_emp(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_employee",
        field:"code",
        field2:"name",
        preview1:"Employee ID",
        preview2:"Employee Name",
        search : $("#pop_search4").val() 
    }, function(r){
        $("#sr4").html(r);
        settings_emp();            
    }, "text");
}

function settings_emp(){
    $("#item_list .cl").click(function(){        
        $("#emp").val($(this).children().eq(0).html());
        $("#emp_des").val($(this).children().eq(1).html());        
        $("#pop_close4").click();                
    })    
}

function load_data9(){
    $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
        data_tbl:"m_customer",
        field:"code",
        field2:"name",
        field3:"nic",
        preview1:"Customer ID",
        preview2:"Customer Name",
        preview3:"Customer NIC",
        search : $("#pop_search2").val() 
    }, function(r){
        $("#sr2").html(r);
        settings9();            
    }, "text");
}

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#r_customer").val($(this).children().eq(0).html());
        $("#r_customer_des").val($(this).children().eq(1).html());        
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


function process_total_sale_stage1(){
   $.post("index.php/main/load_data/r_total_sale/delete_totalSale", {
       f_date:$("#from").val(),
       to_date:$("#to").val(),
       cl3:$("#cluster").val(),
       bc3:$("#branch").val()
   }, function(r){
    if(r=='1'){
       process_total_sale_stage2()
   }

}, "json"); 
}



function process_total_sale_stage2(){
    $("#blanket").css("display", "block");
    $.post("index.php/main/load_data/r_total_sale/process_totalSale", {
       f_date:$("#from").val(),
       to_date:$("#to").val(),
       cl3:$("#cluster").val(),
       bc3:$("#branch").val()
   }, function(r){
    if(r=='1'){
        $("#blanket").css("display", "none");
        alert("Process Success");
        $('#btnPrint').removeAttr("disabled");
        $('#printExcel').removeAttr("disabled");
    }

}, "json"); 
}
