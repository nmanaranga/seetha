$(document).ready(function () {
    $("#btnReset").click(function(){
        location.href="";
    });
    $('#is_con').click(function () {
        if ($('#is_con').is(':checked')) {
            $(this).val('1');
        } else {
            $(this).val('0');
        }
    });


    $("#is_control_acc").click(function(){
        if(!$("#is_control_acc").is(':checked')){
            $("#is_con").removeAttr("checked");
        }
        
    });
        
    $(".is_show").css("display","none");

    $("#type_search").keyup(function () {
        get_data_table();
    });

    $("#is_ledger_acc").click(function(){
        $("#is_con").removeAttr("checked");
        if($("#is_ledger_acc").is(':checked')){
            $(".is_show").fadeIn(500);
            $("#code_samp").val($("#code").val());
            $("#description_samp").val($("#description").val());
            $("#dis_text").val($("#description").val());
            $("#control_type2").val($("#control_type").val());
            $("#account_type2").val($("#account_type").val());
        }else{
            $(".is_show").fadeOut(200);
        }
    });

    $("#is_ledg").click(function(){
       get_data_table(); 
    });

    $("#is_con").attr("checked","checked");
    $("#is_con").attr("title","1");

    $("#is_con").click(function(){

        if(!$("#is_control_acc").is(':checked')){
        

        $("#is_ledger_acc").removeAttr("checked");
        $(".is_show").fadeOut();
        $(".is_show").css("display","none");
        }
    });


    $("#grid").tableScroll({height: 355});

    $('#report').change(function () {
        var select = $('#rtype');
        $('option', select).remove();
        var a = $("#report :selected").val();
        if (a == 1) {
            $('#rtype').append($("<option value='3'>Assets</option>"));
            $('#rtype').append($("<option value='4'>Liabilities</option>"));

        } else {
            $('#rtype').append($("<option value='1'>Income</option>"));
            $('#rtype').append($("<option value='2'>Expense</option>"));

        }
    });

    $("#control_type").keydown(function(e){
        if(e.keyCode==112){
            load_accounts();
            $("#pop_search").select();       
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
        }

        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40  && e.keyCode != 112) { 
                load_accounts();
            }
        });
    });

    $("#control_acc").keydown(function(e){
        if(e.keyCode==112){
            load_con_accounts();
            $("#pop_search").select();       
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
        }
        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40  && e.keyCode != 112) { 
                load_con_accounts();
            }
        });
    });
    $("#control_type2").keydown(function(e){
        if(e.keyCode==112){
            load_accounts1();
            $("#pop_search").select();       
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
        }

        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40  && e.keyCode != 112) { 
                load_accounts1();
            }
        });
    });


    
    $("#pop_search").gselect();

});


load_accounts();
load_accounts1();

function load_accounts(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        search : $("#pop_search").val(),
        field2 :'heading',
        add_query:'AND is_control_category="1"',
        data_tbl:'m_account_type'
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function load_con_accounts(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        search : $("#pop_search").val(),
        field2 :'description',
        add_query:'AND is_control_acc="1"',
        data_tbl:'m_account'
    }, function(r){
        $("#sr").html(r);
        settings3();
    }, "text");
}


function load_accounts1(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        search : $("#pop_search").val(),
        field2 :'heading',
        add_query:'AND is_control_category="1"',
        data_tbl:'m_account_type'
    }, function(r){
        $("#sr").html(r);
        settings1();
    }, "text");
}

