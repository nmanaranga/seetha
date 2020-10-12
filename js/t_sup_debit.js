$(document).ready(function(){
    $("#scustomers").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
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
    
       load_credit_account();
    
    function load_credit_account()
    {
        $.post("index.php/main/load_data/t_sup_credit/credit_account",{
            
            acc : 'creditor_control'
            
       },function(r)
       {
           if(r!='0')
           {    
           $("#dr_account").val(r);
           $("#dr_account").css("color","black");
           $("#dr_account").css("font-weight","Bold");
           get_description(r);
           }
           else
           {
               alert('Please set Creditor account before enter Credit note');
           }    
       });
        
    }
    
     
 function get_description(code)
 {
       $.post("index.php/main/load_data/t_sup_credit/get_description",{

       code :code
            
       },function(r)
       {
           $("#dr_account_des").val(r);
           $("#dr_account_des").css("color","black");
           $("#dr_account_des").css("font-weight","Bold");

       });
     
     
     
 }
    
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    
    $("#btnDelete").click(function(){
        set_delete();
    });
    
    $(".acount").autocomplete('index.php/main/load_data/m_defult_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatCustomer,
        formatResult: formatCustomerResult
    });
    
    $(".acount").blur(function(){
	set_cus_values2($(this));
    });
    
    $(".acount").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values2($(this));
	}
    });
});

function set_cus_values2(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]); 
	if(f.attr('id') == 'cr_account'){
	    $("#hcr").val(v[0]);
	    $("#cr_account_des").val(v[1]);
	    $("#cr_account_des").attr("class", "input_txt_f");
	}else if(f.attr('id') == 'dr_account'){
	    $("#hdr").val(v[0]);
	    $("#dr_account_des").val(v[1]);
	    $("#dr_account_des").attr("class", "input_txt_f");
	}
	
    }
}

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_sup_debit", {
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

function load_data(id){
    $.post("index.php/main/get_data/t_sup_debit/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#customer").val(r.sum.supplier);
            $("#scustomers").val(r.sum.supplier);
            $("#cus_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            $("#amount").val(r.sum.amount);
            $("#description").val(r.sum.description);
            
	    $("#cr_account").val(r.sum.cr_account);
	    $("#cr_account_des").val(r.sum.cr_account_des);
	    $("#hcr").val(r.sum.cr_account);
	    $("#dr_account").val(r.sum.dr_account);
	    $("#dr_account_des").val(r.sum.dr_account_des);
	    $("#hdr").val(r.sum.cr_account);
	    
            $("#hid").val(r.sum.id);
            
               if(r.sum.is_cancel > 0){
                alert("This record canceled.");
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#mframe").css("background-repeat", "repeat-x");
                $("#mframe").css("background-position", "center");
            }
            input_active();
        }else{
            alert("No records");
        }
    }, "json");
}

function set_cus_values(f){
    
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
	$("#customer").val(v[0]);
	$("#cus_des").val(v[1]);
	$("#cus_des").attr("class", "input_txt_f");
    }
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}

function validate(){
    
    var v = false;
    
    if($("#customer").val() == "0"){
        alert("Please select Supplier");
        $("#scustomers").focus();
        return false;
    }else if($("#amount").val() == $("#amount").attr("title") || $("#amount").val()==''|| $("#amount").val()=='0'){
        alert("Please make amount");
        $("#amount").focus();
         v = false;
    }else if($("#cr_account").val() == $("#cr_account").attr("title") || $("#cr_account").val()==""){
        alert("Please Select Credit account");
        $("#cr_account").focus();
         v = false;
    }
    else if($("#dr_account").val() == $("#dr_account").attr("title") || $("#dr_account").val() ==""){
        alert("Please Select Debit account");
        $("#dr_account").focus();
         v = false;
    }
    else
    {
         v = true;
    }     
    return v;
}

function save(){
    $("#_form").submit();
}
