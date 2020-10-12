$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
    $("#grid").tableScroll({height:355});


  $("#srchee").keyup(function(){  
    $.post("index.php/main/load_data/utility/get_data_table", {
                    code:$("#srchee").val(),
                    tbl:"u_modules",
                    tbl_fied_names:"Code-Module Name-Description",
                    fied_names:"m_code-module_name-m_description",
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
             
            if(pid == 0){
              set_msg("Transaction is not completed");
             }else if(pid == 2){
                set_msg("No permission to add data.");
             }else if(pid == 3){
                set_msg("No permission to edit data.");
             }else if(pid==1){
              location.href="";
            }else{
              set_msg(pid);
            }
    loding();
        }
    });
}

function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/s_module/check_code", {
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
    if($("#module_name").val() == ""){
            alert("Please enter module name.");
            $("#code").focus();
            return false;
        }else if($("#des").val() === $("#des").attr('title') || $("#des").val() == ""){
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
        loding();
        $.post("index.php/main/delete/s_module", {
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
    $.post("index.php/main/get_data/s_module", {
        code : code
    }, function(res){
        $("#code_").val(res.m_code);
        $("#code").val(res.m_code);
        $("#code").attr("readonly", true);
        $("#des").val(res.m_description);
        $("#module_name").val(res.module_name);
        $("#package").val(res.package);
        $("#main_mod").val(res.main_mod);
        loding();
        input_active();
    }, "json");
}