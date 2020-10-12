$(document).ready(function () {
    $("#tabs").tabs();
    load_data();

    $("#is_sales_cat").click(function () {
        check_sales_cat();
    });

    $("#def_store").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
    });


    $("#def_store").keypress(function (e) {
        if (e.keyCode == 13) {
            set_cus_values($(this));
        }
    });

    $("#def_store").blur(function () {
        set_cus_values($(this));
    });

});


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

            if (pid == 1) {
                location.reload();
            } else if (pid == 2) {
                alert("No permission to add data.");
            } else if (pid == 3) {
                alert("No permission to edit data.");
            } else {
                alert("Error : \n" + pid);
            }
            loding();
        }
    });
}


function reload_form() {
    setTimeout(function () {
        window.location = '';
    }, 50);
}


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
    return true;
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
    $.post("index.php/main/get_data/default_settings/", {
    }, function (r) {

        if (r.option_stock[0].use_sub_items == 1) {
            $("#sub_item").attr("checked", "checked");
        } else {
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

        if (r.option_stock[0].use_multi_stores == 1) {
            $("#multi_store").attr("checked", "checked");
        } else {
            $("#multi_store").removeAttr("checked");
        }

        $("#def_store").val(r.option_stock[0].def_store_code);
        $("#pur_store").val(r.option_stock[0].def_purchase_store_code);
        $("#sales_store").val(r.option_stock[0].def_sales_store_code);

        if (r.option_stock[0].use_sales_category == 1) {
            $("#is_sales_cat").attr("checked", "checked");
        } else {
            $("#is_sales_cat").removeAttr("checked");
        }

        $("#sales_cat").val(r.option_stock[0].def_sales_category_code);

        if (r.option_stock[0].use_sales_group == 1) {
            $("#is_sales_group").attr("checked", "checked");
        } else {
            $("#is_sales_group").removeAttr("checked");
        }

        $("#sales_group").val(r.option_stock[0].sales_group_code);

        if (r.option_sales[0].use_salesman == 1) {
            $("#is_sales_man").attr("checked", "checked");
        } else {
            $("#is_sales_man").removeAttr("checked");
        }

        $("#sales_man").val(r.option_sales[0].def_salesman_code);

        if (r.option_sales[0].use_collection_officer == 1) {
            $("#is_collection_off").attr("checked", "checked");
        } else {
            $("#is_collection_off").removeAttr("checked");
        }

        $("#collection_off").val(r.option_sales[0].def_collection_officer_code);

        if (r.option_account[0].sep_sales_dis == 1) {
            $("#sales_discount_to_separate_acc").attr("checked", "checked");
        } else {
            $("#sales_discount_to_separate_acc").removeAttr("checked");
        }

        if (r.auto_deptm[0].is_auto_department_id == 1) {
            $("#auto_dep_id").attr("checked", "checked");
        } else {
            $("#auto_dep_id").removeAttr("checked");
        }
        if (r.auto_mcat[0].is_auto_maincat_id == 1) {
            $("#auto_main_cat_id").attr("checked", "checked");
        } else {
            $("#auto_main_cat_id").removeAttr("checked");
        }

//        if (r.auto_sub_cat[0].is_auto_maincat_id == 1) {
//            $("#auto_main_cat_id").attr("checked", "checked");
//        } else {
//            $("#auto_main_cat_id").removeAttr("checked");
//        }
        if (r.auto_sbcat[0].is_auto_subcat_id == 1) {
            $("#auto_sub_cat_id").attr("checked", "checked");
        } else {
            $("#auto_sub_cat_id").removeAttr("checked");
        }

        //$("#auto_dep_id").val(r.option_sales[0].def_collection_officer_code);



        if (r.option_account[0].auto_dep_id == 1) {
            $("#sales_return_to_separate_acc").attr("checked", "checked");
        } else {
            $("#sales_return_to_separate_acc").removeAttr("checked");
        }

        loding();
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