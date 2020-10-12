var serial_items=[];
var get_id;
  

$(document).ready(function(){
  $("#tabs").tabs();
  $(".quns").css("display","none");

  $("#set_serial").delegate(".srl_count", "keyup", function(){
    this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
  });

  $("#free_fix,#pst").keyup(function(){
    var get_code=$(this).val();
    $(this).val(get_code.toUpperCase());
  });


  $("#set_serial").delegate(".removeSerial", "click", function(){
    var dis=$(this).attr("id").split("_")[1];
    var serial_code=$("#item_code").val()+"-"+$("#serial_"+dis).val()+"-"+$("#otherNo1_"+dis).val()+"-"+$("#otherNo2_"+dis).val();
    var qty=parseInt($("#qty").val());
   
    $("#otherNo1_"+dis).empty();
    $("#otherNo2_"+dis).empty();
    $("#serial_"+dis).empty();
    $("#removeSerial"+dis).empty();
    
    $("#serial_"+dis).remove();
    $("#otherNo1_"+dis).remove();
    $("#otherNo2_"+dis).remove();

    $(this).remove();
   
    var n = $( ".removeSerial" ).length;
    if(n==qty){
      $(".removeSerial").attr("disabled","disabled");
    }
  });

  $("#gen").click(function(){
    var free_fix=$("#free_fix").val();
    var post_fix=$("#pst").val();
    var start_no=$("#abc").val();
    var quantity=parseInt($("#quantity").val());

    var len = start_no.length;
    var no = parseInt(start_no);

    for(x=0;x<quantity;x++){

     var c = addzeros(no,len);
     no++;
     var code_gen=free_fix+c+post_fix;
     $("#srl_"+x).val(code_gen);
   }
 });


  $("#add").click(function(){
    var quantity=parseInt($("#quantity").val());
    for(x=0;x<quantity;x++){
      $("#serial_"+x).val($("#srl_"+x).val());
    }
    alert("Success");
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


  $(document).on("click","#btnSave1", function(){
    var clik=0;
    var count=[];
    var h=0;
    var z=[];
    var t=parseInt(0);
    var counts=parseInt($("#qty").val());
    var d = $(".srl_count").size();

    if($(".removeSerial").size()>0){
      $(".removeSerial").each(function(e){
        h=this.id.split('_')[1];
        if($("#serial_"+h).val()!="" || $("#serial_"+h).val()!="undefined"){
          z.push($("#serial_"+h).val()+"-"+$("#otherNo1_"+h).val()+"-"+$("#otherNo2_"+h).val());
        }
      });
    }else{
      for(x=0;x<counts;x++){
        if($("#serial_"+x).val()!="" || $("#serial_"+x).val()!="undefined"){
          z.push($("#serial_"+x).val()+"-"+$("#otherNo1_"+x).val()+"-"+$("#otherNo2_"+x).val());
        }
      }
    }
    $("#numofserial_"+get_id).val(counts);

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

   
    
    if(clik==0){
      $("#light").css("display","none");
      $("#fade").css("display","none");
      $(".clr_"+get_id).css("background-color","#81F7BE");
      $("#all_serial_"+get_id).val(z);
    }
  });


  $(".qun").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode == 13){
      if(parseInt($(this).val())>0){
        check_is_serial_item(scid);
        $.post("index.php/main/load_data/t_serial_in/check_last_serial", {
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
    $.post("index.php/main/load_data/t_serial_in/check_last_serial", {
      item:$("#0_"+scid).val()
    }, function(res){
      $("#last_serial").val(res);
    }, "text");     
  });
});

function load_serial_form(scid){
  get_id=scid;
  var serial_table="";
  var serial_table2="";
  $("#item_code").val($("#0_"+scid).val());
  $("#item").val($("#n_"+scid).val());
  $("#no").val($("#id").val());   
  $("#qty,#quantity").val($(".qtt_"+scid).val()); 
  $("#type").val($("#transtype").val()); 
     
  var count=parseInt($(".qtt_"+scid).val());
    if($("#all_serial_"+scid).val()!=0){
      loading_serial_items(scid);
    }else{
      var serial_table=serial_table2="";
      for(x=0;x<count;x++){
        if(x<10){var row_count="0"+x;}else{ var row_count=x;}
        serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+x+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td><td><input type='text' id='otherNo1_"+x+"'/></td><td><input type='text' id='otherNo2_"+x+"'/></td></tr>";
        serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+x+"' style='border:1px solid #000; width:150px;'/></td></tr>";
      }
      $("#set_serial").html(serial_table);
      $("#set_serial2").html(serial_table2);
    }
    setTimeout('$("#serial_0").focus()',500);
  document.getElementById('light').style.display='block';
  document.getElementById('fade').style.display='block';
}


function loading_serial_items(scid){
  var serial_table="";
  var serial_table2="";
  var serials_count=0;
  var item_code=$("#0_"+scid).val();
  var serial = $("#all_serial_"+scid).val();

  serials=$("#all_serial_"+scid).val().split(",");
  serials_count = serials.length;
  if(parseInt($("#numofserial_"+scid).val())<=parseInt($(".qtt_"+scid).val())){
    var y=0;
    var num_of_qty=parseInt($(".qtt_"+scid).val());           

    for(x=0;serials_count>x;x++){
      var serial_code=serials[x].split("-")[0];
      var other1=serials[x].split("-")[1];
      var other2=serials[x].split("-")[2];

      if(y<10){var row_count="0"+y;}else{ var row_count=y;}
      serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+y+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td><td><input type='text' title='"+other1+"' value='"+other1+"' id='otherNo1_"+y+"'/></td><td><input type='text' title='"+other2+"' value='"+other2+"' id='otherNo2_"+y+"'/></td></tr>";
      serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>";
      y++;                
    }
    var count=parseInt($(".qtt_"+scid).val())-parseInt($("#numofserial_"+scid).val());
    for(x=0;count>x;x++){
      if(y<10){var row_count="0"+y;}else{ var row_count=y;}
      serial_table=serial_table+"<tr><td> "+row_count+" <input type='text' id='serial_"+y+"' class='g_input_txt srl_count' style='border:1px solid #000; width:150px;'/></td><td><input type='text' id='otherNo1_"+y+"'/></td><td><input type='text' id='otherNo2_"+y+"'/></td></tr>";
      serial_table2=serial_table2+"<tr><td> "+row_count+" <input type='text' class='g_input_txt srl_count2' id='srl_"+y+"' style='border:1px solid #000; width:150px;'/></td></tr>"; 
      y++;
    }
  }else{ 
    var aaa=0;
    for(x=0;serials_count>x;x++){
      var serial_code=serials[x].split("-")[0];
      var other1=serials[x].split("-")[1];
      var other2=serials[x].split("-")[2];
      if(x<10){var row_count="0"+x;}else{ var row_count=x;}
      serial_table=serial_table+"<tr><td> "+aaa+" <input type='text' id='serial_"+aaa+"' title='"+serial_code+"' value='"+serial_code+"' class='g_input_txt srlcd srl_count' style='border:1px solid #000; width:150px;'/></td><td><input type='text' title='"+other1+"' value='"+other1+"' id='otherNo1_"+aaa+"'/></td><td><input type='text' title='"+other2+"' value='"+other2+"' id='otherNo2_"+aaa+"'/></td><td><input type='button' title='Remove' value='Remove' style='width:75px;height:22px;border:1px solid #000;border-radius:0' class='removeSerial' id='removeSeri_"+aaa+"' /></td></tr>";
      serial_table2=serial_table2+"<tr><td> "+aaa+" <input type='text' class='g_input_txt srl_count2' id='srl_"+aaa+"' style='border:1px solid #000; width:150px;'/></td></tr>";
      aaa++;
    }
  }
  $("#set_serial").html(serial_table);
  $("#set_serial2").html(serial_table2);
}

function check_is_serial_item(scid){
  var item_code=$("#0_"+scid).val();
  if(item_code!=""){
    $.post("index.php/main/load_data/t_serial_in/check_is_serial_item",{
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
    $.post("index.php/main/load_data/t_serial_out/check_is_serial_item",{
      code:item_code,
    },function(r){
      $("#btn_"+scid).css("display","none");
      if(r==1){
        $("#btn_"+scid).css("display","block");
      }
    },"text"); 
  }
}


function addzeros(number, length) {
  var num = '' + number;
  while (num.length < length) {
    num = '0'+num;
  }
  return num;
}
