var sub_cat;
var is_edit = 0;
var current_search_data;
var data_tbl;
var field;
var field2;
var hid_field;
var filter_value=0;

$(function() {
    $("#brand").change(function() {
        set_select("brand", "brand_des");
    });

    $("#sc").tableScroll({
        height: 200
    });

    $("#item_gen").click(function() {
        generate_code();
        //get_last_code();
    });

    input_active();

    $("#tgrid").tableScroll({height:80});

    $("#batch_item").click(function(){
        check_item_in_db($("#code").val(),'batch_item');
    });

    $("#serial_no").click(function(){
        check_item_in_db($("#code").val(),'serial_no');
    });

    $("#department").click(function() {
        $(this).addClass("input_active");
        $("#main_category").removeClass("input_active");
        $("#sub_category").removeClass("input_active");
        $("#unit").removeClass("input_active");
        $("#supplier").removeClass("input_active");
    });

    $("#main_category").click(function() {
        $(this).addClass("input_active");
        $("#department").removeClass("input_active");
        $("#sub_category").removeClass("input_active");
        $("#unit").removeClass("input_active");
        $("#supplier").removeClass("input_active");
    });

    $("#sub_category").click(function() {
        $(this).addClass("input_active");
        $("#department").removeClass("input_active");
        $("#main_category").removeClass("input_active");
        $("#unit").removeClass("input_active");
        $("#supplier").removeClass("input_active");
    });

    $("#unit").click(function() {
        $(this).addClass("input_active");
        $("#department").removeClass("input_active");
        $("#sub_category").removeClass("input_active");
        $("#main_category").removeClass("input_active");
        $("#supplier").removeClass("input_active");
    });

    $("#supplier").click(function() {
        $(this).addClass("input_active");
        $("#department").removeClass("input_active");
        $("#sub_category").removeClass("input_active");
        $("#main_category").removeClass("input_active");
        $("#unit").removeClass("input_active");
    });

    $("#department").focus(function() {
        $("#department").addClass("input_active");
    });


    $("#last_code").keydown(function(e){       
        if(e.keyCode==112){
            $("#pop_search6").val();
            load_last_code();
            $("#serch_pop6").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search6').focus()", 100);     
        }
        if(e.keyCode==46){
            $("#load_last_code").val("");
        }
        $("#pop_search6").keyup(function(e){ 
            if(e.keyCode==13){           
                load_last_code();
            }
        });
    });

    $("#department").keydown(function(a) {
        var b = a.keyCode || a.which;
        if (9 == b) {
            a.preventDefault();
            $("#main_category").focus();
            $("#department").removeClass("input_active");
            $("#main_category").addClass("input_active");
        }
        if(a.keyCode==13){
            get_last_code();
        }
    });

    $("#main_category").keydown(function(a) {
        var b = a.keyCode || a.which;
        if (9 == b) {
            a.preventDefault();
            $("#main_category").removeClass("input_active");
            $("#sub_category").addClass("input_active");
            $("#sub_category").focus();
        }
        if(a.keyCode==13){
            get_last_code();
        }
    });

    $("#sub_category").keydown(function(a) {
        var b = a.keyCode || a.which;
        if (9 == b) {
            a.preventDefault();
            $("#sub_category").removeClass("input_active");
            $("#unit").addClass("input_active");
            $("#unit").focus();
        }
        if(a.keyCode==13){
            get_last_code();
        }
    });

    $("#unit").keydown(function(a) {
        var b = a.keyCode || a.which;
        if (9 == b) {
            a.preventDefault();
            $("#unit").removeClass("input_active");
            $("#supplier").addClass("input_active");
            $("#supplier").focus();
        }
    });

    $("#supplier").keydown(function(a) {
        var b = a.keyCode || a.which;
        if (9 == b) {
            a.preventDefault();
            $("#supplier").removeClass("input_active");
            $("#code").focus();
        }

        if(a.keyCode==13){
            get_last_code();
        }
    });
});

