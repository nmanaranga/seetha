var is_edit=0;
$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
    $("#btnSave1").click(function()
    {
    if(validate())
    {
        check_permission();
    }    
    });

    $("#grid").tableScroll({height:355});
});

function check_permission()
{
    $.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '012',
        is_edit   : is_edit
        
    },function(r){
    
    if(r=='1')
    {
       save();
       
    }    
    else if(r=='2')
    {
        alert("You have no permission to edit this record");
    }
    else
    {
      save();
    }    
});
    
}

function check_delete_permission(code)
{
    $.post("index.php/main/load_data/user_permissions/get_delete_permission",{
        module_id : '012'
    },function(r){
    
    if(r>0)
    {
       if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_sub_cat", {
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
    else
    {
         alert("You have no permission to delete this record");
    }    
    
    });  
}

function save(){
    $("#form_").submit();
    is_edit=0;
}


function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_sub_cat/check_code", {
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
            alert("Please enter code.");
            $("#code").focus();
            return false;
        }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
            alert("Please enter description.");
            $("#description").focus();
            return false;
        }else if($("#des").val() === $("#code").val()){
            alert("Please enter deferent values for description & code.");
            $("#des").focus();
            return false;
        }else if($("#main_cat option:selected").attr('value') == "0"){
            alert("Please select main category.");
            return false;
        }else{
            return true;
        }
}
    
function set_delete(code){
  check_delete_permission(code);
}
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/m_sub_cat", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#description").val(res.description);
        $("#main_cat").val(res.main_cat);
        
        loding(); input_active();
        is_edit=1;
    }, "json");
}