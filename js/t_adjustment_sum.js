  var serial_items=[];
  var get_id;
  var serialWind;

$(document).ready(function(){

    $("#btnSave").attr("disabled",true);
    $(".sh").attr("disabled",true);
    $(".st").attr("disabled",true);

  	$("#btnReset").click(function(){
  		location.href="";
  	});

    $(".fo").dblclick(function(){
      set_cid($(this).attr("id"));
      load_serial_form(scid);
    });

    $("#btnPrint").click(function(){
      $("#print_pdf").submit();
    });

    $("#btnSave_f").click(function(){
      if(validate()){
        save_first();
      }
    });

    $("#btnDelete").click(function(){
      if($("#hid").val()==0){
        set_msg("Please load data before delete");
      }else{
        set_delete($("#hid").val());  
      }
    });

    $(".ss").keyup(function(){
      set_cid($(this).attr("id"));
      set_difference(scid);
      set_calulation(scid);
    });

    $("#code").blur(function(){
        check_code();
    });
   
    
    $("#max_no").keydown(function(e){
      if(e.keyCode==13){
        load_data($(this).val());
      }
    });

    $(".fo").focus(function(){
         if($("#to_store").val()=="0"){
           set_msg("please select store"); 
         }

    });


    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
        //seriall_items();
      }
    });

    $("#pop_search4").gselect();

    $("#btnExit1").click(function(){
        document.getElementById('light').style.display='none';
        document.getElementById('fade').style.display='none';  
        $("#4_"+get_id).focus();
    });
    
        
        $("#to_store").change(function(){
          set_select("to_store","store");
        });

        $("#tgrid").tableScroll({height:300});


             $(".fo").blur(function(){
                 var id=$(this).attr("id").split("_")[1];
                 if($(this).val()=="" || $(this).val()=="0"){
                 }else if($(this).val()!=$("#itemcode_"+id).val()){
                   // deleteSerial(id);
                 }
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
                
                $("#h_"+scid).val("");
                $("#0_"+scid).val("");
                $("#n_"+scid).val("");
                $("#1_"+scid).val(""); 
                $("#2_"+scid).val(""); 
                $("#3_"+scid).val(""); 
                $("#4_"+scid).val(""); 
                $("#5_"+scid).val("");

                $("#1count_"+scid).val("");
                $("#2count_"+scid).val("");
                $("#3count_"+scid).val("");
                $("#serialhid_"+scid).val("");
                $("#serial_"+scid).attr("checked",false);


                $("#t_"+scid).html("&nbsp;"); 
                  
                    total();
                    set_total();
            }
            
/*
            if(e.keyCode==13){
            $.post("index.php/main/load_data/t_quotation_sum/get_item", {
                code:$("#0_"+scid).val()
            }, function(res){
                if(res.a!=2){
                    $("#0_"+scid).val(res.a[0].code);

                        if(check_item_exist($("#0_"+scid).val())){
                            $("#h_"+scid).val(res.a[0].code);
                            $("#n_"+scid).val(res.a[0].description);
                            $("#0_"+scid).val(res.a[0].code);
                            $("#1_"+scid).val(res.a[0].model);
                            $("#3_"+scid).val(res.a[0].max_price);
                           
                        }else{
                            set_msg("Item "+$("#0_"+scid).val()+" is already added.");
                        }

                }
            }, "json");

        }*/
    

});

        load_items();
        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
            }
        });

        $(".dis, .qun").blur(function(){
            set_cid($(this).attr("id"));
            set_calulation(scid);     
        });

        $("#pop_search").gselect();

        $("#main_category").autocomplete('index.php/main/load_data/r_category/auto_com', {
            width: 350,
            multiple: false,
            matchContains: true,
            formatItem: formatItems,
            formatResult: formatItemsResult
        });
    
        $("#main_category").keydown(function(e){
            if(e.keyCode == 13){
                set_cus_values2($(this));
               // generate_code();
            }
        });
        
    $("#main_category").blur(function(){
        set_cus_values2($(this));
       // generate_code();
    });


     $(".aq").blur(function(){
        set_cid($(this).attr("id"));
        set_calulation(scid);
     });

      $(".qunn").keyup(function(){
        set_cid($(this).attr("id"));
        $("#44_"+scid).val($(this).val());

      });

});