$(document).ready(function() {
    $("#btndepartment").click(function(){
        window.open("?action=r_department","_blank");  
    });

    $("#btncategory").click(function(){
        window.open("?action=r_category","_blank");   
    });

    $("#btnsub_category").click(function(){
     window.open("?action=r_sub_cat","_blank");     
 });

    $("#btn_unit").click(function(){
       window.open("?action=r_units","_blank");
   });

    $("#btn_supplier").click(function(){
        window.open("?action=m_supplier","_blank"); 
    });

    $("#btn_brand").click(function(){
        window.open("?action=r_brand","_blank"); 
    });

    $("#tax_rate_pre").keydown(function(e){       
        if(e.keyCode==112){
            $("#pop_search12").val();
            load_tax();
            $("#serch_pop12").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search12').focus()", 100);     
        }
        if(e.keyCode==46){
            $("#tax_rate_pre").val("");
            $("#tax_rate").val("");
        }
        $("#pop_search12").keyup(function(e){            
            load_tax();
        });
    });


    $("#brand").keydown(function(e){       
        if(e.keyCode==112){
            $("#pop_search14").val($("#brand").val());
            load_data_brand();
            $("#serch_pop14").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search14').focus()", 100);     
        }
        if(e.keyCode==46){
            $("#brand").val("");
            $("#brand_des").val("");
        }
        $("#pop_search14").keyup(function(e){            
            load_data_brand();
            
        });
    });


    $("#department").keydown(function(e){
        if(e.keyCode==112){
            data_tbl=$(this).attr("data");
            field='code';
            field2='description';
            hid_field='code_gen';
            current_search_data=$(this).attr("data");
            load_items2(($(this).attr("data")),'code','description','code_gen',filter_value);
            $("#serch_pop2").center();
            $("#blocker2").css("display", "block");
            setTimeout("select_search2()", 100);
        }
    });


    $("#main_category").keydown(function(e){
        if($("#department").val()==""){
            filter_value=0;
        }else{
            filter_value=$("#department").val();
        }
        
        if(e.keyCode==112){
            data_tbl=$(this).attr("data");
            field='code';
            field2='description';
            hid_field='code_gen';

            current_search_data=$(this).attr("data");
            load_items2(($(this).attr("data")),'code','description','code_gen',filter_value);
            $("#serch_pop2").center();
            $("#blocker2").css("display", "block");
            setTimeout("select_search2()", 100);
        }
    });


    $("#sub_category").keydown(function(e){
        if($("#main_category").val()==""){
            filter_value=0;
        }else{
            filter_value=$("#main_category").val();
        }

        if(e.keyCode==112){
            data_tbl=$(this).attr("data");
            field='code';
            field2='description';
            hid_field='code_gen';
            current_search_data=$(this).attr("data");
            load_items2(($(this).attr("data")),'code','description','code_gen',filter_value);
            $("#serch_pop2").center();
            $("#blocker2").css("display", "block");
            setTimeout("select_search2()", 100);
        }
    });

    $("#unit").keydown(function(e){
        if(e.keyCode==112){
            if($("#is_gift").val()=="0"){
                data_tbl=$(this).attr("data");
                field='code';
                field2='description';
                hid_field='code_gen';
                current_search_data=$(this).attr("data");
                load_items2(($(this).attr("data")),'code','description','code_gen',filter_value);
                $("#serch_pop2").center();
                $("#blocker2").css("display", "block");
                setTimeout("select_search2()", 100);
            }else{
                set_msg("This is a Gift voucher code");
            }
        }
    });


    $("#supplier").keydown(function(e){
        if(e.keyCode==112){
            data_tbl=$(this).attr("data");
            field='code';
            field2='name';
            hid_field='code_gen';
            current_search_data=$(this).attr("data");
            load_items2(($(this).attr("data")),'code','name','code_gen',filter_value);
            $("#serch_pop2").center();
            $("#blocker2").css("display", "block");
            setTimeout("select_search2()", 100);
        }
    });


    $("#min_price").keyup(function(){
        if($("#purchase_price").val()==""){
            set_msg("Please enter purchase price","error");
            $("#min_price").val("");
        }else{
            price_precentage('min_price','purchase_price','min_price_p');    
        }
    });

    $("#max_price").keyup(function(){
       if($("#purchase_price").val()==""){
        set_msg("Please enter purchase price","error");
        $("#max_price").val("");
    }else{
        price_precentage('max_price','purchase_price','max_price_p');
    }
});

    $("#btnReset").click(function() {
        location.href = "?action=m_items";
    });

    $(".fo").focus(function() {
        set_cid($(this).attr("id"));
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
        load_items();
    });

    
    $("#pop_search").keyup(function(a) {
        if (13 != a.keyCode && 38 != a.keyCode && 40 != a.keyCode) load_items();
    });

    $("#pop_search2").keyup(function(a) {
        if (13 != a.keyCode && 38 != a.keyCode && 40 != a.keyCode && 112 != a.keyCode){
            load_items2(data_tbl,field,field2,hid_field,filter_value);
        }    
    });

    $("#pop_search").gselect();
    $("#pop_search2").gselect();

    $("#tabs").tabs();
    $("#code").blur(function() {
        check_code();
    });

    $("#code").keydown(function(e){
        if(e.keyCode == 13){
            check_code();
        }
    });

    $("#grid").tableScroll({
        height: 355
    });

    $("#btnSave1").click(function() {
        if (validate()) check_permission();
    });

    $("#department").autocomplete("index.php/main/load_data/r_department/auto_com", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#department").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values($(this));
    });

    $("#department").blur(function() {
        set_cus_values($(this));
    });

    $("#main_category").autocomplete("index.php/main/load_data/r_category/auto_com", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#main_category").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values2($(this));
    });

    $("#main_category").blur(function() {
        set_cus_values2($(this));
    });

    $("#sub_category").autocomplete("index.php/main/load_data/r_sub_cat/auto_com", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#sub_category").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values3($(this));
    });

    $("#sub_category").blur(function() {
        set_cus_values3($(this));
    });

    $("#unit").autocomplete("index.php/main/load_data/r_units/auto_com", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#unit").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values4($(this));
    });

    $("#unit").blur(function() {
        set_cus_values4($(this));
    });

    $("#supplier").autocomplete("index.php/main/load_data/m_supplier/auto_com", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#supplier").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values5($(this));
    });

    $("#supplier").blur(function() {
        set_cus_values5($(this));
    });

    $("#brand").autocomplete("index.php/main/load_data/r_brand/auto_com", {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });

    $("#brand").keydown(function(a) {
        if (13 == a.keyCode) set_cus_values6($(this));
    });

    $("#brand").blur(function() {
        set_cus_values6($(this));
    });

    $("#branch").click(function(){
        if($("#cluster").val()=="0"){
            set_msg("Please select cluster");
            return false;
        }
    });

    $("#store").click(function(){
        if($("#cluster").val()=="0"){
            set_msg("Please select cluster");
            return false;
        }
    });


    $("#branch").change(function(){
        $("#store").val("");
        if($("#branch").val()!=0){
            $.post("index.php/main/load_data/r_stock_report/get_stores_bc",{
                bc:$(this).val(),
            },function(res){
                $("#store").html(res);
            },'text');  

        }else if($("#cluster").val()!="0"){
            $.post("index.php/main/load_data/r_stock_report/get_stores_cl",{
                cl:$("#cluster").val(),
            },function(res){
                $("#store").html(res);
            },'text');  

        }else{
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

        if($("#cluster").val()!=0){
            path="index.php/main/load_data/r_stock_report/get_branch_name2";
            path_store="index.php/main/load_data/r_stock_report/get_stores_cl";
        }else{
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

    $("#avbl_qty").click(function(){
        if($("#code").val()!=""){
            get_available_stock();
        }else{
            set_msg("Please select item");
            return false;
        }
    });

    $("#srchee").keyup(function(){  
      $.post("index.php/main/load_data/utility/get_data_table", {
       code:$("#srchee").val(),
       tbl:"m_item",
       item:"Y"

   }, function(r){
       $("#grid_body").html(r);
   }, "text");
  });

    $("#department,#main_category,#sub_category,#unit,#supplier").keydown(function(a){
        var id = $(this).attr("id");
        if (a.keyCode == 8 || a.keyCode == 46){
            $("#"+id+"_des").val("");
            $("#"+id).val("");
        }
    });

    $("#department").keydown(function(a){
        var id = $(this).attr("id");
        if (a.keyCode == 8 || a.keyCode == 46){
            $("#department").val("");
            $("#department_des").val("");
            $("#is_gift").val("0");
            $("#unit").removeClass("hid_value");
            $("#unit").addClass("input_active");
        }
    });
});


function load_data_brand(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"r_brand",
        field:"code",
        field2:"description",
        search : $("#pop_search14").val() 
    }, function(r){      
        $("#sr14").html(r);
        settings_brand();
    }, "text");
}


