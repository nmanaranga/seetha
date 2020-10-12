 var storse = 0;
 var is_edit=0;
var sub_items=[];

$(document).ready(function(){
  
  $(".quns").css("display","none");
  $(".subs").css("display","none");
  
  $("#btnPrint").click(function () {
    if ($("#hid").val() == "0") {
        set_msg("Please load data before print");
        return false;
    }else{
        $("#print_pdf").submit();
    }
  });

 var is_serial = $("#df_is_serial").val();

  $(".fo").dblclick(function(){
  set_cid($(this).attr("id"));  
  if($(this).val()!=""){
      $.post("index.php/main/load_data/utility/get_sub_item_detail", {
          code:$(this).val(),
          store:$("#stores").val(),
          po:$("#pono").val(),
          qty:$("#1_"+scid).val()
      }, function(res){
          if(res!=0){
              $("#msg_box_inner").html(res);
              $("#msg_box").slideDown();
          }
      },"text");
     } 
  });

	$("#btnReset").click(function(){
   	location.href="?action=t_open_stock";
	});

  $("#chkserial").click(function() {
    check_serials();
  });

  $("#pop_close12").click(function(){
    $("#serch_pop12").css("display", "none");
    $("#blocker2").css("display", "none");
  });


  $("#btnDelete5").click(function(){
    set_delete();
  });

   $(".fo").blur(function(){
     var id=$(this).attr("id").split("_")[1];
     if($(this).val()=="" || $(this).val()=="0"){
     }else if($(this).val()!=$("#itemcode_"+id).val()){
      if($("#df_is_serial").val()=='1'){
        // deleteSerial(id);
      }
     }
   });


    $("#item_list").tableScroll({height:200});
    $("#stores").val(storse);
    set_select('stores', 'sto_des');

     $(".fo").focus(function(){
      if($("#stores").val()==0){
        set_msg("Please select store","error");
      }else{


    $(".fo").keydown(function(e){
        set_cid($(this).attr("id"));
        if(e.keyCode==112){
            load_items();
            $("#pop_search").select();       
            $("#serch_pop").center();
            $("#blocker").css("display", "block");
            setTimeout("select_search()", 100);
        }

        if(e.keyCode==46){
          if($("#df_is_serial").val()=='1'){
            $("#all_serial_"+scid).val("");
          }
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); 
            $("#2_"+scid).val(""); 
            $("#3_"+scid).val("");
            $("#m_"+scid).val("");
            $("#min_"+scid).val("");
            $("#max_"+scid).val("");
            $("#btn_"+scid).css("display","none");  
            $("#t_"+scid).html(""); 
            $("#subcode_"+scid).html(""); 
            $("#is_click_"+scid).val("");
            $("#sub_"+scid).css("display","none");
            $("#sub_"+scid).removeAttr("data-is_click");
            set_sub_total();
        }


        if(e.keyCode==13){
            $.post("index.php/main/load_data/t_open_stock/get_item", {
                code:$("#0_"+scid).val()
            }, function(res){
                if(res.a!=2){
                    $("#0_"+scid).val(res.a[0].code);

                        if(check_item_exist($("#0_"+scid).val())){

                          if($("#df_is_serial").val()=='1'){
                            check_is_serial_item2(res.a[0].code,scid); 
                          }
                          check_is_sub_item2(res.a[0].code,scid);
               
                          $("#h_"+scid).val(res.a[0].code);
                          $("#n_"+scid).val(res.a[0].description);
                          $("#0_"+scid).val(res.a[0].code);
                          $("#2_"+scid).val(res.a[0].purchase_price);
                          $("#m_"+scid).val(res.a[0].model);
                          $("#min_"+scid).val(res.a[0].min_price);
                          $("#max_"+scid).val(res.a[0].max_price);

                          $("#h_"+scid).attr("title" ,res.a[0].code);
                          $("#n_"+scid).attr("title" ,res.a[0].description);
                          $("#0_"+scid).attr("title" ,res.a[0].code);
                          $("#m_"+scid).attr("title" ,res.a[0].model);
                        
                          $("#1_"+scid).removeAttr("disabled"); 
                          $("#2_"+scid).removeAttr("disabled");
                          $("#3_"+scid).removeAttr("disabled");
                          $("#1_"+scid).focus();
                           
                        }else{
                            set_msg("Item "+$("#0_"+scid).val()+" is already added.");
                        }

                }
            }, "json");
        }
    });
  }
});

    
    load_items();

    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
        }
    });
    
    $("#tgrid").tableScroll({height:300});

    $("#pop_search").gselect();

   
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });





   function load_data(id){
    var g=[];
    empty_grid();
     loding();
    $.post("index.php/main/get_data/t_open_stock/", {
        nno: id
    }, function(r){
            
            if(r=="2"){
               set_msg("No records","error");
            }else{
              
           
              $("#hid").val(r.sum[0].nno);
              $("#qno").val(r.sum[0].nno);
              $("#id").attr("readonly",'readonly');
              $("#stores").val(r.sum[0].store);
              set_select('stores','sto_des');
              $("#ddate").val(r.sum[0].ddate);
              $("#ref_no").val(r.sum[0].ref_no);
              $("#total2").val(r.sum[0].net_amount);


                for(var i=0; i<r.det.length;i++){

                   $("#h_"+i).val(r.det[i].item_code);
                   $("#0_"+i).val(r.det[i].item_code);
                   $("#itemcode_"+i).val(r.det[i].item_code);
                   $("#min_"+i).val(r.det[i].min_price);
                   $("#max_"+i).val(r.det[i].max_price);
                   $("#n_"+i).val(r.det[i].item_desc);
                   $("#1_"+i).val(r.det[i].qty);
                   $("#m_"+i).val(r.det[i].model);
                   if($("#df_is_serial").val()=='1')
                   {
                    check_is_serial_item2(r.det[i].item_code,i);
                    $("#numofserial_"+i).val(r.det[i].qty);

                    for(var a=0;a<r.serial.length;a++){
                      if(r.det[i].item_code==r.serial[a].item){
                        g.push(r.serial[a].serial_no+"-"+r.serial[a].other_no1+"-"+r.serial[a].other_no2);
                        $("#all_serial_"+i).val(g);
                      }   
                    }
                    g=[];  
                   }
                   
                    $("#2_"+i).val(r.det[i].cost);
                        var x = parseFloat($("#1_"+i).val());
                        var y = parseFloat($("#2_"+i).val());
                        var z;
                        if(! isNaN(x) && ! isNaN(y)){
                            z = x*y;
                            $("#t_"+i).html(m_round(z));
                        }else{
                            $("#t_"+i).html("0.00");
                        }
                  is_sub_item(i);
                  check_is_sub_item2(r.det[i].item_code,i); 
                }

                input_active();
            

                    if(r.sum[0].is_cancel==1){
                      $("#btnSave").attr("disabled", "disabled");
                      $("#btnDelete5").attr("disabled", "disabled");
                      $("#btnPrint").attr("disabled", "disabled");
                      set_msg("Transaction Canceled","error");
                      $("#btnDelete").attr("disabled", "disabled");
                      $("#btnSave").attr("disabled", "disabled");
                      $("#mframe").css("background-image", "url('img/cancel.png')");
                    }
     
              loding();     
          }

    }, "json");
}
    

    
    $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();}
    });
    

     $(".amo, .qun, .dis").blur(function(){
        set_cid($(this).attr("id"));    
        set_sub_total();
    });


    
    $("#id").keydown(function(e){
        if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
        }
    });
    

    
    $("#stores").change(function(){
        set_select('stores', 'sto_des');
        load_items();
    });


    //serial coding

    $("#btnExit1").click(function(){
        
         document.getElementById('light').style.display='none';
         document.getElementById('fade').style.display='none';
    });    


    $(document).on("click",".subs",function(){
      set_cid($(this).attr("id"));
      check_is_sub_item(scid); 
      $("#is_click_"+scid).val("1");    
   });


  $(document).on("click",".approve",function(){
    var q="";
    $(".approve").each(function(e){
      if($('#app_' +e).is(":checked")){
        q=q+$("#sub_"+e).text()+"-"+$("#subqty_"+e).text()+",";
      }
    });
      $("#subcode_"+scid).val(q);
  });

});


