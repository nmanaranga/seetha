
var sub_items=[];

$(document).ready(function(){

  var p_code = window.location.search.split('=')[2];
  if(p_code != undefined){
    $("#adj_no").val(p_code);
    setTimeout("$('#ad_load').click();",100);
  }

  $("#get_all").attr('disabled',true);

  $("#id").keydown(function(e){
    if(e.keyCode==13){
      load_data($("#id").val());
    }    
  });

  /*
  $("#id").keydown(function(e){
    if(e.keyCode==13){
      load_adjustment($("#adj_no").val());
    }    
  });
  */

  $("#add_serial").keyup(function(){
    check_avble_serial($(this).val());
  });

  $("#ad_load").click(function(){
    if($("#adj_no").val()!=""){
        $("#pop_search12").val($("#adj_no").val());
        load_adjustment();
        $("#serch_pop12").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search12').focus()", 100);
      }else{
        set_msg("please enter adjustment no");
      }
    $("#pop_search12").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_adjustment();
        }
    });   
  });

  $("#adj_no").keydown(function(e){
    if(e.keyCode == 112){
      $("#ad_load").click();
    }

   $("#pop_search12").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_adjustment();
        }
    }); 

    if(e.keyCode == 46){
        $("#adj_no").val("");
    }
  });

  $("#store").keydown(function(e){
    if(e.keyCode == 112){
        $("#pop_search6").val($("#store").val());
        load_data8();
        $("#serch_pop6").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search6').focus()", 100);
    }

   $("#pop_search6").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_data8();
        }
    }); 

    if(e.keyCode == 46){
        $("#store").val("");
        $("#store_des").val("");
    }
  });

   $("#item").keydown(function(e){
    if(e.keyCode == 112){
        $("#pop_search11").val($("#item").val());
        load_data11();
        $("#serch_pop11").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search11').focus()", 100);
    }

   $("#pop_search11").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_data11();
        }
    }); 

    if(e.keyCode == 46){
        $("#item").val("");
        $("#item_des").val("");
    }
  });




  $("#store").autocomplete("index.php/main/load_data/t_serial_adjustment_sum/auto_store", {
          width: 350,
          multiple: false,
          matchContains: true,
          formatItem: formatItems,
          formatResult: formatItemsResult
      });
  $("#store").keydown(function(a) {
      if (13 == a.keyCode) set_cus_values1($(this));
  });
  $("#store").blur(function() {
    $("#c_stock").val("");
      set_cus_values1($(this));
  });



  $("#item").autocomplete("index.php/main/load_data/t_serial_adjustment_sum/auto_item", {
          width: 350,
          multiple: false,
          matchContains: true,
          formatItem: formatItems,
          formatResult: formatItemsResult
      });
  $("#item").keydown(function(a) {
      if (13 == a.keyCode) set_cus_values2($(this));
  });
  $("#item").blur(function() {
    $("#c_stock").val("");
      set_cus_values2($(this));
  });

  $("#load_serail").click(function(){
    if($("#item").val()=="" || $("#store").val()=="" || $("#batch").val()==0){
      set_msg("Please select item, store and batch","error");
    }else{
      
      $("#tgrid1").css("display","block");
      $("#tgrid2").css("display","none");
      load_all_serial();
      $("#no_row").val(0); 
      $("#get_all").removeAttr('disabled');
    }
  });

  $(document).on("click","#get_all",function(){
 // $("#get_all").click(function(){
    if($("#item").val()=="" || $("#store").val()=="" || $("#batch").val()==0){
      set_msg("Please load serial numbers before add","error");
    }else{
      $('#get_all').attr('isclick', 1);
      $("#tgrid2").css("display","block");
      add_all_serial();
    }
      
      setTimeout(function() {
       load_new_stock();
      }, 1000);
  });

 /* $("#item").blur(function(){
    if($("#item").val()!="" || $("#store").val()!="" ){
      load_batch();
    }
  });*/

  $(document).on("keydown",".se",function(e){
    if(e.keyCode==46){
      set_cccid($(this).attr("id"));
      if(scid2==$(".cl2").size()){
        $(this).closest('tr').fadeOut("normal");
        $(this).closest('tr').removeClass("cl2");

        $(this).eq(0).removeAttr('id');
        $(this).eq(0).removeAttr('name');
        $(this).eq(0).removeClass('se');

        $(this).closest('tr').children().find('input').eq(1).removeAttr('id');
        $(this).closest('tr').children().find('input').eq(1).removeAttr('name');

        $(this).closest('tr').children().find('input').eq(2).removeAttr('id');
        $(this).closest('tr').children().find('input').eq(2).removeAttr('name');
        load_new_stock();
        
        $("#no_row").val($(".cl2").size());
      }else{
        alert("Please Delete Serial Numbers From Last Row");
      }
    }
  });

  
  $(document).on("change","#batch",function(){ 
    load_current_stock();   
  });

  $(document).on("click","#add",function(){ 
    if($("#add_serial").val()==""){
      set_msg("Please enter serial number before add","error");
    }else{
      var y=parseInt( $("#no_row").val()); 
      var t="";
      var row_value1=$("#add_serial").val().toUpperCase();
      $(".cl2").each(function(e){
          e=e+1;
        if($("#serial_"+e).val()==row_value1){    
          t=1;        
          return false;
        }else{
          t=0;
        }
      });
    
      if(t==0){
        if($("#get_all").attr("isclick")==1){
          y=y+1;
        }
        row_value2=$("#add_other1").val();
        row_value3=$("#add_other2").val();
        
        $("#tgrid2").css("display","block");
        r = "<tr class='cl2'><td style='width:275px;'>&nbsp;<input type='text' readonly='reaonly' class='se' name='serial_"+y+"' id='serial_"+y+"' class='g_input_txt' value='"+row_value1+"'/></td><td style='width:300px;'>&nbsp;<input type='text' readonly='reaonly' name='other1_"+y+"' id='other1_"+y+"' class='g_input_txt' value='"+row_value2+"'/></td><td style='width:275px;'>&nbsp;<input type='text' readonly='reaonly' name='other2_"+y+"' id='other2_"+y+"' class='g_input_txt' value='"+row_value3+"'/></td></tr>";
        $("#tgrid2").append(r);
        y++;
        $("#no_row").val($(".cl2").size());
      }else{
      set_msg("This record already added","error");
      } 
      load_new_stock();  
      $("#add_serial").val("");
      $("#add_other1").val("");
      $("#add_other2").val("");
    }
    
  });


  $(document).on("click","#tgrid1 .cl",function(){
    var x=0;
    var t="";
    var row_value1=$(this).children().eq(0).text();
    $(".cl2").each(function(e){     
      if($("#serial_"+e).val()===row_value1){    
        t=1;
        return false;
      }else{
        t=0;
      }
    });

    if(t==0){
      if($("#get_all").data('isclick')!=1){
        var row_value2=$(this).children().eq(1).text();
        var row_value3=$(this).children().eq(2).text();
        var r = "";
        r = "<tr class='cl2'><td style='width:275px;'>&nbsp;<input type='text' readonly='reaonly' class='se' name='serial_"+x+"' id='serial_"+x+"' class='g_input_txt' value='"+row_value1+"'/></td><td style='width:300px;'>&nbsp;<input type='text' readonly='reaonly' name='other1_"+x+"' id='other1_"+x+"' class='g_input_txt' value='"+row_value2+"'/></td><td style='width:275px;'>&nbsp;<input type='text' readonly='reaonly' name='other2_"+x+"' id='other2_"+x+"' class='g_input_txt' value='"+row_value3+"'/></td></tr>";
        $("#tgrid2").css("display","block");
        $("#tgrid2").append(r);
        x++;
        $("#no_row").val($(".cl2").size());       
      }
    }else{
      set_msg("This record already added","error");
    } 
    load_new_stock();
  });


});