function settings_brand(){
    $("#item_list .cl").click(function(){
        $("#brand").val($(this).children().eq(0).html());
        $("#brand_des").val($(this).children().eq(1).html());
        $("#pop_close14").click();
    });    
}


function set_cus_values(a) {
    var b = a.val();

    b = b.split("|");

    if (2 == b.length) {
        d = b[1];
        e = d.split("_");
        a.val(b[0]);
        $("#department_des").val(e[0]);
        $("#dep_codegen").val(e[1]);
    }

}

function set_cus_values2(a) {
    var b = a.val();

    b = b.split("|");

    if (2 == b.length) {
        d = b[1];
        e = d.split("_");
        a.val(b[0]);
        $("#main_category_des").val(e[0]);
        $("#mcat_codegen").val(e[1]);
    }
}

function set_cus_values3(a) {
    var b = a.val();

    b = b.split("|");
    
    if (2 == b.length) {
        d = b[1];
        e = d.split("_");
        a.val(b[0]);
        $("#sub_category_des").val(e[0]);
        $("#scat_codegen").val(e[1]);
    }
}

function set_cus_values4(a) {
    var b = a.val();

    b = b.split("|");

    if (2 == b.length) {
        d = b[1];
        e = d.split("_");
        a.val(b[0]);
        $("#unit_des").val(e[0]);
        $("#unit_codegen").val(e[1]);
    }
}

