var storse = 0;
var is_edit = 0;
$(document).ready(function () {

    $("#btnPrint").click(function () {
        if ($("#hid").val() == "0") {
            alert("Please load data before print");
            return false;
        }
        else
        {
            $("#print_pdf").submit();
        }
    });
//  $(".cl").each(function(){
//      
//  });
   // load_accounts();

    $("#tgrid").tableScroll({height: 280});

    $(".fo").keydown(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            $("#serch_pop").center($("#0_"+scid).val());
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
            load_accounts();
        }

        if(e.keyCode==46){
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#3_"+scid).val("");
            $("#2_"+scid).val("");
        }

        if(e.keyCode==13){
            $.post("index.php/main/load_data/t_journal_sum/get_account", {
                code:$("#0_"+scid).val()
            }, function(res){
                if(res.a!=2){
                  $("#0_"+scid).val(res.a[0].code);
                  if(check_item_exist($("#0_"+scid).val())){

                    $("#h_"+scid).val(res.a[0].code);
                    $("#0_"+scid).val(res.a[0].code);
                    $("#n_"+scid).val(res.a[0].description);
                   
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

    $(".dr").keyup(function () {
        set_cid($(this).attr("id"));
        set_dr_total();
    });

    $(".cr").keyup(function () {
        set_cid($(this).attr("id"));
        set_cr_total();
    });

    $("#id").keydown(function (e) {
        if (e.keyCode == 13) {
            $(this).blur();
            $("#qno").val($(this).val());
            $("#jtype").val($("#sjournal_type").val());
            $("#jtype_desc").val($("#description").val());

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

    $("#sjournal_type").autocomplete('index.php/main/load_data/r_journal_type/auto_com', {
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


   



});

function check_delete_permission() {
    set_delete();
}

function check_permission() {
    save();
}


function load_data8(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_journal_type_sum",
        field:"code",
        field2:"description",
        preview2:"Type Name",
        add_query:"AND payble_type='2'",
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

function formatjtype(row) {
    return "<strong> " + row[0] + "</strong> | <strong> " + row[1] + "</strong>";
}

function formatjtypeResult(row) {
    return row[0] + "~" + row[1];
}

/*$(document).keydown(function (e) {
    if (e.keyCode == 112) {
        $("#0_0").focus();
    }
});*/

function load_je_type(je_type)
{
    $.post("index.php/main/load_data/t_journal_sum/load_je_type", {
        je_type: je_type

    }, function (r)
    {
        if (r.sum.code != undefined) {
            empty_grid();
            for (var i = 0; i < r.det.length; i++) {
                $("#h_" + i).val(r.det[i].account_code);
                $("#0_" + i).val(r.det[i].account_code);
                $("#n_" + i).val(r.det[i].acc_des);
                $("#2_" + i).val(r.det[i].dr);
                $("#3_" + i).val(r.det[i].cr);
            }
            set_dr_total();
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
            $.post("index.php/main/delete/t_journal_sum", {
                id: id
            }, function (r) {
                if (r != 1) {
                    set_msg(r,"error");
                } else {
                    //$("#btnReset").click();
                    location.href="";
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
    $.post("index.php/main/get_data/t_journal_sum/", {
        id: id
    }, function (r) {
        if (r.sum.no != undefined) {

            $("#date").val(r.sum.date);
            $("#journal_type").val(r.sum.journal_type);
            $("#sjournal_type").val(r.sum.journal_type);
            $('#journal_type_des').val(r.sum.journal_type_des);
            $("#description").val(r.sum.je_des);
            $("#ref_no").val(r.sum.ref_no);
            $("#narration").val(r.sum.narration);
            load_accounts();

            for (var i = 0; i < r.det.length; i++) {
                $("#h_" + i).val(r.det[i].account_code);
                $("#0_" + i).val(r.det[i].account_code);
                $("#n_" + i).val(r.det[i].acc_des);
                $("#1_" + i).val(r.det[i].memo);
                $("#2_" + i).val(r.det[i].dr_amount);
                $("#3_" + i).val(r.det[i].cr_amount);
            }
            set_dr_total();
            set_cr_total();
            if (r.sum.is_cancel > 0) {
                set_msg("This record canceled.","error");

                $("#btnDelete1").attr("disabled", "disabled");
                $("#btnSave1").attr("disabled", "disabled");
                $("#btnPrint").attr("disabled", "disabled");
                $("#mframe").css("background-image", "url('img/cancel.png')");
                //$("#mframe").css("background-repeat", "repeat-x");
                //$("#mframe").css("background-position", "center");
            }
            is_edit = 1;
            $("#hid").val(r.sum.no);
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

function set_dr_total() {

    var t = tt = 0;
    $(".dr").each(function (e) {
        if ($("#0_" + e).val() != "") {
            tt = parseFloat($(this).val());
            if (isNaN(tt)) {
                tt = 0;
            }
            t += tt;
        }
    });

    $("#tot_dr").val(m_round(t));
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

    $("#tot_cr").val(m_round(t));
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
    if ($("#tot_dr").val() == 0) {
        alert("Please enter valid dr amount");
        v = false;
    }
    if ($("#tot_cr").val() == 0) {
        alert("Please enter valid cr amount");
        v = false;
    }
    if (parseFloat($("#tot_cr").val()) != parseFloat($("#tot_dr").val()))
    {
        alert("Dr amount and cr amount should be tally");
        v = false;
    }

    return v;
}

function save() {
    $("#is_duplicate").val("1");
    var frm = $('#form_');
    loding();
    $.ajax({
        type: frm.attr('method'),
        url: frm.attr('action'),
        data: frm.serialize(),
        success: function (pid) {
            var sid=pid.split('@');
            loding();
            if (sid[0]==1)
            {
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

            } else {
                set_msg(pid,"error");
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
    //clear(); 
}



function get_max() {
    $.post("/index.php/main/load_data/t_journal_sum/get_max", {
    }, function (r) {
        $("#id").val(r.max_no);
    }, "json");
}
