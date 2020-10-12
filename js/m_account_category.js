$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    $("#grid").tableScroll({height:355,width:700});

    $("#srchee").keyup(function(){  
    $.post("index.php/main/load_data/utility/get_data_table", {
                    code:$("#srchee").val(),
                    tbl:"m_account_category",
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
            }else if(pid == 2){
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                set_msg("Error : \n"+pid);
            }
            loding();
        }
    });
}

function get_data_table(){
    $.post("index.php/main/load_data/m_account_category/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function set_delete(code){
  
            if(confirm("Are you sure delete "+code+"?")){
               loding();
               $.post("index.php/main/delete/m_account_category", {
                   code : code
               }, function(res){
                   if(res == 1){
                      location.href="";
                   }else{
                       set_msg("Item deleting fail.");
                   }
                   
                   
                   loding();
               }, "text");
           }

}


function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_account_category/check_code", {
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
            set_msg("Please enter code.");
            $("#code").focus();
            return false;
        }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
            set_msg("Please enter description.");
            $("#description").focus();
            return false;
       
        }else{
            return true;
        }
}
        
function set_edit(code){
   loding();
    $.post("index.php/main/get_data/m_account_category", {
        code : code
    }, function(res){
        
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#description").val(res.description);
        
      loding(); 
      input_active();
    }, "json");
}s