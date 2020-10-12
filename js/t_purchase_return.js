var is_edit=0;
var storse = 0;

	
$(document).ready(function(){
    $("#stores").val(storse);
    set_select('stores', 'sto_des');
    
    //load_items();
    
    $("#tgrid").tableScroll({height:280});
    
    $(".fo").focus(function(){
        set_cid($(this).attr("id"));
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
    });

        $("#invoice_no").change(function(e){
           load_parent_record($("#invoice_no option:selected").val());
         });
    
//    $("#pop_search").keyup(function(e){
//        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
//    });
    
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
    
        module_id : '021',
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
	//delete click
    $("#btnDelete").click(function(){
       	check_delete_permission();
	  
    });
    
	function check_delete_permission()
    {
    $.post("index.php/main/load_data/user_permissions/get_delete_permission",{
        module_id : '021'
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
	
    $("#btnDelete, #btnSave, #btnReset").removeAttr("disabled");
    
    $("#btnPrint").click(function(){
        var id = $("#hid").val()
        if(id != 0){
            window.open('index.php/prints/trance_forms/t_purchase_return/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    //
    //$("#supplier").change(function(){
    //    set_select('supplier', 'sup_des');
    //});
    
    $("#discount").keyup(function(){
        set_total();
    });
    
    $("#stores").change(function(){
        set_select('stores', 'sto_des');
        //load_items();
    });
    

    
    
    $("#purchase_no").keydown(function(e){
        if(e.keyCode == 13){
            //$("#purchase_no").blur();
            
            check_is_purchase($(this).val()); 
        }
    });
    
    function check_is_purchase(id){
   
    $.post("index.php/main/get_return_data/t_purchase_return/", {
        id : id
    }, function(r){

           if(r.a>0)
            {
                load_purchase_return_data(id)
            }
            else
            {
                load_order_data(id)
                alert('This is a complited purchase return');
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
            }    

    }, "json");
} 
    
    
    $("#purchase_no").blur(function(){
        load_parent_record($(this).val());
    });
    
    enter_setup_trance();
    
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
        
        load_invoice(v[0]);
        
        
    }
}

function load_invoice($id){
    
    $.post("index.php/main/load_data/t_purchase_return/select", {
	    sup:$id 
        }, function(res){
            $("#invoice_no").html(res);
            }, "text");
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}

function load_parent_record(id){
    empty_grid();
    $.post("index.php/main/get_return_data/t_purchase/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            if(r.sum.is_cancel == 0){
                $("#supplier").val(r.sum.supplier);
                $("#ssupplier").val(r.sum.supplier);
                $('#sup_des').val(r.sum.name+" ("+r.sum.full_name+")");
                $("#discount").val(r.sum.discount);
                $("#id").attr("readonly", "readonly");
                $("#purchase_no").attr("readonly", "readonly");
                $("#stores").val(r.sum.stores);
                set_select('stores', 'sto_des');
                //load_items();
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
                $("#purchase_no").attr("readonly", "readonly");
                input_active();
            }else{
                $("#purchase_no").val("");
                alert("This is deleted record.");
            }
        }else{
            $("#purchase_no").val("");
            alert("No records");
        }
        
    }, "json");
}

$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_purchase_return", {
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
    var d = parseFloat($("#3_"+scid).val());
    var z; if(isNaN(d)){ d = 0; }
    if(! isNaN(x) && ! isNaN(y)){
        z = x*y;
        $("#t_"+scid).html(m_round(z - d));
    }else{
        $("#t_"+scid).html("0.00");
    }
    
    set_total();
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_purchase_return/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#supplier").val(r.sum.supplier);
            $("#ssupplier").val(r.sum.supplier);
            $('#sup_des').val(r.sum.name+" ("+r.sum.full_name+")");
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#discount").val(r.sum.discount);
            $("#id").attr("readonly", "readonly");
            $("#stores").val(r.sum.stores);
            set_select('stores', 'sto_des');
           /// $("#purchase_no").val(r.sum.purchase_no);
            $("#invoice_no").html(r.inv);
            $("#invoice_no").val(r.sum.purchase_no);
            
            $("#stores,#invoice_no").attr("disabled","disabled");
            
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
function load_purchase_return_data(id){

    empty_grid();
    $.post("index.php/main/load_return_qty/t_purchase/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#supplier").val(r.sum.supplier);
            $("#ssupplier").val(r.sum.supplier);
            $('#sup_des').val(r.sum.name+" ("+r.sum.full_name+")");
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#discount").val(r.sum.discount);
            $("#id").attr("readonly", "readonly");
            $("#stores").val(r.sum.stores);
            set_select('stores', 'sto_des');
            $("#purchase_no").val(r.sum.po_no);
            
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].item_code);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                
                if(r.det[i].is_measure == 1){
                    $("#1_"+i).val(r.tran[i].bal);
                    $("#1_"+i).autoNumeric({mDec:2});
                }else{
                    $("#1_"+i).val(parseInt(r.tran[i].bal));
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
            
            //$("#hid").val(r.sum.id);
            input_active();
        }else{
            alert("No records");
        }
    }, "json");
}
function load_order_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_purchase/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#supplier").val(r.sum.supplier);
            $("#ssupplier").val(r.sum.supplier);
            $('#sup_des').val(r.sum.name+" ("+r.sum.full_name+")");
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#discount").val(r.sum.discount);
            $("#id").attr("readonly", "readonly");
            $("#stores").val(r.sum.stores);
            set_select('stores', 'sto_des');
            //$("#purchase_no").val(r.sum.purchase_no);
            
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

function set_total(){
    var t = tt = 0; 
    $(".tf").each(function(){;
        tt = parseFloat($(this).html());
        if(isNaN(tt)){ tt = 0;}
        t += tt;
    });
    
    $("#total2").val(m_round(t));
    
    var dis = parseFloat($("#discount").val());
    if(! isNaN(dis)){
        t -= dis;
    }
    
    $("#net_amount").val(m_round(t));
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
    }else if($("#net_amount").val() == 0 || $("#net_amount").val() == ''){
        alert("Please use minimum one item.");
        v = false;
    }else if($("#supplier").val() == 0){
        alert("Please select supplier");
        v = false;
    }else if($("#stores option:selected").val() == 0){
        alert("Please select stores");
        v = false;
    }
    
    return v;
}

function save(){
    
    $("#stores,#invoice_no").removeAttr("disabled","disabled");
    $("#form_").submit();
	is_edit=0;
}