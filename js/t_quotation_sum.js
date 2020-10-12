$(document).ready(function() {
  $("#btnSavee").css("display", "none");

  $("#id").keydown(function(e) {
    if (e.keyCode == 13) {
      $(this).blur();
      load_data($(this).val());
      $("#qno").val($('#id').val());

    }
  });

  $("#btnReset").click(function() {
    location.href = "?action=t_quotation_sum";
  });

  $("#grid").tableScroll({
    height: 355
  });
  $("#tgrid").tableScroll({
    height: 355
  });
  $("#qno").val($('#id').val());
  $("#cus_id").val($('#customer').val());

  $("#click").click(function() {
    var x = 0;
    $(".me").each(function() {
      set_msg(x);
      if ($(this).val() != "" && $(this).val() != 0) {
        v = true;
      }
      x++;
    });
  });

  $("#officer").keydown(function(e) {
    if (e.keyCode == 112) {
      $("#pop_search4").val($("#officer").val());
      load_emp();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);
    }
    $("#pop_search4").keyup(function(e) {
      if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
        load_emp();
      }
    });
    if (e.keyCode == 46) {
      $("#officer").val("");
      $("#officer2").val("");
    }
  });

  $(".fo").keydown(function(e) {
    set_cid($(this).attr("id"));
    if (e.keyCode == 112) {

      $("#pop_search").val($("#0_" + scid).val());
      load_items();
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("select_search()", 100);
    }

    if (e.keyCode == 46) {


      $("#h_" + scid).val("");
      $("#0_" + scid).val("");
      $("#n_" + scid).val("");
      $("#1_" + scid).val("");
      $("#2_" + scid).val("");
      $("#3_" + scid).val("");
      $("#4_" + scid).val("");
      $("#5_" + scid).val("");
      $("#t_" + scid).html("&nbsp;");
      $("#6 _" + scid).val("");
      set_discount();
      total();
      set_total();
    }


    if (e.keyCode == 13) {
      $.post("index.php/main/load_data/t_quotation_sum/get_item", {
        code: $("#0_" + scid).val()
      }, function(res) {
        if (res.a != 2) {
          $("#0_" + scid).val(res.a[0].code);

          if (check_item_exist($("#0_" + scid).val())) {
            $("#h_" + scid).val(res.a[0].code);
            $("#n_" + scid).val(res.a[0].description);
            $("#0_" + scid).val(res.a[0].code);
            $("#1_" + scid).val(res.a[0].model);
            $("#3_" + scid).val(res.a[0].max_price);

          } else {
            set_msg("Item " + $("#0_" + scid).val() + " is already added.", "error");
          }

        } else {
          set_msg("Item not in stock type item detail manualy")
        }
      }, "json");

    }


  });

  load_items();
  $("#pop_search").keyup(function(e) {
    if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40) {
      load_items();
    }
  });

  $(".dis, .qun, .dis_pre").blur(function() {
    set_cid($(this).attr("id"));
    set_discount();
    total();
    set_total();

  });

  $(".free_is").blur(function() {
    set_cid($(this).attr("id"));
    set_discount();
    total();
    set_total();
  });


  $("#customer").keydown(function(e) {
    if (e.keyCode == 112) {
      $("#pop_search2").val($("#customer").val());
      load_customer();
      $("#serch_pop2").center();
      $("#blocker2").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);
    }
    $("#pop_search2").keyup(function(e) {
      if (e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112) {
        load_customer();
      }
    });
    if (e.keyCode == 46) {
      $("#customer").val("");
      $("#customer_id").val("");
    }
  });



  $("#pop_search").gselect();


  $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });

  $("#customer").keydown(function(e) {
    if (e.keyCode == 13) {
      set_cus_values($(this));
    }
  });

  $("#customer").blur(function() {
    set_cus_values($(this));
  });


  $("#btnDelete").click(function() {
    var id = $("#id").val();
    if (id == "") {
      set_msg('Please enter no', "error");
    } else {
      var id = $("#id").val();
      $.post("index.php/main/load_data/t_quotation_sum/check_code", {
        id: id,
      }, function(r) {

        if (r == 1) {
          set_delete(id);
        } else {
          set_msg("Please enter valid quotation No", "error");
        }
      });
    }
  });


  $("#btnPrint").click(function() {
    $("#cus_id").val($('#customer').val());
    $("#print_pdf").submit();
  });



});

