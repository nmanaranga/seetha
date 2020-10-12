var storse = 0;
var is_edit = 0;
$(document).ready(function () {
    $("#btnPrint").click(function () {
        if ($("#hid").val() == "0") {
            alert("Please load data before print");
            return false;
        }
        if ($("#qno").val() == "0") {
            alert("Please load data before print");
            return false;
        }
        if ($("#pblt").val() == "0") {
            alert("Please load data before print");
            return false;
        }
        else
        {
            $("#print_pdf").submit();
        }
    });

    load_accounts();

    $("#tgrid").tableScroll({height: 280});

    $(".fo").keydown(function (e) {
        set_cid($(this).attr("id"));
        if (e.keyCode == 112) {
            set_cid($(this).attr("id"));
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
            load_accounts();
        }

        if (e.keyCode == 13) {
            $.post("index.php/main/load_data/t_payable_invoice/get_account", {
                code: $("#0_" + scid).val()
            }, function (res) {
                if (res.a != 2) {
                    $("#0_" + scid).val(res.a[0].code);
                    if (check_item_exist($("#0_" + scid).val())) {

                        $("#h_" + scid).val(res.a[0].code);
                        $("#0_" + scid).val(res.a[0].code);
                        $("#n_" + scid).val(res.a[0].description);

                        $("#1_" + scid).focus();
                    } else {
                        alert("Account " + $("#0_" + scid).val() + " is already added.");
                    }
                } else {
                    set_msg($("#0_" + scid).val() + " Account not available in account list", "error");
                    $("#0_" + scid).val("");
                }
            }, "json");
        }

        if (e.keyCode == 46) {
            $("#h_" + scid).val("");
            $("#0_" + scid).val("");
            $("#n_" + scid).val("");
            $("#1_" + scid).val("");
        }

    });

    if ($("#autofill_payable").val() > 0)
    {
        $("#sjournal_type").removeAttr("readonly", "readonly");
    }
    else
    {
        $("#sjournal_type").attr("readonly", "readonly");
        $("#sjournal_type").val('P001');
        $("#journal_type").val('P001');
        $("#journal_type_des").val('PAYABLE COMMON');

    }

    $("#btnSave1").click(function () {
        if (validate())
        {
            check_permission();
        }
    });

    $("#saccount").blur(function () {
        set_account_values($(this));
    });

    $("#saccount").keydown(function (e) {
        if (e.keyCode == 13) {
            set_account_values($(this));
        }
    });

    $("#saccount").autocomplete('index.php/main/load_data/m_account/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatAccount,
        formatResult: formatAccountResult
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

    $("#id").keydown(function (e) {
        if (e.keyCode == 13) {
            $(this).blur();
            $(this).attr("readonly", "readonly");
            load_data($(this).val());
        }
    });

    $("#btnDelete1").click(function () {
        if ($("#hid").val() > 0)
        {
            check_delete_permission();
        }
        else
        {
            alert("Please load a record");
        }
    });

    $("#btnDelete1, #btnSave1, #btnPrint").removeAttr("disabled");

    $("#sjournal_type").autocomplete('index.php/main/load_data/r_journal_type/auto_com_payable', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatjtype,
        formatResult: formatjtypeResult
    });

    $("#sjournal_type").blur(function () {
        set_jt_values($(this));
    });

    $("#sjournal_type").keydown(function (e) {
        if (e.keyCode == 13) {
            set_jt_values($(this));
        }
    });

    $("#sjournal_type").keydown(function(e){
      if(e.keyCode == 112){
          $("#pop_search6").val($("#sjournal_type").val());
          load_data8();
          $("#serch_pop6").center();
          $("#blocker").css("display", "block");
          setTimeout("$('#pop_search6').focus()", 100);
      }

     $("#pop_search6").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
               load_data8();
          }
      }); 

      if(e.keyCode == 46){
          $("#sjournal_type").val("");
          $("#journal_type").val("");
          $("#journal_type_des").val("");
      }
  });

    $("#saccount").keydown(function(e){
        if(e.keyCode == 112){
            $("#pop_search11").val($("#saccount").val());
            load_data9();
            $("#serch_pop11").center();
            $("#blocker").css("display", "block");
            setTimeout("$('#pop_search11').focus()", 100);
        }

       $("#pop_search11").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                 load_data9();
            }
        }); 

        if(e.keyCode == 46){
            $("#saccount").val("");
            $("#account").val("");
            $("#account_des").val("");
        }
    });

});

