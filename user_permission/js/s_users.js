$(document).ready(function(){
    $("#cCode").blur(function(){
        check_code();
    });
    
    $("#grid").tableScroll({height:355});

    $("#bc").click(function(){
        if($("#cl").val()==0){
            set_msg("Please select cluster");
        }
    });

    $("#cl").change(function(){
        $.post("index.php/main/load_data/s_users/select_branch", {
            code : $("#cl").val()
        }, function(res){
            $("#branch_list").html(res);
        }, "text");
    });

    $("#srchee").keyup(function(){  
        $.post("index.php/main/load_data/utility/get_data_table", {
            code:$("#srchee").val(),
            tbl:"s_users",
            tbl_fied_names:"Code-Username-Description",
            fied_names:"cCode-loginName-discription",
            col4:"Y"
            }, function(r){
        $("#grid_body").html(r);
        }, "text");
    });

    /*$("#create_user").click(function() {
        if($("#code_").val() != ""){
            if($("#is_create").val()=="1"){
                $("#create_user").css("pointer-events", "auto");
            }else{
                $("#create_user").css("pointer-events", "none");
            }
        }
    });*/

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
              // sucess_msg();
              set_msg("User adding sucess");
              
              location.href="";
            }else{
              set_msg(pid);
            }
    loding();
        }
    });
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
    if($("#discription").val() == $("#discription").attr("title") || $("#discription").val() == ""){
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
        }else if($("#cl option:selected").val() == 0){
            alert("Please seletct cluster");
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
                location.href="";
            }else{
                alert(res);
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
        $("#code_").val(res.a.cCode);
        $("#cCode").val(res.a.cCode);
        $("#cCode").attr("readonly", true);
        $("#discription").val(res.a.discription);
        $("#loginName").val(res.a.loginName);
        
        if(res.a.isAdmin == 1){
            $("#isAdmin").attr("checked", "checked");
        }else{
            $("#isAdmin").removeAttr("checked");
        }
        if(res.a.create_user == 1){
            $("#create_user").attr("checked", "checked");
        }else{
            $("#create_user").removeAttr("checked");
        }
        
        if(res.b.create_user == 1){
            //$("#create_user").attr("checked", "checked");
            //$("#is_create").val("1");
            $("#create_user").css("pointer-events", "auto");
        }else{
            
            //$("#create_user").removeAttr("checked");
            //$("#is_create").val("2");
            $("#create_user").css("pointer-events", "none");
        }
        
        $("#permission").val(res.a.permission);
        $("#cl").val(res.a.cl);
        $("#bc").html("<select><option value='"+res.a.bc+"'>"+res.a.name+"</option></select>");

        loding(); 
        input_active();
    }, "json");
}