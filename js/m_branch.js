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

    $("#def_store_create").click(function(){
     window.open("?action=m_stores","_blank");  
 });

    $("#sales_cat_create").click(function(){
     window.open("?action=r_sales_category","_blank");  
 });

    $("#sales_group_create").click(function(){
     window.open("?action=r_groups","_blank");  
 });

    /*---------------------------------------*/

    $("#show_cat").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search2").val();
            load_category();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search2').focus()", 100);
        }
        $("#pop_search2").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) {
                load_category();
            }   
        });

        if(e.keyCode == 46){
            $("#show_cat").val("");
            $("#desc_cat").val("");
        }
    });

    $("#show_town").keydown(function(e){
        if(e.keyCode == 112){
          $("#pop_search7").val();
          load_town();
          $("#serch_pop7").center();
          $("#blocker2").css("display","block");
          setTimeout("$('#pop_search7').focus()", 100);   
      }
      $("#pop_search7").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112){
            load_town();
        }
    });

      if(e.keyCode == 46){
        $("#show_town").val("");
        $("#desc_town").val("");
    }
});

    $("#show_area").keydown(function(e){
        if(e.keyCode == 112){
          $("#pop_search").val();
          load_area();
          $("#serch_pop").center();
          $("#blocker").css("display","block");
          setTimeout("$('#pop_search').focus()", 100);   
      }
      $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112){
            load_area();
        }
    });

      if(e.keyCode == 46){
        $("#show_area").val("");
        $("#desc_area").val("");
    }
});

    $("#show_nation").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search14").val();
            load_nation();
            $("#serch_pop14").center();
            $("#blocker").css("display","block");
            setTimeout("$('#pop_search14').focus()",100);
        }
        $("#pop_search14").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112){
                load_nation();
            }
        });

        if(e.keyCode == 46){
            $("#show_nation").val("");
            $("#desc_nation").val("");
        }


    });
    $("#show_route").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search15").val();
            load_route();
            $("#serch_pop15").center();
            $("#blocker").css("display","block");
            setTimeout("$('#pop_search15').focus()",100);
        }
        $("#pop_search15").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112){
                load_route();
            } 
        });

        if(e.keyCode == 46){
            $("#show_route").val("");
            $("#desc_route").val("");
        }
        
    });

    $("#show_status").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search4").val();
            cus_status();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('pop_search4').focus()",100);
        }

        $("#pop_search4").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112){
                cus_status();
            }      
        });
        if(e.keyCode == 46){
            $("#show_status").val("");
            $("#desc_status").val("");
            $("#desc_status").css("background-color","");
            $("#desc_status").addClass("hid_value");
            $("#desc_status").removeClass("input_txt");
        }

    });

    /*---------------------------------------*/


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

    $("#sales_man").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search6").val();
            load_salesman();
            $("#serch_pop6").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search6').focus()", 100);
        }

        $("#pop_search6").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_salesman();
           }
       }); 

        if(e.keyCode == 46){
            $("#sales_man").val("");
            $("#desc_sales_man").val("");
        }
    });
    $("#collection_off").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search4").val();
            load_c_off();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }

        $("#pop_search4").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_c_off();
           }
       }); 

        if(e.keyCode == 46){
            $("#collection_off").val("");
            $("#desc_collection_off").val("");
        }
    });

    $("#driver").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search10").val();
            load_driver();
            $("#serch_pop10").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search10').focus()", 100);
        }

        $("#pop_search10").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_driver();
           }
       }); 

        if(e.keyCode == 46){
            $("#driver").val("");
            $("#desc_driver").val("");
        }
    });

    $("#helper").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search11").val();
            load_helper();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }

        $("#pop_search11").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_helper();
           }
       }); 

        if(e.keyCode == 46){
            $("#helper").val("");
            $("#desc_helper").val("");
        }
    });


    //stores-------------------------------------
    $("#def_store").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search13").val();
            load_def_store();
            $("#serch_pop13").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search13').focus()", 100);
        }

        $("#pop_search13").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_def_store();
           }
       }); 

        if(e.keyCode == 46){
            $("#def_store").val("");
            $("#desc_def_store").val("");
        }
    });

    $("#sales_order_store").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search13").val();
            load_def_s_order_store();
            $("#serch_pop13").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search13').focus()", 100);
        }

        $("#pop_search13").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_def_store();
           }
       }); 

        if(e.keyCode == 46){
            $("#sales_order_store").val("");
            //$("#desc_def_store").val("");
        }
    });
    //-------------------------------------------

    //purchase store----------------------------

    $("#pur_store").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search14").val();
            load_purchase_store();
            $("#serch_pop14").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search14').focus()", 100);
        }

        $("#pop_search14").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_purchase_store();
           }
       }); 

        if(e.keyCode == 46){
            $("#pur_store").val("");
            $("#desc_pur_store").val("");
        }
    });

     //-------------------------------------------

    //sales store----------------------------
    $("#sales_store").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search15").val();
            load_sales_store();
            $("#serch_pop15").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search15').focus()", 100);
        }

        $("#pop_search15").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_sales_store();
           }
       }); 

        if(e.keyCode == 46){
            $("#sales_store").val("");
            $("#desc_sales_store").val("");
        }
    });
    //------------------------------------------

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

