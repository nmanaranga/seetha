var storse = 0;
$(document).ready(function(){
    
    $("#tgrid").tableScroll({height:300});
    
    $("#stores").val(storse);
    set_select('stores', 'sto_des');
    
    load_items();
    
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
            window.open('index.php/prints/trance_forms/t_supplier_opening_balance/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    
    $("#stores").change(function(){
        set_select('stores', 'sto_des');
        load_items();
    });
    
    $(".fo").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatCustomer,
        formatResult: formatCustomerResult
    });
    
    $(".fo").blur(function(){
	set_cus_values($(this));
    });
    
    $(".fo").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values($(this));
	}
    });
    
    enter_setup_trance();
    
    $(".dr").keyup(function(){
        dr_total();
    });
    
    
    $(".cr").keyup(function(){
        cr_total();
    });
});

function dr_total(){
    var t = tt = 0;
        $(".dr").each(function(){
            t = parseFloat($(this).val());
            if(! isNaN(t)){ tt += t; }
        });
        $("#dr_total").val(m_round(tt));
}

function cr_total(){
    var t = tt = 0;
        $(".cr").each(function(){
            t = parseFloat($(this).val());
            if(! isNaN(t)){ tt += t; }
        });
        $("#cr_total").val(m_round(tt));
}

function set_cus_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
        set_cid(f.attr("id"));
	f.val(v[0]);
        $("#h_"+scid).val(v[0]);
        $("#n_"+scid).val(v[1]);
    }
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}

$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});

function set_delete(){
    var id = $("#id").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_supplier_opening_balance", {
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
}

function set_sub_total(){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z;
    if(! isNaN(x) && ! isNaN(y)){
        z = x*y;
        $("#t_"+scid).html(m_round(z));
    }else{
        $("#t_"+scid).html("0.00");
    }
    
    set_total();
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_supplier_opening_balance/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            
            $("#date").val(r.sum.date);
            $("#id").attr("readonly", "readonly");
            $("#memo").val(r.sum.memo);
            
            
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].supplier);
                $("#0_"+i).val(r.det[i].supplier);
                $("#n_"+i).val(r.det[i].name);
                
                $("#1_"+i).val(r.det[i].dr);
                $("#2_"+i).val(r.det[i].cr);
                $("#1_"+i).removeAttr("disabled");
                $("#2_"+i).removeAttr("disabled");
                
                set_cid("1_"+i);
                set_sub_total();
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
            
            cr_total(); dr_total();
             $("#hid").val(r.sum.id);
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
        stores : $("#stores option:selected").val()
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

function set_total(){
    var t = tt = 0; 
    $(".tf").each(function(){;
        tt = parseFloat($(this).html());
        if(isNaN(tt)){ tt = 0;}
        t += tt;
    });
    
    $("#total2").val(m_round(t));
}

function validate(){
    var v = false;
    $("input[type='hidden']").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });
    
    if(v == false){
        alert("Please use minimum one item.");
    }
    
    return v;
}

function save(){
    $("#form_").submit();
}