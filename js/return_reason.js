$(document).ready(function(){
    $("#code").blur(function(){
        //check_code();
    });
    
    $("#grid").tableScroll({height:355, width:590});

    $(".rt").click(function(){
        get_max_no($(this).val());
    });


    $("#srchee").keyup(function(){  
      $.post("index.php/main/load_data/utility/get_data_table", {
        code:$("#srchee").val(),
        tbl:"r_return_reason",
        tbl_fied_names:"Code-Description",
	    fied_names:"code-description",
        is_r:"Y",
        return_r:"type"
      }, function(r){
        $("#grid_body").html(r);
    }, "text");

});



});

function get_max_no(typ){
    $.post("index.php/main/load_data/return_reason/genarate_max", {
       type:typ 
    }, function(r){
        $("#code").val(r);
    }, "text");
}

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
                set_msg(pid);
            }
        }
    });
}

function get_data_table(){
    $.post("index.php/main/load_data/return_reason/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}

function check_code(){
    loding();
    var code = $("#code").val();
    if($("#purchase_ret").is(":checked")){
        var type = 1;
    }else if($("#sales_ret").is(":checked")){
        var type = 2;
    }else if($("#cheque_ret").is(":checked")){
        var type = 3;
    }else if($("#bank").is(":checked")){
        var type = 4;
    }else if($("#sup_event").is(":checked")){
        var type = 5;
    }else if($("#cus_event").is(":checked")){
        var type = 6;
    }else if($("#card_cat").is(":checked")){
        var type = 7;
    }
    $.post("index.php/main/load_data/return_reason/check_code", {
        code : code,
        type : type
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code+'-'+type);
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
    if($("#code").val() == ""){
        set_msg("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#code").val().length<4){
        set_msg("Code must contains 4 charactors");
        $("#code").focus();
        return false;
    }else if($("#des").val() === $("#des").attr('title') || $("#des").val() == ""){
        set_msg("Please enter description.");
        $("#des").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    var type = code.split("-")[1];
    var c_code = code.split("-")[0];
      if(confirm("Are you sure delete no "+c_code+"?")){
        loding();
         $.post("index.php/main/delete/return_reason", {
             code : c_code,
             type : type
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
    var type = code.split("-")[1];
    var c_code = code.split("-")[0];
    loding();
    $.post("index.php/main/get_data/return_reason", {
        code : c_code,
        type : type
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#des").val(res.description);
        $("#hid_max").val(res.max_no);

        if(res.type==1){
          $("#purchase_ret").attr("checked", "checked");
        }
        if(res.type==2){
            $("#sales_ret").attr("checked", "checked");
        }
        if(res.type==3){
            $("#cheque_ret").attr("checked", "checked");
        }
        if(res.type==4){
            $("#bank").attr("checked", "checked");
        }
        if(res.type==5){
            $("#sup_event").attr("checked", "checked");
        }
        if(res.type==6){
            $("#cus_event").attr("checked", "checked");
        }
        if(res.type==7){
            $("#card_cat").attr("checked", "checked");
        }

        loding(); 
        input_active();
    }, "json");
}