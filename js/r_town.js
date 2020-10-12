

var is_edit=0;
$(document).ready(function(){
    $("#code").blur(function(){
	
        check_code();
    });
    
    $("#btnSave1").click(function(){
        if(validate())
        {
            check_permission();
        }    
    });
    
    
       $("#grid").tableScroll({height:355,width:590});
    
    $("#sub_region").change(function(){
        set_select('sub_region', 'sr_des');
    });
    
    $("#sales_ref").change(function(){
        set_select('sales_ref', 'sre_des');
    });


    $("#srchee").keyup(function(){  
	 	$.post("index.php/main/load_data/utility/get_data_table", {
	                code:$("#srchee").val(),
	                tbl:"r_town",
	                tbl_fied_names:"Code-Description",
	        		fied_names:"code-description"
	             }, function(r){
	        $("#grid_body").html(r);
	    }, "text");

	});








});

function save(){
    $("#form_").submit();
    is_edit=0;
}

function check_permission()
{
  
      save();

}

function check_delete_permission(code)
{
  
   
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/r_town", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else{
                set_msg(res);
            }
        loding();
        }, "text");
    }
 
}

function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/r_town/check_code", {
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
            set_msg("Please enter code.");
            $("#code").focus();
            return false;
        }else if($("#des").val() === $("#des").attr('title') || $("#des").val() == ""){
            set_msg("Please enter description.");
            $("#des").focus();
            return false;
        }else if($("#sub_region option:selected").attr('value') == "0"){
            set_msg("Please select sub region.");
            return false;
        }else if($("#sales_ref option:selected").attr('value') == "0"){
            set_msg("Please select sales ref.");
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
    $.post("index.php/main/get_data/r_town", {
        code : code
    }, function(res){
		
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#des").val(res.description);
        
      //  $("#sub_region").val(res.region);
       // set_select('sub_region', 'sr_des');
        
      //  $("#sales_ref").val(res.sales_ref);
      //  set_select('sales_ref', 'sre_des');
        loding(); 
	   input_active();
      is_edit=1;
    }, "json");
}