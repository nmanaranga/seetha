$(document).ready(function(){
    
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
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    
    $("#btnDelete").click(function(){
        set_delete();
    });
});

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure ? ")){
            $.post("index.php/main/delete/t_supp_debit_credit", {
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
    $.post("index.php/main/get_data/t_supp_debit_credit/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#supplier").val(r.sum.supplier);
            $("#ssupplier").val(r.sum.supplier);
            $("#sup_des").val(r.sum.outlet_name+" ("+r.sum.name+")");
            $("#amount").val(r.sum.amount);
            
            $("#hid").val(r.sum.id);
            
            if(r.sum.type == 1){
                $("input[type='radio']")[0].checked = true;
            }else{
                $("input[type='radio']")[1].checked = true;
            }
            
            if(r.sum.is_cancel > 0){
                alert("This record canceled.");
                
                $("#btnDelete").attr("disabled", "disabled");
                $("#btnSave").attr("disabled", "disabled");
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


function validate(){
    if($("#supplier").val() == "0"){
        alert("Please select supplier");
        $("#ssupplier").focus();
        return false;
    }else if($("#amount").val() == $("#amount").attr("title")){
        alert("Please make amount");
        $("#amount").focus();
        return false;
    }else{
        return true;
    }
}

function save(){
    $("#_form").submit();
}