function settings1(){
    $("#item_list .cl").click(function(){
        $("#code").addClass("hid_value");
        $("#code").attr("readonly","readonly");
        $("#code").removeClass("input_txt");

        $("#control_type2").val($(this).children().eq(0).html());
        $("#account_type2").val($(this).children().eq(1).html());



        // $.post("index.php/main/load_data/m_account_type/generate_acc_code", {
        //     get_code:$(this).children().eq(0).html()
        // }, function (res) {
        //     $("#code").val(res.code);
        //     //$("#report").val(res.report);



        //     if(res.report==1){
        //         $("#report").html("<option value='1'>Balance Sheet</option>");
                
        //         if(res.rtype==3){
        //            $("#rtype").html("<option value='3'>Assets</option>"); 
        //         }

        //         if(res.rtype==4){
        //            $("#rtype").html("<option value='4'>Liabilities</option>"); 
        //         }
                
        //     }else if(res.report==2){
        //         $("#report").html("<option value='2'>Profit and Loss</option>");
                
        //         if(res.rtype==1){
        //            $("#rtype").html("<option value='1'>Income</option>"); 
        //         }

        //         if(res.rtype==2){
        //            $("#rtype").html("<option value='2'>Expense</option>"); 
        //         }
        //     }


        //     // $("#rtype").val(res.rtype);
            
        // }, "json");

        $("#pop_close").click();
    });
}

function settings3(){
    $("#item_list .cl").click(function(){
        $("#control_acc").val($(this).children().eq(0).html());
        $("#control").val($(this).children().eq(1).html());
        $("#pop_close").click();
    });
}


function settings(){
    $("#item_list .cl").click(function(){
        $("#code").addClass("hid_value");
        $("#code").attr("readonly","readonly");
        $("#code").removeClass("input_txt");

        $("#control_type").val($(this).children().eq(0).html());
        $("#account_type").val($(this).children().eq(1).html());


        $.post("index.php/main/load_data/m_account_type/generate_acc_code", {
            get_code:$(this).children().eq(0).html()
        }, function (res) {
            $("#code").val(res.code);
            //$("#report").val(res.report);



            if(res.report==1){
                $("#report").html("<option value='1'>Balance Sheet</option>");
                
                if(res.rtype==3){
                   $("#rtype").html("<option value='3'>Assets</option>"); 
                }

                if(res.rtype==4){
                   $("#rtype").html("<option value='4'>Liabilities</option>"); 
                }
                
            }else if(res.report==2){
                $("#report").html("<option value='2'>Profit and Loss</option>");
                
                if(res.rtype==1){
                   $("#rtype").html("<option value='1'>Income</option>"); 
                }

                if(res.rtype==2){
                   $("#rtype").html("<option value='2'>Expense</option>"); 
                }
            }


            // $("#rtype").val(res.rtype);
            
        }, "json");

        $("#pop_close").click();
    });
}


function save() {
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid) {
            if (pid == 1) {
               loding();
               sucess_msg();
            } else if (pid == 2) {
                set_msg("No permission to add data.");
            } else if (pid == 3) {
                set_msg("No permission to edit data.");
            } else {
                set_msg(pid);
            }
            
        }
    });
}


function get_data_table() {
    var cond=0;
    if($("#is_ledg").is(':checked')){
        cond=1;   
    }else{
        cond=0;
    }


    $.post("index.php/main/load_data/m_account_type/get_data_table", {
        cond:cond,
        search:$("#type_search").val()
    }, function (r) {
        $("#grid_body").html(r);
    }, "text");
}


