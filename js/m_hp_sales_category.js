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

    $("#de_code").autocomplete('index.php/main/load_data/r_department/auto_com', {
            width: 350,
            multiple: false,
            matchContains: true,
            formatItem: formatItems,
            formatResult: formatItemsResult
        });


        $("#de_code").keydown(function(e){
            if(e.keyCode == 13){
            set_cus_values($(this));
            }
        });

        $("#de_code").blur(function(){
            set_cus_values($(this));
        });


        //  $("#code").autocomplete('index.php/main/load_data/r_category/auto_com', {
        //     width: 350,
        //     multiple: false,
        //     matchContains: true,
        //     formatItem: formatItems,
        //     formatResult: formatItemsResult
        // });


        // $("#code").keydown(function(e){
        //     if(e.keyCode == 13){
        //     set_cus_values($(this));
        //     }
        // });

        // $("#code").blur(function(){
        //     set_cus_values2($(this));
        // });
   

    $("#grid").tableScroll({height:355, width:590});


    $("#srchee").keyup(function(){  
   		$.post("index.php/main/load_data/utility/get_data_table", {
                code:$("#srchee").val(),
                tbl:"r_category",
                tbl_fied_names:"Code-Description",
	        	fied_names:"code-description"
             }, function(r){
        	$("#grid_body").html(r);
    	}, "text");

	});

});

function check_permission()
{
    $.post("index.php/main/load_data/user_permissions/get_permission",{
    
        module_id : '013',
        is_edit   : is_edit
        
    },function(r){
    
    if(r=='1')
    {
       save();
       
    }    
    else if(r=='2')
    {
        set_msg("You have no permission to edit this record","error");
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
        module_id : '013'
    },function(r){
    
    if(r>0)
    {
         if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_category", {
            code : code
        }, function(res){
            if(res == 1){
                location.reload();
            }else{
                set_msg("Item deleting fail.","error");
            }
            loding();
        }, "text");
    }
    }    
    else
    {
         set_msg("You have no permission to delete this record","error");
    }    
    
    });  
}

// function save(){
//     $("#form_").submit();
//     is_edit=0;
// }


    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"|"+row[1];
    }

    function set_cus_values(f){
            var v = f.val();
            v = v.split("|");
            if(v.length == 2){
            f.val(v[0]);
            $("#department").val(v[1]);
    }
 }


     function set_cus_values2(f){
            var v = f.val();
            v = v.split("|");
            if(v.length == 2){
            f.val(v[0]);
            $("#description").val(v[1]);
    }
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
                input_reset();
                get_data_table();
            }else if(pid == 2){
                set_msg("No permission to add data.","error");
            }else if(pid == 3){
                set_msg("No permission to edit data.","error");
            }else{
                set_msg("Error : \n"+pid,"error");
            }
            loding();
        }
    });
}


function get_data_table(){
    $.post("index.php/main/load_data/r_category/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_category/check_code", {
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
            set_msg("Please enter code.","error");
            $("#code").focus();
            return false;
        }else if($("#code").val().length<4){
            set_msg("Code must contains 4 charactors","error");
            $("#code").focus();
            return false;
        }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
            set_msg("Please enter description.","error");
            $("#des").focus();
            return false;
        }else if($("#des").val() === $("#code").val()){
            set_msg("Please enter deferent values for description & code.","error");
            $("#des").focus();
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
    $.post("index.php/main/get_data/r_category", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code_gen").val(res.code_gen);
        $("#description").val(res.description);
        $("#de_code").val(res.de_code);
        $("#department").val(res.r_des);


        
        loding(); input_active();
        is_edit=1;
    }, "json");
}


