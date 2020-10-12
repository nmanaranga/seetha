$(document).ready(function(){

  $("#payment_option").attr("checked", "checked");
  $("#btnSave").attr("disabled","disabled");
  $("#issue1").prop("checked", true);

  $("#showPayments").click(function(){
    payment_opt('t_gift_voucher',$("#net").val());
    $("#btnSave").removeAttr("disabled","disabled");
  });

  $("#btnReset").click(function(){
  	location.href="index.php?action=t_gift_voucher";
  });

  $("#btnSave").click(function(){
    save();
  });
       
  $("#grid").tableScroll({height:355});

  $("#no").keydown(function(e){
    if(e.keyCode == 13){
      $(this).blur();
      load_data($(this).val());
      load_payment_option_data($(this).val(),"28");
    }
  }); 

  $(".radi").click(function(){
    $("#from").val("");
    $("#from_des").val("");
  });

  $("#issue1").click(function(){
    $("#type").val("1");

    $("#from").autocomplete("", {
      width: 350,
      multiple: false,
      matchContains: true,
      formatItem: formatItemss,
      formatResult: formatItemsResultt
    });

    $("#from").keydown(function(a) {
      if (13 == a.keyCode) set_cus_values12($(this));
    });

    $("#from").blur(function() {
      set_cus_values12($(this));
    });

  });

  $("#issue2").click(function(){
    $("#type").val("2");
    $("#from").autocomplete("index.php/main/load_data/m_customer/auto_com", {
      width: 350,
      multiple: false,
      matchContains: true,
      formatItem: formatItemss,
      formatResult: formatItemsResultt
    });
    
    $("#from").keydown(function(a) {
      if (13 == a.keyCode) set_cus_values12($(this));
    });
    
    $("#from").blur(function() {
      set_cus_values12($(this));
    });
  });

  $("#issue3").click(function(){
    $("#type").val("3");
    $("#from").autocomplete("index.php/main/load_data/m_supplier/auto_com", {
      width: 350,
      multiple: false,
      matchContains: true,
      formatItem: formatItemss,
      formatResult: formatItemsResultt
    });

    $("#from").keydown(function(a) {
      if (13 == a.keyCode) set_cus_values12($(this));
    });

    $("#from").blur(function() {
      set_cus_values12($(this));
    }); 
  });

   $("#btnDelete").click(function(){
      deletes();
   });

  $("#from").keydown(function(e){ 
    if(e.keyCode==112){
      if($("#type").val()=="2"){
         $("#pop_search").val($("#from").val());
         load_customer(); 
         $("#serch_pop").center();
         $("#blocker").css("display", "block");
         setTimeout("$('#pop_search').focus()", 100);   

        $("#pop_search").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
            load_customer();
          }
        });      
      }else if($("#type").val()=="3"){
        $("#pop_search2").val($("#from").val());
         load_supplier(); 
         $("#serch_pop2").center();
         $("#blocker2").css("display", "block");
         setTimeout("$('#pop_search2').focus()", 100);   

        $("#pop_search2").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
            load_supplier();
          }
        });     
      }
    }
    if(e.keyCode==46){
       $("#from").val("");
       $("#from_des").val("");
    }   
  });

});

function load_supplier(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_supplier",
      field:"code",
      field2:"name",
      preview2:"Supplier Name",
      search : $("#pop_search2").val() 
  }, function(r){
      $("#sr2").html(r);
      settings_sup();      
 }, "text");
}

function settings_sup(){
    $("#item_list .cl").click(function(){        
        $("#from").val($(this).children().eq(0).html());
        $("#from_des").val($(this).children().eq(1).html());
        $("#pop_close2").click();                
    })    
}


/*function load_customer(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_customer",
      field:"code",
      field2:"name",
      preview2:"Customer Name",
      search : $("#pop_search").val() 
  }, function(r){
      $("#sr").html(r);
      settings_cus();      
 }, "text");
}*/


function load_customer(){
    $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
        data_tbl:"m_customer",
        field:"code",
        field2:"name",
        field3:"nic",
        preview1:"Customer ID",
        preview2:"Customer Name",
        preview3:"Customer NIC",
        search : $("#pop_search").val() 
    }, function(r){
        $("#sr").html("");
        $("#sr").html(r);
        settings_cus();            
    }, "text");
}