function settings8(){
    $("#item_list .cl").click(function(){        
        $("#def_cash_customer").val($(this).children().eq(0).html());
        $("#def_customer_des").val($(this).children().eq(1).html());
        $("#pop_close6").click();                
    })    
}

//stores------------------------
function load_def_store(){

    $.post("index.php/main/load_data/m_branch/load_all_stores", {
      search : $("#pop_search13").val() 
  }, function(r){
      $("#sr13").html(r);
      settings_def_store();      
  }, "text");
}

function settings_def_store(){
    $("#item_list .cl").click(function(){        
        $("#def_store").val($(this).children().eq(0).html());
        $("#desc_def_store").val($(this).children().eq(1).html());
        $("#pop_close13").click();                
    })    
}
function load_def_s_order_store(){

    $.post("index.php/main/load_data/m_branch/load_all_stores", {
      search : $("#pop_search13").val() 
  }, function(r){
      $("#sr13").html(r);
      settings_def_store2();      
  }, "text");
}

function settings_def_store2(){
    $("#item_list .cl").click(function(){        
        $("#sales_order_store").val($(this).children().eq(0).html());
        $("#pop_close13").click();                
    })    
}
//-------------------------------------
//purchase store-----------------------
function load_purchase_store(){

    $.post("index.php/main/load_data/m_branch/load_all_stores", {
      add_query:"AND purchase='1'",
      search : $("#pop_search14").val()
  }, function(r){
      $("#sr14").html(r);
      settings_purshase_store();      
  }, "text");
}

function settings_purshase_store(){
    $("#item_list .cl").click(function(){        
        $("#pur_store").val($(this).children().eq(0).html());
        $("#desc_pur_store").val($(this).children().eq(1).html());
        $("#pop_close14").click();                
    })    
}

//-------------------------------------
//sales  store-----------------------

function load_sales_store(){

    $.post("index.php/main/load_data/m_branch/load_all_stores", {
        add_query:"AND sales='1'",
        search : $("#pop_search15").val() 
    }, function(r){
      $("#sr15").html(r);
      settings_sales_store();      
  }, "text");
}

function settings_sales_store(){
    $("#item_list .cl").click(function(){        
        $("#sales_store").val($(this).children().eq(0).html());
        $("#desc_sales_store").val($(this).children().eq(1).html());
        $("#pop_close15").click();                
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
function load_salesman(){

    $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
        filter_emp_cat:"salesman",
        search : $("#pop_search6").val() 
    }, function(r){
      $("#sr6").html(r);
      settings_salesman();      
  }, "text");
}

function settings_salesman(){
    $("#item_list .cl").click(function(){        
        $("#sales_man").val($(this).children().eq(0).html());
        $("#desc_sales_man").val($(this).children().eq(1).html());
        $("#pop_close6").click();                
    })    
}

function load_c_off(){

    $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
        filter_emp_cat:"c_officer",
        search : $("#pop_search4").val() 
    }, function(r){
      $("#sr4").html(r);
      settings_c_off();      
  }, "text");
}

function settings_c_off(){
    $("#item_list .cl").click(function(){        
        $("#collection_off").val($(this).children().eq(0).html());
        $("#desc_collection_off").val($(this).children().eq(1).html());
        $("#pop_close4").click();                
    })    
}

function load_driver(){

 $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
    filter_emp_cat:"driver",
    search : $("#pop_search10").val() 
}, function(r){
  $("#sr10").html(r);
  settings_driver();      
}, "text");
}

