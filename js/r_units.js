
$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
   $("#grid").tableScroll({height:355, width:590});


   $("#srchee").keyup(function(){  
 		$.post("index.php/main/load_data/utility/get_data_table", {
                code:$("#srchee").val(),
                tbl:"r_unit",
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
                set_msg("No permission to add data.","error");
            }else if(pid == 3){
                set_msg("No permission to edit data.","error");
            }else{
                set_msg(pid,"error");
            }

        }
    });
}


function get_data_table(){
    $.post("index.php/main/load_data/r_units/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}




function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_units/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
                $("#code").attr("readonly",false);
                input_reset();
            }
        }
    }, "text");
}

function validate(){
    if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
            set_msg("Please enter code.","error");
            $("#code").focus();
            return false;
        }else if($("#code").val().length<4){
            set_msg("Code must contains 4 charactors","error");
            $("#code").focus();
            return false;
        }else if($("#code_gen").val()==""){
            set_msg("Please enter key code","error");
            $("#code_gen").focus();
            return false;
        }else if($("#code_gen").val().length<2){
            set_msg("Code must contains 2 charactors","error");
            $("#code_gen").focus();
            return false;
        }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
            set_msg("Please enter description.","error");
            $("#des").focus();
            return false;
        }else if($("#des").val() === $("#code").val()){
            set_msg("Please enter deferent values for description & code.","error");
            $("#des").focus();
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_units", {
            code : code
        }, function(res){
            if(res == 1){
                 loding();
                 delete_msg();
            }else{
                set_msg(res,"error");
            }           
        }, "text");
    }else{
        $("#code").val("");
    }
}
    
function set_edit(code){
   loding();
    $.post("index.php/main/get_data/r_units", {
        code : code
    }, function(res){
        
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code_gen").val(res.code_gen);
        $("#description").val(res.description);
        $("#code").attr("readonly","readonly");
       loding(); 
       input_active();
        
    }, "json");
}