var branch_list = new Array(); var auto_fill; var sroot = sarea = 0;  
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
    
    $("#btnDelete").click(function(){
        set_delete();
    });
    
    $("#btnDelete, #btnSave, #btnReset").removeAttr("disabled");
    
    $("#btnPrint").click(function(){
        var id = $("#hid").val()
        if(id != 0){
            window.open('index.php/prints/trance_forms/t_customer_receipt/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    
    //$("#customer").change(function(){
    //    set_select('customer', 'cus_des');
    //    load_balance();
    //});
    
    $("#bank").change(function(){
        set_bank_branch();
    });
    
    enter_setup_trance(); get_bank_branch();
    
    $("#auto_fill").change(function(){
        set_auto_fill();
    });
    
    set_auto_fill();
    
    $(".tamo").keyup(function(){
        set_total();
    });
    
    $(".tamo").blur(function(){
        check_balance($(this));
    });
    
    $("#btnSaveAreaRoot").click(function(){
        set_session();
    });
    
    $("#btnSetRootArea").click(function(){
        $("#area").val(sarea);
        set_root();
        $("#route").val(sroot);
        $("#root_area").center();
        $("#blocker").css("display", "block");
    });
    
    get_route_list();
    
    $("#area").change(function(){
        set_root();
    });
    
    $("#total").keyup(function(){
        if(auto_fill == true){
            auto_filling();
            var chq = parseFloat($("#cheque").val()); if(isNaN(chq)){ chq = 0; }
            var cash = parseFloat($("#cash").val()); if(isNaN(cash)){ cash = 0; }
            $("#credit").val(parseFloat($(this).val()) - (chq + cash));
        }
    });
    
    $("#scustomers").autocomplete('index.php/main/load_data/m_customer/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatCustomer,
        formatResult: formatCustomerResult
    });
    
    $("#scustomers").blur(function(){
	set_cus_values($(this));
    });
    
    $("#scustomers").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values($(this));
	}
    });
    
    $("#pay_credit_label").html("Settle Balance");
    $("#credit").attr("title", "Amount");
    input_reset();
    
    $(".ttt").keyup(function(){
	set_cheque_total();
    });
    
    $("#cash").keyup(function(){
        set_cheque_total();
    });
    
    $("#btnSavePay").click(function(){
        $("#btnSave").click();
    });
});

function set_cheque_total(){
    var t = ttt = 0;
    $(".ttt").each(function(){
	t = parseFloat($(this).val());
	if(! isNaN(t)){
	    ttt += t;
	}
    });
    
    var ct = parseFloat($("#cash").val());
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
	$("#customer").val(v[0]);
	$("#cus_des").val(v[1]);
	$("#cus_des").attr("class", "input_txt_f");
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
    var cash = parseFloat($("#cash").val()); if(isNaN(cash)){ cash = 0; }
    t = t - (chq + cash);
    $("#credit").val(m_round(t));
    
    input_active();
}

function get_route_list(){
    $.post("index.php/main/load_data/m_root/select_area_wise", {
        
    }, function(r){
        root = r;
    }, "json");
}

function set_root(){
    var txt = "<option value='0'>---</option>";
    var area = $("#area option:selected").val();
    
    if(root[area] != undefined){
        for(var i=0; i<root[area].length; i++){
            txt += "<option value='"+root[area][i][0]+"'>"+root[area][i][1]+"</option>";
        }
    }
    
    $("#route").html(txt);
}

function set_session(){
    $.post("/index.php/main/load_data/loder/report_session", {
        route : $("#route option:selected").val(),
        area : $("#area option:selected").val()
    }, function(r){
        $("#root_area").css("display", "none");
        $("#blocker").css("display", "none");
        location.reload();
    }, "text");
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
    var total = parseFloat($("#total").val());
    if(isNaN(total)){ total = 0; }
    var t = 0;
    $(".tamo").each(function(){
        t = parseFloat($(this).parent().parent().children().eq(2).children().val());
        
        if(total > 0){
            if(! isNaN(t)){
                if(t <= total){
                    $(this).val(t);
                    total -= t;
                }else if(t > total){
                    $(this).val(total);
                    total = 0;
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
    var code = $("#customer").val();
    if(code != "0"){
        loding();
        $.post("index.php/main/load_data/m_customer/balance", {
            code : code
        }, function(r){
            if(r.length == 0){
                alert("No crdited invoice for this customer.");
                $("#customer").val(0);
                set_select("customer", "cus_des");
            }else{
                var total_balance = 0;
                for(var i=0; i<r.length; i++){
                    $("#h_"+i).val(r[i].id);
                    $("#0_"+i).val(r[i].no);
                    $("#1_"+i).val(r[i].total);
                    $("#2_"+i).val(r[i].balance);
                    
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
            $.post("index.php/main/delete/t_customer_receipt", {
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
    $.post("index.php/main/get_data/t_customer_receipt/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.customer);
            $("#scustomers").val(r.sum.customer);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            //set_select('customer', 'cus_des');
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#id").attr("readonly", "readonly");
            $("#hid").val(r.sum.id);
            $("#cheque").val(r.sum.cheque_amount);
            $("#cash").val(r.sum.cash_amount);
            $("#credit").val(0);
            $("#total").val(parseFloat(r.sum.cash_amount) + parseFloat(r.sum.cheque_amount));
            $("#balance").val(r.sum.balance);
            
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].purchase_id);
                $("#0_"+i).val(r.det[i].purchase_no);
                $("#1_"+i).val(r.det[i].total);
                $("#2_"+i).val(r.det[i].balance);
                $("#3_"+i).val(r.det[i].paid);
            }
            
            for(var i=0; i<r.cheque.length; i++){
                $("#qbh_"+i).val(r.cheque[i].bank);
                $("#qbbh_"+i).val(r.cheque[i].bank_branch);
                $("#q0_"+i).val(r.cheque[i].bank_name);
                $("#qn_"+i).val(r.cheque[i].description);
                $("#q4_"+i).val(r.cheque[i].realize_date);
                $("#q1_"+i).val(r.cheque[i].cheque_no);
                $("#q2_"+i).val(r.cheque[i].account_no);
                $("#q3_"+i).val(r.cheque[i].cheque_amount);
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
        dep : 1
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
    
    var c = parseFloat($("#credit").val());
    if(v == false){
        alert("Please use minimum one item.");
    }else if($("#customer").val() == 0){
        alert("Please select customer");
        v = false;
    }else if(c != 0){
        alert("Please make settle balance is 0.");
        $("#btnPayments").click();
        v = false;
    }
    
    return v;
}

function save(){
    $("#form_").submit();
}