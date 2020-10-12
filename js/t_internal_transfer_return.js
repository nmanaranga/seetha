var sub_items = [];


$(document).ready(function() {
    $(".qunsb").css("display", "none");
    $(".quns").css("display", "none");

    $("#t_type").change(function() {
        empty_grid();
        get_sub_no();
    });

    $("#to_cluster").change(function() {
        $.post("index.php/main/load_data/t_internal_transfer_return/to_branch", {
            cluster: $("#to_cluster").val()
        }, function(res) {
            $("#to_branch").html(res)
        }, "text");
    });

    $("#branch").change(function() {
        set_select('branch', 'branch_hid');
    });

    $("#store_from").change(function() {
        set_select('store_from', 'store_hid');
        empty_grid();
    });

    $("#v_store").change(function() {
        set_select('v_store', 'location_store_hid');
    });

    $("#sub_no").keydown(function(e) {
        if (e.keyCode == 13) {
            load_data($(this).val());
        }
    });

    $("#vehicle").keydown(function(e) {
        if (e.keyCode == 112) {
            $("#pop_search2").val($("#vehicle").val());
            load_vehicle();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search2').focus()", 100);
        }
        $("#pop_search2").keyup(function(e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_vehicle();
            }
        });
        if (e.keyCode == 46) {
            $("#vehicle").val("");
            $("#vehicle_des").val("");
            $("#v_store").val("");
        }
    });

    $("#this_store").keydown(function(e) {
        if (e.keyCode == 112) {
            $("#pop_search11").val($("#this_store").val());
            load_store();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }
        $("#pop_search11").keyup(function(e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_store();
            }
        });
        if (e.keyCode == 46) {
            $("#this_store").val("");
            $("#this_store_des").val("");
        }
    });


    $("#issue_no").keydown(function(e) {
        if ($("#v_store").val() != 0) {
            if (e.keyCode == 112) {
                $("#pop_search10").val($("#issue_no").val());
                load_all_returns();
                $("#serch_pop10").center();
                $("#blocker").css("display", "block");
                setTimeout("$('#pop_search10').focus()", 100);
            }
            $("#pop_search10").keyup(function(e) {
                if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                    load_all_returns();
                }
            });
        } else {
            set_msg("please select return from store");
        }
        if (e.keyCode == 46) {
            $("#issue_no").val("");
        }
    });


    $("#free_fix,#pst").blur(function() {
        var get_code = $(this).val();
        $(this).val(get_code.toUpperCase());
    });

    $("#btnExit1").click(function() {
        document.getElementById('light').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
        $("#2_" + get_id).focus();
    });

    $("#gen").click(function() {
        var free_fix = $("#free_fix").val();
        var post_fix = $("#pst").val();
        var start_no = parseInt($("#abc").val());
        var quantity = parseInt($("#quantity").val());

        for (x = 0; x < quantity; x++) {
            start_no = start_no + 1;
            var code_gen = free_fix + start_no.toString() + post_fix;
            $("#srl_" + x).val(code_gen);
        }
    });

    $("#btnReject").click(function() {
        reject();
    });

    $(".qunsb").click(function() {
        set_cid($(this).attr("id"));
        check_is_batch_item(scid);
    });

    $("#btnDelete").click(function() {
      if($("#hid").val()=="" || $("#hid").val()=="0"){
        set_msg("please load data before delete");
    }else{
        set_delete();
    }
});

    $("#btnPrint").click(function() {
        $("#print_pdf").submit();
    });

    $("#grid").tableScroll({
        height: 355
    });
    $("#tgrid").tableScroll({
        height: 355 , width: 1000
    });

    $("#click").click(function() {
        var x = 0;
        $(".me").each(function() {

            if ($(this).val() != "" && $(this).val() != 0) {
                v = true;
            }
            x++;
        });
    });



    $(".fo").keydown(function(e) {
        set_cid($(this).attr("id"));

        if (e.keyCode == 46) {
            if ($("#df_is_serial").val() == '1') {
                $("#all_serial_" + scid).val("");
            }

            $("#h_" + scid).val("");
            $("#0_" + scid).val("");
            $("#n_" + scid).val("");
            $("#m_" + scid).val("");
            $("#1_" + scid).val("");
            $("#c_" + scid).val("");
            $("#min_" + scid).val("");
            $("#bal_" + scid).val("");
            $("#issue_" + scid).val("");
            $("#cur_" + scid).val("");
            $("#3_" + scid).val("");
            $("#2_" + scid).val("");
            $("#2_" + scid).attr("placeholder", "");
            $("#qtyh_" + scid).val("");
            $("#subcode_" + scid).val("");
            $("#subcode_" + scid).removeAttr("data-is_click");
            $("#2_" + scid).attr("placeholder", "");
            $("#btn_" + scid).css("display", "none");
            $("#btnb_" + scid).css("display", "none");
            $("#t_" + scid).val("");
            total();
        }
    });

    $("#pop_search").keyup(function(e) {
        if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40) {
            load_items();
        }
    });

    $(".dis, .qun, .dis_pre").blur(function() {
        set_cid($(this).attr("id"));
        cal_amount(scid);
        total();
    });

    $("#pop_search").gselect();

});

