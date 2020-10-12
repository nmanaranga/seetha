$(document).ready(function(){
    
  $("#emp_id").keydown(function(e){
    if(e.keyCode == 112){
        $("#pop_search4").val($("#emp_id").val());
        load_emp();
        $("#serch_pop4").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search4').focus()", 100);
    }
    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_emp();
      }
    }); 
    if(e.keyCode == 46){
      $("#emp_id").val("");
      $("#emp_name").val("");
    }
  });


  $("#category").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search2").val($("#category").val());
      load_category();
      $("#serch_pop2").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);
    }
    $("#pop_search2").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_category();
      }
    }); 
    if(e.keyCode == 46){
      $("#category").val("");
      $("#cat_name").val("");
    }
  });

  $("#srchee").keyup(function(){  
    $.post("index.php/main/load_data/m_employee_activity/search_result", {
            code:$("#srchee").val(),
        }, function(r){
          $("#grid_body").html(r);
      }, "text");
  });

  $("#btnPrint").click(function(){
    if($("#hid").val()=="0"){
      set_msg("Please load data before print");
      return false;
    }else{
      $("#print_pdf").submit();
    }
  });
    
});

function load_category(){
      $.post("index.php/main/load_data/utility/f1_selection_acc_code", {
          data_tbl:"m_employee_category",
          field:"code",
          field2:"description",
          preview2:"Category Name",
          search : $("#pop_search2").val() 
      }, 
      function(r){
          $("#sr2").html(r);
          settings_category();        
      }, "text");
  }

function load_emp(){
      $.post("index.php/main/load_data/utility/f1_selection_list", {
          data_tbl:"m_employee",
          field:"code",
          field2:"name",
          preview2:"Employee Name",
          search : $("#pop_search4").val() 
      }, 
      function(r){
          $("#sr4").html(r);
          settings_emp();            
      }, "text");
  }

function settings_emp(){
  $("#item_list .cl").click(function(){        
    $("#emp_id").val($(this).children().eq(0).html());
    $("#emp_name").val($(this).children().eq(1).html());
    $("#pop_close4").click();                
  })    
}

function settings_category(){
  $("#item_list .cl").click(function(){        
    $("#category").val($(this).children().eq(0).html());
    $("#cat_name").val($(this).children().eq(1).html());
    $("#pop_close2").click();                
  })    
}

function validate(){
    save();
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
            }else{
              loding();
              set_msg(pid);
            }
    
        }
    });
}


function empty_grid(){
  $("#emp_id").val("");
  $("#emp_name").val("");
  $("#category").val("");
  $("#cat_name").val("");
  $("#designation").val("");
  $("#desig_name").val("");
  $("#note").val("");
  $("#is_active").attr("checked",false);
} 


function set_edit(code){
    empty_grid();
    loding();
    $.post("index.php/main/get_data/m_employee_activity", {
        code : code
    }, function(res){
        $("#hid").val(res[0].nno);
        $("#id").val(res[0].nno);
        $("#ddate").val(res[0].ddate);
        $("#emp_id").val(res[0].employee_id);
        $("#emp_name").val(res[0].name);
        $("#category").val(res[0].category);
        $("#cat_name").val(res[0].description);
        $("#designation").val(res[0].designation);
        $("#note").val(res[0].note);
        if(res[0].is_active=="1"){
          $("#is_active").attr("checked",true);
        }
        loding(); 
    }, "json");
}

function set_delete(code){
  if(confirm("Are you sure delete "+code+"?")){
    loding();
    $.post("index.php/main/delete/m_employee_activity", {
       code : code
    },function(res){
      if(res == 1){
        loding();
        delete_msg();
      }else{
        set_msg(res);
      }
    }, "text");
  }
}