function set_cus_values5(a) {
    var b = a.val();

    b = b.split("|");

    if (2 == b.length) {
        d = b[1];
        e = d.split("_");
        a.val(b[0]);
        $("#supplier_des").val(e[0]);
        $("#s_codegen").val(e[1]);
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

function generate_code() {
    if($("#department_des").val()!="" && $("#main_category_des").val()!="" && $("#sub_category_des").val()!="" && $("#supplier_des").val()!="") 
    {
        var a = $("#dep_codegen").val() + $("#mcat_codegen").val() + $("#scat_codegen").val() + $("#unit_codegen").val() + $("#s_codegen").val();
        var b = $("#dep_codegen").val() + $("#mcat_codegen").val() + $("#scat_codegen").val() + $("#s_codegen").val();
        
        $.post("index.php/main/load_data/m_items/get_next_no",{
            pre:a,
            pre_gift:b,
            is_gift:$("#is_gift").val()
        },
        function(b){
            $("#code").val(a + b);
        }, "text");
    }
}

function check_permission() {
    $.post("index.php/main/load_data/user_permissions/get_permission", {
        module_id: "014",
        is_edit: is_edit
    }, function(a) {
        save();
    });
}

function check_delete_permission(a) {

    if (confirm("Are you sure delete " + a + "?")) {
        loding();
        $.post("index.php/main/delete/m_items", {
            code: a
        }, function(a) {
            if (1 == a){
                loding();
                delete_msg() 
            }else{
                set_msg(a);
            }
            
        }, "text");
    }

}

function save() {
    $("#form_").submit();
}


function load_tax(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_tax_setup",
        field:"description",
        field2:"rate",
        search : $("#pop_search12").val(),
        add_query: " AND is_cancel='0'",
        preview1 : 'Tax Type',
        preview2 : 'Rate',
        hid_field: 'code'
    }, function(r){      
        $("#sr12").html(r);
        settings_tax();
    }, "text");
}


function settings_tax(){
    $("#item_list .cl").click(function(){
        $("#tax_rate").val($(this).children().find('input').val());
        $("#tax_rate_pre").val($(this).children().eq(1).html());
        $("#pop_close12").click();
    });    
}


function check_code() {
    var a = $("#code").val();
    $.post("index.php/main/load_data/m_items/check_code", {
        code: a
    }, function(b) {
        if (1 == b) if (confirm("This code (" + a + ") already added. \n\n Do you need edit it?")) set_edit(a); else $("#code").val("");
    }, "text");
}

function get_data_table() {
    $.post("index.php/main/load_data/m_items/get_data_table", {}, function(a) {
        $("#grid_body").html(a);
    }, "text");
}