function is_sub_item(x){
  $.post("index.php/main/load_data/utility/is_sub_items_load", {
        code:$("#0_"+x).val(),
       hid:$("#hid").val(),
       type:'2'
      }, function(r){
        if(r!=2){
          sub_items=[];
            var a = "";
          for(var i=0; i<r.sub.length;i++){
            //sub_items.push(r.sub[i].sub_item+"-"+r.sub[i].qty_in);
            a=a+r.sub[i].sub_item+"-"+r.sub[i].qty+",";
          }  
            $("#subcode_"+x).val(a);
        }
      },"json");
}

function select_search3(){
    $("#pop_search3").focus();
}


function check_is_sub_item(scid){        
    var store=$("#stores").val();
    $.post("index.php/main/load_data/utility/is_sub_item",{
        code:$("#0_"+scid).val(),          
    },function(res){        
       if(res==1)
        {
            $("#serch_pop3").center();
            $("#blocker3").css("display", "block");
            setTimeout("select_search3()", 100);
            load_items4($("#0_"+scid).val());
        }
    },'text');    
}


function load_items4(x){
    $.post("index.php/main/load_data/utility/sub_item_window", {
        search : x,
    }, function(r){
        $("#sr3").html(r);
        match_sub_item();
    }, "text");

}

function match_sub_item(){
  if($("#subcode_"+scid).val()!="" && $("#subcode_"+scid).val()!=0){ 
    var hid_subs = $("#subcode_"+scid).val();
    var scode = hid_subs.split(",");
    for(var c =0; c<scode.length; c++){
      var sub_item = scode[c].split("-");
      var item =sub_item[0];
      var qty =sub_item[1];

      $(".approve").each(function(e){
        if($("#sub_"+e).text()==item){
          $("#app_"+e).prop('checked',true);
        }
      });
    } 
  }
}