function get_sub_no() {
    var type = $("#t_type").val();
    $("#types").val(type);
    $.post("index.php/main/load_data/t_internal_transfer_return/get_max_no_in_type_transfer_echo", {
        sub_hid: $("#sub_hid").val(),
        type: type,
        table: 't_internal_transfer_return_sum',
        sub_no: 'sub_no',
    }, function(res) {
        $("#sub_no").val(res);
    }, "text"

    );
}

function load_vehicle() {
    $.post("index.php/main/load_data/t_internal_transfer_return/f1_selection_list_vehicle", {
        data_tbl: "m_vehicle_setup",
        field: "code",
        field2: "description",
        field3: "stores",
        preview1: "Vehicle Code",
        preview2: "Description",
        search: $("#pop_search2").val()
    }, function(r) {
        $("#sr2").html(r);
        settings_vehicle();
    }, "text");
}

function load_store() {
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl: "m_stores",
        field: "code",
        field2: "description",
        preview1: "Code",
        preview2: "Name",
        add_query: "AND cl='" + $("#cluster").val() + "' AND bc='" + $("#branch").val() + "'",
        search: $("#pop_search11").val()
    }, function(r) {
        $("#sr11").html(r);
        settings_store();
    }, "text");
}


function load_all_returns() {
    $.post("index.php/main/load_data/t_internal_transfer_return/load_all_return", {
        type: $("#types").val(),
        search: $("#pop_search10").val()
    }, function(r) {
        $("#sr10").html(r);
        settings_all_returns();
    }, "text");
}

function load_return_details(no) {
    $.post("index.php/main/load_data/t_internal_transfer_return/load_return_details", {
        type: $("#types").val(),
        no: no,
    }, function(r) {
        for (var x = 0; x < r.length; x++) {
            $("#h_" + x).val(r[x].code);
            $("#0_" + x).val(r[x].code);
            $("#n_" + x).val(r[x].description);
            $("#m_" + x).val(r[x].model);
            $("#1_" + x).val(r[x].batch_no);
            $("#c_" + x).val(r[x].cost);
            $("#min_" + x).val(r[x].min_price);
            $("#3_" + x).val(r[x].max_price);
            $("#issue_" + x).val(r[x].qty);
            $("#2_" + x).val(0);
            //$("#bal_" + x).val(r[x].balance);
           // $("#t_" + x).val(r[x].amount);
           check_is_serial_item2(r[x].code, x);
           check_is_batch_item2(r[x].code, x);
           is_sub_item(x);
           check_is_batch_item(x);
           total();

       }
       load_return_balance(no);
   }, "json");
}

function load_return_balance(no){
    $.post("index.php/main/load_data/t_internal_transfer_return/load_balance", {
        type: $("#types").val(),
        no: no,
    }, function(r) {
        for(var y=0; y<25; y++){
            if($("#0_"+y).val() !=""){
                for (var x = 0; x < r.length; x++){
                    if($("#0_"+y).val() ==r[x].item_code){
                        $("#bal_"+y).val(r[x].balance);
                    }
                }
                
            }
        }
    }, "json");

}


function is_sub_item(x) {
    sub_items = [];
    $("#subcode_" + x).val("");
    $.post("index.php/main/load_data/utility/is_sub_items", {
        code: $("#0_" + x).val(),
        qty: $("#2_" + x).val(),
        batch: $("#1_" + x).val()
    }, function(r) {
        if (r != 2) {
            for (var i = 0; i < r.sub.length; i++) {
                add(x, r.sub[i].sub_item, r.sub[i].qty);
            }
            $("#subcode_" + x).attr("data-is_click", "1");
        }
    }, "json");
}