function validate() {


    if ($("#code").val() === $("#code").attr("title") || "" == $("#code").val()) {
        set_msg("Please enter code.","error");
        $("#code").focus();
        return false;
    } else if ($("#purchase_price").val()=="" || $("#min_price").val()=="" || $("#max_price").val()==""){
        set_msg("Price can not be empty","error");
    }else if ($("#description").val() === $("#description").attr("title") || "" == $("#description").val()) {
        set_msg("Please enter description.","error");
        $("#des").focus();
        return false;
    } else if ($("#description").val() === $("#code").val()) {
        set_msg("Please enter diffferent values for description & code.","error");
        $("#des").focus();
        return false;
    } else if ($("#brand").val()=="" || $("#brand_des").val()=="") {
        set_msg("Please enter brand for item","error");
        $("#des").focus();
        return false;
    } else if ("0" == $("#m_department option:selected").attr("value")) {
        set_msg("Please select department.","error");
        $("#m_department").focus();
        return false;
    } else if ("0" == $("#supplier").attr("value")) {
        set_msg("Please select a Supplier.","error");
        $("#txtSupplier").focus();
        return false;
    } else if ("0" == $("#m_main_category option:selected").attr("value")) {
        set_msg("Please select main category.","error");
        $("#m_main_category").focus();
        return false;
    } else if ("0" == $("#m_sub_category option:selected").attr("value")) {
        set_msg("Please select sub category.","error");
        $("#m_main_category").focus();
        return false;
    } else if ("0" == $("#m_units option:selected").attr("value")) {
        set_msg("Please select base unit.","error");
        $("#m_units").focus();
        return false;
    } else if(parseFloat($("#purchase_price").val()) =="" || parseFloat($("#purchase_price").val()) =="0" ){
        set_msg("Purchase price can't be 0","error");
        return false;
    }
    else if(parseFloat($("#min_price").val()) =="" || parseFloat($("#min_price").val()) =="0" ){
        set_msg("Min price can't be 0","error");
        return false;
    }
    else if(parseFloat($("#max_price").val()) =="" || parseFloat($("#max_price").val()) =="0" ){
        set_msg("Max price can't be 0","error");
        return false;
    }
    else if($("#tax_rate").val()==""){
        set_msg("Please select tax rate","error");
        return false;
    }
    else if(parseFloat($("#purchase_price").val()) > parseFloat($("#min_price").val())){
        set_msg("Min price should be greater than purchase price","error");
        return false;
    }
    else if(parseFloat($("#min_price").val()) > parseFloat($("#max_price").val())){
        set_msg("Max price should be greater than min price","error");
        return false;
    }
    else if (parseFloat($("#purchase_price").val()) > parseFloat($("#cost_price").val())) {
        set_msg("The purchase price should be less than Min and Max sales prices","error");
        $("#purchase_price").focus();
        return false;
    } else if (parseFloat($("#cost_price").val()) > parseFloat($("#max_sales").val())) {
        set_msg("Min sales should be less than max sales","error");
        return false;
    } else if ("" == $("input[name='image_name1']").val() && "" != $("input[name='userfile1']").val()) {
        set_msg("Please enter image name","error");
        $("input[name='image_name1']").focus();
        $("input[name='image_name1']").addClass("input_txtBorder");
        return false;
    } else if ("" != $("input[name='image_name1']").val() && "" == $("input[name='userfile1']").val()) {
        set_msg("Please select image","error");
        $("input[name='userfile1']").focus();
        return false;
    } else if ("" == $("input[name='image_name2']").val() && "" != $("input[name='userfile2']").val()) {
        set_msg("Please enter image name","error");
        $("input[name='image_name2']").focus();
        $("input[name='image_name2']").addClass("input_txtBorder");
        return false;
    } else if ("" != $("input[name='image_name2']").val() && "" == $("input[name='userfile2']").val()) {
        set_msg("Please select image","error");
        $("input[name='userfile2']").focus();
        return false;
    } else if ("" == $("input[name='image_name3']").val() && "" != $("input[name='userfile3']").val()) {
        set_msg("Please enter image name","error");
        $("input[name='image_name3']").focus();
        $("input[name='image_name3']").addClass("input_txtBorder");
        return false;
    } else if ("" != $("input[name='image_name3']").val() && "" == $("input[name='userfile3']").val()) {
        st_msg("Please select image","error");
        $("input[name='userfile3']").focus();
        return false;
    } else if ("" == $("input[name='image_name4']").val() && "" != $("input[name='userfile4']").val()) {
        set_msg("Please enter image name","error");
        $("input[name='image_name4']").focus();
        $("input[name='image_name4']").addClass("input_txtBorder");
        return false;
    } else if ("" != $("input[name='image_name4']").val() && "" == $("input[name='userfile4']").val()) {
        set_msg("Please select image","error");
        $("input[name='userfile4']").focus();
        return false;
    } else if ("" == $("input[name='image_name5']").val() && "" != $("input[name='userfile5']").val()) {
        set_msg("Please enter image name","error");
        $("input[name='image_name5']").focus();
        $("input[name='image_name5']").addClass("input_txtBorder");
        return false;
    } else if ($("#code").val().length > 14) {
        set_msg("Item code length should be 14","error");        
        return false;
    } else if ("" != $("input[name='image_name5']").val() && "" == $("input[name='userfile5']").val()) {
        set_msg("Please select image","error");
        $("input[name='userfile5']").focus();
        return false;
    } else return true;
}

function set_delete(a) {
    check_delete_permission(a);
}

