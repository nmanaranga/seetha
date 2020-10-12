$(document).ready(function () {
    
    $("#tabs").tabs();
    load_data();

    $("#is_sales_cat").click(function () {
        check_sales_cat();
    });

    //  $("#auto_item_id").click(function () {
    //     check_sales_cat();
    // });

    $(".gen_ic").click(function(){
        $(".gen_ic").prop("checked", false);
        $(this).prop("checked", true);
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

    // $("#def_store").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
    //     width: 350,
    //     multiple: false,
    //     matchContains: true,
    //     formatItem: formatItems,
    //     formatResult: formatItemsResult
    // });


    // $("#def_store").keydown(function (e) {
    //     if (e.keyCode == 13) {
    //         set_cus_values($(this));
    //     }
    // });

    // $("#def_store").blur(function () {
    //     set_cus_values($(this));
    // });

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
                 load_def_store();
            }
        }); 

        if(e.keyCode == 46){
            $("#pur_store").val("");
            $("#desc_pur_store").val("");
        }
    });

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

    $("#sales_cat").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search12").val();
            load_sales_category();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);
        }

       $("#pop_search12").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_sales_category();
            }
        }); 

        if(e.keyCode == 46){
            $("#sales_cat").val("");
            $("#desc_sales_cat").val("");
        }
    });

    $("#sales_group").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search2").val();
            load_group_sales();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search2').focus()", 100);
        }

       $("#pop_search2").keyup(function(e){            
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_group_sales();
            }
        }); 

        if(e.keyCode == 46){
            $("#sales_group").val("");
            $("#desc_sales_group").val("");
        }
    });
});


function load_def_store(){

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_stores",
      field:"code",
      field2:"description",
      preview2:"Name",
      preview2:"Description",
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

function load_purchase_store(){

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_stores",
      field:"code",
      field2:"description",
      add_query:" AND purchase='1'",
      preview2:"Name",
      preview2:"Description",
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

function load_sales_store(){

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_stores",
      field:"code",
      field2:"description",
      add_query:" AND sales='1'",
      preview2:"Name",
      preview2:"Description",
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

function load_sales_category(){

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"r_sales_category",
      field:"code",
      field2:"description",
      preview2:"Name",
      preview2:"Description",
      search : $("#pop_search12").val() 
  }, function(r){
      $("#sr12").html(r);
      settings_sales_category();      
  }, "text");
    }

    function settings_sales_category(){
    $("#item_list .cl").click(function(){        
        $("#sales_cat").val($(this).children().eq(0).html());
        $("#desc_sales_cat").val($(this).children().eq(1).html());
        $("#pop_close12").click();                
    })    
}

function load_group_sales(){

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"r_groups",
      field:"code",
      field2:"name",
      preview2:"Name",
      preview2:"Description",
      search : $("#pop_search2").val() 
  }, function(r){
      $("#sr2").html(r);
      settings_group_sales();      
  }, "text");
    }

    function  settings_group_sales(){
    $("#item_list .cl").click(function(){        
        $("#sales_group").val($(this).children().eq(0).html());
        $("#desc_sales_group").val($(this).children().eq(1).html());
        $("#pop_close2").click();                
    })    
}



function load_salesman(){

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_employee_category",
      field:"code",
      field2:"description",
      preview2:"Name",
      preview2:"Description",
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

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_employee_category",
      field:"code",
      field2:"description",
      preview2:"Name",
      preview2:"Description",
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

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_employee_category",
      field:"code",
      field2:"description",
      preview2:"Name",
      preview2:"Description",
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

    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_employee_category",
      field:"code",
      field2:"description",
      preview2:"Name",
      preview2:"Description",
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

function check_sales_cat() {
    if ($("#is_sales_cat").is(':checked')) {
        // alert("checked");
        $("#sales_cat").removeAttr("readonly");

    } else {
        // alert("un-checked");
        $("#sales_cat").attr("readonly", "readonly");
    }
}




function save() {
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid) {
            //alert(pid);
            if (pid == 1) {
                location.reload();
            } else if (pid == 2) {
                alert("No permission to add data.");
            } else if (pid == 3) {
                alert("No permission to edit data.");
            } else {
                alert("Error : 1\n" + pid);
            }
            loding();
        }
    });
}


