$(document).ready(function(){

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

});   


function load_card_cat(scid){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"r_return_reason",
      field:"code",
      field2:"description",
      preview2:"Card Name",
      add_query: " AND type='7'",
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

function validate(){
    if($("#card_cat").val() === $("#card_cat").attr('title') || $("#card_cat").val() == ""){
        alert("Please Select Card Category.");
        $("#card_cat").focus();
        return false;
    }else if($("#earn_rs").val() == ""){
        alert("Please enter Earn Rs.");
        $("#earn_rs").focus();
        return false;
    }else if($("#earn_point").val() ==""){
        alert("Please enter Earn Point.");
        $("#earn_point").focus();
        return false;
    }else if($("#red_point").val() ==""){
        alert("Please enter Redeem Point.");
        $("#red_point").focus();
        return false;
    }else if($("#red_rs").val() ==""){
        alert("Please enter Redeem Point.");
        $("#red_rs").focus();
        return false;
    }else if($("#upd_point").val() ==""){
        alert("Please enter Update Point.");
        $("#upd_point").focus();
        return false;
    }else{
        return true;
    }
}


function set_delete(code){

    if(confirm("Are you sure delete no "+code+"?")){
        loding();
        $.post("index.php/main/delete/t_loyalty_card_category", {
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
    $.post("index.php/main/get_data/t_loyalty_card_category", {
        code : code
    }, function(res){
        $("#code").val(res.code);
        $("#code_").val(res.code);
        $("#description").val(res.des);
        $("#card_cat").val(res.card_cat);
        $("#card_cat_name").val(res.description);
        $("#earn_rs").val(res.earn_rs);
        $("#earn_point").val(res.earn_point);
        $("#red_point").val(res.red_point);
        $("#red_rs").val(res.red_rs);
        $("#upd_point").val(res.update_level);

        loding(); 
        input_active();
    }, "json");
}