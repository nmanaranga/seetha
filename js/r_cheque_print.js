var sub_cat;

var is_edit = 0;

$(function() {
    $("#brand").change(function() {
        set_select("brand", "brand_des");
    });
    $("#sc").tableScroll({
        height: 200
    });
    $("#item_gen").click(function() {
        generate_code();
    });
    input_active();  
   });
$(document).ready(function() {



 $("#by").val("r_cheque_print");
 $("#by").attr("title","r_cheque_print");
 $("#type").val("r_cheque_print");
 $("#type").attr("title","r_cheque_print");


$("#branch").click(function(){
	if($("#cluster").val()=="0"){
		alert("Please select cluster");
		return false;
	}
});

$("#store").click(function(){
	if($("#cluster").val()=="0"){
		alert("Please select cluster");
		return false;
	}
});


//--------------------------------------------------------------------------------


	$("#branch").change(function(){
		$("#store").val("");

		
		if($("#branch").val()!=0)
		{
			$.post("index.php/main/load_data/r_stock_report/get_stores_bc",{
		    bc:$(this).val(),
		    },function(res){
		    $("#store").html(res);
		    },'text');	

		}
		else if($("#cluster").val()!="0")
		{
			$.post("index.php/main/load_data/r_stock_report/get_stores_cl",{
		    cl:$("#cluster").val(),
		    },function(res){
		    $("#store").html(res);
		    },'text');	

		}
		else
		{
			$.post("index.php/main/load_data/r_stock_report/get_stores_default",{
		    cl:$("#cluster").val(),
		    },function(res){
		    $("#store").html(res);
		    },'text');	

		}

	});


	$("#cluster").change(function(){
		$("#store").val("");
		
		
		var path;
		var path_store;

		if($("#cluster").val()!=0)
		{
			path="index.php/main/load_data/r_stock_report/get_branch_name2";
			path_store="index.php/main/load_data/r_stock_report/get_stores_cl";
		}
		else
		{
			path="index.php/main/load_data/r_stock_report/get_branch_name3";
			path_store="index.php/main/load_data/r_stock_report/get_stores_default";
		}


		$.post(path,{
	    cl:$(this).val(),
	    },function(res){
	    $("#branch").html(res);
	    },'text');	


	    $.post(path_store,{
	    cl:$(this).val(),
	    },function(res){
	    $("#store").html(res);
	    },'text');	
		
	});

    $("#print").click(function(){
    	if($("#cheque_list").val()==0){
    		set_msg("Please select type");
    		return false;
    	}else{
	       $("#print_pdf").submit(); 
    	}
    });    

    $("#btnExit").click(function(){
        return false;
    });
});