// function reload_form() {
//     setTimeout(function () {
//         window.location = '';
//     }, 50);
// }


function check_code() {
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/t_cash_sales_sum/check_code", {
        code: code
    }, function (res) {
        if (res == 1) {
            if (confirm("This code (" + code + ") already added. \n\n Do you need edit it?")) {
                set_edit(code);
            } else {
                $("#code").val('');
                $("#code").attr("readonly", false);
            }
        }
        loding();
    }, "text");
}

function validate() {

   
    if($("#auto_dep_id").is(':checked')&&($("#auto_dep").val() == "")){
        set_msg("Please enter auto department number format.");
        return false;
   
    }else if($("#auto_main_cat_id").is(':checked')&&($("#auto_main_cat").val() == "")){
        set_msg("Please enter auto main category number format.");
        return false;
   
    }else if($("#auto_sub_cat_id").is(':checked')&&($("#auto_sub_cat").val() == "")){
        set_msg("Please enter auto sub category number format.");
        return false;
   
    }else if($("#auto_unit_id").is(':checked')&&($("#auto_unit").val() == "")){
        set_msg("Please enter auto unit number format.");
        return false;
   
    }else if($("#auto_brand_id").is(':checked')&&($("#auto_brand").val() == "")){
        set_msg("Please enter auto brand number format.");
        return false;
   
    }else if($("#auto_clr_id").is(':checked')&&($("#auto_clr").val() == "")){
        set_msg("Please enter auto color number format.");
        return false;
   
    }else if($("#auto_area_id").is(':checked')&&($("#auto_area").val() == "")){
        set_msg("Please enter auto area number format.");
        return false;
   
    }else if($("#auto_route_id").is(':checked')&&($("#auto_route").val() == "")){
        set_msg("Please enter auto route number format.");
        return false;
   
    }else if($("#auto_town_id").is(':checked')&&($("#auto_town").val() == "")){
        set_msg("Please enter auto town number format.");
        return false;
   
    }else if($("#auto_supplier_id").is(':checked')&&($("#auto_supplier").val() == "")){
        set_msg("Please enter auto supplier number format.");
        return false;
   
    }else if($("#auto_national_id").is(':checked')&&($("#auto_national").val() == "")){
        set_msg("Please enter auto nationality number format.");
        return false;
   
    }else if($("#auto_c_category_id").is(':checked')&&($("#auto_c_category").val() == "")){
        set_msg("Please enter auto customer category number format.");
        return false;
   
    }else if($("#auto_c_type_id").is(':checked')&&($("#auto_c_type").val() == "")){
        set_msg("Please enter auto customer category type number format.");
        return false;
   
    }else if(($("#is_sales_cat").is(':checked'))&& ($("#sales_cat").val() == "")){
            set_msg("Please select sales category.");
            return false;

    }else if($("#is_sales_group").is(':checked')&& ($("#sales_group").val() == "")){
            set_msg("Please select sales group.");
            return false;

    }else if($("#auto_item_id").is(':checked')&& ($("#auto_item").val() == "")){
            set_msg("Please enter auto item id number format.");
            return false;
    }else if($("#is_sales_man").is(':checked')&& ($("#sales_man").val() == "")){
            set_msg("Please select salesman");
            return false;
    }else if($("#is_collection_off").is(':checked')&& ($("#collection_off").val() == "")){
            set_msg("Please select Collection officer");
            return false;
    }else if($("#is_driver").is(':checked')&& ($("#driver").val() == "")){
            set_msg("Please select driver");
            return false;
    }else if($("#is_helper").is(':checked')&& ($("#helper").val() == "")){
            set_msg("Please select helper");
            return false;
    }else if(!$("#multi_store").is(':checked')){
        if(($("#def_store").val() == "") ||($("#pur_store").val() == "")||($("#sales_store").val() == "")){
          set_msg("Please select stores.");
          return false;
          }else{
                return true;
          }  
    }else{
        return true;
    }
    
}


