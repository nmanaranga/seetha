$(document).ready(function(){



   $("#cluster").val($("#d_cl").val());
   $("#branch").val($("#d_bc").val());
   cl_change();

   $("#btnPrint").click(function(){

       if($("input[type='radio']:checked").length == 0)
       {
          alert("Please select report");
          return false;
      }
      else

      {  
        
        $("#print_pdf").submit();

    } 

});



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
            load_data9();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }

        $("#pop_search11").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_data9();
           }
       }); 

        if(e.keyCode == 46){
            $("#acc_code").val("");
            $("#acc_code_des").val("");
        }
    });

    $("#chkdate1").click(function() {
        if($("#chkdate1").is(":checked")){
            $("#from").removeAttr('disabled');
            $("#to").removeAttr('disabled');
        }else {
            $("#from").attr("disabled",true);
            $("#to").attr("disabled",true);
        }

    });

    $("#chknumRange").click(function() {
        if($("#chknumRange").is(":checked")){
            $("#t_range_from").attr("disabled",false);
            $("#t_range_to").attr("disabled",false);
        }else {
            $("#t_range_from").attr("disabled",true);
            $("#t_range_to").attr("disabled",true);
        }

    });

});

function cl_change(){

    $("#cluster").change(function(){

        var path;

        if($("#cluster").val()!=0)
        {
            path="index.php/main/load_data/r_transaction_list_voucher/get_branch_name2";
            
        }
        else
        {
            path="index.php/main/load_data/r_transaction_list_voucher/get_branch_name3";

        }


        $.post(path,{
            cl:$(this).val(),
        },function(res){
            $("#branch").html(res);
        },'text');  

    });
}
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

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#acc_code").val($(this).children().eq(0).html());
        $("#acc_code_des").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}