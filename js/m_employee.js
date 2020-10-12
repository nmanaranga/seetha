

$(document).ready(function(){

	$("#btnReset").click(function(){
		location.href="index.php?action=m_employee";
	});

    $("#code").blur(function(){
        check_code();
    });
     
    $("#grid").tableScroll({height:355});

    $("#designation").change(function(){
        set_select('designation','designation_id');
    });

    $("#srchee").keyup(function(){  
	 $.post("index.php/main/load_data/utility/get_data_table", {
	                code:$("#srchee").val(),
	                tbl:"m_employee",
	                tbl_fied_names:"Code-Name",
	        		fied_names:"code-name"
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
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
                loding();
                alert("Error : \n"+pid);
            }
            
        }
    });
}

function get_data_table(){
    $.post("index.php/main/load_data/m_employee/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_employee/check_code", {
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
    }else if($("#name").val() === $("#name").attr('title') || $("#name").val() == ""){
        alert("Please enter name.");
        $("#name").focus();
        return false;
    }else if($("#designation").val() == "0"){
        alert("Please select a designation");
        $("#designation").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_employee", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                loding();
                alert("Item deleting fail.");
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
    $.post("index.php/main/get_data/m_employee", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
	    $("#code").attr("readonly", true);
        $("#description").val(res.description);
        $("#name").val(res.name);
        $("#address1").val(res.address1);
        $("#address2").val(res.address2);
        $("#address3").val(res.address3);
        $("#tp1").val(res.tp1);
        $("#tp2").val(res.tp2);
        $("#tp3").val(res.tp3);
        $("#doj").val(res.doj);
        $("#designation").val(res.designation);
        set_select('designation','designation_id');

        if(res.inactive==1){
            $("#inactive").attr("checked", "checked");
            }else{
            $("#inactive").removeAttr("checked");
        }


  
        
        
       // is_edit('010');
        loding(); input_active();
    }, "json");
}