function set_edit(a) {
    loding();
    $.post("index.php/main/get_data/m_items", {
        code: a
    }, function(a) {
        $(".picNameClass").empty();
        $("#code_").val(a.c.code);
        $("#code").val(a.c.code);
        if(a.cc.ddate==undefined){
            var str = a.c.action_date;
            $("#modified_date").val(str.split(" ")[0]);
        }else{
            $("#modified_date").val(a.cc.ddate);
        }

        $("#description").val(a.c.description);
        $("#department").val(a.c.department);
        $("#warranty").val(a.c.warranty);
        $("#tax_rate").val(a.tax[0].tax_rate);
        $("#tax_rate_pre").val(a.tax[0].rate);

        $.post("index.php/main/get_data/r_department", {
            code: a.c.department
        }, function(a) {
            $("#department_des").val(a.description);
        }, "json");

        if(a.c.department!="0030"){
            $("#unit").val(a.c.unit);
            $.post("index.php/main/get_data/r_units", {
                code: a.c.unit
            }, function(a) {
                $("#unit_des").val(a.description);
            }, "json");
        }

        $("#main_category").val(a.c.main_category);
        $.post("index.php/main/load_data/m_items/main_category", {
            code: a.c.main_category,
            dep:a.c.department
        }, function(a) {
            $("#main_category_des").val(a);
        }, "json");
        $("#sub_category").val(a.c.category);
        
        $.post("index.php/main/load_data/m_items/sub_category", {
            code: a.c.category,
            cat : a.c.main_category
        }, function(a) {
            $("#sub_category_des").val(a);
        }, "json");
        
        $("#inactive").val(a.c.inactive);
        $("#serial_no").val(a.c.serial_no);
        $("#brand").val(a.c.brand);
        $.post("index.php/main/get_data/r_brand", {
            code: a.c.brand
        }, function(a) {
            $("#brand_des").val(a.description);
        }, "json");
        $("#model").val(a.c.model);
        $("#rol").val(a.c.rol);
        $("#roq").val(a.c.roq);
        $("#supplier").val(a.c.supplier);
        $.post("index.php/main/get_data/m_supplier", {
            code: a.c.supplier
        }, function(a) {
            $("#supplier_des").val(a.data.name);
        }, "json");
        var b = 0;
        $("#barcode").val(a.c.barcode);
        $("#purchase_price").val(a.c.purchase_price);
        $("#min_price").val(a.c.min_price);
        $("#max_price").val(a.c.max_price);
        b = (a.c.min_price - a.c.purchase_price) / a.c.min_price;
        $("#min_price_p").val(m_round(100 * b));
        b = (a.c.max_price - a.c.purchase_price) / a.c.max_price;
        $("#max_price_p").val(m_round(100 * b));
        if (1 == a.c.inactive) $("#inactive").attr("checked", "checked"); else $("#inactive").removeAttr("checked");
        if (1 == a.c.serial_no) $("#serial_no").attr("checked", "checked"); else $("#serial_no").removeAttr("checked");
        if (1 == a.c.batch_item) $("#batch_item").attr("checked", "checked"); else $("#batch_item").removeAttr("checked");
        set_select("supplier", "txtSupplier");
        set_select("department", "dep_des");
        set_select("unit", "units_des");
        set_select("category", "s_cat_des");
        set_select("main_category", "m_cat_des");
        set_select("brand", "brand_des");
        for (var c = 0; c < 12; c++) {
            $("#0_" + c).val("");
            $("#n_" + c).val("");
        }
        for (var c = 0; c < a.det.length; c++) {
            $("#0_" + c).val(a.det[c].code);
            $("#n_" + c).val(a.det[c].description);
        }
        if (2 != a.pic) {
            for (var c = 0; c < a.pic.length; c++) {
                $("#pic_name" + c).val(a.pic[c].pic_name);
                if ("" == $("#pic_name0").val()) $("#pic_name0").val("No Image");
                if ("" == $("#pic_name1").val()) $("#pic_name1").val("No Image");
                if ("" == $("#pic_name2").val()) $("#pic_name2").val("No Image");
                if ("" == $("#pic_name3").val()) $("#pic_name3").val("No Image");
                if ("" == $("#pic_name4").val()) $("#pic_name4").val("No Image");
            }
            $("#pic_name0").click(function() {
                $("#pic_pic").css("display", "block");
                $("#pic_pic").empty();
                $("#pic_pic").html("<img width='150px' height='150px' src='" + a.pic[0].pic_picture + "'/><button class='deletePic' value='0'>Delete</button><input type='hidden' name='pp' class='picpath' value='" + a.pic[0].pic_picture + "'>");
            });
            $("#pic_name1").click(function() {
                $("#pic_pic").css("display", "block");
                $("#pic_pic").empty();
                $("#pic_pic").html("<img width='150px' height='150px' src='" + a.pic[1].pic_picture + "'/><button class='deletePic' value='1'>Delete</button><input type='hidden' name='pp' class='picpath' value='" + a.pic[1].pic_picture + "'>");
            });
            $("#pic_name2").click(function() {
                $("#pic_pic").css("display", "block");
                $("#pic_pic").empty();
                $("#pic_pic").html("<img width='150px' height='150px' src='" + a.pic[2].pic_picture + "'/><button class='deletePic' value='2'>Delete</button><input type='hidden' name='pp' class='picpath' value='" + a.pic[2].pic_picture + "'>");
            });
            $("#pic_name3").click(function() {
                $("#pic_pic").css("display", "block");
                $("#pic_pic").empty();
                $("#pic_pic").html("<img width='150px' height='150px' src='" + a.pic[3].pic_picture + "'/><button class='deletePic' value='3'>Delete</button><input type='hidden' name='pp' class='picpath' value='" + a.pic[3].pic_picture + "'>");
            });
            $("#pic_name4").click(function() {
                $("#pic_pic").css("display", "block");
                $("#pic_pic").empty();
                $("#pic_pic").html("<img width='150px' height='150px' src='" + a.pic[4].pic_picture + "'/><button class='deletePic' value='4'>Delete</button><input type='hidden' name='pp' class='picpath' value='" + a.pic[4].pic_picture + "'>");
            });
            $(document).on("mouseenter", ".deletePic", function() {
                $(".imgMsg").css("display", "block");
            });
            $(document).on("mouseout", ".deletePic", function() {
                $(".imgMsg").css("display", "none");
            });
            $(document).on("click", ".deletePic", function(b) {
                var c = $(this).val();
                var d = $("#code").val();
                if (confirm("Are you sure to delete image '" + a.pic[c].pic_name + "' ")) ; else b.preventDefault();
            });
        }
        loding();
        input_active();
        is_edit = 1;
    }, "json");
}