function settings_driver(){
    $("#item_list .cl").click(function(){        
        $("#driver").val($(this).children().eq(0).html());
        $("#desc_driver").val($(this).children().eq(1).html());
        $("#pop_close10").click();                
    })    
}

function load_helper(){

    $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
        filter_emp_cat:"helper",
        search : $("#pop_search11").val() 
    }, function(r){
      $("#sr11").html(r);
      settings_helper();      
  }, "text");
}

function settings_helper(){
    $("#item_list .cl").click(function(){        
        $("#helper").val($(this).children().eq(0).html());
        $("#desc_helper").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
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

    delete_set_edit();
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
        $("#cash_float").val(res[0].cash_float);

        
        $("#current_acc").val(res[0].current_acc);
        $("#acc_dtls").val(res[0].Acc_name);

        if (res[0].use_multi_stores == 1) {
            $("#multi_store").attr("checked", "checked");
        } else {
            $("#multi_store").removeAttr("checked");
        }
        $("#def_store").val(res[0].def_sales_store);
        $("#desc_def_store").val(res[0].stores_name);

        $("#pur_store").val(res[0].def_purchase_store_code);
        $("#desc_pur_store").val(res[0].def_p_store);
        $("#sales_store").val(res[0].def_sales_store_code);
        $("#desc_sales_store").val(res[0].def_s_store);
        $("#sales_order_store").val(res[0].def_sales_order_store);

        if (res[0].use_sales_category == 1) {
            $("#is_sales_cat").attr("checked", "checked");
        } else {
            $("#is_sales_cat").removeAttr("checked");
        }
        $("#category_id").val(res[0].category_name);
        $("#def_sales_category").val(res[0].def_sales_category);

        if (res[0].use_sales_group == 1) {
            $("#is_sales_group").attr("checked", "checked");
        } else {
            $("#is_sales_group").removeAttr("checked");
        }
        $("#group_dtls").val(res[0].group_name); 
        $("#def_sales_group").val(res[0].def_sales_group);

        if (res[0].use_salesman == 1) {
            $("#is_sales_man").attr("checked", "checked");
        } else {
            $("#is_sales_man").removeAttr("checked");
        }

        $("#sales_man").val(res[0].def_salesman);
        $("#desc_sales_man").val(res[0].def_salesman_code);

        if (res[0].use_collection_officer == 1) {
            $("#is_collection_off").attr("checked", "checked");
        } else {
            $("#is_collection_off").removeAttr("checked");
        }

        $("#collection_off").val(res[0].def_collection_officer);
        $("#desc_collection_off").val(res[0].def_collection_officer_code);

        if (res[0].use_driver == 1) {
            $("#is_driver").attr("checked", "checked");
        } else {
            $("#is_driver").removeAttr("checked");
        }
        $("#driver").val(res[0].def_driver);
        $("#desc_driver").val(res[0].def_driver_code);

        if (res[0].use_helper == 1) {
            $("#is_helper").attr("checked", "checked");
        } else {
            $("#is_helper").removeAttr("checked");
        }
        $("#helper").val(res[0].def_helper);
        $("#desc_helper").val(res[0].def_helper_code);

        $("#open_bal_date").val(res[0].open_bal_date);

        if(res[0].def_use_opening_hp== 1){
         $("#def_use_opening_hp").prop('checked', true);
         $("#no_of_opening_hp").val(res[0].no_of_opening_hp); 
     }

     $("#desc_cat").val(res[0].cat_desc);
     $("#desc_town").val(res[0].town_desc);
     $("#desc_area").val(res[0].area_desc);
     $("#desc_route").val(res[0].root_desc);
     $("#desc_nation").val(res[0].nation_desc);
     $("#desc_status").val(res[0].status_desc);

     $("#show_cat").val(res[0].def_category);
     $("#show_town").val(res[0].def_town);
     $("#show_area").val(res[0].def_area);
     $("#show_route").val(res[0].def_route);
     $("#show_nation").val(res[0].def_nationality);
     $("#show_status").val(res[0].def_status);

     if(res[0].def_status == "" || res[0].def_status == null){
        $("#show_status").val("");
        $("#desc_status").css("background-color","");
        $("#desc_status").val("");
        $("#desc_status").addClass("hid_value");
        $("#desc_status").removeClass("input_txt");

    }else{
        $("#show_status").val(res[0].def_status);
        $("#desc_status").val(res[0].status_desc);
        $("#desc_status").removeClass("hid_value");
        $("#desc_status").addClass("input_txt");
        $("#desc_status").css("background-color",res[0].color);
    }






        // is_edit('010');
        loding(); 
        input_active();
    }, "json");
}

