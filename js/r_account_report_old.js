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
        $("#acc_code").val("");
        $("#acc_code_des").val("");
        $("#t_type").val("");
        $("#t_type_des").val("");
        $("#t_range_from").val("");
        $("#t_range_to").val("");

        return false;
    });

    
    $("#chart_acc").click(function(){
        $("#by").val("r_account_chart");
        $("#type").val("r_account_chart");
        $("#acc_table").css("display","none");
        //set_msg("Chart of account report not filering","msg_info");

    });

    $("#acc_update").click(function(){
        $("#by").val("r_account_update");
        $("#type").val("r_account_update");
        $("#acc_table").css("display","block");
        $("#trans_type").css("display","block");
        $("#account_c").css("display","none");
    });

    $("#acc_det_sub").click(function(){
        $("#by").val("r_account_report_sub");
        $("#acc_table").css("display","block");
        $("#account_c").css("display","block");
        $("#trans_type").css("display","none");
    });

    $("#acc_det").click(function(){
        $("#by").val("r_account_report");
        $("#acc_table").css("display","block");
        $("#account_c").css("display","block");
        $("#trans_type").css("display","none");
        
    });

    $("#credit_note").click(function(){
        $("#by").val("r_credit_note");
        $("#type").val("r_credit_note");
        $("#acc_table").css("display","block");
        $("#trans_type").css("display","block");
        $("#account_c").css("display","none");
    });

    $("#debit_note").click(function(){
        $("#by").val("r_debit_note");
        $("#type").val("r_debit_note");
        $("#acc_table").css("display","block");
        $("#trans_type").css("display","block");
        $("#account_c").css("display","none");
    });

    $("#trial_balance").click(function(){
        $("#by").val("r_trial_balance");
        $("#type").val("r_trial_balance");
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
    });

    $("#profit_n_lost").click(function(){
        $("#by").val("r_profit_n_lost");
        $("#type").val("r_profit_n_lost");
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
    });
    
    $("#balance_sheet").click(function(){
        $("#by").val("r_balance_sheet");
        $("#type").val("r_balance_sheet");
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
    });