function check_is_sub_item2(x,scid){
    var store=$("#stores").val();
    $.post("index.php/main/load_data/utility/is_sub_item",{
        code:x          
     },function(res){
        $("#sub_"+scid).css("display","none");    
        if(res==1){
            $("#sub_"+scid).css("display","block");
            $("#sub_"+scid).attr("data-is_click","1");
        }
    },'text');
}


  function set_delete(){
    if($("#df_is_serial").val()=='1'){
      serial_items.sort();
      $("#srls").attr("title",serial_items);
      $("#srls").val(serial_items);   
    }

    var id = $("#hid").val();
    
    if(id != 0){
      if(confirm("Are you sure to delete this open stock ["+$("#id").val()+"]? ")){
        $.post("index.php/main/delete/t_open_stock", {
          trans_no:id,
        },function(r){
            if(r != 1){
              set_msg(r,"error");
            }else{
              delete_msg();
            }
        }, "text");
      }
    }else{
      set_msg("Please load record","error");
    }
  }

function empty_grid(){
    for(var i=0; i<40; i++){
        $("#h_"+i).val(0);
        $("#is_ser_"+i).val(0);
        $("#is_ser_upt_"+i).val(0);
        $("#0_"+i).val("");
        $("#n_"+i).val("");
        $("#t_"+i).html("&nbsp;");
        $("#1_"+i).val("");
        $("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#subcode_"+i).val("");
        $("#is_click_"+i).val("");
        $("#sub_"+i).removeAttr("data-is_click");
    }
       $(".quns").css("display","none");
       $(".subs").css("display","none");
}

function set_sub_total(){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z;
    if(! isNaN(x) && ! isNaN(y)){
        z = x*y;
        $("#t_"+scid).html(m_round(z));
    }else{
        $("#t_"+scid).html("0.00");
    }
    
    set_total();
}


function select_search(){
    $("#pop_search").focus();
}


function load_items(){
    $.post("index.php/main/load_data/t_open_stock/item_list_all", {
        search : $("#pop_search").val(),
        stores : false
    }, function(r){
        $("#sr").html(r);
        settings();
    }, "text");
}

function empty_row(scid){
  if($("#df_is_serial").val()=='1'){
    $("#all_serial_"+scid).val("");
  }
  $("#h_"+scid).val("");
  $("#0_"+scid).val("");
  $("#n_"+scid).val("");
  $("#1_"+scid).val(""); 
  $("#2_"+scid).val(""); 
  $("#3_"+scid).val("");
  $("#m_"+scid).val("");
  $("#min_"+scid).val("");
  $("#max_"+scid).val("");
  $("#btn_"+scid).css("display","none");  
  $("#t_"+scid).html(""); 
  $("#subcode_"+scid).html(""); 
  $("#is_click_"+scid).val("");
  $("#sub_"+scid).css("display","none");
  $("#sub_"+scid).removeAttr("data-is_click");
  set_sub_total();
}

