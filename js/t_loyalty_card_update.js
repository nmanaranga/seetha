$(document).ready(function(){
  $("#card_no").keydown(function(e){
    if(e.keyCode == 13){
      check_code();
    }
  });

  $("#customer_id").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search2").val($("#customer_id").val());
      make_delay('load_data9()');
      $("#serch_pop2").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);
    }

    if(e.keyCode == 46){
     $("#customer_id").val("");
     $("#customer_des").val("");
     $("#address").val("");
     $("#tp").val("");
     $("#email").val("");


   }
 });

  $("#pop_search2").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
     make_delay('load_data9()');
   }
 });

  $("#grid").tableScroll({height:355});

  $("#card_cat").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode == 112){
      $("#pop_search4").val();
      load_card_cat();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);
    }
    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_card_cat();
     }
   }); 
    if(e.keyCode == 46){
      $("#card_cat").val(""); 
      $("#card_cat_name").val(""); 
    }
  });

  $("#new_card_cat").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode == 112){
      $("#pop_search4").val();
      load_new_card_cat();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);
    }
    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_new_card_cat();
     }
   }); 
    if(e.keyCode == 46){
      $("#new_card_cat").val(""); 
      $("#new_card_cat_name").val(""); 
    }
  });

});   

function save(){
  var frm = $('#form_');
  loding();
  $.ajax({
   type: frm.attr('method'),
   url: frm.attr('action'),
   data: frm.serialize(),
   success: function (pid){
    if(pid == 1){
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
}

function check_code(){
  loding();
  var card_no = $("#card_no").val();
  $.post("index.php/main/load_data/t_loyalty_card_update/check_code", {
    card_no : card_no
  }, function(res){
    if(res != 2){

      $("#code_").val(1);
      $("#ddate").val(res.issue_date);
      $("#edate").val(res.expire_date);
      $("#customer_id").val(res.customer_id);
      $("#customer_des").val(res.name);
      $("#address").val(res.address);
      $("#tp").val(res.tp);
      $("#email").val(res.email);
      $("#card_cat").val(res.category);
      $("#card_cat_name").val(res.des);

    }
    loding();
  }, "json");
}

function validate(){
  if($("#card_no").val() === $("#card_no").attr('title') || $("#card_no").val() == ""){
    alert("Please enter Card No.");
    $("#card_no").focus();
    return false;
  }else if($("#customer_id").val() === $("#customer_id").attr('title') || $("#customer_id").val() == ""){
    alert("Please enter Customer ID.");
    $("#description").focus();
    return false;
  }else if($("#card_cat").val() == ""){
    alert("Please enter Card Category.");
    $("#card_cat").focus();
    return false;
  }else if($("#new_card_no").val() === $("#new_card_no").attr('title') || $("#new_card_no").val() == ""){
    alert("Please enter New Card No.");
    $("#new_card_no").focus();
    return false;
  }else if($("#new_card_cat").val() == ""){
    alert("Please enter New Card Category.");
    $("#new_card_cat").focus();
    return false;
  }else{
    return true;
  }
}

function load_data9(){
  $.post("index.php/main/load_data/t_loyalty_card_update/customer_list", {
    search : $("#pop_search2").val() 
  }, function(r){
    $("#sr2").html("");
    $("#sr2").html(r);
    settings9();            
  }, "text");
}

function settings9(){
  $("#item_list .cl").click(function(){        
    $("#customer_id").val($(this).children().eq(0).html());
    $("#customer_des").val($(this).children().eq(1).html());
    $("#address").val($(this).children().eq(3).html());
    $("#tp").val($(this).children().eq(4).find('input').val());
    $("#email").val($(this).children().eq(5).find('input').val());
    $("#blocker").css("display","none");
    $("#pop_close2").click();                      
  })    
}


function load_card_cat(scid){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"t_loyalty_card_category",
    field:"code",
    field2:"des",
    preview2:"Card Name",
    search : $("#pop_search4").val() 
  }, 
  function(r){ 
    $("#sr4").html(r);
    settings_reasonf1(scid);            
  }, "text");
}

function settings_reasonf1(scid){
  $("#item_list .cl").click(function(){     
    $("#card_cat").val($(this).children().eq(0).html());
    $("#card_cat_name").val($(this).children().eq(1).html());
    $("#pop_close4").click();                
  })    
}

function load_new_card_cat(scid){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"t_loyalty_card_category",
    field:"code",
    field2:"des",
    preview2:"Card Name",
    search : $("#pop_search4").val() 
  }, 
  function(r){ 
    $("#sr4").html(r);
    settings_new(scid);            
  }, "text");
}

function settings_new(scid){
  $("#item_list .cl").click(function(){     
    $("#new_card_cat").val($(this).children().eq(0).html());
    $("#new_card_cat_name").val($(this).children().eq(1).html());
    $("#pop_close4").click();                
  })    
}
