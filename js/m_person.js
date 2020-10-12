var is_edit=0;
$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
    $("input:radio[name='type']").change(function(){
            
            load_max();
            acc_type=$("input[name='type']:checked").val();
            //$("#code_").val('');
            //$("#code").attr("readonly", false);
          
            
});

function check_permission()
{
    $.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '096',
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
        module_id : '096'
    },function(r){
    
    if(r>0)
    {

      if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_person", {
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

     function load_max(){
        $.post("index.php/main/load_data/m_person/max_person", {
           
            acc_type : $("input[name='type']:checked").val()
  
        }, function(res){
            
            if(acc_type=='1')
              {
                   var a="TC-"+res;
                   $("#code").val(a);
              }  
           else if(acc_type=='2') 
              {
                  var a="D-"+res;
                   $("#code").val(a);
                  
              }
           else if (acc_type=='3') 
              {
                  var a="MC-"+res;
                   $("#code").val(a);
                  
              }


            $("#code").attr("class", "input_number_f");
        }, "json");
    }   
    
    
    $("#grid").tableScroll({height:355});
});

function save(){
    $("#form_").submit();
    is_edit=0;
}

function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_sales_ref/check_code", {
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
    if($("#code").val() == $("#code").attr("title") || $("#code").val() == ""){
            alert("Please enter code");
            $("#code").focus();
            return false;
        }else if($("#name").val() == $("#name").attr("title") || $("#name").val() == ""){
            alert("Please enter name");
            $("#name").focus();
            return false;
        }else {
            return true;
        }
}
    
function set_delete(code){
     check_delete_permission(code);
}
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/m_person", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#name").val(res.name);
        $("#address_no").val(res.address01);
        $("#address_street").val(res.address02);
        $("#address_city").val(res.address03);
        $("#p_mobile").val(res.phone01);
        $("#p_office").val(res.phone02);
        $("#p_fax").val(res.phone03);
        $("#dateOfJoin").val(res.dateOfJoin);
        
        if(res.type=='1')
            {
                $("input[name='type'][value='1']").attr("checked", "checked");
            }
        
        if(res.type=='2')
            {
                $("input[name='type'][value='2']").attr("checked", "checked");
            }
        
        if(res.type=='3')
            {
                $("input[name='type'][value='3']").attr("checked", "checked");
            }
        
        
        loding(); input_active();
        is_edit=1;
    }, "json");
}