function settings(){
    $("#item_list .cl").click(function(){
      empty_row(scid);
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
              if($("#df_is_serial").val()=='1'){
                check_is_serial_item2($(this).children().eq(0).html(),scid); 
              }
                check_is_sub_item2($(this).children().eq(0).html(),scid);

                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#m_"+scid).val($(this).children().eq(2).html());
                $("#2_"+scid).val($(this).children().eq(3).html());
                $("#min_"+scid).val($(this).children().eq(4).html());
                $("#max_"+scid).val($(this).children().eq(5).html());
                $("#3_"+scid).val($(this).children().eq(6).html());

                $("#h_"+scid).attr("title" ,$(this).children().eq(0).html());
                $("#0_"+scid).attr("title" ,$(this).children().eq(0).html());
                $("#n_"+scid).attr("title" ,$(this).children().eq(1).html());
                $("#m_"+scid).attr("title" ,$(this).children().eq(2).html());
                
                if($(this).children().eq(6).html() == 1){
                    $("#1_"+scid).autoNumeric({mDec:2});
                }else{
                    $("#1_"+scid).autoNumeric({mDec:0});
                }
                $("#1_"+scid).removeAttr("disabled"); 
                $("#2_"+scid).removeAttr("disabled");
                $("#3_"+scid).removeAttr("disabled");
                $("#1_"+scid).focus();$("#pop_close").click();
            }else{
                set_msg("Item "+$(this).children().eq(1).html()+" is already added","error");
            }
        }else{
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#m_"+scid).val("");
            $("#1_"+scid).val(""); 
            $("#2_"+scid).val("");
            $("#min_"+scid).val("");
            $("#max_"+scid).val(""); 
            $("#3_"+scid).val(""); 
            $("#t_"+scid).html("&nbsp;");
            $("#1_"+scid).attr("disabled", "disabled"); 
            $("#2_"+scid).attr("disabled", "disabled");
            $("#3_"+scid).attr("disabled", "disabled");
            set_total();
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


function set_total(){
    var t = tt = 0; 
    $(".tf").each(function(){
        tt = parseFloat($(this).html());
        if(isNaN(tt)){ tt = 0;}
        t += tt;
    });
    
    $("#total2").val(m_round(t));
}

function validate(){
    var v = false;
    var g = true;
    $("input[type='hidden']").each(function(){
        if($(this).val() != "" && $(this).val() != 0){
            v = true;
        }
    });

    for(var i=0; i<40; i++){
      if($("#is_click_"+i).val()!=1 && $("#sub_"+i).data("is_click")==1){
        set_msg("Please check sub items in ("+$("#0_"+i).val()+")" ,"error");
        return false;
        break;
      } 
    }

    if(v == false){
        set_msg("Please use minimum one item","error");
    }else if($("#stores option:selected").val() == 0){
        set_msg("Please select stores","error");
        v = false;
    }else if($("#0_"+scid).val()!="" && $("#1_"+scid).val()==""){
      set_msg("Quantity should be more than 0","error");
    return false;
    }
    return v;

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
    data:frm.serialize(),
    success: function (pid){
            var sid=pid.split('@');
            if(sid[0]==1){
              $("#btnSave").attr("disabled",true);
              loding();
              //sucess_msg();
              if(confirm("Save Completed, Do You Want A print?")){ 
                    
                    if($("#is_prnt").val()==1){
                        $("#qno").val(sid[1]);
                        $("#print_pdf").submit();
                    }
                        location.href="";
                    }else{
                        location.href="";
                    }
            }else if(pid == 2){
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
              set_msg(pid,"error");
            }
        }
    });
}




function check_is_serial_item(scid){
  if($("#df_is_serial").val()=='1'){

        var item_code=$("#0_"+scid).val();
        if(item_code!=""){
         $.post("index.php/main/load_data/t_open_stock/check_is_serial_item",{
                code:item_code,
             },function(r){
                if(r==1){
                  load_serial_form(scid);
                }
             },"text"); 
       }
    }
    
  }


function check_serials(){
  var frm = $('#form_');
  loding();
  $.ajax({
    type: frm.attr('method'),
    url: "index.php/main/load_data/validation/is_serial_exsit",
    data:frm.serialize(),
    success: function (pid){
      loding();
      var result=pid.split('|');
      if(result[0]=="1"){
        alert("All Serial Numbers OK");
      }else{
        $("#pop_search12").val();
        $("#serch_pop12").center();
        $("#blocker2").css("display", "block");
        $("#pop_search12").css("display", "none");
        $("#sr12").html(result[1]); 
      }
    }
  },'text');
}
