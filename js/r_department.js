$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
       // alert($(this).val());
    });
    
    $("#grid").tableScroll({height:355, width:590});

    $("#privilege_card_rate").keyup(function(){
        this.value=this.value.replace(/[^\d\.]/g,'');
    });

    

	$("#srchee").keyup(function(){  
		$.post("index.php/main/load_data/utility/get_data_table", {
	        code:$("#srchee").val(),
	        tbl:"r_department",
	        tbl_fied_names:"Code-Description-Privi.Rate",
	        fied_names:"code-description-pv_card_rate",
	        col4:"Y"
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
    $.post("index.php/main/load_data/r_department/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_department/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
            }
        }
        loding();
    }, "text");
}

function validate(){
    if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
        set_msg("Please enter code","error");
        $("#code").focus();
        return false;
    }else if($("#code_gen").val()==""){
        set_msg("Please enter code key","error");
        $("#code_gen").focus();
        return false;
    }else if($("#code_gen").val().length<2){
        set_msg("Code key must contains 2 charactors","error");
        $("#code_gen").focus();
        return false;
    }else if($("#code").val().length<4){
        set_msg("Code must contains 4 charactors","error");
        $("#code").focus();
        return false;
    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
        set_msg("Please enter description.","error");
        $("#description").focus();
        return false;
    }else if($("#description").val() === $("#code").val()){
        set_msg("Please enter different values for description & code.","error");
        $("#description").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){

    if(confirm("Are you sure delete "+code+"?")){
       loding();
       $.post("index.php/main/delete/r_department", {
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
    $.post("index.php/main/get_data/r_department", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code_gen").val(res.code_gen);
        $("#code").attr("readonly", true);
        $("#description").val(res.description);
		$("#privilege_card_rate").val(res.pv_card_rate);
        
        //is_edit('018');
        loding(); input_active();
    }, "json");
}