function select_search() {
    $("#pop_search").focus();
}


function select_search2() {
    $("#pop_search2").focus();
}

function load_items() {
    $.post("index.php/main/load_data/r_subitem/item_list_all", {
        search: $("#pop_search").val(),
        stores: false
    }, function(a) {
        $("#sr").html(a);
        settings();
    }, "text");
}

function load_items2(data_tbl,field,field2,hid_field,filter_value) {

    $.post("index.php/main/load_data/m_items/get_code", {
        search: $("#pop_search2").val(),
        data_tbl:data_tbl,
        field:field,
        field2:field2,
        hid_field:hid_field,
        filter_value:filter_value
    }, function(a) {
        $("#sr2").html(a);
        settings2();
    }, "text");
}

function settings() {
    $("#item_list .cl").click(function() {
        if ("&nbsp;" != $(this).children().eq(0).html()) if (check_item_exist($(this).children().eq(0).html())) {
            $("#h_" + scid).val($(this).children().eq(0).html());
            $("#0_" + scid).val($(this).children().eq(0).html());
            $("#n_" + scid).val($(this).children().eq(1).html());
            $("#2_" + scid).val($(this).children().eq(2).html());
            $("#3_" + scid).val($(this).children().eq(3).html());
            if (1 == $(this).children().eq(4).html()) $("#1_" + scid).autoNumeric({
                mDec: 2
            }); else $("#1_" + scid).autoNumeric({
                mDec: 0
            });
            $("#1_" + scid).removeAttr("disabled");
            $("#2_" + scid).removeAttr("disabled");
            $("#3_" + scid).removeAttr("disabled");
            $("#1_" + scid).focus();
            $("#pop_close").click();
        } else set_msg("Item " + $(this).children().eq(1).html() + " is already added."); else {
            $("#h_" + scid).val("");
            $("#0_" + scid).val("");
            $("#n_" + scid).val("");
            $("#1_" + scid).val("");
            $("#2_" + scid).val("");
            $("#3_" + scid).val("");
            $("#t_" + scid).html("&nbsp;");
            $("#1_" + scid).attr("disabled", "disabled");
            $("#2_" + scid).attr("disabled", "disabled");
            $("#3_" + scid).attr("disabled", "disabled");
            $("#pop_close").click();
        }
    });
//alert($("#supplier").val());
}

