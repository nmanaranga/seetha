$(document).ready(function(){
  $("#id").keydown(function(e){
    if(e.keyCode == 13){
      $(this).blur();
      load_data($(this).val());
      $("#qno").val($('#id').val());
    }
  });

  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });
  $("#qno").val($('#id').val());

  $("#tgrid").tableScroll({height:200,width:1100});
  $(".tablescroll_wrapper").css("width","1100px");
  $(".tablescroll_head").css("width","1100px");


  $("#tgrid2").tableScroll({height:200,width:1100});
  $(".tablescroll_wrapper").css("width","1100px");
  $(".tablescroll_head").css("width","1100px");

  $("#click").click(function(){
    var x=0;
    $(".me").each(function(){
      set_msg(x);
      if($(this).val() != "" && $(this).val() != 0){
        v = true;
      }
      x++;
    });
  });

  $("#item_code").autocomplete('index.php/main/load_data/m_items/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });

  $("#item_code").keydown(function(e){
    if(e.keyCode == 13){
      set_cus_values($(this));
    }
  });

  $("#item_code").blur(function(){
    set_cus_values($(this));
  });

  $(".fo").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode==112){

     $("#pop_search").val($("#0_"+scid).val());
     load_items();
     $("#serch_pop").center();
     $("#blocker").css("display", "block");
     setTimeout("select_search()", 100);
   }

   if(e.keyCode==46){
    empty_grid();
    $("#h_"+scid).val("");
    $("#0_"+scid).val("");
    $("#n_"+scid).val("");
    $("#1_"+scid).val(""); 
    $("#2_"+scid).val("");
    $("#22_"+scid).val(""); 
    $("#3_"+scid).val(""); 
    $("#4_"+scid).val(""); 
    $("#41_"+scid).val(""); 
    $("#5_"+scid).val("");
    $("#55_"+scid).val("");
    $("#t_"+scid).html(""); 
    $("#t1_"+scid).html(""); 
  }


  if(e.keyCode==13){
    $.post("index.php/main/load_data/t_price_change_sum/get_item", {
      code:$("#0_"+scid).val(),

    }, function(res){
      if(res.a!=2){
        $("#0_"+scid).val(res.a[0].code);

        if(check_item_exist($("#0_"+scid).val())){


          var cost =parseFloat(res.a[0].purchase_price);
          var last =parseFloat(res.a[0].min_price);
          var max  =parseFloat(res.a[0].max_price);

          var last_precentage=m_round((last-cost)/last*100);
          var max_precentage=m_round((max-cost)/max*100);

          $("#h_"+scid).val(res.a[0].code);
          $("#n_"+scid).val(res.a[0].description);
          $("#0_"+scid).val(res.a[0].code);
          $("#1_"+scid).val(res.a[0].model);

          $("#2_"+scid).val(res.a[0].purchase_price);
          $("#22_"+scid).val(last_precentage);
          $("#3_"+scid).val(res.a[0].min_price);
          $("#5_"+scid).val(res.a[0].max_price);
          $("#55_"+scid).val(max_precentage);

        }else{
          set_msg("Item "+$("#0_"+scid).val()+" is already added.");
        }
      }
    }, "json");

  }


});

  load_items();
  $("#pop_search").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
    }
  });

  $(".dis, .qun, .dis_pre").blur(function(){
    set_cid($(this).attr("id"));

  });

  $(".free_is").blur(function (){
    set_cid($(this).attr("id"));
  });

  $("#pop_search").gselect();
  $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });

  $("#customer").keydown(function(e){
    if(e.keyCode == 13){
      set_cus_values($(this));
    }
  });

  $("#customer").blur(function(){
    set_cus_values($(this));
  });

  $("#war_close").click(function() {
    $("#war").fadeOut(2000);
  });

  $("#search_item").click(function(){
    clear_grid();
    $.post("index.php/main/load_data/t_price_change_sum/get_item_detail",{
      code:$("#item_code").val(),
      date_to:$("#date_to").val(),
      date_from:$("#date_from").val()
    },function(r){
     if(r.data[0]!='0'){
      for(x=0;x<r.data.length;x++){
        $("#00_"+x).val(r.data[x].nno);
        $("#nn_"+x).val(r.data[x].ddate);
        $("#222_"+x).val(r.data[x].cost);
        $("#op_"+x).val(r.data[x].user);
        $("#33_"+x).val(r.data[x].last_price);
        $("#44_"+x).val(r.data[x].last_price_new);
        $("#555_"+x).val(r.data[x].max_price);
        $("#tt_"+x).val(r.data[x].max_price_new);
      }
    }else{
      set_msg('No Data','error');
    } 
  },"json");   
  });


  $("#btnDelete").click(function(){
    var id=$("#id").val();
    if(id==""){
      set_msg('Please enter no');
    }else{      
      var id=$("#id").val();
      $.post("index.php/main/load_data/t_price_change_sum/check_code",{
        id:id,
      },function(r){

        if(r==1){
          set_delete(id);
        }else{
         set_msg("There is no data "); 
       }
     });      
    }
  });



  $(".precent").keyup(function(){
    set_cid($(this).attr("id"));
    price_precentage('0','t','2','t1',scid);

  });

  $(".p_value").keyup(function(){
    set_cid($(this).attr("id"));
    price_precentage('0','4','2','41',scid);

  });

  $("#item_code").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search4").val($("#item_code").val());
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
      $("#item_code").val("");
      $("#item_description").val("");
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
      $("#item_code").val($(this).children().eq(0).html());
      $("#item_description").val($(this).children().eq(1).html());
      $("#pop_close4").click();                
    })    
}


