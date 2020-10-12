var storse = 0;
var is_edit = 0;
$(document).ready(function () {
    load_previous_bal();
    is_approve();
    setTimeout("set_dr_total();set_cr_total();",3000);
    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            load_data($(this).val());
        }
    });

    $("#btnApprove").click(function(){
        approve();
    })
    
    $("#btnPrint").click(function () {
        if ($("#hid").val() == "0") {
            set_msg("Please load data before print");
            return false;
        }else{
            $("#print_pdf").submit();
        }
    });
    $("#tgrid").tableScroll({height: 280, width: 960});

    $(".fo").keydown(function(e) {
        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            set_cid($(this).attr("id"));
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
            load_accounts();
        }

        if(e.keyCode==46){

            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
            $("#4_"+scid).val("");
        }

        if(e.keyCode==13){
            $.post("index.php/main/load_data/t_opening_balance/get_account", {
                code:$("#0_"+scid).val()
            }, function(res){
                if(res.a!=2){
                  $("#0_"+scid).val(res.a[0].code);
                  if(check_item_exist($("#0_"+scid).val())){

                    $("#h_"+scid).val(res.a[0].code);
                    $("#0_"+scid).val(res.a[0].code);
                    $("#n_"+scid).val(res.a[0].description);
                    $("#3_"+scid).val(res.a[0].heading);
                    $("#4_"+scid).val(res.a[0].rtype);
                    
                    $("#1_"+scid).focus();
                  }else{
                    alert("Account "+$("#0_"+scid).val()+" is already added.");
                  }
                }else{
                  set_msg($("#0_"+scid).val()+" Account not available in account list","error");
                  $("#0_"+scid).val("");
                }
            }, "json");
        }
    });

    $("#btnSave1").click(function () {
        if (validate())
        {
            check_permission();
        }
    });

    $("#pop_search").keyup(function (e) {
        if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40) {
            load_accounts();
        }
    });

    $("#pop_search").gselect();

    $(".cr").keyup(function () {
        set_cid($(this).attr("id"));
        set_cr_total();
    });

    $(".dr").keyup(function () {
        set_cid($(this).attr("id"));
        set_dr_total();
    });

    $("#a_id").click(function () {
        load_data($("#id").val());
    });

    $("#btnDelete1").click(function () {
        if ($("#hid").val() > 0){
            check_delete_permission();
        }else{
            alert("Please load a record");
        }
    });

    $("#btnDelete1, #btnSave1, #btnPrint").removeAttr("disabled");

});

function check_delete_permission(){
    set_delete();
}

function check_permission(){
    save();
}

$(document).keydown(function (e) {
    if (e.keyCode == 112) {
        $("#0_0").focus();
    }
});

function set_delete() {
    var id = $("#hid").val();
    if (id != 0) {
        if (confirm("Are you sure ? ")) {
            $.post("index.php/main/delete/t_opening_balance", {
                id: id
            }, function (r) {
                if (r != 1) {
                    set_msg(r,"error");
                } else {
                    location.href="";
                }
            }, "text");
        }
    } else {
        alert("Please load record");
    }
}

function empty_grid() {
    for (var i = 0; i < 100; i++) {
        $("#h_" + i).val(0);
        $("#0_" + i).val("");
        $("#n_" + i).val("");
        $("#1_" + i).val("");
        $("#2_" + i).val("");
        $("#3_" + i).val("");
    }
}

function load_data(id) {
    loding();
    empty_grid();
    $.post("index.php/main/get_data/t_opening_balance/", {
        id: id
    }, function (r) {
        loding();
        if (r.sum.no != undefined ) {
            $("#p_cr").val(m_round(0.00));
            $("#p_dr").val(m_round(0.00));
            $("#id").attr("readonly", "readonly");
            $("#hid").val(r.sum.no);
            $("#date").val(r.sum.date);
            $("#description").val(r.sum.je_des);
            $("#ref_no").val(r.sum.ref_no);
            $("#note").val(r.sum.note);
            $("#qno").val(r.sum.no);
         
            for (var i = 0; i < r.det.length; i++) {
                $("#h_" + i).val(r.det[i].account_code);
                $("#0_" + i).val(r.det[i].account_code);
                $("#n_" + i).val(r.det[i].acc_des);
                $("#1_" + i).val(r.det[i].dr_amount);
                $("#2_" + i).val(r.det[i].cr_amount);
                $("#3_" + i).val(r.det[i].heading);
                $("#4_" + i).val(r.det[i].type);
            }
            set_dr_total();
            set_cr_total();
            if (r.sum.is_cancel > 0) {
                set_msg("This record canceled.","error");
                $("#btnDelete1").attr("disabled", "disabled");
                $("#btnSave1").attr("disabled", "disabled");
                $("#btnPrint").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
            }

            if (r.sum.is_approve > 0) {
                $("#btnDelete1").attr("disabled", "disabled");
                $("#btnSave1").attr("disabled", "disabled");
                $("#btnPrint").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/approve.png')");
            }
            input_active();
        } else {
            set_msg("No records");
        }
    }, "json");
}

