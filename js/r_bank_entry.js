var sub_cat;
var is_edit = 0;

$(function() {

    $("#brand").change(function() {
        set_select("brand", "brand_des");
    });
    $("#tgrid").tableScroll({
        height: 200
    });
    $("#item_gen").click(function() {
        generate_code();
    });
    $("#cluster").change(function() {
        $("#clusters").val($(this).val());
    });
    $("#branch").change(function() {
        $("#branchs").val($(this).val());
    });

    input_active();

});

$(document).ready(function() {

    $("#btnExit").click(function(){
        return false;
    });

    $("#btnReset").click(function(){  //reset all values in the textboxes
      
        return false;
    });



//Bank Entry List
 $("#bank_entry_list").click(function(){
        $("#by").val("r_bank_entry");// model name
        $("#type").val("r_bank_entry"); //tc pdf file name
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
   });

  $("#r_cheque_list").click(function(){
        $("#by").val("r_cheque_list");// model name
        $("#type").val("r_cheque_list"); //tc pdf file name
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
   });


    

    $("#cluster").change(function(){
        
        var path;
    
        if($("#cluster").val()!=0)
        {
            path="index.php/main/load_data/r_stock_report/get_branch_name2";
        }
        else
        {
            path="index.php/main/load_data/r_stock_report/get_branch_name3";
        }


        $.post(path,{
        cl:$(this).val(),
        },function(res){
        $("#branch").html(res);
        },'text');  
        
    });

    $("#print").click(function(){
        if($("#by").val()=="" ){
            set_msg("Please select report","error");
            return false;
        }else{
           $("#print_pdf").submit(); 
        }
    });    

    

    $("#cntrl_acc").blur(function() {
        set_cus_values3($(this));
    });
 

});










