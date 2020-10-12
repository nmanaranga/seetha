$(document).ready(function(){
  $("#code").blur(function(){
    check_code();
  });

  $("#grid").tableScroll({height:355, width:590});

	$("#srchee").keyup(function(){  
		$.post("index.php/main/load_data/utility/get_data_table", {
   	 	code:$("#srchee").val(),
   	 	tbl:"g_m_gift_voucher",
    	tbl_fied_names:"Code-Description-Price",
      fied_names:"code-description-price",
      col4:"Y",
      is_r:"Y"
     }, function(r){
    		$("#grid_body").html(r);
		}, "text");
	});

  $("#supplier").keydown(function(e){
      if(e.keyCode == 112){
        $("#pop_search4").val($("#supplier").val());
        load_supp();
        $("#serch_pop4").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search4').focus()", 100);
      }
      $("#pop_search4").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_supp();
        }
      }); 
      if(e.keyCode == 46){
        $("#supplier").val("");
        $("#supplier_des").val("");
      }
  });
});


function load_supp(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"m_supplier",
    field:"code",
    field2:"name",
    preview2:"Name",
    search : $("#pop_search4").val() 
  }, 
  function(r){
    $("#sr4").html(r);
    settings_supp();            
  }, "text");
}

function settings_supp(){
  $("#item_list .cl").click(
  function(){        
    $("#supplier").val($(this).children().eq(0).html());
    $("#supplier_des").val($(this).children().eq(1).html());
    $("#pop_close4").click();                
  })    
}

function save(){
  var frm = $('#form_');
  loding();
  $.ajax({
  	type: frm.attr('method'),
  	url: frm.attr('action'),
  	data: frm.serialize(),
  	success: function (pid){
      if(pid == 1){
          loding();
          sucess_msg();
      }else if(pid == 2){
          set_msg("No permission to add data.");
      }else if(pid == 3){
          set_msg("No permission to edit data.");
      }else{
          set_msg(pid);
      }
    }
  });
}

function get_data_table(){
  $.post("index.php/main/load_data/m_gift_voucher/get_data_table", {
  }, function(r){
      $("#grid_body").html(r);
  }, "text");
}

function check_code(){
  loding();
  var code = $("#code").val();
  $.post("index.php/main/load_data/m_gift_voucher/check_code", {
      code : code
  }, function(res){
    if(res == 1){
      if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
        set_edit($("#code").val());
      }else{
        $("#code").val('');
      }
    }
    loding();
  }, "text");
}

function validate(){
  if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
    set_msg("Please enter code.");
    $("#code").focus();
    return false;
  }else if($("#cost").val()==""){
    set_msg("Please Enter Cost");
    $("#cost").focus();
    return false;
  }else if($("#supplier").val()==""){
    set_msg("Please Enter supplier");
    $("#supplier").focus();
    return false;
  }else if($("#price").val()==""){
    set_msg("Please Enter price");
    $("#price").focus();
    return false;    
  }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
    set_msg("Please enter description.");
    $("#description").focus();
    return false;
  }else if(parseFloat($("#cost").val()) > parseFloat($("#price").val())){
    set_msg("price should be greater than cost");
    $("#price").focus();
    return false;
  }else{
    return true;
  }
}
    
function set_delete(code){
  if(confirm("Are you sure delete "+code+"?")){
    loding();
    $.post("index.php/main/delete/m_gift_voucher", {
      code : code
    }, function(res){
      if(res == 1){
         loding();
         delete_msg();
      }else{
          loding();
          set_msg(res);
      }
    }, "text");
  }
}

 
function set_edit(code){
  loding();
  $.post("index.php/main/get_data/m_gift_voucher", {
    code : code,
  }, function(res){
    $("#code_").val(res.code);
    $("#code").val(res.code);
    $("#code").attr("readonly","readonly");
    $("#description").val(res.description);
    $("#cost").val(res.cost);
    $("#price").val(res.price);     
    $("#supplier").val(res.supplier);
    $("#supplier_des").val(res.name);
    loding();
  }, "json");
}