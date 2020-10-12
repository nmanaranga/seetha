$(document).ready(function(){

    $("#code").blur(function(){
       check_code();
    });

   $("#grid").tableScroll({height:355, width:590});

   
   $("#btnReset").click(function(){
   	location.href="?action=m_employee_category";
   });


	$("#srchee").keyup(function(){  
		$.post("index.php/main/load_data/utility/get_data_table", {
	        code:$("#srchee").val(),
	        tbl:"m_employee_category",
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
    $.post("index.php/main/load_data/m_employee_category/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_employee_category/check_code", {
        code : code,
        //pre_code: pre_code
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
    // if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
    //     set_msg("Please enter code.","error");
    //     $("#code").focus();
    //     return false;
    // }else 
    if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
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
        $.post("index.php/main/delete/m_employee_category", {
            code : code
        }, function(res){
            loding();
            if(res == 1){
                delete_msg();
            }else{
                set_msg(res,"error");
            }
           
        }, "text");
   }
}
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/m_employee_category", {
        code :code
    }, function(res){
		   loding();
        var code = res.code;
        //var pre_code = code.substring(0,4);
       // var post_code =code.substring(4,10); 
           
        $("#code_").val(res.code);
        $("#code").val(res.code);
       // $("#pre_code").val(pre_code);
        $("#description").val(res.description);

		
	   //input_active();
    
    }, "json");
}