//jurnal List
    $("#jurnal_entry").click(function(){
        $("#by").val("r_jurnal_entry");// model name
        $("#type").val("r_jurnal_entry"); //tc pdf file name
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
    });

 //opening Balance Report
    $("#opening_balance").click(function(){
        $("#by").val("r_opening_balance");// model name
        $("#type").val("r_opening_balance"); //tc pdf file name
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
    });

    $("#trading_report").click(function(){
        $("#by").val("r_trading_report");// model name
        $("#type").val("r_trading_report"); //tc pdf file name
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
    });

    $("#sales_report").click(function(){
        $("#by").val("r_sales_report");// model name
        $("#type").val("r_sales_report"); //tc pdf file name
        $("#acc_table").css("display","none");
        $("#trans_type").css("display","none");
        $("#account_c").css("display","none");
    });


    $("#acc_code,#t_type").keydown(function(e){
        var id = $(this).attr("id");
        if(e.keyCode==46){
            $("#"+id+"_des").val("");
            $("#"+id).val("");
            if(id=="t_type"){
                $("#t_range_from").val("");
                $("#t_range_to").val("");
            }
        }

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


    $("#btnCheckAll").click(function(){
     var row_count=parseInt($(".check").size());
        for(x=0;x<row_count;x++){
            $(".Checkbox").prop('checked', true);
        }

    }); 

     $("#btnUncheckAll").click(function(){
     var row_count=parseInt($(".check").size());
        for(x=0;x<row_count;x++){
            $(".Checkbox").prop('checked', false);
        }

    }); 

    $("#add_acc_code").click(function(){
        if($("#acc_code").val()=="" || $("#acc_code").val().length==0){
            alert("Please select account");
        }else{

            var row_count=parseInt($(".check").size());
            var check_row=0;
            for(x=0;x<row_count;x++){
                if($("#2_"+x).val()==""){

                    $.post("index.php/main/load_data/r_account_report/get_account_det", {
                       code:$("#acc_code").val()
                    }, function(r){
                        $("#n_"+x).val(x);
                        $("#1_"+x).val(x+1);
                        $("#2_"+x).val(r.acc[0].code);
                        $("#3_"+x).val(r.acc[0].description);
                        $("#4_"+x).val(r.acc[0].type);
                        $("#5_"+x).val(r.acc[0].heading);
                    }, "json");

                    return false;
                }else{
                    check_row=check_row+1;
                }
            }

            if(row_count==check_row){
                var x=row_count+1;
                var html="";

                 $.post("index.php/main/load_data/r_account_report/get_account_det", {
                       code:$("#acc_code").val()
                    }, function(r){
                        html+="<tr>";
                        html+="<td class='check' style='background:#F9F9EC'><input type='checkbox' class='g_input_txt g_col_fixed Checkbox'  id='n_"+x+"' name='n_"+x+"' /></td>";
                        html+="<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='1_"+x+"' name='1_"+x+"' value='"+x+"' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        html+="<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='2_"+x+"' name='2_"+x+"' value='"+r.acc[0].code+"' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        html+="<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='3_"+x+"' name='3_"+x+"' value='"+r.acc[0].description+"' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        html+="<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='4_"+x+"' name='4_"+x+"' value='"+r.acc[0].type+"' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        html+="<td style='background:#F9F9EC'><input type='text' class='g_input_txt g_col_fixed' id='5_"+x+"' name='5_"+x+"' value='"+r.acc[0].heading+"' style='width:100%;border:1px dotted #ccc;text-align:right;'/></td>";
                        html+="</tr>";

                        $("#tbl_body").append(html);
                        $("#row_count").val(x);
                    }, "json");


            }
        }

        
        
    }); 

     
    $("#print").click(function(){
        if($("#by").val()=="" ){
            set_msg("Please select report","error");
            return false;
        }else{
           $("#print_pdf").submit(); 
        }
    });    


    $("#acc_code").autocomplete("index.php/main/load_data/r_account_report/auto_com", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });
    $("#acc_code").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values4($(this));
    });
    $("#acc_code").blur(function() {
        set_cus_values4($(this));
    });

   
    $("#acc_type").autocomplete("index.php/main/load_data/r_account_report/account_type", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });
    $("#acc_type").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values1($(this));
    });
    $("#acc_type").blur(function() {
        set_cus_values1($(this));
    });



    $("#t_type").autocomplete("index.php/main/load_data/r_account_report/trans_type", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });
    $("#t_type").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values5($(this));
    });
    $("#t_type").blur(function() {
        set_cus_values5($(this));
    }); 


    $("#acc_cat").autocomplete("index.php/main/load_data/r_account_report/acc_cat", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#acc_cat").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values2($(this));
    });
    $("#acc_cat").blur(function() {
        set_cus_values2($(this));
    });

    $("#cntrl_acc").autocomplete("index.php/main/load_data/r_account_report/cntrl_acc", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });
    $("#cntrl_acc").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values3($(this));
    });

    $("#cntrl_acc").blur(function() {
        set_cus_values3($(this));
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


    $("#t_type").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search12").val($("#t_type").val());
            load_data10();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);
        }

       $("#pop_search12").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data10();
            }
        }); 

        if(e.keyCode == 46){
            $("#t_type").val("");
            $("#t_type_des").val("");
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

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#acc_code").val($(this).children().eq(0).html());
        $("#acc_code_des").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}

function load_data10(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"t_trans_code",
        field:"code",
        field2:"description",
        preview2:"Name",
        search : $("#pop_search12").val() 
    }, function(r){
        $("#sr12").html(r);
        settings10();            
    }, "text");
}

function settings10(){
    $("#item_list .cl").click(function(){        
        $("#t_type").val($(this).children().eq(0).html());
        $("#t_type_des").val($(this).children().eq(1).html());
        $("#pop_close12").click();                
    })    
}

function set_cus_values4(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#acc_code_des").val(b[1]);
    }
}

function set_cus_values1(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#acc_type_des").val(b[1]);
    }
}

function set_cus_values2(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#acc_cat_des").val(b[1]);
    }
}

function set_cus_values3(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#cntrl_acc_des").val(b[1]);
    }
}


function set_cus_values5(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#t_type_des").val(b[1]);
    }
}


function formatItems(a) {
    return "<strong> " + a[0] + "</strong> | <strong> " + a[1] + "</strong>";
}

function formatItemsResult(a) {
    return a[0] + "|" + a[1];
}




function select_search() {
    $("#pop_search").focus();
}

function load_items() {
    $.post("index.php/main/load_data/r_subitem/item_list_all", {
        search: $("#pop_search").val(),
        stores: false
    }, function(a) {
        $("#sr").html(a);
        settings();
    }, "text");
}