function emptyElement(element) {
    if (element == null || element == 0 || element.toString().toLowerCase() == 'false' || element == '')
       return false;
       else return true;
  }



function set_cus_values2(f){
            var v = f.val();
            v = v.split("-");
                if(v.length == 2){
                f.val(v[0]);
                $("#main_category_des").val(v[1]);
                //$("#main_category_des").attr("class", "input_txt_f");
        }
    }
function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

function formatItemsResult(row){
        return row[0]+"-"+row[1];
    }

function save(){
  serial_items.sort();
  $("#srls").attr("title",serial_items);
  $("#srls").val(serial_items);

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
        sucess_msg();
      }else{
        loding();
        set_msg(pid);
      }
      
    }
  });
}

function save_first(){
  serial_items.sort();
  $("#srls").attr("title",serial_items);
  $("#srls").val(serial_items);

  var frm = $('#form_');
  loding();
  $.ajax({
    type: frm.attr('method'),
    url: "index.php/main/load_data/t_adjustment_sum/save_first",
    data: frm.serialize(),
    success: function (pid){
      if(pid == 1){
        $("#btnSave_f").attr("disabled",true);
        loding();
        sucess_msg();
      }else{
        loding();
        set_msg(pid);
      }
      
    }
  });
}

function master_save(status){
  var st="0";
  if(status !="1"){
    $(".stcl").each(function(e){
      if($("#0_"+e).val()!=""){
        if($("#statushid_"+e).val()=="1"){
          st="1";  
        }else{
         st="2";
         return false;
        }
      }
    });
    if(st=="1"){
      $("#btnSave").attr("disabled",false);      
    }    
  }

}


function get_data_table(){
    $.post("/index.php/main/load_data/t_adjustment_sum/get_data_table", {
        
    }, function(r){
        $("#grid_body").html(r);
    }, "text");
}


function is_staus_ok(i,item){
  $.post("index.php/main/load_data/t_adjustment_sum/is_status", {
    code:$("#max_no").val(),
    item:item
  }, function(r){
    if(r==1){
      $("#status_"+i).attr("checked",true);    
      $("#statushid_"+i).val("1"); 
      $("#sbar_"+i).css("background-color","#00CC99");     
    }
  }, "json");
}

function validate(){
    // if($("#code").val() === $("#code").attr('title') || $("#code").val() == ""){
    //     set_msg("Please enter code.");
    //     $("#code").focus();
    //     return false;
    // }else if($("#description").val() === $("#description").attr('title') || $("#description").val() == ""){
    //     set_msg("Please enter description.");
    //     $("#description").focus();
    //     return false;
    // }else if($("#description").val() === $("#code").val()){
    //     set_msg("Please enter deferent values for description & code.");
    //     $("#des").focus();
    //     return false;
    // }else{
    //     return true;
    // }
    for(var x=0; x<25; x++){
      if($("#0_"+x).val()!="" && $("#4_"+x).val()=="0"){
        set_msg("quantity difference shouldn't be zero ")
        return false;
      }      
    }
    return true;
}
    
