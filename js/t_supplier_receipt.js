var is_edit=0;
var branch_list = new Array(); var auto_fill;
$(document).ready(function(){
    load_items();
    
    $("#tgrid").tableScroll({height:250});
    
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
    });

    $("#pop_search").gselect();
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    
    $("#btnDelete").click(function()
	{
        
       check_issuecheque_status_del($("#hid").val());
        
    });
	
    $("#btnDelete, #btnSave, #btnReset").removeAttr("disabled");
    $("#amt").removeAttr("readonly","readonly");
    
    
    $("#btnPrint").click(function(){
        var id = $("#hid").val()
        if(id != 0){
            window.open('index.php/prints/trance_forms/t_supplier_receipt/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    
    $("#bank").change(function(){
        set_bank_branch();
    });
    
    enter_setup_trance(); get_bank_branch();
    
    $("#auto_fill").change(function(){
        set_auto_fill();
    });
    
    $("#total").keyup(function(){
        if(auto_fill == true){
            auto_filling();
            var chq = parseFloat($("#cheque").val()); if(isNaN(chq)){ chq = 0; }
            var cash = parseFloat($("#cash").val()); if(isNaN(cash)){ cash = 0; }
            $("#credit").val(parseFloat($(this).val()) - (chq + cash));
        }
    });
    
    set_auto_fill();
    
    $(".tamo").keyup(function(){
        set_total();
    });
    
    $(".tamo").blur(function(){
        check_balance($(this));
    });
    
    $("#ssupplier").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatCustomer,
        formatResult: formatCustomerResult
    });
    
    $("#ssupplier").blur(function(){
	set_cus_values($(this));
    });
    
    $("#ssupplier").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values($(this));
	}
    });
    
    $(".ttt").keyup(function(){
	set_cheque_total();
    });
    
    $("#amt").keyup(function(){
        set_cheque_total();
    });
    
    $("#pay_credit_label").html("Settle Balance");
    $("#credit").attr("title", "Amount");
    input_reset();
    
    $("#btnSavePay").click(function(){
        if(validate())
        {
			check_permission();
           // check_issuecheque_status($("#hid").val());
           
        }
       
    });
});

function check_permission()
{
    $.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '022',
        is_edit   : is_edit
        
    },function(r){
    
    if(r=='1')
    {
       //save();
	   check_issuecheque_status($("#hid").val());
       
    }    
    else if(r=='2')
    {
        alert("You have no permission to edit this record");
    }
    else
    {
      save();
    }    
});
    
}

function check_issuecheque_status(id)
    {
        $.post("index.php/main/load_data/loder/check_issuecheque_status",{
           
            id :id,
            trans :"VOUCHER"
            
        },function(data)
        {
            if(data=='1')
            {
                alert("you cannot edit this record.This is deposit cheque");
            }
            else if(data=='2')
            {
                
                alert("you cannot edit this record.This is returned cheque");
            }    
            else
            {

                $("#btnSave").click();
				
            }    
            
        },"text");
        
        
    }

function check_issuecheque_status_del(id)
    {
        $.post("index.php/main/load_data/loder/check_issuecheque_status",{
           
            id :id,
            trans :"VOUCHER"
            
        },function(data)
        {
            if(data=='1')
            {
                alert("you cannot delete this record.This is deposit cheque");
            }
            else if(data=='2')
            {
                
                alert("you cannot delete this record.This is returned cheque");
            }    
            else
            {

				check_delete_permission();
            }    
            
        },"text");
             
    }
	
	function check_delete_permission()
    {
    $.post("index.php/main/load_data/user_permissions/get_delete_permission",{
        module_id : '022'
    },function(r){
    
    if(r>0)
    {
        set_delete();
 
    }    
    else
    {
         alert("You have no permission to delete this record");
    }    
    
    });  
    }

function set_cheque_total(){
    var t = ttt = 0;
    $(".ttt").each(function(){
	t = parseFloat($(this).val());
	if(! isNaN(t)){
	    ttt += t;
	}
    });
    
    var ct = parseFloat($("#amt").val());
    if(isNaN(ct)){
	ct = 0;
    }
    var crt = parseFloat($("#total").val());
    if(isNaN(crt)){
	crt = 0;
    }
    
    $("#cheque").val(ttt);
    ttt += ct;
    $("#credit").val(crt - ttt);
    
    input_active();
}

