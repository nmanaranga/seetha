var is_edit=0;
var root = new Array(); var sroot = sarea = 0;  var auto_fill;
$(document).ready(function(){    
    $("#tgrid, #tgrid1").tableScroll({height:250});
    
    if($("#auto_fill").attr("checked") == undefined){
        auto_fill = false;
    }else{
        auto_fill = true;
    }
    
    $("#btnDelete").click(function(){
       // set_delete();
		check_delete_permission();
    });
    
	function check_delete_permission()
    {
    $.post("index.php/main/load_data/user_permissions/get_delete_permission",{
        module_id : '023'
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
	
	// save click
    $("#btnSave").click(function()
    {
    if(validate())
    {
        check_permission();
    }    
    });
	
	function check_permission()
	{
    	$.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '023',
        is_edit   : is_edit
        
    },function(r){
    
    if(r=='1')
    {
       save();
       
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
    
    $("#auto_fill").change(function(){
        if($(this).attr("checked") == undefined){
            auto_fill = false;
        }else{
            auto_fill = true;
            set_auto_fill($("input[name='sett_pay']").val());
        }
    });
    
    $("#btnSaveAreaRoot").click(function(){
        set_session();
    });
    
    $("#tgrid tr").click(function(){
        if($(this).children().eq(4).children().val() == ""){
            alert("Please Select row with value");
        }else{
            $("#tgrid tr").css("background-color", "transparent");
            $("#tgrid tr").removeAttr("id");
            $("#tgrid tr").each(function(){
                $(this).children().eq(5).children().val("");
                $(this).children().eq(5).children().attr("readonly", "readonly");
                $(this).children().eq(0).children().removeAttr("name");
                $(this).children().eq(1).children().removeAttr("name");
                $(this).children().eq(2).children().removeAttr("name");
                $(this).children().eq(3).children().removeAttr("name");
                $(this).children().eq(4).children().removeAttr("name");
                $(this).children().eq(5).children().removeAttr("name");
            });
            
            $(this).attr("id", "sel");
            $(this).css("background-color", "#dde458");
            $(this).children().eq(5).children().val($(this).children().eq(4).children().val());
            $(this).children().eq(5).children().removeAttr("readonly");
            $(this).children().eq(0).children().attr("name", "type_pay");
            $(this).children().eq(1).children().attr("name", "no_pay");
            $(this).children().eq(2).children().attr("name", "total_pay");
            $(this).children().eq(3).children().attr("name", "paid_pay");
            $(this).children().eq(4).children().attr("name", "balance_pay");
            $(this).children().eq(5).children().attr("name", "sett_pay");
            $(this).children().eq(5).children().select();
            
            $("input[name='sett_pay']").keyup(function(){
                set_auto_fill($(this).val());
            });
            
            if(auto_fill == true){ set_auto_fill($(this).children().eq(4).children().val()); }
        }
    });
    
    $(".set_amo").blur(function(){
        var s = parseFloat($(this).val());
        var b = parseFloat($(this).parent().parent().children().eq(4).children().val());
        if(isNaN(s)){
            $(this).parent().parent().css("background-color", "transparent");
        }else if(b >= s){
            $(this).parent().parent().css("background-color", "#dde458");
        }else{
            alert("Please use "+b+" for maximum");
            $(this).parent().parent().css("background-color", "#dde458");
            $(this).val(b);$(this).focus();
        }
    });
    
    $(".pay_amo").blur(function(){
        var s = parseFloat($(this).val());
        var b = parseFloat($(this).parent().parent().children().eq(4).children().val());
        if(isNaN(s)){
            $(this).parent().parent().css("background-color", "transparent");
        }else if(b >= s){
            $(this).parent().parent().css("background-color", "#dde458");
        }else{
            alert("Please use "+b+" for maximum");
            $(this).parent().parent().css("background-color", "transparent");
            $(this).val("");
        }
    });
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){ load_data($(this).val()); }
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
    
});

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

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_supplier_settlement/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#supplier").val(r.sum.supplier);
            $("#ssupplier").val(r.sum.supplier);
            $("#sup_des").val(r.sum.name+" ("+r.sum.full_name+")");
            //set_select('customer', 'cus_des');
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#id").attr("readonly", "readonly");
            $("#dbalance").val(r.sum.dbalance);
            $("#cbalance").val(r.sum.cbalance);
            
            $("#c0_0").val(r.sum.type_pay);
            $("#c1_0").val(r.sum.no_pay);
            $("#c2_0").val(r.sum.total_pay);
            $("#c3_0").val(r.sum.paid_pay);
            $("#c4_0").val(r.sum.balance_pay);
            $("#c5_0").val(r.sum.sett_pay);
            
            $("#c0_0").attr("name", "type_pay");
            $("#c1_0").attr("name", "no_pay");
            $("#c2_0").attr("name", "total_pay");
            $("#c3_0").attr("name", "paid_pay");
            $("#c4_0").attr("name", "balance_pay");
            $("#c5_0").attr("name", "sett_pay");
            
            $("#c0_0").parent().parent().css("background-color", "#dde458");
            
            for(var i=0; i<r.det.length; i++){
                $("#d0_"+i).val(r.det[i].trance_type);
                $("#d1_"+i).val(r.det[i].trance_no);
                $("#d2_"+i).val(r.det[i].total);
                $("#d3_"+i).val(r.det[i].paid);
                $("#d4_"+i).val(r.det[i].balance);
                $("#d5_"+i).val(r.det[i].settle);
                
                $("#d0_"+i).parent().parent().css("background-color", "#dde458");
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

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_supplier_settlement", {
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

function set_auto_fill(v){
    v = parseFloat(v);
    if(! isNaN(v)){
        var t = 0;
        if(auto_fill == true){
            $(".set_amo").each(function(){
                if(v > 0){
                    t = parseFloat($(this).parent().parent().children().eq(4).children().val());
                    if(! isNaN(t)){
                        if(t <= v){
                            $(this).val(t);
                            v -= t;
                        }else if(t > v){
                            $(this).val(v);
                            v=0;
                        }
                        $(this).parent().parent().css("background-color", "#dde458");
                    }
                }else{
                    $(this).val("");
                    $(this).parent().parent().css("background-color", "transparent");
                }
            });
        }
    }
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

function load_balance(){
    empty_grid();
    var code = $("#supplier").val();
    
    if(code != "0"){
        $.post("index.php/main/load_data/m_supplier/get_unsettle_balance", {
            supplier : code
        }, function(r){
            var cb = db = 0;
            for(var i=0; i<r.cr.length; i++){
                $("#c0_"+i).val(r.cr[i].dr_trnce_code);
                $("#c1_"+i).val(r.cr[i].dr_trnce_no);
                $("#c2_"+i).val(r.cr[i].total);
                $("#c3_"+i).val(r.cr[i].paid);
                $("#c4_"+i).val(r.cr[i].bal);
                cb += parseFloat(r.cr[i].bal);
            }
            $("#dbalance").val(cb);
            for(var i=0; i<r.dr.length; i++){
                $("#d0_"+i).val(r.dr[i].dr_trnce_code);
                $("#d1_"+i).val(r.dr[i].dr_trnce_no);
                $("#d2_"+i).val(r.dr[i].total);
                $("#d3_"+i).val(r.dr[i].paid);
                $("#d4_"+i).val(r.dr[i].bal);
                db += parseFloat(r.dr[i].bal);
            }
            $("#cbalance").val(db);
            input_active();
        }, "json");
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

function get_route_list(){
    $.post("index.php/main/load_data/m_root/select_area_wise", {
        
    }, function(r){
        root = r;
    }, "json");
}

function pay_total(){
    var t = tt = 0;
    $(".set_amo").each(function(){
        t = parseFloat($(this).val());
        if(! isNaN(t)){
            tt += t;
        }
    });
    
    return tt;
}

function validate(){
    if($("#supplier").val() == 0){
        alert("Please select customer");
        return false;
    }else if(isNaN($("#dbalance").val())){
        alert('No debit balance for pay');
        return false;
    }else if(isNaN($("#cbalance").val())){
        alert('No credit balance to pay');
        return false;
    }else if(isNaN(parseFloat($("input[name='sett_pay']").val()))){
        alert('Set debit balance to pay');
        return false;
    }else if(parseFloat($("input[name='sett_pay']").val()) != pay_total()){
        alert('Pay & Settle balanese are not match');
        return false;
    }else{
        return true;
    }
}

function save(){
    $("#form_").submit();
	is_edit=0;
}