function settings2() {
    $("#item_list .cl").click(function() {
        if ("&nbsp;" != $(this).children().eq(0).html()){
         if(current_search_data=='r_department'){
            $("#department").val($(this).children().eq(0).html());
            $("#department_des").val($(this).children().eq(1).html());
            $("#dep_codegen").val($(this).children().eq(2).find('input').val());

            $("#main_category").val("");
            $("#main_category_des").val("");
            $("#mcat_codegen").val("");

            $("#sub_category").val("");
            $("#sub_category_des").val("");
            $("#scat_codegen").val("");
            if($(this).children().eq(0).html()=='0030'){
                gift_code();
            }else{
                $("#is_gift").val("0");
                $("#unit").removeClass("hid_value");
                $("#unit").addClass("input_active");
            }

        }else if(current_search_data=='r_category'){

            $("#main_category").val($(this).children().eq(0).html());
            $("#main_category_des").val($(this).children().eq(1).html());
            $("#mcat_codegen").val($(this).children().eq(2).find('input').val());

            $("#sub_category").val("");
            $("#sub_category_des").val("");
            $("#scat_codegen").val("");

        }else if(current_search_data=='r_sub_category'){

            $("#sub_category").val($(this).children().eq(0).html());
            $("#sub_category_des").val($(this).children().eq(1).html());
            $("#scat_codegen").val($(this).children().eq(2).find('input').val());

        }else if(current_search_data=='r_unit'){

            $("#unit").val($(this).children().eq(0).html());
            $("#unit_des").val($(this).children().eq(1).html());
            $("#unit_codegen").val($(this).children().eq(2).find('input').val());

        }else if(current_search_data=='m_supplier'){
            $("#supplier").val($(this).children().eq(0).html());
            $("#supplier_des").val($(this).children().eq(1).html());
            $("#s_codegen").val($(this).children().eq(2).find('input').val());
        }
        $("#pop_close2").click();
        $("#pop_search2").val("");
    } else {

        $("#pop_close2").click();
        $("#pop_search2").val("");
    }
});
}

function gift_code(){
    $("#is_gift").val("1");
    $("#unit").addClass("hid_value");
}




function check_item_exist(a) {
    var b = true;
    $("input[type='hidden']").each(function() {
        if ($(this).val() == a) b = false;
    });
    return b;
}

function empty_grid(){
    for (var a = 0; a < 25; a++) {
        $("#a0_"+a).val("");
        $("#a1_"+a).val("");
        $("#a2_"+a).val("");
        $("#a3_"+a).val("");
    }
}

function get_available_stock() {
    empty_grid();
    $("#tot_qty").val("0");
    $.post("index.php/main/load_data/m_items/get_available_stock", {
        item: $("#code").val(),
        cl: $("#cluster").val(),
        bc: $("#branch").val(),
        store: $("#store").val()
    }, function(a) {
        if(a==2){
            set_msg("Item ("+$('#code').val()+") not availabe in stock","error");
            return false;
        }else{
            var total=parseFloat(0);
            for (var b = 0; b < a.det.length; b++) {
                total=total+parseFloat(a.det[b].qty);
                $("#a0_" + b).val(a.det[b].c_name +"  ("+ a.det[b].c_code+")");
                $("#a1_" + b).val(a.det[b].b_name +"  ("+ a.det[b].b_code+")");
                $("#a2_" + b).val(a.det[b].s_name +"  ("+ a.det[b].s_code+")");
                $("#a3_" + b).val(a.det[b].qty);
            }
            $("#tot_qty").val(total);
        }
    }, "json");
}


function  price_precentage(min,purchase,min_p){
    var precentage=parseFloat(0);
    var value=parseFloat(0);
    var min_c =parseFloat($("#"+min).val());
    var purchase_c =parseFloat($("#"+purchase).val());
    precentage = (min_c - purchase_c)/min_c*100;
    value = m_round(precentage);
    $("#"+min_p).val(value);
}


function check_item_in_db(item,text_name){
    if(item!=""){
        $.post("index.php/main/load_data/m_items/item_in_db", {
            code:item
        }, function(a) {
         if(a==1){
            if($("#"+text_name).is(":checked")){
                $("#"+text_name).attr("checked",false);
            }else{
                $("#"+text_name).attr("checked",true);
            }
        }else{

        }
    }, "text");

    }

}


function get_last_code(){
    $("#last_code").val('');
    var department    = ($("#department").val()!='')?$("#department").val():0;
    var main_category = ($("#main_category").val()!='')?$("#main_category").val():0;
    var sub_category  = ($("#sub_category").val()!='')?$("#sub_category").val():0;
    var unit          = ($("#unit").val()!='')?$("#unit").val():0;
    var supplier      = ($("#supplier").val()!='')?$("#supplier").val():0;
    var last_code ='';
    $.ajax({
        url: "index.php/main/load_data/m_items/get_last_code",
        type: "POST",                
        data: {department:department
          ,main_category:main_category
          ,sub_category:sub_category
          ,unit:unit
          ,supplier:supplier 
      },
      dataType : 'json',
      async: false,
      success: function(r){
        last_code = r.lcode;
    }            
});
    $("#last_code").val(last_code);
}


function load_last_code(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_item",
        field:"code",
        field2:"description",
        add_query: " ORDER BY code DESC",
        search : $("#pop_search6").val(),
    }, function(r){      
        $("#sr6").html(r);
        settings_last_code();
    }, "text");
}


function settings_last_code(){
    $("#item_list .cl").click(function(){
        $("#last_code").val($(this).children().eq(0).html());
        $("#pop_close6").click();
    });    
}

