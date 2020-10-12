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

    $("#acc_code").keypress(function(e){
     if(e.keyCode == 112){
         $("#pop_search").val();
         select_account();
         $("#serch_pop").center();
         $("#blocker").css("display","block");
         setTimeout("$('#pop_search').focus()", 100);
     }
     if(e.keyCode == 46){
        $("#acc_code").val("");
        $("#account_des").val("");

    }
});

    $("#pop_search").keyup(function(){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        select_account();
    }
});



    $("#emp_code").keypress(function(e){
     if(e.keyCode == 112){
         $("#pop_search").val();
         select_emp();
         $("#serch_pop").center();
         $("#blocker").css("display","block");
         setTimeout("$('#pop_search').focus()", 100);
     }
     if(e.keyCode == 46){
        $("#emp_code").val("");
        $("#emp_des").val("");

    }
});

    $("#pop_search").keyup(function(){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        select_emp();
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
    }else if($("#cl option:selected").val() == 0){
        alert("Please seletct cluster");
        return false;
    } else if($("#is_use_pos").is(":checked") && $("#acc_code").val()==""){
        set_msg("Please enter a account");
        $("#acc_code").focus();
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
        if(res.is_use_pos == 1){
           $("#is_use_pos").attr("checked", "checked");
           $("#acc_code").val(res.acc_code); 
           $("#account_des").val(res.account_name); 
       }else{
        $("#is_use_pos").removeAttr("checked");
        $("#acc_code").val(""); 
        $("#account_des").val("");
    }


    $("#permission").val(res.permission);
    $("#cl").val(res.cl);
    $("#bc").html("<select><option value='"+res.bc+"'>"+res.name+"</option></select>");

    loding(); input_active();
}, "json");
}

function select_account(){
    $.post("index.php/main/load_data/utility/f1_selection_list",{

        data_tbl:"m_account",
        field:"code",
        field2:"description",
        search:$("#pop_search").val()

    },function(r){
        $("#sr").html(r);
        acc_settings();
    },"text");
}
function acc_settings(){
    $("#item_list .cl").click(function(){        
       $("#acc_code").val($(this).children().eq(0).html());  
       $("#account_des").val($(this).children().eq(1).html());  
       $("#pop_close").click();                
   });    
}


function select_emp(){
    $.post("index.php/main/load_data/utility/f1_selection_list",{

        data_tbl:"m_employee",
        field:"code",
        field2:"description",
        search:$("#pop_search").val()

    },function(r){
        $("#sr").html(r);
        emp_settings();
    },"text");
}
function emp_settings(){
    $("#item_list .cl").click(function(){        
       $("#emp_code").val($(this).children().eq(0).html());  
       $("#emp_des").val($(this).children().eq(1).html());  
       $("#pop_close").click();                
   });    
}