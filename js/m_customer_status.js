var is_edit=0; 
$(document).ready(function(){

   
    $("#code").change(function(){
        check_code();
    });

    $("#search").keyup(function(){
        $.post("index.php/main/load_data/utility/get_data_table",{
            code:$("#search").val(),
            tbl:"m_customer_status",
            tbl_fied_names:"Code-Description-Color",
            fied_names:"code-description-color",
            col4:"Y" 
        },function(r){
            $("#grid_body").html(r);
        },"text");
    });
    $(".fn").keyup(function(){
        if(!$("#edit_name").is(":checked")){
            genarate_name();
        }
    });

    $("#btnReset").click(function(){
   	    location.href="?action=m_customer_status";
	});

   
    $("#tgrid").tableScroll({height:200});
    $("#grid").tableScroll({height:355});       
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
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
              set_msg("Error : \n"+pid);
            }
            
      }
    });
}


function get_data_table(){
    $.post("index.php/main/load_data/m_customer_status/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}



function validate(){
    if($("#code").val()=="" || $("#code").val()== "0"){
        set_msg("Please enter code","error");
        return false;
    }
    else if($("#txtDesc").val()==""){
        set_msg("Please enter Description");
        return false;
    }
   else if($("#color").val()=="" || $("#color").val()=="0"){
        set_msg("Please select a Color");
        return false;
    }
    else{
        return true;
    }
}

 
function set_delete(code){
   if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/m_customer_status", {
            code : code
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
   // loading();
    $.post("index.php/main/get_data/m_customer_status",{
        code:code
    },function(res){
       $("#code_").val(res.data.code);
        $("#code").val(res.data.code);
        $("#code").attr("readonly", true);
        $("#txtDesc").val(res.data.description);
        $("#color").val(res.data.color);
        $("#color").css("background-color",res.data.color);
        input_active();
    },"json");
}

function genarate_name(){


        var p_name = $("#name_title").val()+' '+$("#sur_name").val()+' '+$("#initials").val();
        $("#name").val(p_name);
   
}


function check_code(){
    var code=$("#code").val();
    $.post("index.php/main/load_data/m_customer_status/check_code",{
        code:code
    },function(res){
        if(res == 1){
            if(confirm("This code ("+code+")already added.\n\n Do you need to edit it")){
                set_edit(code);

            }else{
                $("#code").val("");
            }
        }
    },"json");
}
