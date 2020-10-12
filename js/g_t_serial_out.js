var serial_items=[];
var get_id;
var serialWind;
var current_serial_no;
$(document).ready(function(){

  $(".quns").css("display","none");

  $("#pop_search44").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
      seriall_items();
    }
  });

  $("#pop_search44").gselect();

  $("#set_serial").delegate(".srl_count", "keyup", function(){
    this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
  }); 


  $("#set_serial").delegate(".removeSerial", "click", function(){
    var dis=$(this).attr("id").split("_")[1];
    var serial_code=$("#item_code").val()+"-"+$("#serial_"+dis).val();
    var qty=parseInt($("#qty").val());

    
    $("#serial_"+dis).remove();
    $(this).remove();
   // $("#btnExit1").attr("disabled","disabled");

    var n = $( ".removeSerial" ).length;
    if(n==qty){
      $(".removeSerial").attr("disabled","disabled");
    }
  });
      
  $("#add").click(function(){
    var quantity=parseInt($("#quantity").val());
      for(x=0;x<quantity;x++){
        $("#serial_"+x).val($("#srl_"+x).val());
      }
  });

  $("#clear").click(function(){
    var quantity=parseInt($("#quantity").val());
    for(x=0;x<quantity;x++){
      $("#srl_"+x).val("");
    }

    $("#free_fix").val("");
    $("#pst").val("");
    $("#abc").val("");   
  });

  $(".qunss").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode == 13){
      if(parseInt($(this).val())>0){
        check_is_serial_item(scid);
        $.post("index.php/main/load_data/g_t_serial_out/check_last_serial", {
        item:$("#0_"+scid).val()
        }, function(res){
          $("#last_serial").val(res);
        }, "text");
      }
    }
  }); 

  $(".quns").click(function(){
    set_cid($(this).attr("id"));
    check_is_serial_item(scid);
    $.post("index.php/main/load_data/g_t_serial_out/check_last_serial", {
    item:$("#0_"+scid).val()
    }, function(res){
      $("#last_serial").val(res);
    }, "text");
  }) ;


  $("#btnSave1").click(function(){
    var clik=0;
    var validateCount=parseInt($("#qty").val());
    var result=1;
    var z=[];  
    var q=0;
    var counts=parseInt($(".qtycl"+scid).val());
    var d = $(".srl_count").size();

    $("#numofserial_"+get_id).val(parseInt($(".qtycl"+scid).val()));

   
    if($(".removeSerial").size()>0){
      $(".removeSerial").each(function(e){
        h=this.id.split('_')[1];
        if($("#serial_"+h).val()!="" || $("#serial_"+h).val()!= undefined){
          z.push($("#serial_"+h).val());
        }
      });

    }else{
      for(e=0;e<counts;e++){
        if($("#serial_"+e).val()!="" || $("#serial_"+e).val()!= undefined){
          z.push($("#serial_"+e).val());
        }
      }
    }

    var rslt;
    $(".srl_count").each(function(e){      
      var ser=$(this).val();
      $(".srl_count").each(function(a){ 
        if(e!=a){
          if(ser==$(this).val()){
            rslt=0;
            set_msg("("+ser+") serial no is duplicated.");
            clik=1;
            return false;
          }
        }
      });
      if(rslt==0){return false;}
    });

    $(".srl_count").each(function(e){ 
      if($(this).val()==""){
        set_msg("Please check the serials");
        clik=1;
      }
    });

    if(d!=counts){
      set_msg("Please check the serial quantity");
      clik=1;
    }

    //$("#all_serial_"+scid).val(z);

    if(clik==0){
      $("#light").css("display","none");
      $("#fade").css("display","none");
      $("#all_serial_"+scid).val(z);
    }

  });
});


function emptyElement(element) {
    if (element == null || element == 0 || element.toString().toLowerCase() == 'false' || element == '')
       return false;
       else return true;
}



function seriall_items(){
  var trans_no;
  if($("#hid_grn").val()!=undefined){
    if($("#hid_grn").val()!=0){
      trans_no=$("#hid_grn").val();
    }else{
      if($("#transCode").val()=="117"){
        trans_no=$("#grnno").val();
      }else{
        trans_no=0;
      }
    }
  }else if($("#inv_no").val()!=undefined){
    if($("#inv_no").val()!=0){
      trans_no=$("#inv_no").val();
    }else{
      trans_no=0;
    }
  }else{
    trans_no=0;
  }    

  $.post("index.php/main/load_data/g_t_serial_out/serial_item", {
      search : $("#pop_search44").val(),
      item:$("#item_code").val(),
      stores :$(".store11").val(),
      v_store :$("#v_store").val(),
      //batch_no:$(".btt_"+get_id).val(),
      trans_code:$("#transCode").val(),
      trans_no:trans_no,
      type:$("#types").val(),
      hid:$("#hid").val(),
      no:$("#hid_nno").val(),
      max_no:$("#max_nno").val()
  }, function(r){
       $("#sr44").html(r);
       $("#serial_item_list tr td input").focus();
      settings4();
  }, "text");

}


