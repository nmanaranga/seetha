$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
     
    $("#grid").tableScroll({height:355});
});

function save(){
    var frm = $('#form_');
    loding();
    $.ajax({
	type: frm.attr('method'),
	url: frm.attr('action'),
	data: frm.serialize(),
	success: function (pid){
            if(pid == 1){
                input_reset();
                get_data_table();
            }else if(pid == 2){
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
                alert("Error : \n"+pid);
            }
            loding();
        }
    });
}

function get_data_table(){
    $.post("/index.php/main/load_data/t_authorization_update/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/t_authorization_update/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
		$("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
        alert("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
        alert("Please enter description.");
        $("#description").focus();
        return false;
    }else if($("#description").val() === $("#code").val()){
        alert("Please enter deferent values for description & code.");
        $("#des").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/t_authorization_update", {
            code : code
        }, function(res){
            if(res == 1){
                get_data_table();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
    }
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
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/t_authorization_update", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
	$("#code").attr("readonly", true);
        $("#description").val(res.description);
        
           if(res.is_vehical == 1){
            $("#is_vehical").attr("checked", "checked");
        }else{
            $("#is_vehical").removeAttr("checked");
        }
        
        
        
       // is_edit('010');
        loding(); input_active();
    }, "json");
}