function add(x, items, qty) {
    $.post("index.php/main/load_data/utility/is_sub_items_available", {
        code: items,
        qty: qty,
        grid_qty: $("#2_" + x).val(),
        batch: $("#1_" + x).val(),
        hid: $("#hid").val(),
        store: $("#v_store").val(),
        trans_type: "80"
    }, function(res) {
        if (res != 2) {
            sub_items.push(res.sub[0].sub_item + "-" + res.sub[0].qty);
            $("#subcode_" + x).val(sub_items);
        } else {
            set_msg("Not enough quantity in this sub item (" + items + ")", "error");
            $("#subcode_" + x).val("");
        }
    }, "json");
}


function save() {

    $("#qno").val($("#id").val());
    $("#org_print").val('1');
    $("#dt").val($("#ddate").val());

    if ($("#df_is_serial").val() == '1') {
        serial_items.sort();
        $("#srls").attr("title", serial_items);
        $("#srls").val(serial_items);
    }

    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function(pid) {
            var sid=pid.split('@');
            if (pid == 5) {
                set_msg('Please check the serial numbers');
            } else if (sid[0]==1) {
                $("#btnSave").attr("disabled", true);
                loding();
                if (confirm("Save Completed, Do You Want A print?")) {
                    if ($("#is_prnt").val() == 1) {
                        $("#qno").val(sid[1]);
                        $("#print_pdf").submit();
                    }
                    reload_form();
                } else {
                    location.href = "";
                }

            } else if (pid == 2) {
                set_msg("No permission to add data.");
            } else if (pid == 3) {
                set_msg("No permission to edit data.");
            } else {
                loding();
                set_msg(pid, "error");
            }

        }
    });


}

function reload_form() {
    setTimeout(function() {
        location.href = '';
    }, 50);
}


function empty_grid() {
    for (var i = 0; i < 25; i++) {
        $("#h_" + i).val("");
        $("#0_" + i).val("");
        $("#n_" + i).val("");
        $("#m_" + i).val("");
        $("#1_" + i).val("");
        $("#c_" + i).val("");
        $("#min_" + i).val("");
        $("#3_" + i).val("");
        $("#cur_" + i).val("");
        $("#bal_" + i).val("");
        $("#issue_" + i).val("");
        $("#2_" + i).val("");
        $("#t_" + i).val("");
        $("#2_" + scid).attr("placeholder", "");
        $("#qtyh_" + i).val("");
        $("#subcode_" + i).val("");
        $("#subcode_" + i).removeAttr("data-is_click");

    }
    $(".quns").css("display", "none");
    $(".qunsb").css("display", "none");
}

function empty_txt_box() {
    $("#store_from").val("");
    $("#store_to").val("");
    $("#officer").val("");
    $("#ref_des").val("");
    $("#ref_no").val("");
    $("#officer_id").val("");
    $("#store_to_id").val("");
    $("#store_from_id").val("");
}


