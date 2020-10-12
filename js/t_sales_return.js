var is_edit=0;
var sroot = sarea = sales_ref = storse = i= 0;
$(document).ready(function(){
    
    $("#sales_ref").val(sales_ref); $("#stores").val(storse);
    set_select('sales_ref', 'ref_des'); set_select('stores', 'sto_des');
    
    //load_items();
	$("#btnSave").click(function(){
        if(validate())
        {    
           
            check_permission();
            
        }
    });
    
    $("#tgrid,#tgrid1").tableScroll({height:250});
        
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
        //set_delete(); 
		check_delete_permission();  
    });
	
	function check_delete_permission()
    {
    $.post("index.php/main/load_data/user_permissions/get_delete_permission",{
        module_id : '027'
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
	
	
    
	function check_permission()
{
    $.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '027',
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
	
    $("#btnDelete, #btnSave, #btnReset").removeAttr("disabled");
    
    $("#btnPrint").click(function(){
        var id = $("#hid").val()
        if(id != 0){
            window.open('index.php/prints/trance_forms/t_sales_return/?id='+id);
        }else{
            alert("Please load record. Or save first.");
        }
    });
    
    $("#sales_ref").change(function(){
        set_select('sales_ref', 'ref_des');
    });
    
    $("#discount").keyup(function(){
        set_total();
    });
    
    $("#stores").change(function(){
        set_select('stores', 'sto_des');
        //load_items();
    });
    
    $("#invoice_no").change(function(e){

            var sale=$("#sale").val();
            
            if(sale=='0')
                {
                    load_cashsales_return($("#invoice_no option:selected").val());
                }
            else
                {
                    load_creditsales_return($("#invoice_no option:selected").val());
                }
         
	});

    
    enter_setup_trance();
   
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
                $("#h_"+i).val(r.det[i].item_code);
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

                
                for(var i=0; i<r.det.length; i++){
                    $("#h_"+i).val(r.det[i].item_code);
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
    
    
    $("#scustomers").autocomplete('index.php/main/load_data/m_customer/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatCustomer,
        formatResult: formatCustomerResult
    });

    $("#scustomers").keydown(function(e){
	if(e.keyCode == 13){
	    
	    var a=$(this).val();
	    var b=a.split("~");
	    set_cus_values($(this));
	    load_invoice(b[0]);
	    
	}
    });
    
    $("#sale").change(function(){
        
     var cus=$("#customer").val()   
     load_invoice(cus);   
    });
    
    
    $("#tgrid tr").dblclick(function() {
        
         var t = tt = ct =ctt=0;
        
	$(this).attr("id", "sel");
        $(this).css("background-color", "#dde458");
        
        
        var code = $(this).children().eq(0).children().val();
        var item_name = $(this).children().eq(1).children().val();
        var rate = $(this).children().eq(3).children().val();
        var qty = $(this).children().eq(2).children().val();
        var amount = $(this).children().eq(4).children().val();
        var amt= parseFloat(amount);
        
              
        
        $("#h2_" + i).val(code);
        $("#d0_" + i).val(code);
        $("#d1_" + i).val(item_name);
        $("#d3_" + i).val(qty);
        $("#d4_" + i).val(rate);
        $("#d5_" + i).val(amt);

        i = i + 1;

        $(".tf").each(function(){
        set_cid($(this).attr("id"));
        ctt = parseFloat($("#d5_"+scid).val());
        if(isNaN(ctt)){ ctt = 0;}
        t += ctt;
        });

        $("#total2").val(m_round(t));


        $(this).children().eq(0).children().val('');
        $(this).children().eq(1).children().val('');
        $(this).children().eq(2).children().val('');
        $(this).children().eq(4).children().val('');
        $(this).children().eq(3).children().val('');
        $(this).children().eq(5).children().val('');

       
    });

    $("#tgrid1 tr").dblclick(function(){
           
       var t = tt = ct =0; 
        
      $(this).css("background-color", "#dde458");
            
               
        var code = $(this).children().eq(0).children().val();
        var item_name = $(this).children().eq(1).children().val();
        var rate = $(this).children().eq(3).children().val();
        var qty = $(this).children().eq(2).children().val();
        var amount = $(this).children().eq(4).children().val();
        var amt= parseFloat(amount);
           
        $("#h2_" + i).val(code);
        $("#c0_" + i).val(code);
        $("#c1_" + i).val(item_name);
        $("#c3_" + i).val(qty);
        $("#c4_" + i).val(rate);
        $("#c5_" + i).val(amt);
                    
        i = i + 1;

        $(this).children().eq(0).children().val('');
        $(this).children().eq(1).children().val('');
        $(this).children().eq(2).children().val('');
        $(this).children().eq(4).children().val('');
        $(this).children().eq(3).children().val('');
        $(this).children().eq(5).children().val('');  
  
        $(".tf").each(function(){
        set_cid($(this).attr("id"));
        ct = parseFloat($("#d5_"+scid).val());
        if(isNaN(ct)){ ct = 0;}
        tt += ct;
        });

        
        $("#total2").val(m_round(tt));

    });

$("#tgrid tr").click(function() {
	
	
        if ($(this).children().eq(4).children().val() == "") {
            alert("Please Select row with value");
        } else {
            $("#tgrid tr").css("background-color", "transparent");
            $(this).attr("id", "sel");
            $(this).css("background-color", "#dde458");

        }
    });

    
});


function load_invoice($id){
    
    $.post("index.php/main/load_data/t_sales_return/select", {
	    cus:$id,
            sales : $("#sale option:selected").val()
            
        }, function(res){
            $("#invoice_no").html(res);
            }, "text");
}

function set_cus_values(f){
    
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
	$("#customer").val(v[0]);
	$("#cus_des").val(v[1]);
	$("#cus_des").attr("class", "input_txt_f");
        //load_balance();
    }
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}



function load_parent_record(id){
    empty_grid();
    $.post("index.php/main/get_return_data/t_sales/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            if(r.sum.is_cancel == 0){
                $("#customer").val(r.sum.customer);
                $("#scustomers").val(r.sum.customer);
                $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
                set_select('customer', 'sup_des');
                $("#discount").val(r.sum.discount);
                $("#id").attr("readonly", "readonly");
                $("#sales_ref").val(r.sum.sales_ref);
                set_select('sales_ref', 'ref_des');
                $("#stores").val(r.sum.stores);
                set_select('stores', 'sto_des');
                $("#invoice_no").attr("readonly", "readonly");
                
		if(r.det==''){alert('All the items has returned');
		empty_grid();
		}
                for(var i=0; i<r.det.length; i++){
                    $("#h_"+i).val(r.det[i].item_code);
                    $("#c0_"+i).val(r.det[i].item_code);
                    $("#c1_"+i).val(r.det[i].description);
                    
                    if(r.det[i].is_measure == 1){
                        $("#c3_"+i).val(r.det[i].totqty);
                        $("#c3_"+i).autoNumeric({mDec:2});
                    }else{
                        $("#c3_"+i).val(parseInt(r.det[i].totqty));
                        $("#c3_"+i).autoNumeric({mDec:0});
                    }
                    
		    var c=parseFloat((r.det[i].totqty)- (r.det[i].discount));
                    $("#c4_"+i).val(r.det[i].cost);
                    $("#c5_"+i).val(((r.det[i].cost) * c));
                    $("#c3_"+i).removeAttr("disabled");
                    $("#c4_"+i).removeAttr("disabled");
                    $("#c5_"+i).removeAttr("disabled");
                    
                    set_cid("c3_"+i);
                    set_sub_total();
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

$(document).keydown(function(e){
    if(e.keyCode == 112){
        $("#0_0").focus();
    }
});

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_sales_return", {
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
        $("#c0_"+i).val("");
        $("#c1_"+i).val("");
        $("#t_"+i).html("&nbsp;");
        $("#c3_"+i).val("");
        $("#c4_"+i).val("");
        $("#c5_"+i).val("");
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
    
    $("#d3_"+ i).attr("readonly","readonly");
    empty_grid();
    $.post("index.php/main/get_data/t_sales_return/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.customer);
            $("#scustomers").val(r.sum.customer);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            //set_select('customer', 'sup_des');
            $("#sales_ref").val(r.sum.sales_ref);
            set_select('sales_ref', 'ref_des');
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#discount").val(r.sum.discount);
            $("#id").attr("readonly", "readonly");
          
           $("#invoice_no").html(r.inv);
            $("#invoice_no").val(r.sum.invoice_no);
           
            
            //$("#invoice_no").val(r.sum.invoice_no);
            $("#crn_no").val(r.sum.crn_no);
            $("#stores").val(r.sum.stores);
            set_select('stores', 'sto_des');
   
            if((r.sum.trance_type)>0)
            {
                $("#sale").val('1');
            }
            else
            {
                $("#sale").val('0');
            }    
            
            $("#sale,#sales_ref,#stores,#invoice_no").attr("disabled","disabled");
            
	    var amt=tot=0;
	    for (var i = 0; i < r.det.length; i++) {
                
                $("#d0_"+ i).val(r.det[i].item_code);
                $("#d1_"+ i).val(r.det[i].description);
                $("#d3_"+ i).val(r.det[i].quantity);
                $("#d4_"+ i).val(r.det[i].cost);
                
          
	   
           var q=r.det[i].quantity;
           var c=r.det[i].cost;

	   var amt=(parseFloat(q)*parseFloat(c));
           $("#d5_" + i).val(parseFloat(m_round(amt)));
              
	     
	   tot=tot+amt;


                $("#d0_" + i).parent().parent().css("background-color", "#dde458");
            }
	    
          $("#total2").val(m_round(tot));
	    
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
            
             // is_edit('027');
            
            $("#hid").val(r.sum.id);
            input_active();
			is_edit=1;
        }else{
            alert("No records");
        }
    }, "json");
}

function is_edit($mod)
{
    $.post("index.php/main/is_edit/user_permissions/is_edit", {
        module : $mod
        
    }, function(r){
       if(r==1)
           {
             $("#btnSave").removeAttr("disabled", "disabled");
           }
       else{
             $("#btnSave").attr("disabled", "disabled");
       }
       
    }, "json");

}

function select_search(){
    $("#pop_search").focus();
    $("#pop_search").val("");
}

function settings(){
    $("#item_list tr").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#2_"+scid).val($(this).children().eq(3).html());
                
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
            set_total();
            $("#pop_close").click();
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
    if($("#total2").val()=='0' || $("#total2").val()==''){
        alert("Please Select item");
         v = false;
        }
    else if($("#stores option:selected").val() == 0){
        alert("Please select stores");
        v = false;
    }
    return v;
}

function save(){
    $("#sale,#sales_ref,#stores,#invoice_no").removeAttr("disabled","disabled");
    
    $("#form_").submit();
	var is_edit=0;
}