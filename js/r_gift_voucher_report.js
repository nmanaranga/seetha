var sub_cat;

var is_edit = 0;

$(function() {
   
    $("#sc").tableScroll({
        height: 200
    });
    $("#item_gen").click(function() {
        generate_code();
    });
    input_active();

    $("#cluster").val($("#d_cl").val());
    cl_change();
    //alert($("#d_bc").val());
    
   

     $("#item").click(function() {
        $(this).addClass("input_active");
        $("#main_category").removeClass("input_active");
        $("#sub_category").removeClass("input_active");
        $("#unit").removeClass("input_active");
        $("#supplier").removeClass("input_active");
    });

     $("#item").keydown(function(a) {
        var b = a.keyCode || a.which;
        if (9 == b) {
            a.preventDefault();
            $("#main_category").focus();
            $("#department").removeClass("input_active");
            $("#main_category").addClass("input_active");
        }
        if (46 == b ){
            $("#item").val("");
            $("#item_des").val("");
        }
    });

});

$(document).ready(function(){

//$("#r_stock_in_hand").val("");
//$("#r_serial_in_hand").val("");

// $("#by").val("r_stock_in_hand");
// $("#by").attr("title","r_stock_in_hand");
// $("#type").val("r_stock_in_hand");
 //$("#type").attr("title","r_stock_in_hand");

 $("#print").click(function(){
      
        
        if($("#r_gift_stock_in_hand").val()=="r_gift_stock_in_hand"){
           $("#print_pdf").submit(); 
        }
        else{
             
             alert("Please select report type");
            return false; 
        } 
       
        //if($("#cluster").val()==0)
       // {
         //   alert("Please select cluster");
        //    return false;
        //}
        //else
        //{

          //  $("#print_pdf").submit(); 
        //}

        if($("#r_bin_card_gift_stock").is(':checked')){

            if($("#store").val()=="" || $("#store").val()==0){
                set_msg("please select a store");
                return false;
            }else if($("#item").val()== ""){
                set_msg("please select a Item code");
                return  false;
            } else{
                 $("#print_pdf").submit(); 
            }
          }

    });   


//($("#r_gift_stock_in_hand").is(':checked')){
$("#r_gift_stock_in_hand").click(function(){
    //alert("a");
    $("#by").val("r_gift_stock_in_hand");
    $("#by").attr("title","r_gift_stock_in_hand");

    $("#type").val("r_gift_stock_in_hand");
    $("#type").attr("title","r_gift_stock_in_hand");
    alert(("#type").val("r_gift_stock_in_hand"));
//}
}); 



$("#r_bin_card_gift_stock").click(function(){
    $("#by").val("r_bin_card_gift_stock");
    $("#by").attr("title","r_bin_card_gift_stock");

    $("#type").val("r_bin_card_gift_stock");
    $("#type").attr("title","r_bin_card_gift_stock");
});

// $("#r_stock_detail").click(function(){

//     $("#by").val("r_stock_detail");
//     $("#by").attr("title","r_stock_detail");

//     $("#type").val("r_stock_detail");
//     $("#type").attr("title","r_stock_detail");

// });

// $("#r_item_category").click(function(){
//     $("#by").val("r_item_category");
//     $("#by").attr("title","r_item_category");

//    $("#type").val("r_item_category");
//    $("#type").attr("title","r_item_category");

// });


// $("#item_lists").click(function(){

//     $("#by").val("item_lists");
//     $("#by").attr("title","item_lists");

//     $("#type").val("item_lists");
//     $("#type").attr("title","item_lists");

// });

// $("#r_stock_details").click(function(){

//     $("#by").val("r_stock_details");
//     $("#by").attr("title","r_stock_details");

//     $("#type").val("r_stock_details");
//     $("#type").attr("title","r_stock_details");

// });



// $("#r_open_stock").click(function(){

//     $("#by").val("r_open_stock");
//     $("#by").attr("title","r_open_stock");

//     $("#type").val("r_open_stock");
//     $("#type").attr("title","r_open_stock");

// });





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








$("#item").keydown(function(e){ 
    if(e.keyCode==112){    
        $("#pop_search11").val($("#item").val());
        load_item();
        $("#serch_pop11").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search11').focus()", 100);   
    }
    $("#pop_search11").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_item();
      }
    });
    if(e.keyCode==46){
        $("#item").val("");
        $("#item_des").val("");
    }  
});









