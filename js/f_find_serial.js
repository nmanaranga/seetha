var press_count=0;
$(document).ready(function () {
    $("#btnPrint").click(function(){
        $("#print_pdf").submit();
    });

    $("#pop_searchs").keyup(function (e) {

        if (e.keyCode == 13) {
            if(press_count==0){
                load_items();
            }else{
                alert("Please Wait.. Searching...");
            }
            press_count=1;
        }

    });


    $("#grid").tableScroll({
        height: 355,
        width: 1000
    });


    $("#pop_searchs").keyup(function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            press_count=0;
        }
    });


    $("#txt_cluster").keydown(function (e) {
        if (e.keyCode == 112) {/*
            $("#pop_search11").val();
            load_cluster();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        */}
        $("#pop_search11").keyup(function (e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_cluster();
            }
        });
        if (e.keyCode == 46) {
            $("#txt_cluster").val("");
            $("#hid_cluster").val("");
            $("#txt_branch").val("");
            $("#hid_branch").val("");
            $("#txt_store").val("");
            $("#hid_store").val("");
        }
    });

    $("#txt_branch").keydown(function (e) {
        if (e.keyCode == 112) {/*
            $("#pop_search2").val();
            load_branch();
            $("#serch_pop2").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search2').focus()", 100);
        */}
        $("#pop_search2").keyup(function (e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_branch();
            }
        });
        if (e.keyCode == 46) {
            $("#txt_branch").val("");
            $("#hid_branch").val("");
            $("#txt_store").val("");
            $("#hid_store").val("");
        }
    });

    $("#txt_store").keydown(function (e) {
        if (e.keyCode == 112) {
            $("#pop_search4").val();
            load_store();
            $("#serch_pop4").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search4').focus()", 100);
        }
        $("#pop_search4").keyup(function (e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_store();
            }
        });
        if (e.keyCode == 46) {
            $("#txt_store").val("");
            $("#hid_store").val("");
        }
    });

    $("#txt_item").keydown(function (e) {
        if (e.keyCode == 112) {
            $("#pop_search6").val();
            load_item();
            $("#serch_pop6").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search6').focus()", 100);
        }
        $("#pop_search6").keyup(function (e) {
            if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
                load_item();
            }
        });
        if (e.keyCode == 46) {
            $("#txt_item").val("");
            $("#hid_item").val("");
        }
    });




});


function load_cluster() {
    $.post("index.php/main/load_data/f_find_serial/get_cluster_name", {
        search: $("#pop_search11").val()
    }, function (r) {
        $("#sr11").html(r);
        cluster_settings();
    }, "text");
}

function cluster_settings() {
    $("#item_list .cl").click(function () {
        $("#txt_branch").val("");
        $("#hid_branch").val("");
        $("#txt_store").val("");
        $("#hid_store").val("");
        $("#txt_cluster").val($(this).children().eq(0).html());
        $("#hid_cluster").val($(this).children().eq(1).html());
        $("#pop_close11").click();
    })
}

function load_branch() {

    $.post("index.php/main/load_data/f_find_serial/get_branch_name", {
        cluster:$("#txt_cluster").val(),
        search: $("#pop_search2").val()
    }, function (r) {
        $("#sr2").html(r);
        branch_settings();
    }, "text");
}

function branch_settings() {
    $("#item_list .cl").click(function () {
        $("#txt_store").val("");
        $("#hid_store").val("");
        $("#txt_branch").val($(this).children().eq(0).html());
        $("#hid_branch").val($(this).children().eq(1).html());
        $("#pop_close2").click();
    })
}

function load_store() {
    $.post("index.php/main/load_data/f_find_serial/get_store", {
        bc:$("#txt_branch").val(),
        search: $("#pop_search4").val()
    }, function (r) {
        $("#sr4").html(r);
        store_settings();
    }, "text");
}

function store_settings() {
    $("#item_list .cl").click(function () {
        $("#txt_store").val($(this).children().eq(0).html());
        $("#hid_store").val($(this).children().eq(1).html());
        $("#pop_close4").click();
    })
}


function load_item() {
    $.post("index.php/main/load_data/f_find_serial/get_items", {
       search: $("#pop_search6").val()
   }, function (r) {
    $("#sr6").html(r);
    item_settings();
}, "text");
}