/*function load_customer(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_customer",
      field:"code",
      field2:"name",
      preview2:"Customer Name",
      search : $("#pop_search2").val() 
  }, function(r){
      $("#sr2").html(r);
      settings_cus();      
 }, "text");
}*/

function load_emp(){
  $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
      filter_emp_cat:"salesman",
      search : $("#pop_search4").val() 
  }, function(r){
      $("#sr4").html(r);
      settings_emp();      
 }, "text");
}

function settings_emp(){
    $("#item_list .cl").click(function(){        
        $("#officer").val($(this).children().eq(0).html());
        $("#officer2").val($(this).children().eq(1).html());
        $("#pop_close4").click();                
    })    
}


function load_customer() {
  $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
    data_tbl: "m_customer",
    field: "code",
    field2: "name",
    field3: "nic",
    preview1: "Customer ID",
    preview2: "Customer Name",
    preview3: "Customer NIC",
    search: $("#pop_search2").val()
  }, function(r) {
    $("#sr2").html("");
    $("#sr2").html(r);
    settings_cus();
  }, "text");
}

function settings_cus() {
  $("#item_list .cl").click(function() {
    $("#customer").val($(this).children().eq(0).html());
    $("#customer_id").val($(this).children().eq(1).html());
    get_cus_address($(this).children().eq(0).html());
    $("#pop_close2").click();
  })
}

function get_cus_address(cus){
  $.post("index.php/main/load_data/t_quotation_sum/cus_address", {
    code:cus
  }, function(r) {
    $("#address").val(r);
  }, "text");
}

function set_cus_values(f) {
  var v = f.val();
  v = v.split("-");

  if (v.length == 2) {
    //$("#vehicle_no").val(v[0]);
    f.val(v[0]);
    $("#customer_id").val(v[1]);
    // $("#customer_id").attr("class", "input_txt_f");
    var cus = $("#customer").val();
    $.post("index.php/main/load_data/m_customer/load", {
      code: cus,
    }, function(rs) {

      $("#address").val(rs.data.address1 + ", " + rs.data.address2 + ", " + rs.data.address3);
      input_active();
    }, "json");

  }
}

function formatItems(row) {
  return "<strong> " + row[0] + "</strong> | <strong> " + row[1] + "</strong>";
}

function formatItemsResult(row) {
  return row[0] + "-" + row[1];
}


function save() {
  $("#qno").val($("#id").val());
  var frm = $('#form_');
  loding();
  $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    success: function(pid) {
      var sid=pid.split('@');
      if (sid[0]==1) {
        $("#btnSave").attr("disabled", true);
        if (confirm("Save Completed, Do You Want A print?")) {
          if ($("#is_prnt").val() == 1) {
            $("#qno").val(sid[1]);
            $("#print_pdf").submit();
          }
          reload_form();
        } else {
          location.href = "";
        }
        $("#btnSave").css("display", "none");
        $("#btnSavee").css("display", "inline");
      } else if (pid == 2) {
        set_msg("No permission to add data.");
      } else if (pid == 3) {
        set_msg("No permission to edit data.");
      } else {
        set_msg(pid, "error");
      }
      loding();
    }
  });
}

function reload_form() {
  setTimeout(function() {
    location.href = '';
  }, 100);
}




function empty_grid() {
  for (var i = 0; i < 25; i++) {
    $("#h_" + i).val(0);
    $("#0_" + i).val("");
    $("#n_" + i).val("");
    $("#t_" + i).html("&nbsp;");
    $("#1_" + i).val("");
    $("#2_" + i).val("");
    $("#3_" + i).val("");
    $("#4_" + i).val("");
    $("#5_" + i).val("");
    $("#6_" + i).val("");
  }
}



