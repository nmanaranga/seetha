

$(document).ready(function(){

	//var myString = $(location).attr('href').split("&num=")[1];
	
	
    $("#code").blur(function(){
        check_code();
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
	                tbl:"m_cluster",
	                tbl_fied_names:"Code-Description",
	        		fied_names:"code-description"
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
            if(pid == 1){
                loding();
                sucess_msg();  
            }else if(pid == 2){
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                loding();
                set_msg("Error : \n"+pid);
            }
            
        }
    });
}


function check_code(){
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_cluster/check_code", {
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


function get_data_table(){
    $.post("/index.php/main/load_data/m_cluster/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
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
        }else{
            return true;
        }
}
    
function set_delete(code){
       if(confirm("Are you sure delete "+code+"?")){
       loding();
        $.post("index.php/main/delete/m_cluster", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else{
                loding();
                set_msg("Item deleting fail.");
            }
           
        }, "text");
    }
}
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/m_cluster", {
        code : code
    }, function(res){
		loding(); 
        $("#code_").val(res.code);
        $("#code").val(res.code);
        $("#code").attr("readonly", true);
        $("#des").val(res.description);
        
      //  $("#sub_region").val(res.region);
       // set_select('sub_region', 'sr_des');
        
      //  $("#sales_ref").val(res.sales_ref);
      //  set_select('sales_ref', 'sre_des');
       
	   input_active();
    }, "json");
}