function check_delete_permission() {
    set_delete();
}

function check_permission() {
    save();
}

function load_data9(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_account",
        field:"code",
        field2:"description",
        preview2:"Account Name",
        search : $("#pop_search11").val() 
    }, function(r){
        $("#sr11").html(r);
        settings9();            
    }, "text");
}

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#saccount").val($(this).children().eq(0).html());
        $("#account").val($(this).children().eq(0).html());
        $("#account_des").val($(this).children().eq(1).html());
        $("#pop_close11").click();                
    })    
}


function load_data8(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_journal_type_sum",
        field:"code",
        field2:"description",
        preview2:"Type Name",
        add_query:"AND payble_type='1'",
        search : $("#pop_search6").val() 
    }, function(r){
        $("#sr6").html(r);
        settings8();            
    }, "text");
}
function settings8(){
    $("#item_list .cl").click(function(){        
        $("#sjournal_type").val($(this).children().eq(0).html());
        $("#journal_type").val($(this).children().eq(0).html());
        $("#journal_type_des").val($(this).children().eq(1).html());
        $("#pop_close6").click();  
        load_je_type($(this).children().eq(0).html());
    })    
}


function set_jt_values(f) {
    var v = f.val();
    v = v.split("~");
    if (v.length == 2) {
        f.val(v[0]);
        $("#journal_type").val(v[0]);
        $("#journal_type_des").val(v[1]);
        $("#journal_type_des").attr("class", "input_txt_f");
        load_je_type(v[0]);
    }
}

function set_account_values(f) {
    var v = f.val();
    v = v.split("~");
    if (v.length == 2) {
        f.val(v[0]);
        $("#account").val(v[0]);
        $("#account_des").val(v[1]);
        $("#account_des").attr("class", "input_txt_f");
    }
}

function formatAccount(row) {
    return "<strong> " + row[0] + "</strong> | <strong> " + row[1] + "</strong>";
}

function formatAccountResult(row) {
    return row[0] + "~" + row[1];
}

function formatjtype(row) {
    return "<strong> " + row[0] + "</strong> | <strong> " + row[1] + "</strong>";
}

function formatjtypeResult(row) {
    return row[0] + "~" + row[1];
}
/*
$(document).keydown(function (e) {
    if (e.keyCode == 112) {
        $("#0_0").focus();
    }
});
*/
function load_je_type(je_type)
{
    $.post("index.php/main/load_data/t_journal_sum/load_je_type", {
        je_type: je_type

    }, function (r)
    {
        if (r.sum.code != undefined) {

            $("#saccount").attr("readonly", "readonly");
            $("#saccount").val(r.sum.account);
            $("#account").val(r.sum.account);
            $("#account_des").val(r.sum.payable_acc);

            empty_grid();
            for (var i = 0; i < r.det.length; i++) {
                $("#h_" + i).val(r.det[i].account_code);
                $("#0_" + i).val(r.det[i].account_code);
                $("#n_" + i).val(r.det[i].acc_des);
                $("#1_" + i).val(r.det[i].cr);
            }
            set_cr_total();
        }
        else
        {
            alert("No record found for this payable type")
        }

    }, "json");
}

function set_delete() {
    var id = $("#hid").val();
    if (id != 0) {
        if (confirm("Are you sure ? ")) {
            $.post("index.php/main/delete/t_payable_invoice", {
                id: id
            }, function (r) {
                if (r != 1) {
                    set_msg(r, "error");
                } else {
                    location.href = "";
                }
            }, "text");
        }
    } else {
        alert("Please load record");
    }
}