function settings4(){
  $("#serial_item_list .cl").dblclick(function(){
    var q =parseInt($("#qty").val());
      if($(this).children().eq(0).html() != "&nbsp;"){
        if(check_item_exist4($(this).children().eq(1).html())){
          var get=$(this).children().eq(1).html();
          for(var i=0; i<q ;i++){
            if($("#serial_"+i).val()==get){
              return false;
            }else if($("#serial_"+i).val()==""){
              $("#serial_"+i).val(get);
              break;    
            }
          }
          $("#pop_close4").click();
        }else{
           alert("Serial "+$(this).children().eq(1).html()+" is already added.");
        }
      }else{
        $("#pop_close4").click();
      }  
  });
}

function check_item_exist4(id){
  var v = true;
  $(".srl_count").each(function(){
    if($(this).val() == id){
      v = false;
    }
  });
  return v;
}


function deleteSerial(scid){
  var item_code= $("#itemcode_"+scid).val();
  for(x=serial_items.length-1;x>=0;x--){
    var get_code=serial_items[x].split("-")[0];
    var serial_code=serial_items[x].split("-")[1];

    if(get_code==item_code){
      var cd=get_code+"-"+serial_code;
      var del=serial_items.indexOf(cd);
      serial_items.splice(del, 1);
    }
  }
  $("#numofserial_"+scid).removeAttr("title");
  $("#numofserial_"+scid).removeAttr("value");
  $("#setserial_"+scid).removeAttr("title");
  $("#setserial_"+scid).removeAttr("value");
  $("#itemcode_"+scid).removeAttr("value");
  $("#itemcode_"+scid).removeAttr("title");
}


function load_serial_form(scid){
  get_id=scid;
  var serial_table="";
  var serial_table2="";
  $("#item_code").val($("#0_"+scid).val());
  $("#item").val($("#0_"+scid).val());
  $("#no").val($("#id").val());   
  $("#qty,#quantity").val($(".qtycl"+scid).val()); 
  $("#type_seri").val($("#transtype").val());
   
  var count=parseInt($(".qtycl"+scid).val());

  if($("#all_serial_"+scid).val()!=0){
    loading_serial_items(scid);
  }else{
    var serial_table=serial_table2="";
    for(x=0;x<count;x++){
      if(x<10){var row_count="0"+x;}else{ var row_count=x;}
      serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+x+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
      serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+x+"' style='border:1px solid #000; width:150px;'/></td></tr>";
    }
    $("#set_serial").html(serial_table);
    $("#set_serial2").html(serial_table2);
  }
  seriall_items(); 
  document.getElementById('light').style.display='block';
  document.getElementById('fade').style.display='block';
}


function loading_serial_items(scid){
    
  var serial_table="";
  var serial_table2="";
  var item_code=$("#0_"+scid).val();
  var serial = $("#all_serial_"+scid).val();

  serials=$("#all_serial_"+scid).val().split(",");
  serials_count = serials.length;

  if(parseInt($("#numofserial_"+scid).val())<=parseInt($(".qtycl"+scid).val())){
            
    var y=0;
    var num_of_qty=parseInt($(".qtycl"+scid).val());
    for(x=0;serials_count>x;x++){
      var serial_code=serials[x];
      if(y<10){var row_count="0"+y;}else{ var row_count=y;}
      serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+y+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
      serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>";
      y++;
    }

    var count=parseInt($(".qtycl"+scid).val())-parseInt($("#numofserial_"+scid).val());
    for(x=0;count>x;x++){
      if(y<10){var row_count="0"+y;}else{ var row_count=y;}
      serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+y+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td></tr>";
      serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>"; 
      y++;
    }
  }else{
    var aaa=0;
    for(x=0;serials_count>x;x++){
      var serial_code=serials[x];
      if(x<10){var row_count="0"+x;}else{ var row_count=x;}
      serial_table=serial_table+"<tr><td> "+aaa+" <input type='text' id='serial_"+x+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srlcd srl_count' style='border:1px solid #000; width:150px;'/><input type='button' title='Remove' value='Remove' style='width:75px;height:22px;border:1px solid #000;border-radius:0' class='removeSerial' id='removeSeri_"+x+"' /></td></tr>";
      serial_table2=serial_table2+"<tr><td> "+aaa+" <input type='text' class='g_input_txt srl_count2' id='srl_"+x+"' style='border:1px solid #000; width:150px;'/></td></tr>";
      aaa++;     
    }
  }

  $("#set_serial").html(serial_table);
  $("#set_serial2").html(serial_table2);
}


function check_is_serial_item(scid){
  var item_code=$("#0_"+scid).val();
  if(item_code!=""){
    $.post("index.php/main/load_data/g_t_serial_out/check_is_serial_item",{
       code:item_code,
    },function(r){
      if(r==1){
        load_serial_form(scid);
      }
    },"text"); 
  }
}


function check_is_serial_item2(x,scid){
  var item_code=x;
  if(item_code!=""){
    $.post("index.php/main/load_data/g_t_serial_out/check_is_serial_item",{
      code:item_code,
    },function(r){
      $("#btn_"+scid).css("display","none");
      if(r==1){
        $("#btn_"+scid).css("display","block");
      }
    },"text"); 
  }
}

