$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
    
   
     
    $("#srchee").keyup(function(){  
    $.post("index.php/main/load_data/utility/get_data_table", {
                    code:$("#srchee").val(),
                    tbl:"m_account",
                    tbl_fied_names:"Type-Code-Description",
                    fied_names:"type-code-description",
                    col4:"Y"
                 }, function(r){
            $("#grid_body").html(r);
        }, "text");
    });
    

    $("#is_control_acc,#is_bank_acc").removeAttr("checked","checked"); 
     
    $("#btnSave1").click(function(){
       check_is_transaction($("#code").val()); 
    }); 
     
    $("#grid").tableScroll({height:355});
    $("#tgrid").tableScroll({height:355});
    $("#type").change(function(){
        set_select('type','account_type');
     });

    $("#category").change(function(){
        set_select('category','account_category');
     });

    $("#control_acc").autocomplete('index.php/main/load_data/m_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });
    
    $("#control_acc").keydown(function(e){
        if(e.keyCode == 13){
            set_cus_values($(this));
        }
    });
    
    $("#control_acc").blur(function(){
        set_cus_values($(this));
    });

});

function check_is_transaction(code)
{
    $.post("index.php/main/load_data/m_account/check_is_transaction",{
     
     code:code
     
    },function(r)    
    {    
        if(r>0)
        {
            set_msg("You cannot edit this account.Account code alredy use for the transaction");
        }  
        else
        {
            save();
        }    
        
    },"text");
    
}

function check_is_transaction_del(code)
{
    $.post("index.php/main/load_data/m_account/check_is_transaction",{
     
     code:code
     
    },function(r)    
    {    
        if(r>0)
        {
            set_msg("You cannot edit this account.Account code alredy use for the transaction");
        }  
        else
        {
            detele_data(code);
        }    
        
    },"text");
    
}

    function set_cus_values(f){
            var v = f.val();
            v = v.split("-");
            
                if(v.length == 2){
                f.val(v[0]);
                $("#control").val(v[1]);
        }
    }

    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"-"+row[1];
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
                location.href="";
                input_reset();
                get_data_table();
            }else if(pid == 2){
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                set_msg("Error : \n"+pid);
            }
            loding();
        }
    });
}

function get_data_table(){
    $.post("index.php/main/load_data/m_account/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
        $("#is_control_acc,#is_bank_acc").removeAttr("checked","checked"); 
        $("#code").attr("readonly", false);
    }, "text");
}


function check_code(){
        loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_account/check_code", {
        code : code
    }, function(res){
        if(res == 1){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#code").val('');
		$("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate(){

    if($("#type").val() == "0"){
        set_msg("Please please select type.");
        $("#description").focus();
        return false;
    }else if($("#type").val() === $("#code").val()){
        set_msg("Please enter diffent account code.");
        $("#description").focus();
        return false   
    }else if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
        set_msg("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
        set_msg("Please enter description.");
        $("#description").focus();
        return false;
    }else if($("#description").val() === $("#code").val()){
        set_msg("Please enter different values for description & code.");
        $("#description").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    check_is_transaction_del(code);
}

function detele_data(code){

    if(confirm("Are you sure delete "+code+"?")){
       loding();
       $.post("index.php/main/delete/m_account", {
           code : code
       }, function(res){
           if(res == 1){
               location.reload();
           }else{
               set_msg("Item deleting fail.");
           }
           loding();
       }, "text");
   }
    
}
  
function set_edit(code){

    $.post("index.php/main/get_data/m_account", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
	$("#code").attr("readonly", true);
        $("#account_type").val(res.type);
        set_select('type','account_type');
        $("#description").val(res.description);
        $("#control_acc").val(res.control_acc);
        $("#type").val(res.type);
        set_select('type','account_type');
        $("#category").val(res.category);
        $("#account_category").val(res.category);
        set_select('category','account_category');
        $("#order_no").val(res.order_no);
        $("#display_text").val(res.display_text);
        $("#control_acc").val(res.control_acc);
        $("#control").val(res.con_des);
        
           if(res.is_control_acc == 1){
            $("#is_control_acc").attr("checked", "checked");
              }else{
            $("#is_control_acc").removeAttr("checked");
        }

           if(res.is_bank_acc == 1){
            $("#is_bank_acc").attr("checked", "checked");
              }else{
            $("#is_bank_acc").removeAttr("checked");
        }
      
      input_active();
    }, "json");
}