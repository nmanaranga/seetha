$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
    $("#grid").tableScroll({height:355, width:590});


    $("#srchee").keyup(function(){  
      $.post("index.php/main/load_data/utility/get_data_table", {
        code:$("#srchee").val(),
        tbl:"r_sales_return_reason",
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
                input_reset();
                get_data_table();
                 $("#code").attr("readonly",false); 
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
    $.post("index.php/main/load_data/r_sales_return_reason/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function check_code(){
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_sales_return_reason/check_code", {
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
        loding();
    }, "text");
}

function validate(){
    if($("#code").val() == $("#code").attr('title') || $("#code").val() == ""){
            alert("Please enter code.");
            $("#code").focus();
            return false;
        }else if($("#code").val().length<4){
            alert("Code must contains 4 charactors");
            $("#code").focus();
            return false;
    }   else if($("#des").val() === $("#des").attr('title') || $("#des").val() == ""){
            alert("Please enter description.");
            $("#des").focus();
            return false;
        }else if($("#managers option:selected").attr('value') == "0"){
            alert("Please select manager.");
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
            if(confirm("Are you sure delete "+code+"?")){
               //loding();
               $.post("index.php/main/delete/r_sales_return_reason", {
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


 
    
function set_edit(code){
 loding();
    $.post("index.php/main/get_data/r_sales_return_reason", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#des").val(res.description);
        
       
       loding(); 
        input_active();
    }, "json");
}