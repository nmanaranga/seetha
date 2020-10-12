var storse = 0;
$(document).ready(function(){
    
    
    $("#scustomers").removeAttr("disabled", "disabled");
    $("#address").removeAttr("disabled", "disabled");
    $("#person").removeAttr("disabled", "disabled");
    $("#person1").removeAttr("disabled", "disabled");
    $("#person2").removeAttr("disabled", "disabled");
    $("#stores").removeAttr("disabled", "disabled");
    
    
    $("#stores").attr("disabled", "disabled");
    $("#scustomers").attr("readonly", "readonly");
    $("#address").attr("readonly", "readonly");
    $("#person").attr("readonly", "readonly");
    $("#person1").attr("readonly", "readonly");
    $("#person2").attr("readonly", "readonly");
    
    //$("#stores").val(storse);
    //set_select('stores', 'sto_des');
    
    //load_items();
    
    $("#tgrid").tableScroll({height:280});
    
//    $(".fo").focus(function(){
//        if($("#stores option:selected").val() != 0){
//            set_cid($(this).attr("id"));
//            $("#serch_pop").center();
//            $("#blocker").css("display", "block");
//            //setTimeout("select_search()", 100);
//        }else{
//            alert("Please Select Stores");
//        }
//    });
    
//    $("#pop_search").keyup(function(e){
//        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
//    });
    
    //$("#pop_search").gselect();
    
    $(".amo, .qun, .dis").keyup(function(){
        set_cid($(this).attr("id"));
        set_discount('dis');
        
        set_sub_total();
    });
    
    $(".dis_pre").keyup(function(){
        set_cid($(this).attr("id"));
        set_discount('pre');
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
            window.open('index.php/prints/trance_forms/t_dispatch_return/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    
    $("#supplier").change(function(){
        //set_select('supplier', 'sup_des');
    });
    
    $("#discount").keyup(function(){
        set_total();
    });
    
    $("#stores").change(function(){
        set_select('stores', 'sto_des');
 
       //load_items();
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

 $("#pono").keydown(function(e){
        if(e.keyCode == 13){

            $(this).blur();
             check_dispatch_return($(this).val());
            //load_order_data($(this).val());
        }
    });

});



function check_dispatch_return(id){
   
    $.post("index.php/main/get_subitem_data/t_dispatch_return/", {
        id : id
    }, function(r){
            //load_items();
            
         // alert(r.det.out_no);
           
           
           if(r.a>0)
            {
                load_order_return_data(id)
            }
            else
            {
                load_order_data(id)
                alert('This is a Return Dispatch');
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
            }    

    }, "json");
}


function load_order_data(id){
    
    //check_dispatch_return($("#pono").val());
    empty_grid();
    $.post("index.php/main/get_data/t_dispatch_note/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#address").val(r.sum.address);
            $("#reson").val(r.sum.reson);
            $("#scustomers").val(r.sum.customer);
            $("#customer").val(r.sum.customer);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            $("#trans_codi").val(r.sum.transport_codi);
            set_select('trans_codi', 'trans_des');
            
            $("#mar_codi").val(r.sum.marketing_codi);
            set_select('mar_codi', 'mar_des');
            
            $("#driver").val(r.sum.driver);
            set_select('driver', 'driver_des');
            
            $("#ref_no").val(r.sum.ref_no);

            $("#id").attr("readonly", "readonly");
            $("#stores").val(r.sum.stores);
            set_select('stores', 'sto_des');
	    //$("#btnSave").attr("disabled", "disabled");
	    //$("#btnDelete").attr("disabled", "disabled");
            //load_items();
            
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].item_code);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                                
                $("#1_"+i).val(r.det[i].qty);
                $("#2_"+i).val(r.det[i].qtyc);
                $("#4_"+i).val(r.det[i].current_stock);
                $("#3_"+i).val(r.det[i].cartoon);
               
                $("#1_"+i).removeAttr("disabled");
                $("#2_"+i).removeAttr("disabled");
                $("#3_"+i).removeAttr("disabled");
                
                set_cid("1_"+i);
               
            }
            
            $("#hid").val(r.sum.id);
            input_active();
        }else{
            alert("No records");
        }
    }, "json");
}