function set_cus_values(f){
    
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
	$("#supplier").val(v[0]);
	$("#sup_des").val(v[1]);
	$("#sup_des").attr("class", "input_txt_f");
        load_balance();
    }
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}

function check_balance(o){
    set_cid(o.attr("id"));
    
    var eb = parseFloat(o.val());
    var rb = parseFloat($("#2_"+scid).val());
    
    if(! isNaN(eb)){
        if(isNaN(rb)){
            alert("No credited balance.");
            o.val("");
        }else if(rb < eb){
            alert("Please use balance amount.");
            o.val(rb);
        }
    }set_total();
}

function get_total(){
    var t = tt = 0; 
    $(".tamo").each(function(){
        tt = parseFloat($(this).val());
        if(isNaN(tt)){ tt = 0;}
        t += tt;
    });
    
    return t;
}

function set_total(){
    var t = get_total();
    $("#total").val(m_round(t));
    var chq = parseFloat($("#cheque").val()); if(isNaN(chq)){ chq = 0; }
    var cash = parseFloat($("#amt").val()); if(isNaN(cash)){ cash = 0; }
    t = t - (chq + cash);
    $("#credit").val(m_round(t));
    $("#cash").val(m_round(t));
    
    input_active();
}

function set_auto_fill(){
    if($("#auto_fill").attr("checked") == undefined){
        $(".tamo").removeAttr("readonly");
        $("#total").attr("readonly", "readonly");
        auto_fill = false;
    }else{
        $("#total").removeAttr("readonly");
        $(".tamo").attr("readonly", "readonly");
        auto_fill = true; auto_filling();
    }
}

function auto_filling(){
    var v = parseFloat($("#total").val());
    if(isNaN(v)){ v = 0; } var t = 0; 
    $(".tamo").each(function(){
        if(v > 0){
            t = parseFloat($(this).parent().parent().children().eq(2).children().val());
            if(! isNaN(t)){
                if(t <= v){
                    $(this).val(t);
                    v -= t;
                }else if(t > v){
                    $(this).val(v);
                    v=0;
                }
            }
        }else{
            $(this).val("");
        }
    });
    
    input_active();
}

function load_balance(){
    empty_grid();
    var code = $("#supplier").val();
    if(code != "0"){
        loding();
        $.post("index.php/main/load_data/m_supplier/balance", {
            code : code
        }, function(r){
            if(r.length == 0){
                alert("No crdited purchase invoice for this supplier.");
            }else{
                var total_balance = 0;
                for(var i=0; i<r.length; i++){
                    $("#0_"+i).val(r[i].no);
                    $("#1_"+i).val(r[i].total);
                    $("#2_"+i).val(r[i].balance);
                    $("#2_"+i).val(r[i].balance);
                    $("#4_"+i).val(r[i].dr_trnce_code);    
                    
                    total_balance += parseFloat(r[i].balance);
                    
                }
                if(auto_fill == true){ auto_filling();}
                $("#balance").val(total_balance);
                input_active();
            } loding();
        }, "json");
    }
}

$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});

function get_bank_branch(){
    $.post("index.php/main/load_data/m_bank_branch/bank_branch_list", {
        
    }, function(r){
        branch_list = r;
    }, "json");
}

function set_bank_branch(){
    var m = $("#bank option:selected").val();
    var txt  = "<option value='0'>---</option>";
    if(branch_list[m] != undefined){
        for(var i=0; i<branch_list[m].length; i++){
            txt += "<option value='"+branch_list[m][i][0]+"'>"+branch_list[m][i][1]+"</option>";
        }
    }
    
    $("#bank_branch").html(txt);
}

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_supplier_receipt", {
                id : id
            }, function(r){
                if(r != 1){
                    alert("Record cancel fail.");
                }else{
                    $("#btnReset").click();
                }
            }, "text");
        }
    }else{
        alert("Please load record");
    }
}