function load_data(id) {
    var g = [];
    empty_grid();
    loding();
    empty_txt_box();
    $.post("index.php/main/load_data/t_internal_transfer_return/get_display", {
        sub_no: id,
        type: $("#types").val(),
    }, function(r) {
        if (r == "2") {
            set_msg("No records");
        } else {
            $("#qno").val(r.sum[0].nno);
            $("#dt").val(r.sum[0].ddate);
            $("#hid").val(r.sum[0].nno);
            $("#id").val(r.sum[0].nno);
            $("#ddate").val(r.sum[0].ddate);
            $("#ref_no").val(r.sum[0].ref_no);
            $("#note").val(r.sum[0].note);
            $("#issue_no").val(r.sum[0].issue_no);
            $("#issue_no_main").val(r.sum[0].issue_no_main);
            $("#v_store").val(r.sum[0].from_store);
            $("#this_store").val(r.sum[0].to_store);
            $("#sub_no").val(r.sum[0].sub_no);
            $("#sub_hid").val(r.sum[0].sub_no);
            $("#t_type").val(r.sum[0].type);
            $("#types").val(r.sum[0].type);

            if(r.sum[0].tr_mode=='0'){
                var tr='Internal Transfer';
            }else{
                var tr='Direct Transfer';
            }

            $("#tr_type").val(r.sum[0].tr_mode);
            $("#tr_mode").val(tr);

            $("#br_code").val(r.sum[0].to_bc);
            $("#br_name").val(r.sum[0].name);

            $("#vehicle").val(r.sum[0].vehicle);
            $("#vehicle_des").val(r.sum[0].vehicle_name);
            $("#this_store_des").val(r.sum[0].to_store_name);
            $("#t_type").attr("disabled", "disabled");
            set_select('v_store', 'location_store_hid');
            
            if (r.sum[0].is_cancel == 1) {
                $("#form_").css("background-image", "url('img/cancel.png')");
                $("#btnDelete").attr("disabled", true);
                $("#btnSave").attr("disabled", true);
            }

            $("#id").attr("readonly", "readonly")

            for (var i = 0; i < r.det.length; i++) {

              $("#h_" + i).val(r.det[i].item_code);
              $("#0_" + i).val(r.det[i].item_code);
              $("#n_" + i).val(r.det[i].description);
              $("#m_" + i).val(r.det[i].model);
              $("#1_" + i).val(r.det[i].batch_no);
              $("#c_" + i).val(r.det[i].item_cost);
              $("#min_" + i).val(r.det[i].min_price);
              $("#3_" + i).val(r.det[i].max_price);
              $("#issue_" + i).val(r.det[i].issued_qty);
              $("#2_" + i).val(r.det[i].accept_qty);
              $("#bal_" + i).val(r.det[i].balance_qty);
              $("#t_" + i).val(r.det[i].amount);
              
              $("#itemcode_" + i).val(r.det[i].item_code);
              if ($("#df_is_serial").val() == '1') {
                  $("#numofserial_" + i).val(r.det[i].accept_qty);
                  check_is_serial_item2(r.det[i].item_code, i);
                  for (var a = 0; a < r.serial.length; a++) {
                    if (r.det[i].item_code == r.serial[a].item) {
                      g.push(r.serial[a].serial_no);
                      $("#all_serial_" + i).val(g);
                  }
              }
              g = [];
          }
          check_is_batch_item2(r.det[i].item_code, i);
          is_sub_item(i);
          total();
      }
      input_active();
  }
  loding();
}, "json");
}

function validate() {

    for (var t = 0; t < 25; t++) {
        if ($("#subcode_" + t).data("is_click") == 1 && $("#subcode_" + t).val() == "") {
            set_msg("Not enough sub items in (" + $("#0_" + t).val() + ")", "error");
            return false;
        }
        if($("#0_"+t).val()!="" && parseFloat($("#2_"+t).val()) > parseFloat($("#bal_"+t).val())){
            set_msg("This item ("+$("#0_"+t).val()+") return qty should be less than balance qty");
            return false;
        }
    }
    if($("#this_store").val() == "") {
        set_msg("Please select to store");
        return false;
    }else if ($("#v_store").val() == "0") {
        set_msg("Please select from store");
        return false;
    }else if ($("#issue_no").val() == "") {
        set_msg("Please enter issue no ");
        return false;
    } else if ($("#vehicle").val() == "") {
        set_msg("Please Select transport Vehicle");
        return false;
    }else {
        return true;
    }

}

function set_delete() {
    if (confirm("Are you sure delete " + $("#id").val() + "?")) {
        loding();
        $.post("index.php/main/delete/t_internal_transfer_return", {
            id: $("#id").val(),
            store : $("#v_store").val(),
            issue_no: $("#issue_no").val(),
            types: $("#types").val()
        }, function(res) {
            if (res == 1) {
                loding();
                delete_msg();
            }else{
                set_msg(res);
            }
        }, "text");
    }
}

function select_search() {
    $("#pop_search").focus();
}


function settings_vehicle() {
    $("#item_list .cl").click(function() {
        $("#vehicle").val($(this).children().eq(0).html());
        $("#vehicle_des").val($(this).children().eq(1).html());
        $("#v_store2").val($(this).children().eq(2).html());
        $("#pop_close2").click();
    });
}

function settings_store() {
    $("#item_list .cl").click(function() {
        $("#this_store").val($(this).children().eq(0).html());
        $("#this_store_des").val($(this).children().eq(1).html());
        $("#pop_close11").click();
    });
}

