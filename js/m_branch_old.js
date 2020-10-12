

$(document).ready(function(){
    $("#code").blur(function(){
        check_code();
    });
     
    $("#grid").tableScroll({height:355});

    $("#cluster").change(function(){
        set_select("cluster","cluster_id");
    });

    $("#acc").change(function(){
        set_select("acc","acc_id");
    });

   
    $("#def_cash_customer").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search6").val();
            load_data_cus();
            $("#serch_pop6").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search6').focus()", 100);
        }

        $("#pop_search6").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data_cus();
            }
        }); 

        if(e.keyCode == 46){
            $("#def_cash_customer").val("");
            $("#def_customer_des").val("");
        }
    });

    $("#def_loan_customer").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search6").val();
            loan_cus();
            $("#serch_pop6").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search6').focus()", 100);
        }

        $("#pop_search6").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 loan_cus();
            }
        }); 

        if(e.keyCode == 46){
            $("#def_loan_customer").val("");
            $("#def_loan_customer_des").val("");
        }
    });

    

    //stores-------------------------------------
     $("#def_sales_store").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search7").val();
           load_data_stores();
            $("#serch_pop7").center();
            $("#blocker2").css("display", "block");
            setTimeout("$('#pop_search7').focus()", 100);
        }

        $("#pop_search7").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data_stores();
            }
        }); 

        if(e.keyCode == 46){
            $("#def_sales_store").val("");
            $("#store_id").val("");
        }
    });
    //-------------------------------------------

    //category-----------------------------------
    $("#def_sales_category").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search10").val();
           load_data_category();
            $("#serch_pop10").center();
            $("#blocker4").css("display", "block");
            setTimeout("$('#pop_search10').focus()", 100);
        }

        $("#pop_search10").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data_category();
            }
        }); 

        if(e.keyCode == 46){
            $("#def_sales_category").val("");
            $("#category_id").val("");
        }
    });
    //-------------------------------------------

     //Group-----------------------------------
    $("#def_sales_group").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search11").val();
          load_data_group();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }

        $("#pop_search11").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_data_group();
            }
        }); 

        if(e.keyCode == 46){
            $("#def_sales_group").val("");
            $("#group_dtls").val("");
        }
    });
    //-------------------------------------------
     //Accounts-----------------------------------
    $("#current_acc").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search12").val();
            load_data_acc();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);
        }

        $("#pop_search12").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                load_data_acc();
            }
        }); 

        if(e.keyCode == 46){
            $("#current_acc").val("");
            $("#acc_dtls").val("");
        }
    });
    //-------------------------------------------

/*
	$("#srchee").keyup(function(){  
	 $.post("index.php/main/load_data/m_stores/get_data_table", {
	                code:$("#srchee").val(),
	                tbl:"m_branch",
	                tbl_fied_names:"Code-Description-Cluster",
			        fied_names:"bc-name-cl",
			        col4:"Y"
	             }, function(r){
	        $("#grid_body").html(r);
	    }, "text");
	});

*/
$("#tabs").tabs();
});

function load_data_cus(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_customer",
        field:"code",
        field2:"name",
        preview2:"Customer Name",
        search : $("#pop_search6").val() 
    }, function(r){
        $("#sr6").html(r);
        settings8();            
    }, "text");
}

function loan_cus(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_customer",
        field:"code",
        field2:"name",
        preview2:"Customer Name",
        search : $("#pop_search6").val() 
    }, function(r){
        $("#sr6").html(r);
        settings20();            
    }, "text");
}

function settings20(){
    $("#item_list .cl").click(function(){        
        $("#def_loan_customer").val($(this).children().eq(0).html());
        $("#def_loan_customer_des").val($(this).children().eq(1).html());
        $("#pop_close6").click();                
    })    
}

function settings8(){
    $("#item_list .cl").click(function(){        
        $("#def_cash_customer").val($(this).children().eq(0).html());
        $("#def_customer_des").val($(this).children().eq(1).html());
        $("#pop_close6").click();                
    })    
}


//stores------------------------
function load_data_stores(){
    if($("#bc").val()==""){
        var q="";
    }else{
        var q='AND bc="'+$("#bc").val()+'"';
    }
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_stores",
        field:"code",
        field2:"description",
        preview2:"Store Name",
        search : $("#pop_search7").val(),
        add_query:q 
    }, function(r){
        $("#sr7").html(r);
        settings9();            
    }, "text");
}

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#def_sales_store").val($(this).children().eq(0).html());
        $("#store_id").val($(this).children().eq(1).html());
        $("#pop_close7").click();                
    })    
}


//------------------------------

//category------------------------
function load_data_category(){    
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"r_sales_category",
        field:"code",
        field2:"description",
        preview2:"category Name",
        search :$("#pop_search10").val()    
    }, function(r){
        $("#sr10").html(r);
        settings10();            
    }, "text");
}