function check_avble_serial(item){

  

}

function load_data8(){
    $.post("index.php/main/load_data/t_serial_adjustment_sum/load_stores", {
        data_tbl:"m_stores",
        field:"code",
        field2:"description",
        preview2:"Store Name",
        search : $("#pop_search6").val() 
    }, function(r){
        $("#sr6").html(r);
        settings8();            
    }, "text");
}

function load_data11(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
        data_tbl:"m_item",
        field:"code",
        field2:"description",
        preview2:"Item Name",
        add_query:"AND serial_no='1'",
        search : $("#pop_search11").val() 
    }, function(r){
        $("#sr11").html(r);
        settings11();            
    }, "text");
}

function settings8(){
    $("#item_list .cl").click(function(){        
        $("#store").val($(this).children().eq(0).html());
        $("#store_des").val($(this).children().eq(1).html());
        $("#pop_close6").click();                
    })    
}

function settings11(){
    $("#item_list .cl").click(function(){        
        $("#item").val($(this).children().eq(0).html());
        $("#item_des").val($(this).children().eq(1).html());
        load_batch($(this).children().eq(0).html());
        $("#pop_close11").click();                
    })    
}

function validate(){

  if($("#n_stock").val()!=$("#n_stock_hid").val()){
     set_msg("New Serial Quantity Should Be ('"+$("#n_stock_hid").val()+"') or not tally with Quantity ");
     return false;
  }else{
    return true;
  }
 
  
}

