$(document).ready(function(){
	$("#btnPrint").click(function(){

       if($("input[type='radio']:checked").length == 0)
       {
          alert("Please select report");
          return false;
      }
      else
      {
        if($("input[type='radio']:checked").val()=='r_transfer_rece_outstanding'){
            if($("#acc_code").val()==0 || $("#acc_code").val()==''){
                alert("Please select account Code");
                return false;
            }else{
             $("#print_pdf").attr("action",OrAction+"/reports/generate");//1020-106
        $("#print_pdf").submit();//1020-106
       // $("#print_pdf").attr("action",OrAction);//1020-106
    }
}else{ 
    $("#print_pdf").attr("action",OrAction+"/reports/generate");//1020-106
        $("#print_pdf").submit();//1020-106
        //$("#print_pdf").attr("action",OrAction);//1020-106
    }
} 

});

        var OrAction=$("#print_pdf").attr("action");//1020-106


        $('input:radio').click(function () {
            if ($(this).attr('excel')) {
             $('#printExcel').removeAttr("disabled")
         } else{
             $('#printExcel').attr("disabled","disabled")
         };

     });

    $("#printExcel").click(function(){//1020-106
        $("#print_pdf").attr("action",OrAction+"/excelReports/generate");
        $(".printExcel").submit();
        $("#print_pdf").attr("action",OrAction);        

    }); 

    $("#cluster").val($("#d_cl").val());
    $("#branch").val($("#d_bc").val());
    cl_change();

    $("#btnReset").click(function(){  //reset all values in the textboxes
        $("#acc_code").val("");
        $("#acc_code_des").val("");
        $("#t_type").val("");
        $("#t_type_des").val("");
        $("#t_range_from").val("");
        $("#t_range_to").val("");

        return false;
    });


    $("#acc_code").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search11").val($("#acc_code").val());
            if($("#BraCuAcc").is(':checked')){
                load_brnchCAcc();
            }else{
                load_data9();
            }
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }

        $("#pop_search11").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
              if($("#BraCuAcc").is(':checked')){
                load_brnchCAcc();
            }else{
                load_data9();
            }
        }
    }); 

        if(e.keyCode == 46){
            $("#acc_code").val("");
            $("#acc_code_des").val("");
        }
    });

});

function load_data9(){
    $.post("index.php/main/load_data/r_account_report/get_account", {
        data_tbl:"m_account",
        field:"code",
        field2:"description",
        preview2:"Account Name",
        search : $("#pop_search11").val() 
    }, function(r){
        $("#sr11").html(r);
        settings9();            
    }, "text");
}

function load_brnchCAcc(){
    $.post("index.php/main/load_data/t_internal_trans_receipt/branch_accounts", {
        search : $("#pop_search11").val() 
    }, function(r){
        $("#sr11").html("");
        $("#sr11").html(r);
        settingsbrnchCAcc();            
    }, "text");
}

function settingsbrnchCAcc(){
    $("#item_list .cl").click(function(){        
        $("#acc_code").val($(this).children().eq(2).html());
        $("#acc_code_des").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}
function settings9(){
    $("#item_list .cl").click(function(){        
        $("#acc_code").val($(this).children().eq(0).html());
        $("#acc_code_des").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}

function cl_change(){

    $("#cluster").change(function(){

        var path;

        if($("#cluster").val()!=0)
        {
            path="index.php/main/load_data/r_transaction_list_reciept/get_branch_name2";
            
        }
        else
        {
            path="index.php/main/load_data/r_transaction_list_reciept/get_branch_name3";

        }


        $.post(path,{
            cl:$(this).val(),
        },function(res){
            $("#branch").html(res);
        },'text');  

    });
}