//--------------------------------------------------------------------------------

setTimeout("default_option()",100); 

	$("#branch").change(function(){
		$("#store").val("");

		
		if($("#branch").val()!=0)
		{
			$.post("index.php/main/load_data/r_gift_voucher_report/get_stores_bc",{
		    bc:$(this).val(),
		    },function(res){
		    $("#store").html(res);
		    },'text');	
            setTimeout("default_option()",100); 


		}
		else if($("#cluster").val()!="0")
		{
			$.post("index.php/main/load_data/r_gift_voucher_report/get_stores_cl",{
		    cl:$("#cluster").val(),
		    },function(res){
		    $("#store").html(res);
		    },'text');	
            setTimeout("default_option()",100); 

		}
		else
		{
			$.post("index.php/main/load_data/r_gift_voucher_report/get_stores_default",{
		    cl:$("#cluster").val(),
		    },function(res){
		    $("#store").html(res);
		    },'text');	
            setTimeout("default_option()",100); 
		}

	});








	$("#cluster").change(function(){
		$("#store").val("");
		
		
		var path;
		var path_store;

		if($("#cluster").val()!=0)
		{
			path="index.php/main/load_data/r_gift_voucher_report/get_branch_name2";
			path_store="index.php/main/load_data/r_gift_voucher_report/get_stores_cl";
		}
		else
		{
			path="index.php/main/load_data/r_gift_voucher_report/get_branch_name3";
			path_store="index.php/main/load_data/r_gift_voucher_report/get_stores_default";
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

 

    $("#btnExit").click(function(){
        return false;
    });


    $("#item").autocomplete("index.php/main/load_data/r_gift_voucher_report/auto_com_item", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });
    $("#item").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values8($(this));
    });
    $("#item").blur(function() {
        set_cus_values8($(this));
    });

   
   
    

});

function cl_change(){
    //alert('a');
    $("#store").val("");
        
        var path;
        var path_store;

        if($("#cluster").val()!=0)
        {
            path="index.php/main/load_data/r_gift_voucher_report/get_branch_name2";
            path_store="index.php/main/load_data/r_gift_voucher_report/get_stores_cl";
        }
        else
        {
            path="index.php/main/load_data/r_gift_voucher_report/get_branch_name3";
            path_store="index.php/main/load_data/r_gift_voucher_report/get_stores_default";
        }


        $.post(path,{
        cl:$("#cluster").val(),
        },function(res){
        $("#branch").html(res);
        $("#branch").val($("#d_bc").val());
        },'text');  


        $.post(path_store,{
        cl:$("#cluster").val(),
        },function(res){
        $("#store").html(res);
        $("#branch").val($("#d_bc").val());
        },'text');  


}

function set_cus_values8(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#item_des").val(b[1]);
    }
}

function set_cus_values(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#department_des").val(b[1]);
    }
}

function set_cus_values2(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#main_category_des").val(b[1]);
    }
}

function set_cus_values3(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#sub_category_des").val(b[1]);
    }
}

function set_cus_values4(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#unit_des").val(b[1]);
    }
}

function set_cus_values5(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#supplier_des").val(b[1]);
    }
}

function set_cus_values6(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#brand_des").val(b[1]);
    }
}

function formatItems(a) {
    return "<strong> " + a[0] + "</strong> | <strong> " + a[1] + "</strong>";
}

function formatItemsResult(a) {
    return a[0] + "|" + a[1];
}


function select_search() {
    $("#pop_search").focus();
}

function default_option(){
   
    $.post("index.php/main/load_data/utility/default_option", {
  }, function(r){

            var store=r.def_sales_store;
            $('#store').val(store)
          //$("#store_id").val(r.store);
          
 }, "json");
}



 //});    
//}




function load_item() {
    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"g_m_gift_voucher",
      field:"code",
      field2:"description",
      preview2:"Item Name",
      search : $("#pop_search11").val() 
  }, function(r){
      $("#sr11").html(r);
      settings_item();      
 }, "text");
}

function settings_item(){
    $("#item_list .cl").click(function(){        
        $("#item").val($(this).children().eq(0).html());
        $("#item_des").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}













