var storse = 0;
var is_edit=0;
$(document).ready(function(){
    
    $("#stores").val(storse);
    set_select('stores', 'sto_des');
    
    load_items();
    
    $("#tgrid").tableScroll({height:280});
    
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
    
    $("#btnSave5").click(function(){
        
        if(validate())
         {
             check_is_used_batch($("#hid").val());
         }
        
    });
    
    function check_is_used_batch(id)
    {
       $.post("index.php/main/load_data/t_purchase/check_is_used_batch",{
        id :id
        },function(r)
        {
        if(r>0)
        {
            alert("You cannot edit this record.Batch already use for the Sales process");
            $("#btnSave5").attr("disabled",true);
        }
        else
        {
			check_permission();
			
             
        } 
            
    },"text");

    }
    
	
       function check_permission()
        {
            $.post("index.php/main/load_data/user_permissions/get_permission",{

                module_id : '020',
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
              
              check_is_purchase_return_save($("#id").val());
            }    
        });

        }

    
	
	
    $("#pop_search").gselect();
    
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

           check_is_purchase_return($(this).val()) 

           //load_data($(this).val());
        }
    });
    
    function check_is_purchase_return(id){
   
    $.post("index.php/main/get_subitem_data/t_purchase_return/", {
        id : id
    }, function(r){

           if(r.a>0)
            {
               
                load_data(id)
                alert('This is a returned purchase');
                $("#btnDelete5").attr("disabled", "disabled");
                $("#btnSave5,#btnPrint").attr("disabled", "disabled");
                
            }
            else
            {
                 load_data(id)
            }    

    }, "json");
} 

    function check_is_purchase_return_save(id){
   
    $.post("index.php/main/get_subitem_data/t_purchase_return/", {
        id : id
    }, function(r){

           if(r.a>0)
            {
                alert('This is a  purchase return');

                
            }
            else
            {
                 //save();
                 check_is_payment_voucher();
            }    

    }, "json");
} 
    
    function check_is_payment_voucher()
    {
        $.post("index.php/main/load_data/t_purchase/check_is_payment_voucher", {
        id : $("#id").val(),
        sup :$("#supplier").val()
        
        }, function(r){

       if(r>0)
        {
            alert('Payment already settle by the supplier.You cannot edit this record '+'Please check voucher no: '+r);


        }
        else
        {
           save();

        }    

}, "text"); 
    }
    
    function check_is_payment_voucher_del()
    {
        $.post("index.php/main/load_data/t_purchase/check_is_payment_voucher", {
        id : $("#id").val(),
        sup :$("#supplier").val()
        
        }, function(r){

       if(r>0)
        {
            alert('Payment already settle by the supplier.You cannot edit this record '+'Please check voucher no: '+r);


        }
        else
        {
           set_delete();

        }    

}, "text"); 
    }
    
    
    
    $("#pono").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            
            check_purchase_return($(this).val());            
            //load_order_data($(this).val());
        }
    });
    
   function check_purchase_return(id){
   
    $.post("index.php/main/get_subitem_data/t_purchase/", {
        id : id
    }, function(r){

           if(r.a>0)
            {
                load_purchase_data(id)
            }
            else
            {
                load_order_data(id)
                alert('This is a complited purchase');
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
            }    

    }, "json");
} 

   $("#btnDelete5").click(function(){
       
       check_is_used_batch2($("#hid").val());
       
    });
    
    function check_is_used_batch2(id)
    {
       $.post("index.php/main/load_data/t_purchase/check_is_used_batch",{
        id :id
        },function(r)
        {
        if(r>0)
        {
            alert("You cannot delete this record.Batch already use for the Sales process");
            $("#btnDelete5").attr("disabled",true);
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
        module_id : '020'
    },function(r){
    
    if(r>0)
    {
        
        check_is_payment_voucher_del();
 
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
            window.open('index.php/prints/trance_forms/t_purchase/?id='+id);
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
        load_items();
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

function load_purchase_data(id){
    empty_grid();
    $.post("index.php/main/get_return_data/t_purchase_order/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#supplier").val(r.sum.supplier);
            $("#ssupplier").val(r.sum.supplier);
            $('#sup_des').val(r.sum.name+" ("+r.sum.full_name+")");
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#pono").attr("readonly", "readonly");
            
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
            
           // $("#hid").val(r.sum.id);
            input_active();
        }else{
            alert("No records");
        }
    }, "json");
}

function load_order_data(id){
    empty_grid();
    $.post("index.php/main/get_data/t_purchase_order/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#supplier").val(r.sum.supplier);
            $("#ssupplier").val(r.sum.supplier);
            $('#sup_des').val(r.sum.name+" ("+r.sum.full_name+")");
            $("#memo").val(r.sum.memo);
            $("#ref_no").val(r.sum.ref_no);
            $("#pono").attr("readonly", "readonly");
            
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
            is_edit=1;
            $("#hid").val(r.sum.id);
            input_active();
        }else{
            alert("No records");
        }
    }, "json");
}

