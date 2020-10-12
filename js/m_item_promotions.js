$(document).ready(function(){
  $("#nno").blur(function(){
    check_code();
  });

  $("#grid").tableScroll({height:355, width:590});
  $("#items").change(function(){
    set_select('items','code');
  });

  $("#qty").keyup(function(){
    this.value=this.value.replace(/[^\d\.]/g,'');
  });


  $("#nno").keyup(function(){
    this.value=this.value.replace(/[^\d]/g,'');
  });

  $("#btnReset").click(function(){
    location.href="?action=m_item_promotions";
  });



  $(".fo").keydown(function(e){
    if(e.keyCode == 112){
      set_cid($(this).attr("id"));
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("select_search()", 100);
    }
  });

  load_items();
  $("#pop_search").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
    }
  });

  $("#pop_search").gselect();

  $("#item").autocomplete('index.php/main/load_data/m_items/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });

  $("#item").keydown(function(e){
    if(e.keyCode == 13){
      set_cus_values($(this));
    }
  });

  $("#item").blur(function(){
    set_cus_values($(this));
  });


  $("#srchee").keyup(function(){  
    $.post("index.php/main/load_data/utility/get_data_table", {
     code:$("#srchee").val(),
     tbl:"m_item_promotions",
     tbl_fied_names:"No-From Date-To Date",
     fied_names:"nno-date_from-date_to",
     is_r:"N"

   }, function(r){
     $("#grid_body").html(r);
   }, "text");
  });


  $("#item").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search4").val($("#item").val());
      load_data_itm1();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);
    }

    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_data_itm1();

     }
   }); 

    if(e.keyCode == 46){
      $("#item").val("");
      $("#item_id").val("");
    }
  });



});

function load_data_itm1(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
    data_tbl:"m_item",
    field:"code",
    field2:"description",
    preview2:"Item Name",
    search : $("#pop_search4").val() 
  }, 
  function(r){
    $("#sr4").html(r);
    settings_itmf1();            
  }, "text");
}

function settings_itmf1(){
  $("#item_list .cl").click(
    function(){        
      $("#item").val($(this).children().eq(0).html());
      $("#item_id").val($(this).children().eq(1).html());
      $("#pop_close4").click();                
    })    
}

function set_cus_values(f){
  var v = f.val();
  v = v.split("|");
  if(v.length == 2){
    f.val(v[0]);
    $("#item_id").val(v[1]);
    $("#item_id").attr("class", "input_txt_f");
  }
}


function formatItems(row){
  return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
  return row[0]+"|"+row[1];
}



function select_search(){
  $("#pop_search").focus(); 
}

function load_items(){
  $.post("index.php/main/load_data/m_items/item_list_all", {
    search : $("#pop_search").val(),
    stores : false
  }, function(r){
    $("#sr").html(r);
    settings();

  }, "text");
}



function settings(){
  $("#item_list .cl").click(function(){

    if($(this).children().eq(0).html() != "&nbsp;"){
      if(check_item_exist($(this).children().eq(0).html())){
        $("#h_"+scid).val($(this).children().eq(0).html());
        $("#0_"+scid).val($(this).children().eq(0).html());
        $("#n_"+scid).val($(this).children().eq(1).html());

        $("#pop_close").click();
      }else{
        set_msg
        ("Item "+$(this).children().eq(1).html()+" is already added.");
      }
    }else{
      $("#h_"+scid).val("");
      $("#0_"+scid).val("");
      $("#n_"+scid).val("");

      $("#pop_close").click();
    }
  });
}

function check_item_exist(id){

  var v = true;
  $("input[type='hidden']").each(function(){

    if($(this).val() == id){

      v = false;
    }
  });

  return v;
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
      set_msg("nno permission to add data.");
    }else if(pid == 3){
      set_msg("nno permission to edit data.");
    }else{
      set_msg(pid);
    }            
  }
});

    //$("#form_").submit();

  }

  function get_data_table(){
    $.post("index.php/main/load_data/m_item_promotions/get_data_table", {

    }, function(r){
      $("#grid_body").html(r);
    }, "text");
  }


  function check_code(){
    loding();
    var nno = $("#nno").val();
    $.post("index.php/main/load_data/m_item_promotions/check_code", {
      nno : nno
    }, function(res){
      if(res == 1){
        if(confirm("This code ("+nno+") already added. \n\n Do you need edit it?")){
          set_edit(nno);
        }else{
          $("#nno").val('');
          $("#nno").attr("readonly", false);
        }
      }
      loding();
    }, "text");
  }

  function validate(){

    return true;
    
  }

  function set_delete(nno){
    if(confirm("Are you sure delete transaction no "+nno+"?")){
      loding();
      $.post("index.php/main/delete/m_item_promotions", {
        nno : nno
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

  function is_edit($mod)
  {
    $.post("index.php/main/is_edit/user_permissions/is_edit", {
      module : $mod

    }, function(r){
     if(r==1)
     {
       $("#btnSave").removeAttr("disabled", "disabled");
     }
     else{
       $("#btnSave").attr("disabled", "disabled");
     }

   }, "json");

  }

  function set_edit(nno){
    loding();
    $.post("index.php/main/get_data/m_item_promotions", {
      nno : nno
    }, function(res){


      $("#code_").val(res.c[0].nno);
      $("#nno").val(res.c[0].nno);
      $("#nno").attr("readonly", true);
      $("#ddate").val(res.c[0].ddate);
      $("#date_from").val(res.c[0].date_from);
      $("#date_to").val(res.c[0].date_to);

      for (var x=0; x<12; x++) {
       $("#0_"+x).val("");
       $("#n_"+x).val(""); 
       $("#1_"+x).val("");
     }

     for(var i=0; i<res.c.length; i++){


      $("#0_"+i).val(res.c[i].code);
      $("#n_"+i).val(res.c[i].description); 
      $("#p_"+i).val(res.c[i].promo_type);
      $("#1_"+i).val(res.c[i].note);
    }  
    loding(); 
    input_active();
  }, "json");
  }