function set_delete(code){
    if(confirm("Are you sure delete "+code+"?")){
        loding();
        $.post("index.php/main/delete/t_adjustment_sum", {
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

function is_edit($mod){
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
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/t_adjustment_sum", {
        code : code
    }, function(res){
        $("#code_").val(res.code);
        $("#code").val(res.code);
	      $("#code").attr("readonly", true);
        $("#description").val(res.description);
        
           if(res.is_vehical == 1){
            $("#is_vehical").attr("checked", "checked");
        }else{
            $("#is_vehical").removeAttr("checked");
        }
        
        
        
       // is_edit('010');
        loding(); input_active();
    }, "json");
}

function select_search(){
    $("#pop_search").focus();
}

function load_items(){
  var item= $( "input:checked" ).val();

    $.post("index.php/main/load_data/t_adjustment_sum/item_list_all", {
        search : $("#pop_search").val(),
        stores : $("#to_store").val(),
        category:$("#main_category").val(),
        item:item
    }, function(r){
        $("#sr").html(r);
        settings();
        
    }, "text");
}

function is_serial_item(code,scid){
  $.post("index.php/main/load_data/t_adjustment_sum/is_serial", {
        item:code
    }, function(r){
      if(r==1){
        $("#serialhid_"+scid).val("1");
        $("#serial_"+scid).attr("checked", true);              
      }
    }, "json");
}

function empty_row(scid){
  $("#h_"+scid).val("");
  $("#0_"+scid).val("");
  $("#n_"+scid).val("");
  $("#1_"+scid).val(""); 
  $("#2_"+scid).val(""); 
  $("#3_"+scid).val(""); 
  $("#4_"+scid).val(""); 
  $("#5_"+scid).val("");

  $("#1count_"+scid).val("");
  $("#2count_"+scid).val("");
  $("#3count_"+scid).val("");
  $("#serialhid_"+scid).val("");
  $("#serial_"+scid).attr("checked",false);

  $("#t_"+scid).val(""); 
                  
  total();
  set_total();
}

function settings(){
    $("#item_list .cl").click(function(){
      empty_row(scid);
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
                
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#1_"+scid).val($(this).children().eq(2).html());
                $("#2_"+scid).val($(this).children().eq(3).html());
                $("#3_"+scid).val($(this).children().eq(3).html());
                $("#5_"+scid).val($(this).children().eq(4).html());
                $("#3_"+scid).focus();
                is_serial_item($(this).children().eq(0).html(),scid);
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
            set_total();$("#pop_close").click();
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

function set_difference(scid){
    var current_qty;
    var act_qty;
    
    if ($("#2_"+scid).val()==""){
      current_qty=0;
    }else{
      current_qty = parseFloat($("#2_"+scid).val());  
    }  

    if($("#3count_"+scid).val()!="" && $("#3count_"+scid).val()!="0"){
      act_qty = parseFloat($("#3count_"+scid).val());
    }else if($("#2count_"+scid).val()!="" && $("#2count_"+scid).val()!="0"){
      act_qty = parseFloat($("#2count_"+scid).val());
    }else if($("#1count_"+scid).val()!="" && $("#1count_"+scid).val()!="0"){
      act_qty = parseFloat($("#1count_"+scid).val());
    }else{
      act_qty = parseFloat($("#1count_"+scid).val());
    }
    $("#4_"+scid).val(act_qty - current_qty);

}

function total(){
   
    var x = parseFloat($("#4_"+scid).val());
    var y = parseFloat($("#5_"+scid).val());
 
    var a;
    if(!isNaN(x) && !isNaN(y)){         
         a=x*y;        
         $("#t_"+scid).html(m_round(a));   
    }
}

function set_total(){
    var gross=0;
    var net=0;
    var t=0;
    $(".tf").each(function(){
        var x = parseFloat($("#4_"+t).val());
        var y = parseFloat($("#5_"+t).val());
      
        if(isNaN(x)||isNaN(y)){ x=0; y=0;}
        gross += x*y;       
        net=gross;
        t++;
    });    
  
   $("#net_amount").val( m_round(net));
}

function set_calulation(scid){
  var current_qty=parseFloat($("#2_"+scid).val());
  var actual_qty=parseFloat($("#3_"+scid).val());
  if(isNaN(current_qty)){current_qty=0;}
  if(isNaN(actual_qty)){actual_qty=0;}
  var differ=$("#4_"+scid).val();
  var cost=parseFloat($("#5_"+scid).val());
  if(isNaN(cost)){cost=0;}
  var total=cost*differ;
  $("#t_"+scid).val(m_round(total));
 
  var net_amount=0;
  $(".tf").each(function(e){
    if(!isNaN(parseFloat($("#t_"+e).val()))){
       net_amount=net_amount+parseFloat($("#t_"+e).val());
    }
  });
  $("#net_amount").val(net_amount);
}


function load_data(id){
  var amnt = 0;
  
  var staus_ok = 0;
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_adjustment_sum/", {
      id: id
  }, function(r){

    if(r=="2"){
       set_msg("No records");
    }else{

      $("#hid").val(id);   
      $("#qno").val(id);  
      $("#to_store").val(r.sum[0].store);
      $("#str").val(r.sum[0].store);
      set_select('to_store','store')
      $("#memo").val(r.sum[0].memo);
      $("#mem").val(r.sum[0].memo);
      $("#ref_no").val(r.sum[0].ref_no);
      $("#ddate").val(r.sum[0].ddate);
      $("#dt").val(r.sum[0].ddate);
      $("#net_amount").val(r.sum[0].net_amount);
      $("#max_no").attr("readonly","readonly");            
      if(r.sum[0].is_cancel==1)
      {
        $("#form_").css("background-image", "url('img/cancel.png')");
        $("#btnDelete").attr("disabled", true);
        $("#btnSave").attr("disabled", true);
      }
      for(var i=0; i<r.det.length;i++){

        //get_cur_qty(r.det[i].code,r.det[i].batch_no,i);
        
        $("#h_"+i).val(r.det[i].code);
        $("#0_"+i).val(r.det[i].code);
        $("#n_"+i).val(r.det[i].description);
        $("#1_"+i).val(r.det[i].batch_no);
        $("#2_"+i).val(r.det[i].cur_qty); //---LOAD AVAILABLE STOCK 

        $("#1count_"+i).val(r.det[i].f_qty);
        $("#2count_"+i).val(r.det[i].s_qty);
        $("#3count_"+i).val(r.det[i].t_qty);
        $("#4_"+i).val(r.det[i].difference);
        $("#5_"+i).val(r.det[i].cost);
        $("#t_"+i).val(r.det[i].price);
        $("#itemcode_"+i).val(r.det[i].code);
        
        if(r.det[i].is_serial =="1"){
          $("#serial_"+i).attr("checked",true);    
          $("#serialhid_"+i).val("1"); 
        }else{
          $("#status_"+i).attr("checked",true);    
          $("#statushid_"+i).val("1"); 
          $("#sbar_"+i).css("background-color","#00CC99"); 
          
        }

        if(r.det[i].difference=="0"){
          $("#status_"+i).attr("checked",true);    
          $("#statushid_"+i).val("1"); 
          $("#sbar_"+i).css("background-color","#00CC99"); 
        }

        if(r.det[i].status =="1"){
          $("#btnSave").attr("disabled",true);
          $("#btnSave_f").attr("disabled",true);
          $("#btnDelete").attr("disabled",true);
          staus_ok="1";
        }

      is_staus_ok(i,r.det[i].code);      
      setTimeout("master_save('"+staus_ok+"')",1000); 
      setTimeout("set_readonly('"+i+"')",1000); 
      
      }
      

       
      input_active();  
    }
    loding();
  }, "json");
}



function empty_grid(){
      for(var i=0; i<25; i++){
          $("#h_"+i).val("");
          $("#0_"+i).val("");
          $("#n_"+i).val(""); 
          $("#1_"+i).val("");
          $("#2_"+i).val("");
          $("#3_"+i).val("");
          $("#4_"+i).val("");
          $("#5_"+i).val("");
          $("#1count_"+i).val("");
          $("#2count_"+i).val("");
          $("#3count_"+i).val("");
          $("#serialhid_"+i).val("");
          $("#serial_"+i).attr("checked",false);
          $("#t_"+i).val("");
 }
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

function select_search4(){
    $("#pop_search4").focus();
}

function load_serial_form(scid){
  if($("#serialhid_"+scid).val()=="1" && $("#0_"+scid).val()!="" &&$("#statushid_"+scid).val()!="1"){
    window.open("?action=t_serial_adjustment_sum&data="+$("#max_no").val()+"","_blank");  
  }
}

function set_readonly(i){

  if($("#serialhid_"+i).val()=="1" && $("#statushid_"+i).val()=="1"){
    $("#1count_"+i).attr("readonly",true);
    $("#2count_"+i).attr("readonly",true);
    $("#3count_"+i).attr("readonly",true);   
  }
}

function get_cur_qty(item_code,batch_no,x){
  $.post("index.php/main/load_data/t_adjustment_sum/get_cur_qty", {
        store_code:$("#to_store").val(),
        batch:batch_no,
        item:item_code
    }, function(r){
        $("#2_"+x).val(r);
        set_difference(x);
    }, "json");
}