function settings_cus(){
    $("#item_list .cl").click(function(){        
        $("#from").val($(this).children().eq(0).html());
        $("#from_des").val($(this).children().eq(1).html());
        $("#pop_close").click();                
    })    
}

function set_cus_values12(a) {
  var b = a.val();
  b = b.split("|");
  if (2 == b.length) {
    a.val(b[0]);
    $("#from_des").val(b[1]);
  }
}

function formatItemss(a) {
  return "<strong> " + a[0] + "</strong> | <strong> " + a[1] + "</strong>";
}

function formatItemsResultt(a) {
  return a[0] + "|" + a[1];
}

function save(){

var validation= validate();

if(validation==0){

  $('#form_').attr('action',$('#form_id').val()+"t_gift_voucher");
  var frm = $('#form_');
  loding();
  $.ajax({
  	type: frm.attr('method'),
  	url: frm.attr('action'),
  	data: frm.serialize(),
  	success: function (pid){
      if(pid == 1){
        $("#btnSave").attr("disabled",true);
        $("#showPayments").attr("disabled",true);
        sucess_msg();
      }else if(pid == 2){
        alert("No permission to add data.");
      }else if(pid == 3){
        alert("No permission to edit data.");
      }else{
        alert("Error : \n"+pid);
      }
      loding();
    }
  });

  }else{
    set_msg(validation,"error");
  }
}

function get_data_table(){
  $.post("/index.php/main/load_data/t_gift_voucher/get_data_table", {      
  }, function(r){
    $("#grid_body").html(r);
  }, "text");
}

function load_data(id){
  loding();
  $.post("index.php/main/get_data/t_gift_voucher/", {
    id: id
  }, function(r){
    if(r=="2"){
       alert("No records");
    }else{
      var type ="";
      $("#from_des").val("");
      $("#no").attr('readonly', 'readonly');

      $("#hid").val(id); 
      $("#date").val(r.det[0].ddate); 
      $("#ref_no").val(r.det[0].ref_no);
      
      $("#from").val(r.det[0].account_id);
      $("#description").val(r.det[0].description);

      $("#net").val(r.det[0].total_amount);
      $("#id").attr("readonly","readonly")  
      $("#dt").val(r.det[0].ddate);
      $("#reciviedAmount").val(r.det[0].total_amount);

      if(r.det[0].type=="1"){
        $("#issue1").prop("checked", true);
        type ="1";
      }else if(r.det[0].type=="2"){
        $("#issue2").prop("checked", true);
        type ="2";
      }else if(r.det[0].type=="3"){
        $("#issue3").prop("checked", true);
        type ="3";
      }

       if(r.det[0].is_cancel=="1") {
         
          $(".form").css("background-image", "url('img/cancel.png')");
          $("#btnDelete").attr("disabled", true);
          $("#btnPrint").attr("disabled", true);
          $("#btnSave").attr("disabled", true);
          $("#showPayments").attr("disabled", true);
          
       }   

  $.post("index.php/main/load_data/t_gift_voucher/loadname", {
        code:r.det[0].account_id,
        type:r.det[0].type
      }, function(r){
        input_active();
        if(r!=2){
          $("#from_des").val(r.det[0].name);
        }
      },"json");

        input_active();
       
        }
    loding();
    }, "json");

}


function validate(){
var status="0";

    if($("#from").val() ==""){
        return status ="Please enter from field.";
    }else if($("#description").val()==""){
        return status ="Please enter to field.";
    }else if($("#net").val()==""){
        return status ="Amount can't be empty.";
    }else if(parseInt($("#net").val()) <= 0){
        return status ="Amount should be greater than 0";
    }else{
        return status;
    }
}




function deletes(){

        loding();
        $.post("index.php/main/load_data/t_gift_voucher/checkdelete", {
            ids: $("#no").val(),
        },
        function (r) {
             loding();
            if (r == 2) {
                alert("Please enter exsits gift voucher ID");
                return false;
            } else {
                var id = $("#no").val();
                loding();
                if (confirm("Are you sure delete " + id + "?")) {
                    $.post("index.php/main/load_data/t_gift_voucher/deleteGiftVoucher", {
                        id: id,
                    },
                    function (r) {
                      delete_msg();
                    }, "json")
                }
                /////
            }
            loding();
        }, "json")
    //});
}

    