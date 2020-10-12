
$(document).ready(function(){
    $("#code").blur(function(){
       check_code();
    });
  $("#grid").tableScroll({height:355, width:590});


	$("#srchee").keyup(function(){  
	 $.post("index.php/main/load_data/utility/get_data_table", {
	                code:$("#srchee").val(),
	                tbl:"r_sub_item",
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
                loding();          	
                set_msg(pid,"error");
            }
    
        }
    });
}

function get_data_table(){
	$.post("index.php/main/load_data/r_subitem/get_data_table", {
	    
	}, function(r){
	    $("#grid_body").html(r);
	}, "text");
}




function check_code(){
var code = $("#code").val();
$.post("index.php/main/load_data/r_subitem/check_code", {
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
	if($("#code").val() ==""){
			set_msg("Please enter code.");
			$("#code").focus();
			return false;
		}else if($("#code").val().length<4){
	        set_msg("Code must contains 4 charactors");
	        $("#code").focus();
	        return false;
	    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
			set_msg("Please enter description.");
			$("#des").focus();
			return false;
		}else if($("#des").val() === $("#code").val()){
			set_msg("Please enter different values for description & code.");
			$("#des").focus();
			return false;
		}else if($("#qty").val() == "" || $("#qty").val() == 0){
			set_msg("Please enter valid quantity.");
			$("#qty").focus();
			return false;
		}else{
			return true;
		}
	
}

function set_delete(code){
 if(confirm("Are you sure delete "+code+"?")){
	loding();
	$.post("index.php/main/delete/r_subitem", {
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

function set_edit(code){
	loding();
	$.post("index.php/main/get_data/r_subitem", {
		code : code
	}, function(res){
		$("#code_").val(res.code);
		$("#code").val(res.code);
		$("#description").val(res.description);
		$("#qty").val(res.qty);
		$("#max_no").val(res.max_no);
		loding(); 
		input_active();
		is_edit=1;
	}, "json");
}