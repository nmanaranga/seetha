var storse = 0;var is_edit=0;
$(document).ready(function(){

    load_accounts();
    
    $("#btnSave1").click(function(){
      if(validate())
        {
            check_permission();
        }
    });
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    
    $("#scredit_acc").keydown(function(e){       
        if(e.keyCode==112){
            $("#pop_search").val($("#scredit_acc").val());
            load_cr();
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);     
        }
        $("#pop_search").keyup(function(e){            
            load_cr();                        
        });
        if(e.keyCode==46){
            $("#scredit_acc").val("");
            $("#credit_acc_des").val("");
        }
    });

    $("#sdebit_acc").keydown(function(e){       
        if(e.keyCode==112){
            $("#pop_search2").val($("#sdebit_acc").val());
            load_dr();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search2()", 100);     
        }
        $("#pop_search2").keyup(function(e){            
            load_dr();                        
        });
        if(e.keyCode==46){
            $("#sdebit_acc").val("");
            $("#debit_acc_des").val("");
        }
    });


    $("#btnPrint").click(function(){
        $("#print_pdf").submit();
    });

    $("#btnDelete1").click(function(){
        if($("#hid").val()>0)
        {    
         check_delete_permission();
        }
        else
        {
            alert("Please load a record");
        }
    });
    
    $("#btnDelete1, #btnSave1, #btnPrint").removeAttr("disabled");

    $("#scredit_acc").autocomplete('index.php/main/load_data/m_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatjtype,
        formatResult: formatjtypeResult
    });
    
    $("#scredit_acc").blur(function(){
        set_cracc_values($(this));
    });
    
    $("#scredit_acc").keydown(function(e){
        if(e.keyCode == 13){
            set_cracc_values($(this));
        }
    });

    $("#sdebit_acc").autocomplete('index.php/main/load_data/m_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatjtype,
        formatResult: formatjtypeResult
    });
    
    $("#sdebit_acc").blur(function(){
        set_dracc_values($(this));
    });
    
    $("#sdebit_acc").keydown(function(e){
        if(e.keyCode == 13){
            set_dracc_values($(this));
        }
    });
  
});

function load_cr(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_account",
        field:"code",
        field2:"description",
        search : $("#pop_search").val() 
    }, function(r){
        $("#sr").html(r);
        settings_cr();        
    }, "text");
}

function load_dr(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_account",
        field:"code",
        field2:"description",
        search : $("#pop_search2").val() 
    }, function(r){
        $("#sr2").html(r);
        settings_dr();        
    }, "text");
}

function settings_cr(){
    $("#item_list .cl").click(function(){             
        $("#scredit_acc").val($(this).children().eq(0).html());
        $("#credit_acc_des").val($(this).children().eq(1).html());
        $("#pop_close").click();        
    });    
}

function settings_dr(){
    $("#item_list .cl").click(function(){             
        $("#sdebit_acc").val($(this).children().eq(0).html());
        $("#debit_acc_des").val($(this).children().eq(1).html());
        $("#pop_close2").click();        
    });    
}


function check_delete_permission(){

    set_delete();
 
}

function check_permission(){

     save();
}

function set_cracc_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
    f.val(v[0]);
    $("#credit_acc").val(v[0]);
    $("#credit_acc_des").val(v[1]);
    $("#credit_acc_des").attr("class", "input_txt_f");
       // load_je_type(v[0]);
    }
}

function set_dracc_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
    f.val(v[0]);
    $("#debit_acc").val(v[0]);
    $("#debit_acc_des").val(v[1]);
    $("#debit_acc_des").attr("class", "input_txt_f");
       // load_je_type(v[0]);
    }
}

function formatjtype(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatjtypeResult(row){
    return row[0]+"~"+row[1];
}

$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure cancel bank entry no '"+id+"' ? ")){
            $.post("index.php/main/delete/t_bank_entry", {
                id : id
            }, function(r){
                if(r != 1){
                    set_msg(r);
                }else{
                    location.href="";
                }
            }, "text");
        }
    }else{
        alert("Please load record");
    }
}

function empty_grid(){
    for(var i=0; i<$("#grid_row").val(); i++){
        $("#h_"+i).val(0);
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
    }
}

function load_data(id){

    empty_grid();
    $.post("index.php/main/get_data/t_bank_entry/", {
        id : id
    }, function(r){
        if(r.sum.nno != undefined){
            $("#id").attr("readonly","readonly");

            $("#date").val(r.sum.dDate);
            $("#sdebit_acc").val(r.sum.draccId);
            $("#debit_acc").val(r.sum.draccId);
            $("#debit_acc_des").val(r.sum.draccdes);
            $("#qno").val(r.sum.nno);
            $("#hid").val(r.sum.nno);
            $("#scredit_acc").val(r.sum.craccId);
            $("#credit_acc").val(r.sum.craccId);
            $("#credit_acc_des").val(r.sum.craccdes);

            $("#description").val(r.sum.description);
            $("#ref_no").val(r.sum.ref_no);
            $("#sub_no").val(r.sum.sub_no);
            $("#narration").val(r.sum.narration);
            $("#amount").val(r.sum.amount);
            $("#batch").val(r.sum.batch_code);

            if(r.sum.type=='CashEntry') {$("#R1").attr("checked", true);}
            else {$("#R2").attr("checked", true);}

            if(r.sum.is_cancel > 0){
                              
                $("#btnDelete1").attr("disabled", "disabled");
                $("#btnSave1").attr("disabled", "disabled");
                $("#btnPrint").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }
            is_edit=1;
            
            input_active();
        }else{
            alert("No records");
        }
    }, "json");
}

function select_search(){
    $("#pop_search").focus();
    $("#pop_search").val("");
}

function select_search2(){
    $("#pop_search2").focus();
    $("#pop_search2").val("");
}
function load_accounts(){
    $.post("index.php/main/load_data/m_account/account_list", {
        search : $("#pop_search").val()
       
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function settings(){
    $("#item_list tr").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#1_"+scid).val('');
                $("#2_"+scid).val('');
                $("#2_"+scid).val($(this).children().eq(7).html());
                $("#1_"+scid).focus();$("#pop_close").click();
            }else{
                alert("Account code "+$(this).children().eq(1).html()+" is already added.");
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

function validate(){
    var v = false;
    $("input[type='hidden']").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });
    if($("#description").val() == ''){
        set_msg("Please enter description");
        v = false;
    }
    else if($("#sdebit_acc").val() == 0){
        set_msg("Please enter Dr Account");
        v = false;
    }
    else if($("#scredit_acc").val() == 0){
        set_msg("Please enter Cr Account");
        v = false;
    }else if($("#amount").val() == 0){
        set_msg("Please enter Valid amount");
        v = false;
    }else if(!$('input[name="optConfirm"]:checked').length > 0){
        set_msg("Please select options");
         v = false;
    }
    return v;
}

function save(){
    $("#qno").val($("#id").val());    
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid) {
           if(pid==1){
            $("#btnSave1").attr("disabled",true);
              if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                    $("#print_pdf").submit();
                }
                location.href="";
              }else{
                location.href="";
              }
            }else{
                set_msg(pid);
            }     
       }
    });       
        is_edit=0;
}

function closeMsgBox(){
    $(".msgBox").fadeOut(500);
    empty_grid();
    input_reset();
    get_max();
    //clear(); 
}



function get_max(){
    $.post("/index.php/main/load_data/t_bank_entry/get_max", {
    }, function(r){
       $("#id").val(r.max_no);
    }, "json");
}
