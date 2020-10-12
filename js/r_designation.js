var is_edit=0;
$(document).ready(function(){
    $("#code").blur(function(){
	
        check_code();
    });
    
    $("#btnSave1").click(function(){
    if(validate()){
        check_permission();
    }    
    });
    
    
    $("#grid").tableScroll({height:355});
    
    $("#sub_region").change(function(){
        set_select('sub_region', 'sr_des');
    });
    
    $("#sales_ref").change(function(){
        set_select('sales_ref', 'sre_des');
    });

    $("#srchee").keyup(function(){  
	 $.post("index.php/main/load_data/utility/get_data_table", {
	                code:$("#srchee").val(),
	                tbl:"r_designation",
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
                location.href="";
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


function check_permission()
{
    $.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '004',
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

function check_delete_permission(code)
{
    $.post("index.php/main/load_data/user_permissions/get_delete_permission",{
        module_id : '004'
    },function(r){
    
    if(r>0)
    {
       if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_designation", {
            code : code
        }, function(res){
            if(res == 1){
                location.reload();
            }else{
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
    }
    }    
    else
    {
         alert("You have no permission to delete this record");
    }    
    
    });  
}

function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_designation/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
            }
        }
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
        }else{
            return true;
        }
}
    
function set_delete(code){
     check_delete_permission(code); 
}


function get_data_table(){
    $.post("index.php/main/load_data/r_designation/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/r_designation", {
        code : code
    }, function(res){
		
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#description").val(res.description);
        
        loding(); 
	   input_active();
      is_edit=1;
    }, "json");
}