function select_search() {
    $("#pop_search").focus();
    $("#pop_search").val("");
}
function load_accounts() {
    $.post("index.php/main/load_data/m_account/account_list_opn", {
        search: $("#pop_search").val()

    }, function (r) {
        $("#sr").html(r);
        settings();
    }, "text");
}

function settings() {
    $("#item_list tr").click(function () {
        if ($(this).children().eq(0).html() != "&nbsp;") {
            if (check_item_exist($(this).children().eq(0).html())) {
                $("#0_" + scid).val($(this).children().eq(0).html());
                $("#h_" + scid).val($(this).children().eq(0).html());
                $("#n_" + scid).val($(this).children().eq(1).html());
                $("#1_" + scid).val('');
                $("#2_" + scid).val('');
                $("#2_" + scid).val($(this).children().eq(7).html());
                $("#3_" + scid).val($(this).children().eq(2).html());
                $("#4_" + scid).val($(this).children().eq(3).html());
                $("#1_" + scid).focus();
                $("#pop_close").click();
            } else {
                alert("Account code " + $(this).children().eq(1).html() + " is already added.");
            }
        } else {
            $("#n_" + scid).val("");
            $("#0_" + scid).val("");
            $("#1_" + scid).val("");
            $("#2_" + scid).val("");
            $("#3_" + scid).val("");
            set_total();
            $("#pop_close").click();
        }
    });
}

function check_item_exist(id) {
    var v = true;
    $("input[type='hidden']").each(function () {
        if ($(this).val() == id) {
            v = false;
        }
    });

    return v;
}

function set_dr_total() {

    var bal = t = tt = 0;
    $(".dr").each(function (e) {
        if ($("#0_" + e).val() != "") {
            tt = parseFloat($(this).val());
            if (isNaN(tt)) {
                tt = 0;
            }
            t += tt;
        }
    });
    var dr_t = parseFloat($("#p_dr").val())+parseFloat(t)
    $("#tot_dr").val(m_round(dr_t));

    bal = parseFloat($("#tot_dr").val()) - parseFloat($("#tot_cr").val());
    $("#balance").val(m_round(bal));
}

function set_cr_total() {
    var bal = 0;
    var t = 0;
    var tt = 0;
    $(".cr").each(function (e) {
        if ($("#0_" + e).val() != "") {
            tt = parseFloat($(this).val());
            if (isNaN(tt)) {
                tt = 0;
            }
            t += tt;
        }
    });
    var cr_t = parseFloat($("#p_cr").val())+parseFloat(t)
    $("#tot_cr").val(m_round(cr_t));

    bal = parseFloat($("#tot_dr").val()) - parseFloat($("#tot_cr").val());
    $("#balance").val(m_round(bal));
}



function validate() {
    var v = false;
    $("input[type='hidden']").each(function () {
        if ($(this).val() != "" && $(this).val() != 0) {
            v = true;
        }
    });
    if (v == false) {
        set_msg("Please use minimum one item.");
    } else if ($("#description").val() == '') {
        set_msg("Please enter description");
        v = false;
    }else if (parseFloat($("#tot_dr").val()) == 0 && parseFloat($("#tot_cr").val()) == 0){
        set_msg("Please enter valid dr Or cr amount");
        v = false;
    }
    return v;
}

function save(){
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid) {
            var sid=pid.split('@');
            loding();
            if (sid[0]==1){
                $("#btnSave1").attr("disabled",true);
                if(confirm("Save Completed, Do You Want A print?")){
                    if($("#is_prnt").val()==1){
                        $("#qno").val(sid[1]);
                        $("#print_pdf").submit();
                    }
                        location.href="";
                    }else{
                        location.href="";
                    }
            } else {
                set_msg(pid,"error");
            }
        }
    });
    is_edit = 0;
}




function approve(){
    loding();
    $.post("index.php/main/load_data/t_opening_balance/approve", {
        date:$("#date").val(),
        ref_no:$("#ref_no").val(),
        hid:$("#hid").val(),
        note:$("#note").val()
    }, function (pid) {
        loding();
        if (pid == 1){
            $("#btnSave1").attr("disabled",true);
            if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                    $("#print_pdf").submit();
                }
                    location.href="";
                }else{
                    location.href="";
                }
        } else {
            set_msg(pid,"error");
        }
    }, "text");
}






function closeMsgBox() {
    $(".msgBox").fadeOut(500);
    empty_grid();
    input_reset();
    get_max();
}

function load_previous_bal(){
    $.post("index.php/main/load_data/t_opening_balance/load_previous_bal", {
        search: $("#pop_search").val()
    }, function (r) {
        $("#p_cr").val(m_round(r[0].cr));
        $("#p_dr").val(m_round(r[0].dr));
    }, "json");
}

function is_approve(){
    $.post("index.php/main/load_data/t_opening_balance/is_approve", {
        
    }, function (r) {
        if(r==1){
            $("#btnApprove").attr("disabled",true);
        }else{
            $("#btnApprove").attr("disabled",false);
        }
    }, "json");
}