function set_delete(code) {
    if (confirm("Are you sure delete " + code + "?")) {
        loding();
        $.post("index.php/main/delete/t_cash_sales_sum", {
            code: code
        }, function (res) {
            if (res == 1) {
                get_data_table();
            } else if (res == 2) {
                alert("No permission to delete data.");
            } else {
                alert("Item deleting fail.");
            }
            loding();
        }, "text");
    }
}

function load_data() {
    loding();
    $.post("index.php/main/get_data/default_settings", {
    }, function (r) {
         loding();
        // alert(r);
        

        if(r.option_stock!=2){

             if (r.option_stock[0].use_sub_items == 1) {
            $("#sub_item").attr("checked", "checked");
            } else  {
                $("#sub_item").removeAttr("checked");
            }

            check_sales_cat();

            if (r.option_stock[0].use_serial_no_items == 1) {
            $("#serial_no").attr("checked", "checked");
            } else {
                $("#serial_no").removeAttr("checked");
            } 

            if (r.option_stock[0].use_item_batch == 1) {
            $("#item_batch").attr("checked", "checked");
            } else {
                $("#item_batch").removeAttr("checked");
            }

            if (r.option_stock[0].use_additional_items == 1) {
            $("#add_item").attr("checked", "checked");
            } else {
                $("#add_item").removeAttr("checked");
            }



            if(r.option_stock[0].gen_itemcode_by_department == 1){
                $("#gen_itemcode_by_department").prop('checked', true);
            }else if(r.option_stock[0].gen_itemcode_by_maincat == 1){
                $("#gen_itemcode_by_maincat").prop('checked', true);
            }else if(r.option_stock[0].gen_itemcode_by_standard == 1){
                $("#gen_itemcode_by_standard").prop('checked', true);
            }else if(r.option_stock[0].gen_itemcode_by_normal == 1){
                $("#gen_itemcode_by_normal").prop('checked', true);
            }else if(r.option_stock[0].gen_itemcode_by_subcat == 1){
                $("#gen_itemcode_by_subcat").prop('checked', true);
            } else if(r.option_stock[0].gen_supplier_by_auto_idelse == 1){
                $("#gen_supplier_by_auto_idelse").prop('checked', true);
            }

            // if (r.option_stock[0].use_multi_stores == 1) {
            //     $("#multi_store").attr("checked", "checked");
            // } else {
            //     $("#multi_store").removeAttr("checked");
            // }

            // $("#def_store").val(r.option_stock[0].def_store_code);
            // $("#desc_def_store").val(r.option_stock[0].def_m_store);
            // $("#pur_store").val(r.option_stock[0].def_purchase_store_code);
            // $("#desc_pur_store").val(r.option_stock[0].def_p_store);
            // $("#sales_store").val(r.option_stock[0].def_sales_store_code);
            // $("#desc_sales_store").val(r.option_stock[0].def_s_store);

            // if (r.option_stock[0].use_sales_category == 1) {
            //     $("#is_sales_cat").attr("checked", "checked");
            // } else {
            //     $("#is_sales_cat").removeAttr("checked");
            // }

            // $("#sales_cat").val(r.option_stock[0].def_sales_category_code);
            // $("#desc_sales_cat").val(r.option_stock[0].def_s_category);

            // if (r.option_stock[0].use_sales_group == 1) {
            //     $("#is_sales_group").attr("checked", "checked");
            // } else {
            //     $("#is_sales_group").removeAttr("checked");
            // }

            // $("#sales_group").val(r.option_stock[0].sales_group_code);
            // $("#desc_sales_group").val(r.option_stock[0].def_gs_category);
            
 
   
        }
       

        if(r.option_sales!=2){
            
           
            if (r.option_sales[0].use_salesman == 1) {
            $("#is_sales_man").attr("checked", "checked");
            } else {
                $("#is_sales_man").removeAttr("checked");
            }

            $("#sales_man").val(r.option_sales[0].def_salesman);
            $("#desc_sales_man").val(r.option_sales[0].def_salesman_code);

            if (r.option_sales[0].use_collection_officer == 1) {
                $("#is_collection_off").attr("checked", "checked");
            } else {
                $("#is_collection_off").removeAttr("checked");
            }

            $("#collection_off").val(r.option_sales[0].def_collection_officer);
            $("#desc_collection_off").val(r.option_sales[0].def_collection_officer_code);

            if (r.option_sales[0].def_driver == 03) {
                $("#is_driver").attr("checked", "checked");
            } else {
                $("#is_driver").removeAttr("checked");
            }
           $("#driver").val(r.option_sales[0].def_driver);
           $("#desc_driver").val(r.option_sales[0].def_driver_code);

            if (r.option_sales[0].def_helper == 04) {
                $("#is_helper").attr("checked", "checked");
            } else {
                $("#is_helper").removeAttr("checked");
            }
            $("#helper").val(r.option_sales[0].def_helper);
            $("#desc_helper").val(r.option_sales[0].def_helper_code);


            if (r.option_sales[0].def_use_seettu == 1) {
                $("#use_seettu").attr("checked", "checked");
            } else {
                $("#use_seettu").removeAttr("checked");
            }

            if (r.option_sales[0].def_use_hp == 1) {
                $("#use_hp").attr("checked", "checked");
            } else {
                $("#use_hp").removeAttr("checked");
            }

            if (r.option_sales[0].def_use_service == 1) {
                $("#use_service").attr("checked", "checked");
            } else {
                $("#use_service").removeAttr("checked");
            }

            if (r.option_sales[0].def_use_cheque == 1) {
                $("#use_cheque").attr("checked", "checked");
            } else {
                $("#use_cheque").removeAttr("checked");
            }

            if (r.option_sales[0].def_use_giftV == 1) {
                $("#use_gift").attr("checked", "checked");
            } else {
                $("#use_gift").removeAttr("checked");
            }

            if (r.option_sales[0].def_use_privilege == 1) {
                $("#use_privilage").attr("checked", "checked");
            } else {
                $("#use_privilage").removeAttr("checked");
            }

        }

        if(r.option_account!=2){

            if (r.option_account[0].sep_sales_dis == 1) {
                $("#sales_discount_to_separate_acc").attr("checked", "checked");
            } else {
                $("#sales_discount_to_separate_acc").removeAttr("checked");
            }

            if (r.option_account[0].auto_dep_id == 1) {
              $("#sales_return_to_separate_acc").attr("checked", "checked");
            } else {
              $("#sales_return_to_separate_acc").removeAttr("checked");
            }

            if (r.option_account[0].is_multi_chq_pay_voucher == 1) {
              $("#is_m_chq").attr("checked", "checked");
            } else {
              $("#is_m_chq").removeAttr("checked");
            }
            
            $("#open_bal_date").val(r.option_account[0].open_bal_date);
        }

        if(r.auto_deptm!=2){
             if (r.auto_deptm[0].is_auto_genarate_department == 1) {
            $("#auto_dep_id").attr("checked", "checked");
            } else {
                $("#auto_dep_id").removeAttr("checked");
            }

            $("#auto_dep").val(r.auto_deptm[0].department_code_type);

            if (r.auto_deptm[0].is_auto_genarate_category == 1) {
                $("#auto_main_cat_id").attr("checked", "checked");
            } else {
                $("#auto_main_cat_id").removeAttr("checked");
            }

             $("#auto_main_cat").val(r.auto_deptm[0].category_code_type);

             
            if (r.auto_deptm[0].is_auto_genarate_s_category == 1) {
            $("#auto_sub_cat_id").attr("checked", "checked");
            } else {
                $("#auto_sub_cat_id").removeAttr("checked");
            }
            
             $("#auto_sub_cat").val(r.auto_deptm[0].s_category_code_type); 

            if (r.auto_deptm[0].is_auto_genarate_unit == 1) {
            $("#auto_unit_id").attr("checked", "checked");
            } else {
                $("#auto_unit_id").removeAttr("checked");
            }

              $("#auto_unit").val(r.auto_deptm[0].unit_code_type); 

            if (r.auto_deptm[0].is_auto_genarate_brand == 1) {
            $("#auto_brand_id").attr("checked", "checked");
            } else {
                $("#auto_brand_id").removeAttr("checked");
            }
              $("#auto_brand").val(r.auto_deptm[0].brand_code_type);

            if (r.auto_deptm[0].is_auto_genarate_color == 1) {
            $("#auto_clr_id").attr("checked", "checked");
            } else {
                $("#auto_clr_id").removeAttr("checked");
            }

              $("#auto_clr").val(r.auto_deptm[0].color_code_type); 

            if (r.auto_deptm[0].is_auto_genarate_area == 1) {
            $("#auto_area_id").attr("checked", "checked");
            } else {
                $("#auto_area_id").removeAttr("checked");
            }

                $("#auto_area").val(r.auto_deptm[0].area_code_type);  

            if (r.auto_deptm[0].is_auto_genarate_root == 1) {
            $("#auto_route_id").attr("checked", "checked");
            } else {
                $("#auto_route_id").removeAttr("checked");
            }

              $("#auto_route").val(r.auto_deptm[0].root_code_type);

            if (r.auto_deptm[0].is_auto_genarate_town == 1) {
            $("#auto_town_id").attr("checked", "checked");
            } else {
                $("#auto_town_id").removeAttr("checked");
            }

              $("#auto_town").val(r.auto_deptm[0].town_code_type);  
            
            if (r.auto_deptm[0].is_auto_genarate_nationality == 1) {
            $("#auto_national_id").attr("checked", "checked");
            } else {
                $("#auto_national_id").removeAttr("checked");
            }

              $("#auto_national").val(r.auto_deptm[0].nationality_code_type);  

            if (r.auto_deptm[0].is_auto_genarate_sup_cat == 1) {
            $("#auto_supplier_id").attr("checked", "checked");
            } else {
                $("#auto_supplier_id").removeAttr("checked");
            } 

              $("#auto_supplier").val(r.auto_deptm[0].sup_cat_code_type);  
 
            if (r.auto_deptm[0].is_auto_genarate_cus_cat == 1) {
            $("#auto_c_category_id").attr("checked", "checked");
            } else {
                $("#auto_c_category_id").removeAttr("checked");
            } 

              $("#auto_c_category").val(r.auto_deptm[0].cus_cat_code_type); 

             if (r.auto_deptm[0].is_auto_genarate_cus_type == 1) {
            $("#auto_c_type_id").attr("checked", "checked");
            } else {
                $("#auto_c_type_id").removeAttr("checked");
            }  

              $("#auto_c_type").val(r.auto_deptm[0].cus_type_code_type);  


        }

        if(r.option_common!=2){
            if (r.option_common[0].is_print_cur_time_rec == 1) {
              $("#print_cur_time").attr("checked", "checked");
              } else {
                  $("#print_cur_time").removeAttr("checked");
            }

            if (r.option_common[0].is_print_save_time_rec == 1) {
              $("#print_sav_time").attr("checked", "checked");
              } else {
                  $("#print_sav_time").removeAttr("checked");
            }

             $("#type").val(r.option_common[0].heading_align);  
          }
       

      
       

       /* if(r.auto_sub_cat!=2){
           if (r.auto_sub_cat[0].is_auto_maincat_id == 1) {
               $("#auto_main_cat_id").attr("checked", "checked");
           } else {
               $("#auto_main_cat_id").removeAttr("checked");
           }
        }*/

    }, "json");
}


function set_cus_values(f) {
    var v = f.val();
    v = v.split("|");
    if (v.length == 2) {
        f.val(v[0]);
        $("#desc_def_store").val(v[1]);
    }
}

function formatItems(row) {
    return "<strong> " + row[0] + "</strong> | <strong> " + row[1] + "</strong>";
}

function formatItemsResult(row) {
    return row[0] + "|" + row[1];
}