function settings10(){
    $("#item_list .cl").click(function(){        
        $("#def_sales_category").val($(this).children().eq(0).html());
        $("#category_id").val($(this).children().eq(1).html());
        $("#pop_close10").click();                
    })    
}


//------------------------------
//Group------------------------
function load_data_group(){ 
    if($("#bc").val()==""){
            var q="";
        }else{
            var q=' a AND bc="'+$("#bc").val()+'" AND category="'+$("#def_sales_category").val()+'"';
        }  
    if($("#def_sales_category").val()==""){
        var q = "";
    }else{
        var q = 'AND category="'+$("#def_sales_category").val()+'"';
    } 
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"r_groups",
        field:"code",
        field2:"name",
        preview2:"Group Name",
        search :$("#pop_search11").val(),
        add_query:q     
    }, function(r){
        $("#sr11").html(r);
        settings11();            
    }, "text");
}

function settings11(){
    $("#item_list .cl").click(function(){        
        $("#def_sales_group").val($(this).children().eq(0).html());
        $("#group_dtls").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}


//------------------------------

//accounts------------------------
function load_data_acc(){ 
    if($("#bc").val()==""){
            var q="";
        }else{
            var q='AND is_control_acc="0"';
        }   
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_account",
        field:"code",
        field2:"description",
        preview2:"Account Name",
        search :$("#pop_search12").val(),
        add_query:q     
    }, function(r){
        $("#sr12").html(r);
        settings12();            
    }, "text");
}

function settings12(){
    $("#item_list .cl").click(function(){        
        $("#current_acc").val($(this).children().eq(0).html());
        $("#acc_dtls").val($(this).children().eq(1).html());
        $("#pop_close12").click();                
    })    
}


//------------------------------

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
                alert("No permission to add data.");
            }else if(pid == 3){
                alert("No permission to edit data.");
            }else{
                loding();
                alert("Error : \n"+pid);
            }
            
        }
    });
}

function get_data_table(){
    $.post("index.php/main/load_data/m_branch/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function check_code(){
        // loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/m_branch/check_code", {
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
        // loding();
    }, "text");
}

function validate(){
    if($("#bc").val() === $("#code").attr('title') || $("#code").val() == ""){
        alert("Please enter code.");
        $("#bc").focus();
        return false;
    }else if($("#name").val() === $("#name").attr('title') || $("#name").val() == ""){
        alert("Please enter name.");
        $("#name").focus();
        return false;
    }else if($("#name").val() === $("#bc").val()){
        alert("Please enter deferent values for name & code.");
        $("#name").focus();
        return false;
    }else{
        return true;
    }
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
       loding();
        $.post("index.php/main/delete/m_branch", {
            bc : code
        }, function(res){
            if(res == 1){
                delete_msg();
            }else if(res == 2){
                alert("No permission to delete data.");
            }else{
                loding();
                alert("Item deleting fail.");
            }
           
        }, "text");
    }
}

function is_edit($mod)
{
    $.post("index.php/main/is_edit/user_permissions/is_edit", {
        module : $mod
        
    }, function(r){
       if(r==1)
           {
             $("#btnSave").removeAttr("disabled", "disabled");
           }
       else{
             $("#btnSave").attr("disabled", "disabled");
       }
       
    }, "json");

}
    
function set_edit(bc){
   loding();
    $.post("index.php/main/get_data/m_branch", {
        bc : bc
    }, function(res){
     
        $("#code_").val(res[0].bc);
        $("#code").val(res[0].bc);
	    $("#code").attr("readonly", true);
        $("#name").val(res[0].name);
        $("#cluster").val(res[0].cl);
        $("#fax").val(res[0].fax);
        $("#bc").val(res[0].bc);
        $("#address").val(res[0].address);
        $("#email").val(res[0].email);
        $("#tp").val(res[0].tp);
        $("#current_acc").val(res[0].current_acc);
        $("#cash_customer_limit").val(res[0].cash_customer_limit);
        //$("#def_customer").val(res[0].def_customer);
        $("#def_cash_customer").val(res[0].def_cash_customer);
        $("#def_customer_des").val(res[0].customer_name);
        $("#def_loan_customer").val(res[0].def_loan_customer);
        $("#def_loan_customer_des").val(res[0].loan_customer_name);
       
        $("#current_acc").val(res[0].current_acc);
        $("#acc_dtls").val(res[0].Acc_name);
        $("#def_sales_store").val(res[0].def_sales_store);
        $("#store_id").val(res[0].stores_name);
        $("#category_id").val(res[0].category_name);
        $("#def_sales_category").val(res[0].def_sales_category);
        $("#group_dtls").val(res[0].group_name); 
        $("#def_sales_group").val(res[0].def_sales_group); 
        // is_edit('010');
        loding(); 
        input_active();
    }, "json");
}