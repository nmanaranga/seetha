$(document).ready(function(){
    $("#cCode").blur(function(){
        check_code();
    });
    
    $("#grid").tableScroll({height:355});
});

function save(){
    $("#form_").submit();
}

function check_code(){
    var code = $("#cCode").val();
    $.post("index.php/main/load_data/s_users/check_code", {
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
    if($("#cCode").val() == $("#cCode").attr("title") || $("#cCode").val() == ""){
            alert("Please enter code");
            $("#cCode").focus();
            return false;
        }else if($("#discription").val() == $("#discription").attr("title") || $("#discription").val() == ""){
            alert("Please enter discription");
            $("#discription").focus();
            return false;
        }else if($("#loginName").val() == $("#loginName").attr("title") || $("#loginName").val() == ""){
            alert("Please enter login name");
            $("#loginName").focus();
            return false;
        }else if($("#userPassword").val() == $("#userPassword").attr("title") || $("#userPassword").val() == ""){
            alert("Please enter password");
            $("#userPassword").focus();
            return false;
        }else if($("#userPassword").val() != $("#r_pass").val()){
            alert("Passwords not match");
            $("#r_pass").focus();
            return false;
        }else if($("#bc option:selected").val() == 0){
            alert("Please seletct branch");
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/s_users", {
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
    $.post("index.php/main/get_data/s_users", {
        code : code
    }, function(res){
        $("#code_").val(res.cCode);
        $("#cCode").val(res.cCode);
        $("#cCode").attr("readonly", true);
        $("#discription").val(res.discription);
        $("#loginName").val(res.loginName);
        
        if(res.isAdmin == 1){
            $("#isAdmin").attr("checked", "checked");
        }else{
            $("#isAdmin").removeAttr("checked");
        }
        
        $("#permission").val(res.permission);
        $("#bc").val(res.bc);
        
        loding(); input_active();
    }, "json");
}