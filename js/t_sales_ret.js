var root = new Array(); var sroot = sarea = 0;  var auto_fill; var i=0; var tot1=tot=0;
$(document).ready(function(){    
    $("#tgrid, #tgrid1").tableScroll({height:250});
    
    if($("#auto_fill").attr("checked") == undefined){
        auto_fill = false;
    }else{
        auto_fill = true;
    }
    
    $("#btnDelete").click(function(){
        set_delete();
    });


    $("#inv_no").keydown(function(e)
    {
         if(e.keyCode == 13){
        
            var sale=$("#sale").val();
            
            if(sale=='0')
                {
                    load_cashsales_return($(this).val());
                }
            else
                {
                    load_creditsales_return($(this).val());
                }
         }
    });
   
    
    function load_creditsales_return(id){
    empty_grid();
    $.post("index.php/main/get_return_data/t_sales_credit/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.customer);
            $("#scustomers").val(r.sum.customer);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            $("#sales_ref").val(r.sum.sales_ref);
            set_select('sales_ref', 'ref_des');
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#discount").val(r.sum.discount);
            $("#id").attr("readonly", "readonly");
            $("#so_no").val(r.sum.so_no);
            $("#stores").val(r.sum.stores);
            $("#balance").val(r.sum.balance);
            $("#cash").val(r.sum.cash);
            $("#rm").val(r.sum.r_margin);
            set_select('stores', 'sto_des');
	    //$("#stores").attr("disabled", "disabled");
	    
	    $("#cash").val(r.sum.pay_amount);
	    $("#amt").val(r.sum.cash);
	    $("#advance").val(r.sum.advance);
	    $("#cheque").val(r.sum.cheque);
	    $("#credit").val(r.sum.credit);
	    
           
            
            for(var i=0; i<r.det.length; i++){
                $("#c0_"+i).val(r.det[i].item_code);
                $("#0_"+i).val(r.det[i].item_code);
                $("#c1_"+i).val(r.det[i].description);
                
                  if(r.det[i].is_measure == 1){
                        $("#c3_"+i).val(r.det[i].quantity);
                        $("#c3_"+i).autoNumeric({mDec:2});
                        
                        
                    }else{
                        $("#c3_"+i).val(parseInt(r.det[i].quantity));
                        $("#c3_"+i).autoNumeric({mDec:0});
                    }
                    
                    $("#c2_"+i).val(r.det[i].foc);
                    $("#c4_"+i).val(r.det[i].cost);
                    $("#c5_"+i).val(((r.det[i].cost)*(r.det[i].quantity))-(r.det[i].discount));
                
                set_cid("1_"+i);
              
            }

            $("#cm").val(r.sum.c_margin);
            

            input_active();
        }else{
            alert("No records");
        }
    }, "json");
}
    

    function load_cashsales_return(id)
    {
        empty_grid();
        
        
        $.post("index.php/main/get_return_data/t_sales/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            if(r.sum.is_cancel == 0){
                $("#customer").val(r.sum.customer);
                $("#scustomers").val(r.sum.customer);
                $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
                $("#address").val(r.sum.address)
                set_select('customer', 'sup_des');
                $("#discount").val(r.sum.discount);
                $("#id").attr("readonly", "readonly");
                $("#sales_ref").val(r.sum.sales_ref);
                set_select('sales_ref', 'ref_des');
                $("#stores").val(r.sum.stores);
                set_select('stores', 'sto_des');
                $("#invoice_no").attr("readonly", "readonly");
                $("#customer,#scustomers,#cus_des,#address").attr("readonly","readonly");
                //$("#stores").attr("disabled","disabled");
                
                for(var i=0; i<r.det.length; i++){
                    $("#c0_"+i).val(r.det[i].item_code);
                    $("#0_"+i).val(r.det[i].item_code);
                    $("#c1_"+i).val(r.det[i].description);
                    
                    if(r.det[i].is_measure == 1){
                        $("#c3_"+i).val(r.det[i].quantity);
                        $("#c3_"+i).autoNumeric({mDec:2});
                        
                        
                    }else{
                        $("#c3_"+i).val(parseInt(r.det[i].quantity));
                        $("#c3_"+i).autoNumeric({mDec:0});
                    }
                    
                    $("#c2_"+i).val(r.det[i].foc);
                    $("#c4_"+i).val(r.det[i].cost);
                    $("#c5_"+i).val(((r.det[i].cost)*(r.det[i].quantity))-(r.det[i].discount));
                    
                    
                    $("#1_"+i).removeAttr("disabled");
                    $("#2_"+i).removeAttr("disabled");
                    $("#3_"+i).removeAttr("disabled");
                    
                    set_cid("1_"+i);
                    //set_sub_total();
                }
                $("#purchase_no").attr("readonly", "readonly");
                input_active();
            }else{
                $("#invoice_no").val("");
                alert("This is deleted record.");
            }
        }else{
            $("#invoice_no").val("");
            alert("No records");
        }
        
    }, "json");
           
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
            $(this).attr("id", "sel");
            $(this).css("background-color", "#dde458");

        }
    });

    $("#tgrid tr").dblclick(function(){
 
            $(this).attr("id", "sel");
            $(this).css("background-color", "#dde458");
            
            var code=$(this).children().eq(0).children().val();
            var item_name=$(this).children().eq(1).children().val();
            var foc=$(this).children().eq(2).children().val();
            var rate=$(this).children().eq(4).children().val();
            var qty=$(this).children().eq(3).children().val();
            var amount=$(this).children().eq(5).children().val();
           
                    $("#d0_" + i).val(code);
                    $("#d1_" + i).val(item_name);
                    $("#d3_" + i).val(qty);
                    $("#d4_" + i).val(rate);
                    $("#d5_" + i).val(amt);
                    
                    i=i+1;
        
            $(this).children().eq(0).children().val('');
            $(this).children().eq(1).children().val('');
            $(this).children().eq(2).children().val('');
            $(this).children().eq(4).children().val('');
            $(this).children().eq(3).children().val('');
            $(this).children().eq(5).children().val('');    
            
            tot=tot+amount;
             
            $("#total2").val(parseFloat(tot));
            

    });

    $("#tgrid1 tr").dblclick(function(){
             
            $(this).css("background-color", "#dde458");
            
               
            var code=$(this).children().eq(0).children().val();
            var item_name=$(this).children().eq(1).children().val();
            var foc=$(this).children().eq(2).children().val();
            var rate=$(this).children().eq(4).children().val();
            var qty=$(this).children().eq(3).children().val();
            var amount=$(this).children().eq(5).children().val();
           
                    $("#c0_"+scid).val(code);
                    $("#c1_"+scid).val(item_name);
                    $("#c3_"+scid).val(qty);
                    $("#c2_"+scid).val(foc);
                    $("#c4_"+scid).val(rate);
                    $("#c5_"+scid).val(amount);
                    
                    i=i+1;
                    
            $(this).children().eq(0).children().val('');
            $(this).children().eq(1).children().val('');
            $(this).children().eq(2).children().val('');
            $(this).children().eq(4).children().val('');
            $(this).children().eq(3).children().val('');
            $(this).children().eq(5).children().val('');   

            tot1=tot1+$(this).children().eq(5).children().val();
             
            $("#total2").val(parseFloat(tot1));
  
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
});

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

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}

function load_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_customer_settlement/", {
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
    var code = $("#customer").val();
    
    if(code != "0"){
        $.post("index.php/main/load_data/t_sales_ret/sales_ret_data", {
            customer : code
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
    if($("#inv_no").val() ==''){
        alert("Please Select Invoice number");
        return false;
    }else if($("#total2").val() =='' || $("#total2").val() =='0'){
        alert("Please select Item for return");
        return false;
    }else{
        return true;
    }
}

function save(){
    
   
    
    $("#form_").submit();
}
