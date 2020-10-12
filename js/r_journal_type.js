$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });


   /* $(".fo").keydown(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            $("#pop_search4").val($("#0_"+scid).val());
            load_accounts();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }

       $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_accounts();
            }
        }); 
        if(e.keyCode == 46){
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
        }
    });*/


    $("#srchee").keyup(function(){  
        $.post("index.php/main/load_data/utility/get_data_table", {
            code:$("#srchee").val(),
            tbl:"m_journal_type_sum",
            tbl_fied_names:"Code-Description",
            fied_names:"code-description",
        }, function(r){
            $("#grid_body").html(r);
        }, "text");
    });

    
    $("#pop_search").gselect();
    
    $("#tgrid").tableScroll({height:200});
    
    $("#saccount").blur(function(){
       set_account_values($(this));
   });
    
    $("#saccount").keydown(function(e){
       if(e.keyCode == 13){
           set_account_values($(this));
       }
   });
    
    $("#saccount").autocomplete('index.php/main/load_data/m_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatAccount,
        formatResult: formatAccountResult
    });
    
    $(".fo").keydown(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode == 112){
            if($("#payble_type option:selected").val() != 0){
                $("#serch_pop").center();
                $("#blocker").css("display", "block");
                setTimeout("select_search()", 100);
                load_accounts();
            }else{
                set_msg("Please Select Payble Type and Payble A/C");
            }
        }
        if(e.keyCode == 46){
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
        }
    });
    
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_accounts();}
    });
    
    /*$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});*/

});

function select_search(){
    $("#pop_search").focus();
    $("#pop_search").val("");
}

function load_accounts(){
    $.post("index.php/main/load_data/m_account/account_list", {
        search : $("#pop_search").val()
        
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function empty_grid()
{
    for(var i=0;i<25;i++)
    {
        $("#0_"+i).val('');
        $("#h_"+i).val('0');
        $("#n_"+i).val('');
        $("#2_"+i).val('');
        $("#3_"+i).val('');
        $("#1_"+i).val('');
        
    }    
}


function set_cus_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
       f.val(v[0]);
       $("#account").val(v[0]);
       $("#account_des").val(v[1]);
       $("#account_des").attr("class", "input_txt_f");
   }
}

function settings(){
    $("#item_list tr").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#1_"+scid).val('0.00');
                $("#2_"+scid).val('0.00');
                $("#2_"+scid).val($(this).children().eq(7).html());
                $("#1_"+scid).focus();$("#pop_close").click();
            }else{
                set_msg("Account code "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#n_"+scid).val("");
            $("#0_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
            set_total();$("#pop_close").click();
        }
    });
}

function check_item_exist(id){
    var v = true;
    $("input[type='hidden']").each(function(){
        if($(this).val() == id){
            v = false;
        }
    });
    
    return v;
}

function set_account_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
       f.val(v[0]);
       $("#account").val(v[0]);
       $("#account_des").val(v[1]);
       $("#account_des").attr("class", "input_txt_f");
   }
}

function save(){
    var frm = $('#form_');
    loding();
    $.ajax({
       type: frm.attr('method'),
       url: frm.attr('action'),
       data: frm.serialize(),
       success: function (pid){
        if(pid == 1){
            loding();
            sucess_msg();
        }else if(pid == 2){
            set_msg("No permission to add data.");
        }else if(pid == 3){
            set_msg("No permission to edit data.");
        }else{
            set_msg(pid);
        }
        
    }
});
}

function get_data_table(){
    $.post("index.php/main/load_data/r_journal_type/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
        $("#code").attr("readonly","readonly");
        
    }, "text");
}


function check_code(){
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_journal_type/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
                $("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
        set_msg("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
        set_msg("Please enter description.");
        $("#description").focus();
        return false;
    }else if($("#account").val() ==0){
        set_msg("Please enter Payble A/C");
        $("#saccount").focus();
        return false;
    }else if($("#payble_type option:selected").val()==0)
    {
       set_msg("Please select payable type");
   }else{
    return true;
}
}

function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_journal_type", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else if(res == 2){
                set_msg("No permission to delete data.");
            }else{
                set_msg("Delete fail");
            }
            
        }, "text");
    }
}

function set_edit(code){
    loding();
    
    $.post("index.php/main/get_data/r_journal_type", {
        code : code
    }, function(res){
        
        if(res.sum.code!='')
        {
            $("#code_").val(res.sum.code);
            $("#code").val(res.sum.code);
            $("#code").attr("readonly", true);
            $("#description").val(res.sum.description);
            $("#payble_type").val(res.sum.payble_type);
            
            $("#saccount").val(res.sum.account);
            $("#account").val(res.sum.account);
            $("#account_des").val(res.sum.payable_acc);
            empty_grid();
            for(var i=0;i< res.det.length;i++)
            {
                $("#0_"+i).val(res.det[i].account_code);
                $("#h_"+i).val(res.det[i].account_code);
                $("#n_"+i).val(res.det[i].acc_des);
                $("#1_"+i).val(res.det[i].dr);
                $("#2_"+i).val(res.det[i].cr);
                $("#3_"+i).val(res.det[i].note);
            }    
        }
        else
        {
            set_msg('No record found');
        }    
        loding(); input_active();
    }, "json");
}

function formatAccount(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatAccountResult(row){
    return row[0]+"~"+row[1];
}