function load_order_return_data(id){
    
    //check_dispatch_return($("#pono").val());
    empty_grid();
    $.post("index.php/main/get_return_data/t_dispatch_note/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#address").val(r.sum.address);
            $("#reson").val(r.sum.reson);
            $("#scustomers").val(r.sum.customer);
            $("#customer").val(r.sum.customer);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            $("#trans_codi").val(r.sum.transport_codi);
            set_select('trans_codi', 'trans_des');
            
            $("#mar_codi").val(r.sum.marketing_codi);
            set_select('mar_codi', 'mar_des');
            
            $("#driver").val(r.sum.driver);
            set_select('driver', 'driver_des');
            
            $("#ref_no").val(r.sum.ref_no);

            $("#id").attr("readonly", "readonly");
            $("#stores").val(r.sum.stores);
            set_select('stores', 'sto_des');
	    //$("#btnSave").attr("disabled", "disabled");
	    //$("#btnDelete").attr("disabled", "disabled");
            //load_items();
            
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].item_code);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                                
                
                $("#2_"+i).val(r.det[i].qtyc);
                
                $("#3_"+i).val(r.det[i].cartoon);
               
                $("#1_"+i).removeAttr("disabled");
                $("#2_"+i).removeAttr("disabled");
                $("#3_"+i).removeAttr("disabled");
                
                set_cid("1_"+i);
               
            }
            
             for(var i=0; i<r.tran.length; i++){
                 
                 $("#1_"+i).val(r.tran[i].bal);
                 $("#4_"+i).val(r.tran[i].cs);
                 
             }
            
            
            $("#hid").val(r.sum.id);
            input_active();
        }else{
            alert("No records");
        }
    }, "json");
}

function set_discount(m){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z = x*y;
    if(m == "pre"){
	var d = parseFloat($("#4_"+scid).val()); if(isNaN(d)){ d = 0; }
	d = z*(d/100);
	$("#3_"+scid).val(m_round(d));
    }else if(m == "dis"){
	var d = parseFloat($("#3_"+scid).val()); if(isNaN(d)){ d = 0; }
	d = (d/z)*100;
	$("#4_"+scid).val(m_round(d));
    }
}

function set_cus_values(f){
    
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
	$("#supplier").val(v[0]);
	$("#sup_des").val(v[1]);
	$("#sup_des").attr("class", "input_txt_f");
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
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_dispatch_note", {
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
    if(isNaN(d)){ d = 0; }
    var z;
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
    $.post("index.php/main/get_data/t_dispatch_return/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.customer);
            $("#address").val(r.sum.address);
            $("#reason").val(r.sum.reason);
            $("#scustomers").val(r.sum.customer);
            $('#cus_des').val(r.sum.name);
            $("#ref_no").val(r.sum.ref_no);
            $("#id").attr("readonly", "readonly");
            $("#stores").val(r.sum.stores);
            $("#pono").val(r.sum.dispatch_no);
            set_select('stores', 'sto_des');
            
            $("#invoice_no").val(r.sum.invoice_no);
            //load_items();
            for(var i=0; i<r.det.length; i++){
                $("#h_"+i).val(r.det[i].item_code);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                                
                $("#2_"+i).val(r.det[i].qtyc);
                $("#1_"+i).val(r.det[i].qty);
		$("#4_"+i).val(r.det[i].current_stock);
		$("#3_"+i).val(r.det[i].cartoon);
                $("#1_"+i).removeAttr("disabled");
                $("#2_"+i).removeAttr("disabled");
                $("#3_"+i).removeAttr("disabled");
                
                set_cid("1_"+i);
                set_sub_total();
            }
            
            $("#trans_codi").val(r.sum.transport_codi);
            set_select('trans_codi', 'trans_des');
            
            $("#mar_codi").val(r.sum.marketing_codi);
            set_select('mar_codi', 'mar_des');
            
            $("#driver").val(r.sum.driver);
            set_select('driver', 'driver_des');
            
            $("#btnSave").attr("disabled", "disabled");
            
            
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

//function select_search(){
//    $("#pop_search").focus();
//    $("#pop_search").val("");
//}

function load_items(){
    $.post("index.php/main/load_data/m_items/item_list", {
        search : $("#pop_search").val(),
        stores : $("#stores option:selected").val()
    }, function(r){
        $("#sr").html(r);
        //settings();
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
            
	    load_subitem($(this).children().eq(0).html(),scid)
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
    }else if($("#supplier").val() == 0){
        alert("Please select supplier");
        v = false;
    }else if($("#stores option:selected").val() == 0){
        alert("Please select stores");
        v = false;
    }
    
    return v;
}

//function save(){
//    $("#form_").submit();
//}

function save(){
        $("#stores").removeAttr("disabled", "disabled");
        var frm = $('#form_');
	loding();
	$.ajax({
	    type: frm.attr('method'),
	    url: frm.attr('action'),
	    data: frm.serialize(),
	    success: function (pid) {
		loding();
		if(! isNaN(pid)){
		    if(confirm("Do you need get print?")){
			window.open('index.php/prints/trance_forms/t_dispatch_return/?id='+pid, '_blank');
			window.open('?action=t_dispatch_return', '_self');
		    }else{
			window.open('?action=t_dispatch_return', '_self');
		    }
		}else{
		    alert(pid);
		}
	    }
	});
    
}