function set_cccid(id){
    id2 = id.split('_');
    fcid2 = id2[0];
    scid2 = parseInt(id2[1])+1;
}

  function save(){

    if($("#df_is_serial").val()=='1'){
      serial_items.sort();
      $("#srls").attr("title",serial_items);
      $("#srls").val(serial_items);      
    }
    
    var frm = $('#form_');
    loding();
    $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    success: function (pid){
             if(pid == 1){
                $("#btnSave").attr("disabled",true);
                loding();
                location.href="?action=t_serial_adjustment_sum";
              }else{
                loding();
                  set_msg(pid,"error");
              }
              
        }
    });   
}




function load_data(id){
    $("#tgrid2").html("");
    loding();
    $.post("index.php/main/get_data/t_serial_adjustment_sum/", {
        id: id
    }, function(r){

            if(r=="2"){
               set_msg("No records");
            }else{

              $("#id").val(r.sum[0].nno);
              $("#hid").val(r.sum[0].nno);
              $("#ddate").val(r.sum[0].ddate);
              $("#store").val(r.sum[0].store_id);   
              $("#store_des").val(r.sum[0].store_des); 
              $("#item").val(r.sum[0].item_id);
              $("#item_des").val(r.sum[0].description);
              $("#adj_no").val(r.sum[0].adjustment_no);
              $("#n_stock_hid").val(r.sum[0].new_stock);
              
              $("#batch").html("<select><option>"+r.sum[0].batch_no+"</option></select>");
              //$("#batch").val(r.sum[0].batch_no);

              $("#c_stock").val(r.sum[0].current_stock);

              var qty = parseInt(r.sum[0].current_stock);
              var price = parseInt(r.sum[0].max_price);
              var value = qty * price;

              $("#stock_val").val(value);

              var new_qty = parseInt(r.sum[0].new_stock);
              var value_new = new_qty * price;
              
              $("#n_stock").val(r.sum[0].new_stock);
              $("#stock_val2").val(value_new);
             
              for(var i=0; i<r.det.length;i++){
                var htl="";

                htl = "<tr class='cl2'><td style='width:275px;'>&nbsp;<input type='text' readonly='reaonly' class='se' name='serial_"+i+"' id='serial_"+i+"' class='g_input_txt' value='"+r.det[i].serial_no+"'/></td><td style='width:300px;'>&nbsp;<input type='text' readonly='reaonly' name='other1_"+i+"' id='other1_"+i+"' class='g_input_txt' value='"+r.det[i].other_no1+"'/></td><td style='width:275px;'>&nbsp;<input type='text' readonly='reaonly' name='other2_"+i+"' id='other2_"+i+"' class='g_input_txt' value='"+r.det[i].other_no2+"'/></td></tr>";
                $("#tgrid2").css("display","block");
                $("#tgrid2").append(htl);
                
                setTimeout(function() {
                  $("#no_row").val($(".cl2").size()); 
                }, 1000);              
              }             
            }    
        loding();           
        }, "json");

  
}



