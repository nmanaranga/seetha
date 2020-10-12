var storse = 0;
$(document).ready(function(){
    
    $("#tgrid").tableScroll({height:300});
    
    $("#stores").val(storse);
    set_select('stores', 'sto_des');
    
    load_items();
    
    $(".fo").focus(function(){
        if($("#stores option:selected").val() != 0){
            set_cid($(this).attr("id"));
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }else{
            alert("Please Select Stores");
        }
    });
    
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
    });
    
    $("#pop_search").gselect();
    
    $(".amo, .qun, .dis").keyup(function(){
        set_cid($(this).attr("id"));
        
        set_sub_total();
    });
    
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
            window.open('index.php/prints/trance_forms/t_damages_free_issues/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    
    $("#stores").change(function(){
        set_select('stores', 'sto_des');
        load_items();
    });
    
    enter_setup_trance();
});

$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_damages_free_issues", {
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
    $.post("index.php/main/get_data/t_damages_free_issues/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#id").attr("readonly", "readonly");
            $("#stores").val(r.sum.stores);
            set_select('stores', 'sto_des');
            load_items();
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].item_code);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                
                if(r.det[i].is_measure == 1){
                    $("#1_"+i).val(r.det[i].quantity);
                    $("#1_"+i).autoNumeric({mDec:2});
                }else{
                    $("#1_"+i).val(parseInt(r.det[i].quantity));
                    $("#1_"+i).autoNumeric({mDec:0});
                }
                
                $("#2_"+i).val(r.det[i].cost);
                $("#3_"+i).val(r.det[i].discount);
                $("#1_"+i).removeAttr("disabled");
                $("#2_"+i).removeAttr("disabled");
                $("#3_"+i).removeAttr("disabled");
                
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
        stores : $("#stores option:selected").val(),
        date   : $("date").val()
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function settings(){
    $("#item_list tr").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                if(parseFloat($(this).children().eq(5).html()) > 0){
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
                    alert("Selected item is empty");
                }
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
    }else if($("#stores option:selected").val() == 0){
        alert("Please select stores");
        v = false;
    }
    
    return v;
}

function save(){
    $("#form_").submit();
}