function load_data(id) {
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_quotation_sum/", {
    id: id
  }, function(r) {
    if (r == "2") {
      set_msg("No records", "error");
    } else {
      $("#hid").val(id);
      $("#customer").val(r.det[0].ccode);
      $("#customer_id").val(r.det[0].cus_name);
      $("#address").val(r.det[0].address);
      $("#date").val(r.det[0].ddate);
      $("#ref_no").val(r.det[0].ref_no);
      $("#officer").val(r.det[0].emp_id);
      $("#officer2").val(r.det[0].e_name);
      $("#memo").val(r.det[0].memo);
      $("#total2").val(r.det[0].gross_amount);
      $("#discount").val(r.det[0].dis);
      $("#net_amount").val(r.det[0].net_amount);
      $("#validity_period").val(r.det[0].validity_period);
      $("#id").attr("readonly", "readonly")

      for (var i = 0; i < r.det.length; i++) {
        $("#h_" + i).val(r.det[i].code);
        $("#0_" + i).val(r.det[i].code);
        $("#n_" + i).val(r.det[i].description);
        $("#1_" + i).val(r.det[i].model);
        $("#2_" + i).val(r.det[i].qty);
        $("#3_" + i).val(r.det[i].price);
        $("#4_" + i).val(r.det[i].discountp);
        $("#5_" + i).val(r.det[i].discount);
        $("#6_" + i).val(r.det[i].item_discription);
        if (r.det[i].is_cancel == 1) {
          set_msg("Quotation number is canceled", "error");
          $("#form_").css("background-image", "url('img/cancel.png')");
          $("#btnDelete").attr("disabled", true);
          $("#btnSave").attr("disabled", true);
        }

        var x = parseFloat($("#3_" + i).val());
        var y = parseFloat($("#2_" + i).val());
        var z;
        if (!isNaN(x) && !isNaN(y)) {
          z = x * y;
          $("#t_" + i).html(m_round(z));
        } else {
          $("#t_" + i).html("0.00");
        }
      }
      input_active();
    }
    loding();
  }, "json");
}




function validate() {

  var v = true;

  for (x = 0; x < 24; x++) {
    if ($("#0_" + x).val() != "" || $("#0_" + x).val()) {

    }
  }

  if ($("#id").val() == "") {
    set_msg("Please enter No.", "error");
    $("#id").focus();
    return false;
  } else if ($("#date").val() == "") {
    set_msg("Please select date", "error");
    $("#date").focus();
    return false;
  } else if ($("#officer").val() == "") {
    set_msg("Please select a Officer.", "error");
    $("#officer").focus();
    return false;
  }else if (v == false) {
    set_msg("Please use minimum one item.", "error");
  } else {
    return true;
  }
}



function set_delete(id) {
  if (confirm("Are you sure cancel " + id + "?")) {
    loding();
    $.post("index.php/main/delete/t_quotation_sum", {
      id: id
    }, function(res) {
      if (res == 1) {
        loding();
        delete_msg();
      } else {
        set_msg(res);
      }

    }, "text");
  }
}



function select_search() {
  $("#pop_search").focus();
}

function load_items() {
  $.post("index.php/main/load_data/t_quotation_sum/item_list_all", {
    search: $("#pop_search").val(),
    stores: false
  }, function(r) {
    $("#sr").html(r);
    settings();

  }, "text");
}