function item_settings() {
    $("#item_list .cl").click(function () {
        $("#txt_item").val($(this).children().eq(0).html());
        $("#hid_item").val($(this).children().eq(1).html());
        $("#pop_close6").click();
    })
}

function set_cus_values(f, scid) {

    var v = f.val();
    v = v.split("|");

    if (v.length == 5) {
        f.val(v[0]);

        if (check_item_exist(f.val())) {
            $("#h_" + scid).val(v[0]);
            $("#n_" + scid).val(v[1]);

            $("#2_" + scid).val(v[2]);
            $("#3_" + scid).val(v[3]);
            $("#4_" + scid).val(v[4]);
            $("#2_" + scid).focus();

        } else {


            alert("Item " + f.val() + " is already added.");

        }

    }
}


function formatItems(row) {
    return "<strong> " + row[0] + "</strong> | <strong> " + row[1];
}

function formatItemsResult(row) {
    return row[0] + "|" + row[1] + "|" + row[2] + "|" + row[3] + "|" + row[4];

}

function select_search() {
    $("#pop_search").focus();

}

function load_items() {
    $.post("index.php/main/load_data/f_find_serial/item_list_all", {
        search: $("#pop_searchs").val(),
        cl: $("#txt_cluster").val(),
        bc: $("#txt_branch").val(),
        store: $("#txt_store").val(),
        item: $("#txt_item").val(),
    }, function (r) {
        $("#searchType").html(r);
        settings();
        settings5();

    }, "text");
}


function settings() {
    $("#item_list .cl").click(function () {

        if ($(this).children().eq(0).html() != "&nbsp;") {

            if (check_item_exist($(this).children().eq(0).html())) {
                $("#h_" + scid).val($(this).children().eq(0).html());
                $("#0_" + scid).val($(this).children().eq(0).html());
                $("#n_" + scid).val($(this).children().eq(1).html());
                $("#2_" + scid).val($(this).children().eq(2).html());
                $("#3_" + scid).val($(this).children().eq(3).html());
                $("#4_" + scid).val($(this).children().eq(4).html());

                if ($(this).children().eq(4).html() == 1) {
                    $("#1_" + scid).autoNumeric({
                        mDec: 2
                    });
                } else {
                    $("#1_" + scid).autoNumeric({
                        mDec: 2
                    });
                }
                $("#1_" + scid).removeAttr("disabled");
                $("#2_" + scid).removeAttr("disabled");
                $("#3_" + scid).removeAttr("disabled");
                $("#1_" + scid).focus();
                $("#pop_close").click();
            } else {
                alert("Item " + $(this).children().eq(1).html() + " is already added.");
            }
        } else {
            $("#h_" + scid).val("");
            $("#0_" + scid).val("");
            $("#n_" + scid).val("");
            $("#1_" + scid).val("");
            $("#2_" + scid).val("");
            $("#3_" + scid).val("");
            $("#4_" + scid).val("");
            $("#t_" + scid).html("&nbsp;");
            $("#1_" + scid).attr("disabled", "disabled");
            $("#2_" + scid).attr("disabled", "disabled");
            $("#3_" + scid).attr("disabled", "disabled");
            $("#4_" + scid).attr("disabled", "disabled");

            $("#pop_close").click();
        }
    });
}


function settings5() {
    $(document).on('click', '#item_list .cl', function () {

     $(".cl").children().find('input').css('background-color', '#f9f9ec');
     $(this).children().find('input').css('background-color', '#6699ff');

     if ($(this).children().eq(0).html() != "&nbsp;") {

        $("#itm2").val($(this).children().find('input').eq(0).val());
        $("#des2").val($(this).children().find('input').eq(1).val());
        $("#mPrice2").val($(this).children().find('input').eq(3).val());
        $("#mxPrice2").val($(this).children().find('input').eq(4).val());
        $("#btch2").val($(this).children().find('input').eq(6).val());
        $("#qnty2").val($(this).children().find('input').eq(5).val());


    } else {
        $("#itm2").val("");
        $("#des2").val("");
        $("#mPrice2").val("");
        $("#mxPrice2").val("");
        $("#btch2").val("");
        $("#qnty2").val("");
        $("#pop_close").click();
    }
});
}