function check_code() {
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_account_type/check_code", {
        code: code
    }, function (res) {
        if (res == 1) {
            if (confirm("This code (" + code + ") already added. \n\n Do you need edit it?")) {
                set_edit(code);
            } else {
                $("#code").val('');
                $("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate() {
    if ($("#code").val() == "") {
        set_msg("Please enter code.");
        $("#code").focus();
        return false;
    } else if ($("#description").val() === $("#description").attr('title') || $("#description").val() == "") {
        set_msg("Please enter description.");
        $("#description").focus();
        return false;
    } else if ($("#report").val() == "null") {
        set_msg("Please select report.");
        $("#report").focus();
        return false;
    } else if ($("#rtype").val() == "null") {
        set_msg("Please select type.");
        $("#rtype").focus();
        return false;
    } else if ($('#is_con').is(':checked') || $('#is_con').prop('checked')) {
        x=parseInt($("#ncformat").val());
        if(x!=0){
        set_msg("Please Select correct next code number format");
        return false;
        }else{
            return true;
        }
    } else {
        return true;
    }


   
}

function set_delete(code) {

    if (confirm("Are you sure delete " + code + "?")) {
        loding();
        $.post("index.php/main/delete/m_account_type", {
            code: code
        }, function (res) {
            if (res == 1) {
                loding();
                delete_msg();
            } else {
                set_msg("Item deleting fail.");
            }
        }, "text");
    }

}

function set_edit(code) {
    loding();
    $.post("index.php/main/get_data/m_account_type", {
        code: code
    }, function (res) {
        $("#control_type").val(res.m_code);
        $("#account_type").val(res.m_heading);

        $("#control_type").addClass('hid_value');
        $("#control_type").removeClass('input_txt');

        $("#code").addClass('hid_value');
        $("#code").removeClass('input_txt');

        $("#rtype").addClass('hid_value');
        $("#rtype").removeClass('input_txt');

        $("#report").addClass('hid_value');
        $("#report").removeClass('input_txt');
        

        $("#code_").val(res.det.code);
        $("#code").val(res.det.code);
        $("#code").attr("readonly", true);
        $("#description").val(res.det.heading);
        $("#ncformat").val(res.det.ncformat);

        if(res.det.is_ledger_acc==1){
            $("#is_ledger_acc").attr("checked", "checked");
            $(".is_show").fadeIn(500);
            $.post("index.php/main/load_data/m_account_type/load_m_account", {
            code: res.det.code
            }, function (res) {
                if (res == 2) {
                    set_msg("No records");
                } else {
                   $("#control_type2").val(res[0].type);
                   $("#code_samp").val(res[0].code);
                   $("#description_samp").val(res[0].description);
                   $("#account_type2").val(res[0].heading);
                   $("#control_acc").val(res[0].control_acc);
                   $("#control").val(res[0].con_des);
                   $("#dis_text").val(res[0].display_text);
                   
                   if(res[0].is_bank_acc=='1'){
                    $("#is_bank_acc").attr("checked", "checked");

                   } 

                   if(res[0].is_control_acc=='1'){
                    $("#is_control_acc").attr("checked", "checked");
                   } 
                   $("#order_no").val(res[0].order_no);
                }


                
            }, "json");

        }else{
            $(".is_show").fadeOut(100);
            $("#is_ledger_acc").removeAttr("checked");
        }


        // if (res.det.report == 1) {
        //     $('#rtype').html("<option value='3'>Assets</option><option value='4'>Liabilities</option>");
        // } else {
        //     $('#rtype').html("<option value='1'>Income</option><option value='2'>Expense</option>");
        // }

        // if (res.det.report == 2) {
        //     $('#report').val("2");
        // }

        // if (res.det.report == 1) {
        //     $('#report').val("1");
        // }

        // if (res.det.rtype == 1) {
        //     $('#rtype').val("1");
        // }

        // if (res.det.rtype == 2) {
        //     $('#rtype').val("2");
        // }

        // if (res.det.rtype == 3) {
        //     $('#rtype').val("3");
        // }

        // if (res.det.rtype == 4) {
        //     $('#rtype').val("4");
        // }

        if(res.det.report==1){
            $("#report").html("<option value='1'>Balance Sheet</option>");

            if(res.det.rtype==3){
                $("#rtype").html("<option value='3'>Assets</option>"); 
            }

            if(res.det.rtype==4){
                $("#rtype").html("<option value='4'>Liabilities</option>"); 
            }

        }else if(res.det.report==2){
            $("#report").html("<option value='2'>Profit and Loss</option>");

            if(res.det.rtype==1){
                $("#rtype").html("<option value='1'>Income</option>"); 
            }

            if(res.det.rtype==2){
                $("#rtype").html("<option value='2'>Expense</option>"); 
            }
        }

        if(res.det.is_control_category==1){
            $("#is_con").attr("checked", "checked");
        }else{
            $("#is_con").removeAttr("checked");
        }


        loding();
        input_active();
    }, "json");
}       