function settings() {
  $("#item_list .cl").click(function() {
    if ($(this).children().eq(0).html() != "&nbsp;") {
      if (check_item_exist($(this).children().eq(0).html())) {
        $("#h_" + scid).val($(this).children().eq(0).html());
        $("#0_" + scid).val($(this).children().eq(0).html());
        $("#n_" + scid).val($(this).children().eq(1).html());
        $("#1_" + scid).val($(this).children().eq(2).html());
        $("#3_" + scid).val($(this).children().eq(3).html());

        if ($(this).children().eq(4).html() == 1) {
          $("#11_" + scid).autoNumeric({
            mDec: 2
          });
        } else {
          $("#11_" + scid).autoNumeric({
            mDec: 0
          });
        }
        $("#1_" + scid).removeAttr("disabled");
        $("#2_" + scid).removeAttr("disabled");
        $("#3_" + scid).removeAttr("disabled");
        $("#2_" + scid).focus();
        $("#pop_close").click();
      } else {
        set_msg("Item " + $(this).children().eq(1).html() + " is already added.", "error");
      }
    } else {
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
      $("#0_" + scid).focus();
      set_total();
      $("#pop_close").click();
    }
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


function set_sub_total() {
  var x = parseFloat($("#1_" + scid).val());
  var y = parseFloat($("#2_" + scid).val());
  var z;
  if (!isNaN(x) && !isNaN(y)) {
    z = x * y;
    $("#t_" + scid).html(m_round(z));
  } else {
    $("#t_" + scid).html("0.00");
  }

  set_total();
}

function set_total2() {
  var t = tt = 0;
  $(".tf").each(function() {;
    tt = parseFloat($(this).html());
    if (isNaN(tt)) {
      tt = 0;
    }
    t += tt;
  });

  $("#total2").val(m_round(t));
}

function set_total() {
  var gross = 0;
  var discount = 0;
  var net = 0;
  var t = 0;
  $(".tf").each(function() {
    var x = parseFloat($("#2_" + t).val());
    var y = parseFloat($("#3_" + t).val());
    var z = parseFloat($("#5_" + t).val());
    if (isNaN(x) || isNaN(y)) {
      x = 0;
      y = 0;
    }
    gross += x * y;
    if (isNaN(z)) {
      z = 0;
    }
    discount += z;
    net = gross - discount;
    t++;
  });

  $("#total2").val(m_round(gross));
  $("#discount").val(m_round(discount));
  $("#net_amount").val(m_round(net));
}


function total() {
  var w = parseFloat($("#4_" + scid).val());
  var x = parseFloat($("#2_" + scid).val());
  var y = parseFloat($("#3_" + scid).val());
  var z = parseFloat($("#5_" + scid).val());
  var a;
  var b;

  if (!isNaN(x) && !isNaN(y) && !isNaN(z) && !isNaN(w)) {

    a = x * y;
    b = (a * w) / 100;
    a = a - z;
    $("#t_" + scid).html(m_round(a));
  } else if (!isNaN(x) && !isNaN(y) && !isNaN(z)) {

    a = x * y - z;
    $("#t_" + scid).html(m_round(a));
  } else if (!isNaN(x) && !isNaN(y)) {

    a = x * y;
    $("#t_" + scid).html(m_round(a));
  } else {

    $("#t_" + scid).html("0.00");
  }


}

function set_discount() {

  var x = parseFloat($("#3_" + scid).val());
  var y = parseFloat($("#2_" + scid).val());
  var dis_pre = "";
  var d = parseFloat($("#5_" + scid).val());

  if (!isNaN(x) && !isNaN(y) && !isNaN(d)) {
    dis_pre = (d * 100) / (y * x);
    if (isNaN(dis_pre) || !isFinite(dis_pre)) {
      $("#4_" + scid).val("");
    } else {
      $("#4_" + scid).val(m_round(dis_pre));
    }
  }

}

function set_discount2() {
  var x = parseFloat($("#3_" + scid).val());
  var y = parseFloat($("#2_" + scid).val());
  var z = x * y;
  var d = parseFloat($("#5_" + scid).val());
  if (isNaN(d)) {
    d = 0;
  }
  d = z - d;

  d = parseFloat($("#5_" + scid).val());
  if (isNaN(d)) {
    d = 0;
  }


  $("#t_" + scid).val(m_round(d));
  var a = d * 100 / z;

  if (isNaN(a)) {
    a = 0;
  }
  $("#4_" + scid).val(a);
  $("#4_" + scid).click();

}