function  price_precentage(item,max,purchase,m_precentage,x){

  var precentage=parseFloat(0);
  var value=parseFloat(0);

  var item_c = $("#"+item+"_"+x).val();
  var max_c =parseFloat($("#"+max+"_"+x).val());
  var purchase_c =parseFloat($("#"+purchase+"_"+x).val());

  if(item_c!=""){
    precentage = (max_c - purchase_c)/max_c*100;
    value = m_round(precentage);
    $("#"+m_precentage+"_"+x).val(value);
  }
}

function set_cus_values(f){
  var v = f.val();
  v = v.split("|");
  if(v.length == 3){
    f.val(v[0]);
    $("#item_description").val(v[1]);
    $("#item_model").val(v[2]);
  }
}



function formatItems(row){
  return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>| <strong> " +row[2] + "</strong>";
}

function formatItemsResult(row){
  return row[0]+"|"+row[1]+"|"+row[2];
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
    }else {
      set_msg(pid);
    }
  }
});

}



function empty_grid(){
  for(var i=0; i<25; i++){
    $("#h_"+i).val(0);
    $("#0_"+i).val("");
    $("#n_"+i).val("");
    $("#t_"+i).html("&nbsp;");
    $("#1_"+i).val("");
    $("#2_"+i).val("");
    $("#22_"+i).val("");
    $("#3_"+i).val("");
    $("#4_"+i).val("");
    $("#5_"+i).val("");
    $("#55_"+i).val("");
    $("#t_"+i).val("");
    $("#t1_"+i).val("");

  }
}



function load_data(id){
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_price_change_sum/", {
    id: id
  }, function(r){
    if(r=="2"){
     set_msg("No records");
   }else{
    $("#hid").val(id);    
    $("#customer").val(r.det[0].ccode);
    $("#customer_id").val(r.det[0].name);
    $("#address").val(r.det[0].address1+", "+r.det[0].address2+", "+r.det[0].address3);
    $("#date").val(r.det[0].ddate);
    $("#ref_no").val(r.det[0].ref_no);

    $("#id").attr("readonly","readonly");    
            //$(".ss").attr("readonly","readonly") ;

            $("#btnSave").css("display","none");     



            for(var i=0; i<r.det.length;i++){

              var costs =parseFloat(r.det[i].purchase_price);
              var lasts =parseFloat(r.det[i].last_price);
              var maxs  =parseFloat(r.det[i].max_price);

              var last_precentages=m_round((lasts-costs)/lasts*100);
              var max_precentages=m_round((maxs-costs)/maxs*100);


              $("#0_"+i).val(r.det[i].code);
              $("#n_"+i).val(r.det[i].description);
              $("#1_"+i).val(r.det[i].model);
              $("#2_"+i).val(r.det[i].purchase_price);
              $("#3_"+i).val(r.det[i].last_price);
              $("#4_"+i).val(r.det[i].last_price_new);
              $("#5_"+i).val(r.det[i].max_price);
              $("#t_"+i).val(r.det[i].max_price_new);
              $("#22_"+i).val(last_precentages);
              $("#55_"+i).val(max_precentages);
              price_precentage('0','t','2','t1',i);
              price_precentage('0','4','2','41',i);


            }
            input_active();
          }
          loding();
        }, "json");
}