function settings_helper() {
    $("#item_list .cl").click(function() {
        $("#helper_id").val($(this).children().eq(0).html());
        $("#helper_name").val($(this).children().eq(1).html());
        $("#pop_close12").click();
    });
}

function settings_all_returns() {
    $("#item_list .cl").click(function() {
        if($(this).children().eq(4).html()=='0'){
            var tr_mode='Internal Transfer';
        }else if($(this).children().eq(4).html()=='1'){
            var tr_mode='Direct Transfer';
        }else if($(this).children().eq(4).html()=='2'){
            var tr_mode='Return Transfer';
        }
        $("#issue_no").val($(this).children().eq(0).html());
        $("#issue_no_main").val($(this).children().eq(5).html());
        $("#br_code").val($(this).children().eq(2).html());
        $("#cl_code").val($(this).children().eq(6).html());
        $("#br_name").val($(this).children().eq(3).html());
        $("#tr_mode").val(tr_mode);
        $("#tr_type").val($(this).children().eq(4).html());
        load_return_details($(this).children().eq(0).html());
        $("#pop_close10").click();
    });
}

function check_item_exist(id) {
    var v = true;
    $("input[type='hidden']").each(function() {
        if ($(this).val() == id) {
            v = false;
        }
    });
    return v;
}



function total() {
    var total = amount = parseFloat(0);
    for (var x = 0; x < 25; x++) {
        if ($("#t_" + x).val() != "") {
            amount = parseFloat($("#t_" + x).val());
            total += amount;
        }
    }
    $("#total").val(m_round(total));
}

function cal_amount(scid) {
    var total =0;
    var itmcost=parseFloat($("#c_" + scid).val());
    var acptqty=parseFloat($("#2_" + scid).val());

    total = itmcost*acptqty;

    $("#t_" + scid).val(m_round(total));
}



function select_search4() {
    $("#pop_search4").focus();
}

function check_item_exist3(id) {
    var v = true;
    return v;
}


function load_items3(x) {
    $.post("index.php/main/load_data/t_internal_transfer_return/batch_item", {
        search: x,
        stores: $("#this_store").val()
    }, function(r) {
        $("#sr3").html(r);
        settings3();
    }, "text");
}

function settings3(){

    $("#batch_item_list .cl").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist3($(this).children().eq(0).html())){
                $("#1_"+scid).val($(this).children().eq(0).html());
                //$("#2_"+scid).val($(this).children().eq(1).html());
                //$("#qtyh_"+scid).val($(this).children().eq(1).html());
                $("#3_"+scid).val($(this).children().eq(2).html());

                $("#min_"+scid).val($(this).children().eq(3).html());
                $("#c_"+scid).val($(this).children().eq(4).html());

                $("#1_"+scid).attr("readonly","readonly");
                $("#bal_"+scid).focus();
                
                $("#pop_close3").click();
            }else{
                set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#1_"+scid).val("");
            $("#5_"+scid).val("");
            $("#3_"+scid).val("");
            discount();
            amount();
            
            gross_amount();
            net_amount();
            $("#pop_close3").click();
        }
    });
}

function select_search3() {
    $("#pop_search3").focus();
}

function check_is_batch_item(scid) {
    var store = $("#v_store").val();
    $.post("index.php/main/load_data/t_internal_transfer_return/is_batch_item", {
        code: $("#0_" + scid).val(),
        store: store
    }, function(res) {
        if (res == 1) {
            $("#serch_pop3").center();
            $("#blocker3").css("display", "block");
            setTimeout("select_search3()", 100);
            load_items3($("#0_" + scid).val());
        } else if (res == '0') {
           // $("#1_" + scid).val("0");
           $("#1_" + scid).attr("readonly", "readonly");
       } else {
        $("#1_" + scid).val(res.split("-")[0]);
        $("#5_" + scid).val(res.split("-")[1]);
        $("#bqty_" + scid).val(res.split("-")[1]);
        $("#1_" + scid).attr("readonly", "readonly");
    }
}, 'text');
}

function check_is_batch_item2(x, scid) {

    var store = $("#v_store").val();
    $.post("index.php/main/load_data/t_internal_transfer_return/is_batch_item", {
        code: x,
        store: store
    }, function(res) {
        $("#btnb_" + scid).css("display", "none");
        if (res == 1) {
            $("#btnb_" + scid).css("display", "block");
        }
    }, 'text');
}