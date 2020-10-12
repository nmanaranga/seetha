var is_edit=0; 
$(document).ready(function(){

  $('#tabs').tabs();

  $("#id").blur(function(){
      check_code();
  });
    
  $("#btnSave1").click(function(){
    if(validate()){
      save();
    }  
  });

  $("#btnReset").click(function(){
   	location.href="?action=chq_account_setup";
	});

   
  $("#btnPrint").click(function(){
      $("#print_pdf").submit();
  });

  $("#srchee").keyup(function(){  
  	$.post("index.php/main/load_data/utility/get_data_table", {
        code:$("#srchee").val(),
        tbl:"chq_account_setup",
        tbl_fied_names:"Code-Name",
        fied_names:'id-name'
      }, function(r){
	        $("#grid_body").html(r);
  	  }, "text");
	});
	
  $("#code").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search4").val($("#code").val());
      load_account();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);   
    }
    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_account();
      }
    });
    if(e.keyCode==46){
      $("#code").val("");
      $("#des").val("");
    }
  });

  $("#scheme_code").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search").val($("#scheme_code").val());
      load_scheme();
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search').focus()", 100);   
    }
    $("#pop_search").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_scheme();
      }
    });
    if(e.keyCode==46){
      $("#scheme_code").val("");
      $("#scheme_des").val("");
    }
  });


});


function load_account(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_account",
      field:"code",
      field2:"description",
      preview2:"Name",
      add_query:"AND is_bank_acc='1'",
      search : $("#pop_search4").val() 
  }, function(r){
      $("#sr4").html(r);
      settings10();      
  }, "text");
}

function settings10(){
    $("#item_list .cl").click(function(){        
        $("#code").val($(this).children().eq(0).html());
        $("#des").val($(this).children().eq(1).html());
        $("#pop_close4").click(); 
        $("#pop_close").click();                
    })    
}

function load_scheme(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"chq_print_scheme",
      field:"code",
      field2:"name",
      preview2:"Name",
      search : $("#pop_search").val() 
  }, function(r){
      $("#sr").html(r);
      settings11();      
  }, "text");
}

function settings11(){
    $("#item_list .cl").click(function(){        
        $("#scheme_code").val($(this).children().eq(0).html());
        $("#scheme_des").val($(this).children().eq(1).html());
        $("#pop_close").click();                
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
          set_msg("Error : \n"+pid);
      }
    }
  });
}


function get_data_table(){
    $.post("index.php/main/load_data/m_customer/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}



function check_code(){
    var code = $("#id").val();
    $.post("index.php/main/load_data/chq_account_setup/check_code", {
        code : code
    }, function(res){
        if(res.data!= 2){
            if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
                set_edit(code);
            }else{
                $("#id").val('');
            }
        }
    }, "json");
}




function validate(){
  if($("#code").val()=="" || $("#scheme_code").val()=="" || $("#id").val()=="" || $("#name").val()==""){
    set_msg("Please fill Code and scheme code");
    return false;
  }else{
    return true;
  }  
}
    
function set_delete(code){
   if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/chq_account_setup", {
            code : code
        }, function(res){
            if(res == 1){
                loding();
                delete_msg();
            }else{
                set_msg(res);
            }            
        }, "text");
    }
}



    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/chq_account_setup", {
        code : code
    }, function(res){   
        $("#id").val(res[0].id);
        $("#code_").val(res[0].id);
        $("#name").val(res[0].name);     
        $("#code").val(res[0].code);
        $("#des").val(res[0].description);
        $("#scheme_code").val(res[0].scheme_code);
        $("#scheme_des").val(res[0].ch_name);
        $("#stamp_1").val(res[0].stamp_1);
        $("#stamp_2").val(res[0].stamp_2);
        $("#id").attr("readonly",true);
        $("#Acc_id").text(res[0].code);
        $("#print_s_code").text(res[0].scheme_code);
        $("#stamp_01").text("");
        $("#stamp_02").text("");
        $("#stamp_03").text(res[0].stamp_1);
        $("#stamp_04").text(res[0].stamp_2);
        loding(); 
    }, "json");
}


