$(document).ready(function(){

  $("#id").keydown(function(e){
    if(e.keyCode == 13){
        load_data($(this).val());
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



function validate(){
  if($("#customer_id").val() === $("#customer_id").attr('title') || $("#customer_id").val() == ""){
    alert("Please enter Customer ID.");
    $("#description").focus();
    return false;
}else{
    return true;
}
}

function load_data9(){
  $.post("index.php/main/load_data/t_loyalty_card_request/customer_list", {
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



function load_data(id){

    loding();
    $.post("index.php/main/get_data/t_loyalty_card_request/", {
        id: id,
    }, function(r){

        if(r=="2"){
           set_msg("No records");
       }else{
        $("#id").attr("readonly","readonly");
        if(r.sum[0].is_cancel=="1"){ 
            $("#mframe").css("background-image", "url('img/cancel.png')");
            $("#btnSave").attr('disabled','disabled');
            $("#btnDelete").attr('disabled','disabled');
        }
        if(r.sum[0].is_post=="1"){ 
            $("#mframe").css("background-image", "url('img/approved1.png')");
            $("#btnSave").attr('disabled','disabled');
            $("#btnDelete").attr('disabled','disabled');
            set_msg('Loyalty Card Already Issued');
        }

        $("#hid").val(r.sum[0].nno);   
        $("#nno").val(r.sum[0].nno);
        $("#ddate").val(r.sum[0].ddate); 
        $("#customer_id").val(r.sum[0].cus_code);
        $("#customer_des").val(r.sum[0].note);
        $("#address").val(r.sum[0].address);
        $("#tp").val(r.sum[0].tp);
        $("#email").val(r.sum[0].email);
        $("#note").val(r.sum[0].comments);

    }

    loding();
}, "json");


}