function load_new_stock(){
  var e=0;
  $(".se").each(function(){
    e=e+1;
  });

    var price = parseFloat($("#c_stock2").val());
    var tot = price * e;

    $("#n_stock").val(e);
    $("#stock_val2").val(tot);
}

function load_current_stock(){
  $("#c_stock").val("");
  $("#stock_val").val("");
  $.post("index.php/main/load_data/t_serial_adjustment_sum/get_current", {
        item:$("#item").val(),
        store:$("#store").val(),
        batch:$("#batch").val()
    }, function(res){
        if(res!=2){
            $("#c_stock").val(res[0].qty);
            var qty = parseFloat(res[0].qty);
            var price = parseFloat(res[0].max_price);
            var tot = qty * price;
            $("#c_stock2").val(res[0].max_price);
            $("#stock_val").val(tot);
        }else{
            set_msg("Not availabale stock","error");
        }
    }, "json");
}


function load_batch(item){
  $("#c_stock").val("");
  $.post("index.php/main/load_data/t_serial_adjustment_sum/load_batch", {
      item:item,
      store:$("#store").val()
    }, function(r) {
      $("#batch").html("");
      if(r!=0){
        $("#batch").html(r);
      }
    }, "text");
}

function load_all_serial(){
  
  $.post("index.php/main/load_data/t_serial_adjustment_sum/serial_list_all", {
      item:$("#item").val(),
      store:$("#store").val(),
      batch:$("#batch").val()
    }, function(r) {
      $("#tgrid1").html("");
      $("#tgrid2").html("");
      if(r!=0){

        $("#tgrid1").append(r);
        //$("#no_row").val($(".cl").size());
        
      }else{
         $("#tgrid1").css("display","none");
         set_msg("No available serial numbers","error");
      }
    }, "text");
}

function add_all_serial(){
  $.post("index.php/main/load_data/t_serial_adjustment_sum/serial_list_add", {
      item:$("#item").val(),
      store:$("#store").val(),
      batch:$("#batch").val()
    }, function(r) {
      $("#tgrid2").html("");
      //alert(r);
      if(r!=0){
        $("#tgrid2").append(r);
        $("#no_row").val($(".cl2").size());
      }else{
         $("#tgrid2").css("display","none");
         $("#tgrid1").css("display","none");
         set_msg("Please load serial numbers before add","error");
      }
    }, "text");
}



function set_cus_values1(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#store_des").val(b[1]);
    }
}

function set_cus_values2(a) {
    var b = a.val();
    b = b.split("|");
    if (2 == b.length) {
        a.val(b[0]);
        $("#item_des").val(b[1]);
    }
}

function formatItems(a) {
    return "<strong> " + a[0] + "</strong> | <strong> " + a[1] + "</strong>";
}

function formatItemsResult(a) {
    return a[0] + "|" + a[1];
}
   
function load_adjustment(){
 $.post("index.php/main/load_data/t_serial_adjustment_sum/load_adjustment", {
      id:$("#adj_no").val(),
      search : $("#pop_search12").val() 
    }, function(r) {
      $("#sr12").html(r);
      settings_adj();     
    }, "text");
}

function settings_adj(){
    $("#item_list .cl").click(function(){        
      $("#store").val($(this).children().eq(4).html());
      $("#item").val($(this).children().eq(2).html());     
      $("#item").blur();
      $("#n_stock").val($(this).children().eq(5).html());     
      $("#n_stock_hid").val($(this).children().eq(5).html()); 
      $("#store_des").val($(this).children().eq(6).html());   
      $("#item_des").val($(this).children().eq(7).html());  
      var hh='<option class="selctbtch" value="'+$(this).children().eq(3).html()+'" >'+$(this).children().eq(3).html()+'</option>';
      $("#batch").html(hh);

      //load_batch($(this).children().eq(2).html());
      setTimeout('$("#batch").val($(this).children().eq(3).html())',100); 
      $("#pop_close12").click();                
    })    
}
   