function empty_grid() {
    for (var i = 0; i < $("#grid_row").val(); i++) {
        $("#h_" + i).val(0);
        $("#0_" + i).val("");
        $("#n_" + i).val("");
        $("#1_" + i).val("");
        $("#2_" + i).val("");
        $("#3_" + i).val("");
    }
}

function load_data(id) {
    empty_grid();
    $.post("index.php/main/get_data/t_payable_invoice/", {
        id: id
    }, function (r) {
        if (r.sum.no != undefined) {

            $("#date").val(r.sum.date);
            $("#journal_type").val(r.sum.payable_type);
            $("#sjournal_type").val(r.sum.payable_type);
            $('#journal_type_des').val(r.sum.payable_type_des);
            $("#description").val(r.sum.payable_des);
            $("#ref_no").val(r.sum.ref_no);
            $("#narration").val(r.sum.narration);
            $("#saccount").val(r.sum.payable_account);
            $("#account").val(r.sum.payable_account);
            $("#account_des").val(r.sum.payble_account_des);

            load_accounts();

            for (var i = 0; i < r.det.length; i++) {
                $("#h_" + i).val(r.det[i].account_code);
                $("#0_" + i).val(r.det[i].account_code);
                $("#n_" + i).val(r.det[i].acc_des);
                $("#1_" + i).val(r.det[i].amount);
            }
            set_cr_total();
            if (r.sum.is_cancel > 0) {
                set_msg("This record canceled.", "error");

                $("#btnDelete1").attr("disabled", "disabled");
                $("#btnSave1").attr("disabled", "disabled");
                $("#btnPrint").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                //$("#mframe").css("background-repeat", "repeat-x");
                //$("#mframe").css("background-position", "center");
            }
            is_edit = 1;
            $("#hid").val(r.sum.no);
            is_edit = 1;
            $("#hid").val(r.sum.no);
            $("#qno").val(r.sum.no);
            $("#pblt").val(r.sum.receivable_type);
            input_active();
        } else {
            alert("No records");
        }
    }, "json");
}

function select_search() {
    $("#pop_search").focus();
    $("#pop_search").val("");
}
function load_accounts() {
    $.post("index.php/main/load_data/m_account/account_list", {
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

function set_cr_total() {

    var t = tt = 0;
    $(".cr").each(function (e) {
        if ($("#0_" + e).val() != "") {
            tt = parseFloat($(this).val());
            if (isNaN(tt)) {
                tt = 0;
            }
            t += tt;
        }
    });

    $("#total").val(m_round(t));
}

function validate() {
    var v = false;
    $("input[type='hidden']").each(function () {
        if ($(this).val() != "" && $(this).val() != 0) {
            v = true;
        }
    });

    if (v == false) {
        alert("Please use minimum one item.");
    } else if ($("#description").val() == '') {
        alert("Please enter description");
        v = false;
    }
    else if ($("#account").val() == 0) {
        alert("Please select payable account");
        v = false;
    }
    else if ($("#description").val() == "") {
        alert("Please enter description");
        v = false;
    }
    else if (parseFloat($("#total").val() == 0))
    {
        alert("Please enter expense amount");
        v = false;
    }

    return v;
}

function save()
{

    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid) {
            var sid=pid.split('@');
            loding();
            if (sid[0]==1) {
                //sucess_msg();
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

            }
            else
            {
                set_msg(pid, "error")
            }
        }
    });

    is_edit = 0;
}

function closeMsgBox() {
    $(".msgBox").fadeOut(500);
    empty_grid();
    input_reset();
    get_max();
    clear();
}



function get_max() {
    $.post("/index.php/main/load_data/t_payable_invoice/get_max", {
    }, function (r) {
        $("#id").val(r.max_no);
    }, "json");
}
