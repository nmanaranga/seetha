$(document).ready(function(){
    $(".g_input_txt").autocomplete('index.php/main/load_data/m_defult_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatCustomer,
        formatResult: formatCustomerResult
    });
    
    $(".g_input_txt").blur(function(){
	set_cus_values($(this));
    });
    
    $(".g_input_txt").keydown(function(e){
	if(e.keyCode == 13){
	    set_cus_values($(this));
	}
    });
    
    $("#bank_account").change(function(){
	set_select("bank_account", "sto_des");
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
            $.post("index.php/main/delete/t_cheque_book", {
                id : id
            }, function(r){
                if(r != 1){
                    alert("Cannot Delete record.");
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
    $.post("index.php/main/get_data/t_cheque_book/", {
        id : id
    }, function(r){
        if(r.sum.id != undefined){
            $("#date").val(r.sum.date);
            $("#bank_account").val(r.sum.bank_account);
            set_select('bank_account', 'sto_des');
            $("#pages").val(r.sum.pages);
            $("#start_no").val(r.sum.start_no);
            
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

function set_cus_values(f){
    var v = f.val();
    v = v.split("~");
    if(v.length == 2){
	f.val(v[0]);
        $("#h_"+f.attr("id")).val(v[0]);
        f.parent().parent().children().eq(2).html(v[1]);
    }
}

function validate(){
    if(isNaN(parseInt($("#pages").val()))){
	alert("Please use numbers for page count.");
	$("#pages").focus();
	
	return false;
    }else if(isNaN(parseInt($("#start_no").val()))){
	alert("Please use numbers for page count.");
	$("#start_no").focus();
	
	return false;
    }else if($("#bank_account option:selected").val() == 0){
	alert("Please select bank account.");
	
	return false;
    }else{
	return true;
    }
}

function save(){
    $("#_form").submit();
}

function formatCustomer(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatCustomerResult(row){
    return row[0]+"~"+row[1];
}