/*function set_discount(m){
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
}*/

function set_discount(m){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z = x*y;

    if(m == "pre"){
   
	var d = parseFloat($("#4_"+scid).val()); if(isNaN(d)){ d = 0; }
	d = z*(d/100);
	$("#3_"+scid).val(m_round(d));
        
         if($($("#4_"+scid)).val()=='0' || $("#4_"+scid).val()=='')
        {
        $("#3_"+scid).removeAttr("disabled","disabled");    
        }   
        else
        {    
        $("#3_"+scid).attr("disabled","disabled");
        }
 
    }
    if(m == "dis"){
 
	var d = parseFloat($("#3_"+scid).val()); if(isNaN(d)){ d = 0; }
	d = (d/z)*100;
	$("#4_"+scid).val(m_round(d));
        
        if($("#3_"+scid).val()=='0' || $("#3_"+scid).val()=='')
        {
        $("#4_"+scid).removeAttr("disabled","disabled");    
        }   
        else
        {    
        $("#4_"+scid).attr("disabled","disabled");
        }
        
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
            $.post("index.php/main/delete/t_purchase", {
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
	is_edit=0;
}

function set_sub_total(){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var d = parseFloat($("#3_"+scid).val());
    if(isNaN(d)){ d = 0; }
    var z;
    if(! isNaN(x) && ! isNaN(y)){
        z = x*y;
        $("#t_"+scid).html(m_round(z)- d);
    }else{
        $("#t_"+scid).html("0.00");
    }
    
    set_total();
}

function load_data(id){
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
            $("#pono").val(r.sum.po_no);
            $("#pono").attr("readonly", "readonly");
            
            set_select('stores', 'sto_des');
            $("#invoice_no").val(r.sum.invoice_no);
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
                $("#4_"+i).val(r.det[i].discount_pre);
		$("#5_"+i).val(r.det[i].foc);
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
        date   : $("#date").val()
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


function load_subitem(id,scid){
   
    $.post("index.php/main/get_subitem_data/t_sales/", {
        id : id
    }, function(r){
          load_items();
            
            for(var i=0; i<r.det.length; i++){
                scid=parseInt(scid)+1;   
                $("#h_"+scid).val(r.det[i].sub_item_code);
                $("#0_"+scid).val(r.det[i].sub_item_code);
                $("#n_"+scid).val(r.det[i].description);
                $("#5_"+scid).val(r.det[i].foc);
                $("#2_"+scid).val(r.det[i].cost_price);
                $("#1_"+scid).val("0.00");
                $("#2_"+scid).val("0.00");
                $("#3_"+scid).val("0.00");

           }

    }, "json");
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
    else if ($("#net_amount").val()==0 || $("#net_amount").val()==" ")
    {
        alert("Please enter minimum one item");
        v = false;
    }
    return v;
}

function save(){
    $(".dis,.dis_pre").removeAttr("disabled","disabled");
    $("#form_").submit();
}