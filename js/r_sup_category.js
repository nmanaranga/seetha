$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
     
    $("#grid").tableScroll({height:355});

    $("#srchee").keyup(function(){  
	$.post("index.php/main/load_data/utility/get_data_table", {
	        code:$("#srchee").val(),
	        tbl:"r_sup_category",
	        tbl_fied_names:"Code-Description",
	        fied_names:"code-description"
	        }, function(r){
	        $("#grid_body").html(r);
	    }, "text");
	});


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
                loding();
                sucess_msg();
            }else if(pid == 2){
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                set_msg("Error : \n"+pid);
            }
            
        }
    });
}

function get_data_table(){
    $.post("index.php/main/load_data/r_sup_category/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_sup_category/check_code", {
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
        set_msg("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
        set_msg("Please enter description.");
        $("#description").focus();
        return false;
    }else if($("#description").val() === $("#code").val()){
        set_msg("Please enter deferent values for description & code.");
        $("#description").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_sup_category", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else{
                set_msg(res);
            }
            
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
    $.post("index.php/main/get_data/r_sup_category", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
	$("#code").attr("readonly", true);
        $("#description").val(res.description);
        
         
        
        
        
       // is_edit('010');
        loding(); input_active();
    }, "json");
}