function delete_set_edit(){

    $("#code_").val("");
    $("#code").val("");
    $("#code").attr("readonly", true);
    $("#name").val("");
    $("#cluster").val("");
    $("#fax").val("");
    $("#bc").val("");
    $("#address").val("");
    $("#email").val("");
    $("#tp").val("");
    $("#current_acc").val("");
    $("#cash_customer_limit").val("");
        //$("#def_customer").val(res[0].def_customer);
        $("#def_cash_customer").val("");
        $("#def_customer_des").val("");

        
        $("#current_acc").val("");
        $("#acc_dtls").val("");
        $("#def_sales_store").val("");
        $("#store_id").val("");
        $("#category_id").val("");
        $("#def_sales_category").val("");
        $("#group_dtls").val(""); 
        $("#def_sales_group").val("");


        $("#def_use_opening_hp").prop('checked', false);
        $("#no_of_opening_hp").val(""); 
        
    }

    /*-----------------------------------*/ 


    function load_category(){
       $.post("index.php/main/load_data/utility/f1_selection_list",{
        data_tbl:"r_cus_category",
        field:"code",
        field2:"description",
        search:$("#pop_search2").val()
    },function(r){
        $("#sr2").html(r);
        cat_settings();
    },"text");
   }

   function cat_settings(){
    $("#item_list .cl").click(function(){
        $("#show_cat").val($(this).children().eq(0).html());
        $("#desc_cat").val($(this).children().eq(1).html());
        $("#pop_close2").click();
    })
}

function load_town(){
    $.post("index.php/main/load_data/utility/f1_selection_list",{
        data_tbl:"r_town",
        field:"code",
        field2:"description",
        search:$("#pop_search7").val()
    },function(r){
        $("#sr7").html(r);
        town_settings();
    },"text");
}

function town_settings(){
    $("#item_list .cl").click(function(){
        $("#show_town").val($(this).children().eq(0).html());
        $("#desc_town").val($(this).children().eq(1).html());
        $("#pop_close7").click();
    })
}
function load_area(){
    $.post("index.php/main/load_data/utility/f1_selection_list",{
        data_tbl:"r_area",
        field:"code",
        field2:"description",
        search:$("#pop_search").val()
    },function(r){
        $("#sr").html(r);
        area_settings();
    },"text");
}

function area_settings(){
    $("#item_list .cl").click(function(){
        $("#show_area").val($(this).children().eq(0).html());
        $("#desc_area").val($(this).children().eq(1).html());
        $("#pop_close").click();
    })
}
function load_nation(){
    $.post("index.php/main/load_data/utility/f1_selection_list",{
        data_tbl:"r_nationality",
        field:"code",
        field2:"description",
        search:$("#pop_search14").val()
    },function(r){
        $("#sr14").html(r);
        nation_settings();
    },"text");
}

function nation_settings(){
   $("#item_list .cl").click(function(){
       $("#show_nation").val($(this).children().eq(0).html());
       $("#desc_nation").val($(this).children().eq(1).html());
       $("#pop_close14").click();
   });
}

function load_route(){
    $.post("index.php/main/load_data/utility/f1_selection_list",{
        data_tbl:"r_root",
        field:"code",
        field2:"description",
        search:$("#pop_search15").val()
    },function(r){
        $("#sr15").html(r);
        route_settings();
    },"text");
}

function route_settings(){

    $("#item_list .cl").click(function(){
        $("#show_route").val($(this).children().eq(0).html());
        $("#desc_route").val($(this).children().eq(1).html());
        $("#pop_close15").click();
    });
}


function cus_status(){
    $.post("index.php/main/load_data/utility/f1_selection_list",{
        data_tbl:"m_customer_status",
        field:"code",
        field2:"description",
        hid_field:"color",
        search:$("#pop_search4").val()
    },function(r){
        $("#sr4").html(r);
        cus_status_settings();
    },"text");
}

function cus_status_settings(){
    $("#item_list .cl").click(function(){
        $("#show_status").val($(this).children().eq(0).html());
        $("#desc_status").val($(this).children().eq(1).html());
        $("#desc_status").removeClass("hid_value");
        $("#desc_status").addClass("input_txt");
        $("#desc_status").css("background-color",$(this).children().find('input').val());
        $("#pop_close4").click();
    });
}



       /*-----------------------------------*/