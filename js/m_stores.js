$(document).ready(function(){

    $("#code").blur(function(){
       check_code();
    });

   $("#grid").tableScroll({height:355, width:590});

   
   $("#btnReset").click(function(){
   	location.href="?action=m_stores";
   });


	$("#srchee").keyup(function(){  
		$.post("index.php/main/load_data/utility/get_data_table", {
	        code:$("#srchee").val(),
	        tbl:"m_stores",
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
    $.post("index.php/main/load_data/m_stores/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function check_code(){
    var code = $("#code").val();
    var pre_code = $("#pre_code").val();
    $.post("index.php/main/load_data/m_stores/check_code", {
        code : code,
        pre_code: pre_code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+pre_code+code+") already added. \n\n Do you need edit it?")){
                set_edit(pre_code+code);
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
        $.post("index.php/main/delete/m_stores", {
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
    $.post("index.php/main/get_data/m_stores", {
        code :code
    }, function(res){
		   loding();
        var code = res.code;
        var pre_code = code.substring(0,4);
        var post_code =code.substring(4,10); 
           
        $("#code_").val(res.code);
        $("#code").val(post_code);
        $("#pre_code").val(pre_code);
        $("#description").val(res.description);

		if(res.sales==1){
			$("#sales").attr("checked", "checked");
			}else{
			$("#sales").removeAttr("checked");
		}
		if(res.purchase==1){
			$("#purchase").attr("checked", "checked");
			}else{
			$("#purchase").removeAttr("checked");
		}

        if(res.group_sale==1){
            $("#group_sale").attr("checked", "checked");
            }else{
            $("#group_sale").removeAttr("checked");
        }

        if(res.transfer_location==1){
            $("#transfer_location").attr("checked", "checked");
            }else{
            $("#transfer_location").removeAttr("checked");
        }
  
	   input_active();
    
    }, "json");
}