function validate(){

  var v = true;

  if($("#ref_no").val()=="")
  {
   set_msg("Ref No can't be empty");
   return false;
 }


 for(x=0;x<25;x++)
 {

  var last = parseFloat($("#3_"+x).val());
  var Max = parseFloat($("#5_"+x).val());
  var min_p=parseFloat($("#4_"+x).val());
  var max_p=parseFloat($("#t_"+x).val());


  if($("#t_"+x).val()==""){
    max_p = Max;
  }
  else if($("#4_"+x).val()==""){
    min_p = last; 
  }


  if(max_p < min_p)
  {
    set_msg("Last price shoud be lower then max price");
    return false;
  }

}



if($("#id").val() == "")
{
  set_msg("Please enter No.");
  $("#id").focus();
  return false;
}
else if($("#date").val() == "")
{
  set_msg("Please select date");
  $("#date").focus();
  return false;
}
else if($("#ref_no").val() == "")
{
  set_msg("Please enter Ref No");
  $("#ref_no").focus();
  return false;
}
else if(v == false)
{
  set_msg("Please use minimum one item.");
}
else
{
  return true;
}
}



function set_delete(id){
  if(confirm("Are you sure delete "+id+"?")){
    loding();
    $.post("index.php/main/delete/t_price_change_sum", {
      id : id
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


function select_search(){
  $("#pop_search").focus();
}

function load_items(){
  $.post("index.php/main/load_data/t_price_change_sum/item_list_all", {
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

        var cost =parseFloat($(this).children().eq(3).html());
        var last =parseFloat($(this).children().eq(4).html());
        var max  =parseFloat($(this).children().eq(5).html());

        var last_precentage=m_round((last-cost)/last*100);
        var max_precentage=m_round((max-cost)/max*100);

        $("#h_"+scid).val($(this).children().eq(0).html());
        $("#0_"+scid).val($(this).children().eq(0).html());
        $("#n_"+scid).val($(this).children().eq(1).html());
        $("#1_"+scid).val($(this).children().eq(2).html());
        $("#2_"+scid).val($(this).children().eq(3).html());
        $("#22_"+scid).val(last_precentage);
        $("#3_"+scid).val($(this).children().eq(4).html());
        $("#5_"+scid).val($(this).children().eq(5).html());
        $("#55_"+scid).val(max_precentage);




        if($(this).children().eq(4).html() == 1){
          $("#11_"+scid).autoNumeric({mDec:2});
        }else{
          $("#11_"+scid).autoNumeric({mDec:0});
        }
        $("#1_"+scid).removeAttr("disabled"); 
        $("#2_"+scid).removeAttr("disabled"); 
        $("#3_"+scid).removeAttr("disabled");
        $("#2_"+scid).focus();
        $("#pop_close").click();
      }else{
        set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
      }
    }else{
      $("#h_"+scid).val("");
      $("#0_"+scid).val("");
      $("#n_"+scid).val("");
      $("#1_"+scid).val(""); 
      $("#2_"+scid).val(""); 
      $("#3_"+scid).val(""); 
      $("#t_"+scid).html("&nbsp;");
      $("#1_"+scid).attr("disabled", "disabled"); 
      $("#2_"+scid).attr("disabled", "disabled");
      $("#3_"+scid).attr("disabled", "disabled");
      $("#0_"+scid).focus();
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

function clear_grid(){
  for(x=0;x<25;x++){
    $("#00_"+x).val("");
    $("#nn_"+x).val("");
    $("#11_"+x).val("");
    $("#22_"+x).val(""); 
    $("#33_"+x).val(""); 
    $("#44_"+x).val("");  
    $("#55_"+x).val("");
    $("#66_"+x).val("");    
    $("#tt_"+x).val("");    
  }
}

