$(document).ready(function(){

  var rec_no = window.location.search.split('=')[2];
  var rec_date = window.location.search.split('=')[3];
  var rec_bank = window.location.search.split('=')[4];
  
  if(rec_no != undefined){
     $("#id").val(rec_no);
     $("#date").val(rec_date);
     $("#rec_bank").val(rec_bank);
     chk_is_update(rec_no);
  }else{
    set_msg("please load this form from Bank reconcilation");
    location.href="index.php?action=t_bankrec";
  }

	$("#btnReset").click(function(){
		location.href="index.php?action=t_bankrec_additional_det";
	});

  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });

  $("#btnDelete").click(function(){
    set_delete($("#id").val());
  });

  $(".amo").blur(function(){
    total();
  });
  
  $("#tgrid").tableScroll({height:300,width:980});

  /*$(".des").keydown(function(e){
     set_cid($(this).attr("id"));
    if(e.keyCode == 112){
        $("#pop_search6").val();
        load_des();
        $("#serch_pop6").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search6').focus()", 100);
    }
    $("#pop_search6").keyup(function(e){            
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_des();
      }
    }); 
    if(e.keyCode == 46){
      $("#2_"+scid).val("");
    }
  });*/

  $(".cl").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode==46){
      $("#1_"+scid).val("");
      $("#2_"+scid).val("");
      $("#3_"+scid).val("");
      $("#4_"+scid).val("");
      total();
    }
  })
});

function total(){
  var tot=0;
  $(".amo").each(function(e){
    if($("#2_"+e).val()!="" && $("#4_"+e).val()!=""){
      tot+=parseFloat($("#4_"+e).val());
    }
  });
  $("#net").val(m_round(tot));
}

function save(){
  loding();
  $("#qno").val($("#id").val());
  var frm = $('#form_');
  loding();
  $.ajax({
	type: frm.attr('method'),
	url: frm.attr('action'),
	data: frm.serialize(),
	success: function (pid){
      if(pid == 1){
        alert("Save Completed");
        window.top.close();
      }else{
        set_msg(pid);
      }
      loding();
    }
  });
}


function validate(){
  /*  if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
        alert("Please enter code.");
        $("#code").focus();
        return false;
    }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
        alert("Please enter description.");
        $("#description").focus();
        return false;
    }else if($("#description").val() === $("#code").val()){
        alert("Please enter deferent values for description & code.");
        $("#des").focus();
        return false;
    }else{
       
    } */return true;
}
    
function set_delete(code){
    if(confirm("Are you sure delete no "+code+"?")){
        loding();
        $.post("index.php/main/delete/t_bankrec_additional_det", {
            code : code
        }, function(res){
            if(res == 1){
                delete_msg();
            }else{
                set_msg(res);
            }
            loding();
        }, "text");
    }
}

 
function chk_is_update(code){
  loding();
  $.post("index.php/main/load_data/t_bankrec_additional_det/chk_is_update", {
      code : code
  }, function(res){
      if(res == 1){
          load_data(code);
      }
      loding();
  }, "text");
}

    
function load_data(code){
    loding();
    $.post("index.php/main/get_data/t_bankrec_additional_det", {
        code : code
    }, function(res){
        for(var x=0; x<res.det.length; x++){
          $("#1_"+x).val(res.det[x].date);
          $("#2_"+x).val(res.det[x].description);
          $("#3_"+x).val(res.det[x].remarks);
          $("#4_"+x).val(res.det[x].dr_amount);
        }
        $("#id").val(res.det[0].nno);
        $("#hid").val(res.det[0].nno);
        $("#date").val(res.det[0].rec_ddate);

        if(res.det[0].is_cancel==1){
          $("#btnDelete").attr("disabled", "disabled");
          $("#btnSave").attr("disabled", "disabled");
          $("#mframe").css("background-image", "url('img/cancel.png')");
        }
        total();
        loding(); 
    }, "json");
}

function empty_grid(){
  for(var x=0; x<25; x++){
    $("#1_"+x).val("");
    $("#2_"+x).val("");
    $("#3_"+x).val("");
    $("#4_"+x).val("");
  }
  total();
}