function empty_grid(){
    for(var i=0; i<25; i++){
        $("#h_"+i).val(0);
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#t_"+i).html("&nbsp;");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
    }
    
    $("#balance").val($("#balance").attr("title"));
    $("#balance").attr("class", "input_amount");
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_supplier_receipt/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#supplier").val(r.sum.supplier);
            $("#ssupplier").val(r.sum.supplier);
            $('#sup_des').val(r.sum.name+" ("+r.sum.full_name+")");
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#id").attr("readonly", "readonly");
            $("#hid").val(r.sum.id);
            $("#cheque").val(r.sum.cheque_amount);
            $("#amt").val(r.sum.cash_amount);
            $("#credit").val(0);
            $("#total").val(parseFloat(r.sum.cash_amount) + parseFloat(r.sum.cheque_amount));
            $("#cash").val(parseFloat(r.sum.cash_amount) + parseFloat(r.sum.cheque_amount));
            $("#balance").val(r.sum.balance);
            
            for(var i=0; i<r.det.length; i++){
                $("#0_"+i).val(r.det[i].purchase_no);
                $("#1_"+i).val(r.det[i].total);
                $("#2_"+i).val(r.det[i].balance);
                $("#3_"+i).val(r.det[i].paid);
                $("#4_"+i).val(r.det[i].trans_code);
            }
            
            for(var i=0; i<r.cheque.length; i++){
                //$("#qbh_"+i).val(r.cheque[i].bank);
                $("#qbbh1_"+i).val(r.cheque[i].bank_branch);
                $("#q0_"+i).val(r.cheque[i].bank_name);
                $("#qn1_"+i).val(r.cheque[i].description);
                $("#q41_"+i).val(r.cheque[i].realize_date);
                $("#q11_"+i).val(r.cheque[i].cheque_no);
                $("#q21_"+i).val(r.cheque[i].account_no);
                $("#q31_"+i).val(r.cheque[i].cheque_amount);
            }
            
            if(r.sum.is_cancel > 0){
                alert("This record canceled.");
                
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }else if(r.sum.posting > 0){
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/posted.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }
            
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

function load_items(){
    $.post("index.php/main/load_data/m_items/item_list", {
        search : $("#pop_search").val(),
        dep : 1,
        date : $("#date").val()
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function settings(){
    $("#item_list tr").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#2_"+scid).val($(this).children().eq(2).html());
                
                if($(this).children().eq(4).html() == 1){
                    $("#1_"+scid).autoNumeric({mDec:2});
                }else{
                    $("#1_"+scid).autoNumeric({mDec:0});
                }
                $("#1_"+scid).removeAttr("disabled"); $("#2_"+scid).removeAttr("disabled"); $("#3_"+scid).removeAttr("disabled");
                $("#1_"+scid).focus();$("#pop_close").click();
            }else{
                alert("Item "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); $("#2_"+scid).val(""); $("#3_"+scid).val(""); $("#t_"+scid).html("&nbsp;");
            $("#1_"+scid).attr("disabled", "disabled"); $("#2_"+scid).attr("disabled", "disabled"); $("#3_"+scid).attr("disabled", "disabled");
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
    $(".tamo").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });
    
    if($("#cheque").val()>0)
    {
        
        
        for(var i=0;i<=10;i++)
            {
                 if($("#qn1_"+i).val()!='' || $("#q11_"+i).val()!=''|| $("#q21_"+i).val()!=''|| $("#q31_"+i).val()!=''|| $("#q41_"+i).val()!='')
                {
                 if($("#qbbh1_"+i).val()=='')
                     {
                         alert("Please select a Bank");
                         $("#qbbh1_"+i).focus();
                          v = false;
                     }     
                     else if($("#q11_"+i).val()==='')
                     {
                         alert("Please enter valid cheque number");
                         $("#q11_"+i).focus();
                          v = false;
                     }     
                     else if($("#q21_"+i).val()==='')
                     {
                         alert("Please enter Account number");
                         $("#q21_"+i).focus();
                          v = false;
                     }     
                     else if($("#q41_"+i).val()==='')
                     {
                         alert("Please enter Realized Date");
                         $("#q41_"+i).focus();
                          v = false;
                     }  
                     else if($("#q31_"+i).val()==='')
                     {
                         alert("Please enter Amount");
                         $("#q31_"+i).focus();
                          v = false;
                     }  
                     
                }
            } 
            } 
        
    var c = parseFloat($("#credit").val());
    if(c != 0){
        alert("Please make settle balance is 0.");
        $("#btnPayments").click();
        v = false;
    }
    else if($("#total").val() == 0 || $("#total").val() == ''){
        alert("Please pay minimum one voucher.");
    }else if($("#supplier").val() == 0){
        alert("Please select supplier");
        v = false;
    }
    
    return v;
}

function save(){
    $("#form_").submit();
}