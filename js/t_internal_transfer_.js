var sub_items=[];
var press_enter=0;
$(document).ready(function(){

  $("#mode").change(function(){
    $("#tr_mode").val($(this).val());
    empty_grid();
    if($(this).val()=='0'){
      $(".fo").css("pointer-events","none");
    }else{
     $(".fo").css("pointer-events","auto");
     $("#order_no").css("pointer-events","none");
   }
 });

  $(".qunsb").css("display","none");
  $(".quns").css("display","none");

  $(".ky").keyup(function(){
    var aqty = parseFloat($("#qtyh_"+scid).val());
    var tqty = parseFloat($("#2_"+scid).val());
    
    if(aqty<tqty){
      set_msg("Maximum available quantity is "+aqty);
      return false;
    }
  });

  $("#t_type").change(function(){
    empty_grid();
    get_sub_no();
  });

  $("#to_cluster").change(function(){
    $.post("index.php/main/load_data/t_internal_transfer/to_branch", {
      cluster:$("#to_cluster").val()
    }, function(res){ 
      $("#to_branch").html(res)
    },"text");
  });

  $("#branch").change(function(){
    set_select('branch','branch_hid');
  });

  $("#to_cluster").change(function(){
    set_select('to_cluster','to_cluster_hid');
  });

  $("#to_branch").change(function(){
    set_select('to_branch','to_branch_hid');
  });

  $("#store_from").change(function(){
    set_select('store_from','store_hid');
    empty_grid();
  });

  $("#v_store").change(function(){
    set_select('v_store','location_store_hid');
  });

  $("#sub_no").keydown(function(e){
    if(e.keyCode==13){      
      load_data($(this).val());
    }
  });

  $("#vehicle").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search2").val($("#vehicle").val());
      load_vehicle();
      $("#serch_pop2").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search2').focus()", 100);
    }
    $("#pop_search2").keyup(function(e){        
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_vehicle();
     }
   }); 
    if(e.keyCode == 46){
      $("#vehicle").val("");
      $("#vehicle_des").val("");
      $("#v_store").val("");
    }
  });

  $("#driver_id").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search11").val($("#driver_id").val());
      load_driver();
      $("#serch_pop11").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search11').focus()", 100);
    }
    $("#pop_search11").keyup(function(e){        
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_driver();
     }
   }); 
    if(e.keyCode == 46){
      $("#driver_id").val("");
      $("#driver_name").val("");
    }
  });

  $("#helper_id").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search12").val($("#helper_id").val());
      load_helper();
      $("#serch_pop12").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search12').focus()", 100);
    }
    $("#pop_search12").keyup(function(e){        
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_helper();
     }
   }); 
    if(e.keyCode == 46){
      $("#helper_id").val("");
      $("#helper_name").val("");
    }
  });

  $("#order_no").keydown(function(e){
    if(e.keyCode == 8 || e.keyCode == 46){   
      press_enter=0;
    }  
  });


  $("#order_no").keydown(function(e){
    if(e.keyCode == 112){     
      if($("#to_cluster").val()=="0"){
        set_msg("Please Select To Cluster & Branch.");
      }else{
        $("#pop_search10").val($("#order_no").val());
        load_oreders();
        $("#serch_pop10").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search10').focus()", 100);
      }
    }
    $("#pop_search10").keyup(function(e){        
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_oreders();
     }
   }); 
    if(e.keyCode == 46){
      $("#order_no").val("");
    }
  });


  $("#free_fix,#pst").blur(function(){
    var get_code=$(this).val();
    $(this).val(get_code.toUpperCase());
  });

  $("#btnExit1").click(function(){
    document.getElementById('light').style.display='none';
    document.getElementById('fade').style.display='none';  
    $("#2_"+get_id).focus();
  });

  $("#order_no").keydown(function(e){
    if(e.keyCode==13){
      if(press_enter==0){
        empty_grid();
        if($("#store_from").val()==0){
          set_msg("Please select store");
        }else{
          if($("#to_cluster").val()==0 || $("#t_branch").val()==0){
            set_msg("Please select from cluster and branch");
          }else{
            press_enter=1;
            load_transfer_order();                           
          }
        }
      }else{
        alert('Searching.. Please Wait..');
      }
    }
  });

  $("#btnDelete").click(function () {
    loding();
    $.post("index.php/main/load_data/t_internal_transfer/checkdelete", {
      no: $("#id").val(),
    },
    function (r) {
     loding();
     if (r == 2) {
      set_msg("Please enter exsits ID");
      return false;    
    } else {
      var no = $("#id").val();
      loding();
      if (confirm("Are you sure delete trsnfer issue no " + no + "?")) {
        $.post("index.php/main/delete/t_internal_transfer/", {
          id: no,
          trans_no:$("#transCode").val(),
          order_no:$("#order_no").val(),
          ddate:$("#ddate").val(),
          to_cluster:$("#to_cluster").val(),
          to_bc:$("#t_branch").val(),
          store:$("#store_from").val()
        },
        function (r) {
          if(r==1)
          {
            delete_msg();
          }else{
           set_msg(r);
         }  
       }, "text")
      }
    }
    loding();
  }, "json")
  });

  $("#gen").click(function(){
    var free_fix=$("#free_fix").val();
    var post_fix=$("#pst").val();
    var start_no=parseInt($("#abc").val());
    var quantity=parseInt($("#quantity").val());

    for(x=0;x<quantity;x++){
      start_no=start_no+1;
      var code_gen=free_fix+start_no.toString()+post_fix;
      $("#srl_"+x).val(code_gen);
    }
  });

  $("#btnReject").click(function(){
    reject();
  });

  $(".qunsb").click(function(){
    set_cid($(this).attr("id"));
    check_is_batch_item(scid);  
  });



  $("#btnPrint").click(function(){
    $("#print_pdf").submit();
  });





  check_qty();

  $("#grid").tableScroll({height:355});
  $("#tgrid").tableScroll({height:355});

  $("#click").click(function(){
    var x=0;
    $(".me").each(function(){

      if($(this).val() != "" && $(this).val() != 0){
        v = true;
      }
      x++;
    });
  });

  $(".fo").focus(function(e){
    if($("#store_from").val()==0){
      set_msg("Please select a store.");
    }
  });

  $(".fo").keydown(function(e){
    set_cid($(this).attr("id"));

    if(e.keyCode==46){
      if($("#df_is_serial").val()=='1'){
        $("#all_serial_"+scid).val("");
      }

      $("#h_"+scid).val("");
      $("#0_"+scid).val("");
      $("#n_"+scid).val("");
      $("#m_"+scid).val(""); 
      $("#1_"+scid).val(""); 
      $("#c_"+scid).val(""); 
      $("#min_"+scid).val(""); 
      $("#3_"+scid).val("");
      $("#cur_"+scid).val("");
      $("#2_"+scid).val("");
      $("#2_"+scid).attr("placeholder",""); 
      $("#qtyh_"+scid).val("");
      $("#subcode_"+scid).val("");
      $("#subcode_"+scid).removeAttr("data-is_click");
      $("#2_"+scid).attr("placeholder",""); 
      $("#btn_"+scid).css("display","none");
      $("#btnb_"+scid).css("display","none"); 
      $("#t_"+scid).html("&nbsp;"); 
      set_discount();
      total();
      set_total();
    }

  });

        //load_items();
        $("#pop_search").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
          }
        });

        $(".dis, .qun, .dis_pre").blur(function(){
          set_cid($(this).attr("id"));
          set_discount();
          total();
          set_total();

        });

        $(".free_is").blur(function (){
          set_cid($(this).attr("id"));
          set_discount2();
          total();
          set_total();
        });



        $("#pop_search").gselect();


        $("#officer").autocomplete('index.php/main/load_data/t_dispatch_sum/auto_com', {
          width: 350,
          multiple: false,
          matchContains: true,
          formatItem: formatItems,
          formatResult: formatItemsResult
        });

        $("#officer").keydown(function(e){
          if(e.keyCode == 13){
            set_cus_values($(this));
          }
        });

        $("#officer").blur(function(){
          set_cus_values($(this));
        });

        $(".qun").blur(function(){
          is_sub_item(scid);
        });

        $(".fo").keydown(function(e){
          if(e.keyCode == 112){     
            if($("#to_cluster").val()=="0"){
              set_msg("Please Select To Cluster & Branch.");
            }else{
              $("#pop_search6").val();
              load_items();
              $("#serch_pop6").center();
              $("#blocker").css("display", "block");
              setTimeout("$('#pop_search6').focus()", 100);
            }
          }
          $("#pop_search6").keyup(function(e){        
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_items();
           }
         }); 
          if(e.keyCode == 46){
            $("#0_"+x).val("");
            $("#itemcode_"+x).val("");
            $("#n_"+x).val("");
            $("#m_"+x).val("");
            $("#1_"+x).val("");
            $("#c_"+x).val("");
            $("#min_"+x).val("");
            $("#max_"+x).val();
            $("#3_"+x).val("");
            $("#cur_"+x).val("");
            $("#2_"+x).val("");
          }
        });





      });

function get_sub_no(){
 var type=$("#t_type").val();
 $("#types").val(type);
 $.post("index.php/main/load_data/utility/get_max_no_in_type_transfer_echo",{
  sub_hid:$("#sub_hid").val(),
  type:type,
  table:'t_internal_transfer_sum',
  sub_no:'sub_no',
  t_code:'42'
},function(res){             
 $("#sub_no").val(res);
},"text"

);     
}

function load_vehicle(){
  $.post("index.php/main/load_data/t_internal_transfer/f1_selection_list_vehicle", {
    data_tbl:"m_vehicle_setup",
    field:"code",
    field2:"description",
    field3:"stores",
    preview1:"Vehicle Code",
    preview2:"Description",
    search : $("#pop_search2").val() 
  }, function(r){
    $("#sr2").html(r);
    settings_vehicle();            
  }, "text");
}

function load_driver(){
  $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
    filter_emp_cat:"driver",
    search : $("#pop_search11").val() 
  }, function(r){
    $("#sr11").html(r);
    settings_driver();            
  }, "text");
}

function load_helper(){
  $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
    filter_emp_cat:"helper",
    search : $("#pop_search12").val() 
  }, function(r){
    $("#sr12").html(r);
    settings_helper();            
  }, "text");
}


function load_oreders(){
  $.post("index.php/main/load_data/t_internal_transfer/f1_load_orders", {
    type: $("#types").val(),
    to_bc: $("#t_branch").val(),
    search : $("#pop_search10").val() 
  }, function(r){
    $("#sr10").html(r);
    settings_orders();            
  }, "text");
}
function reject(){
  if (confirm("Are you sure reject order no " + $("#order_no").val() + "?")) {
    $.post("index.php/main/load_data/t_internal_transfer/check_reject", {
      order_no:$("#order_no").val(),
      type:$("#types").val(),
      bc:$("#t_branch").val()
    }, function(res){ 
      if(res==1){
        $.post("index.php/main/load_data/t_internal_transfer/reject", {
          order_no:$("#order_no").val(),
          type:$("#types").val()
        }, function(r){ 
          if(r==1){
            location.href="";
          }else{
            set_msg(r);
          }
        },"json");
      }else if(res==2){
        set_msg("Order no already issued", "error");
      }else if(res==3){
        set_msg("Order no already rejected", "error");
      }

    },"json");




  }
}

function load_t_order(){
  $.post("index.php/main/load_data/t_internal_transfer/load_transfer_order", {
    order_no:$("#order_no").val(),
    type:$("#types").val(),
    get_cl:$("#to_cluster").val(),
    get_bc:$("#t_branch").val()
  }, function(res){ 
    if(res==2){
      set_msg("No records or transfer order already issued");
    }else{
      for(var x=0;x<res.length;x++){               

        $("#0_"+x).val(res[x].item_code);
        $("#itemcode_"+x).val(res[x].item_code);
        $("#n_"+x).val(res[x].description);
        $("#m_"+x).val(res[x].model);
        $("#1_"+x).val(res[x].batch_no);
        $("#c_"+x).val(res[x].purchase_price);
        $("#min_"+x).val(res[x].min_price);
        $("#max_"+x).val(res[x].max_price);
        $("#3_"+x).val(res[x].max_price);
        $("#cur_"+x).val(res[x].cur);
        $("#2_"+x).val(res[x].qty);
        $("#2_"+x).blur();
        branch_current_qty(res[x].item_code,x);
        check_is_serial_item2(res[x].item_code,x);
        check_is_batch_item2(res[x].item_code,x);  
        is_sub_item(x); 
        check_is_batch_item(x);
      }
      setTimeout('check_cur_qty()',1000);
    }
  },"json");
}

function check_cur_qty(){
  for(var t=0; t<25; t++){
    if($("#0_"+t).val()!=""){
      batch_item_wise_qty($("#0_"+t).val(),$("#1_"+t).val(),t);
    }
  }
}
function batch_item_wise_qty(item,batch,x){

  $.post("index.php/main/load_data/t_internal_transfer/load_to_cur_stock", {
    item_code:item,
    batch_no:batch,
    store_from:$("#store_from").val()
  }, function(res){ 
    if(res==2){
      set_msg("No ");
    }else{
      $("#cur_"+x).val(res[0].qty);

    }
  },"json");
}


function load_transfer_order(){
  empty_grid();
  $.post("index.php/main/load_data/t_internal_transfer/check_reject", {
    order_no:$("#order_no").val(),
    type:$("#types").val(),
    bc:$("#t_branch").val()
  }, function(res){ 
    if(res==1){
      load_t_order();
    }else if (res==0){
      set_msg("No records");
    }else if(res==2){
      set_msg("Order no already issued", "error");
      load_t_order();
      $("#btnSave").attr("disabled","disabled");
      $("#btnDelete").attr("disabled","disabled");
      $("#btnReject").attr("disabled","disabled");
      $("#order_no").attr("readonly","readonly");
    }else if(res==3){
      set_msg("Order no already rejected", "error");
      load_t_order();
      $("#btnSave").attr("disabled","disabled");
      $("#btnDelete").attr("disabled","disabled");
      $("#btnReject").attr("disabled","disabled");
      $("#order_no").attr("readonly","readonly");                
    }

  },"json");
} 
function is_sub_item(x){
  sub_items=[];
  $("#subcode_"+x).val("");
  $.post("index.php/main/load_data/utility/is_sub_items", {
    code:$("#0_"+x).val(),
    qty:$("#2_"+x).val(),
    batch:$("#1_"+x).val()
  }, function(r){
    if(r!=2){
      for(var i=0; i<r.sub.length;i++){
        add(x,r.sub[i].sub_item,r.sub[i].qty);
      }
      $("#subcode_"+x).attr("data-is_click","1");  
    }
  },"json");
}

function add(x,items,qty){
  $.post("index.php/main/load_data/utility/is_sub_items_available", {
    code:items,
    qty:qty,
    grid_qty:$("#2_"+x).val(),
    batch:$("#1_"+x).val(),
    hid:$("#hid").val(),
    store:$("#store_from").val(),
    trans_type:"42"
  }, function(res){ 
    if(res!=2){
      sub_items.push(res.sub[0].sub_item+"-"+res.sub[0].qty);
      $("#subcode_"+x).val(sub_items);         
    }else{
      set_msg("Not enough quantity in this sub item ("+items+")","error");
      $("#subcode_"+x).val("");
    }
  },"json");
}

function set_cus_values(f){
  var v = f.val();
  v = v.split("-");

  if(v.length == 2){
                //$("#vehicle_no").val(v[0]);
                f.val(v[0]);
                $("#officer_id").val(v[1]);


              }
            }

            function formatItems(row){
              return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
            }

            function formatItemsResult(row){
              return row[0]+"-"+row[1];
            }




            function save(){

              $("#qno").val($("#id").val());
    //$("#sub_qno").val($("#sub_no").val());
    //$("#sub_qno2").val($("#sub_no").val());
    $("#p_type").val($("#types").val());
    $("#dt").val($("#ddate").val());

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
        var sid=pid.split('@');

        if(pid==5){
          set_msg('Please check the serial numbers');
        }else if(sid[0]==1){
          //$("#qno").val(sid[1]);
          $("#sub_qno").val(sid[1]);
          $("#sub_qno2").val(sid[1]);
          $("#btnSave").attr("disabled",true);
          loding();
          if(confirm("Save Completed, Do You Want A print?")){
            if($("#is_prnt").val()==1){

              $("#org_print").val("1");
              $("#print_pdf").submit(); 
            }
            reload_form();
          }else{
            location.href="";
          }

        }else if(pid == 2){
          set_msg("No permission to add data.");
        }else if(pid == 3){
          set_msg("No permission to edit data.");
        }else{
          loding();
          set_msg(pid,"error");
        }

      }
    });


  }

  function reload_form(){
    setTimeout(function(){
      location.href= '';
    },50); 
  }


  function empty_grid(){
    for(var i=0; i<25; i++){
      $("#h_"+i).val("");
      $("#0_"+i).val("");
      $("#n_"+i).val("");
      $("#m_"+i).val(""); 
      $("#1_"+i).val(""); 
      $("#c_"+i).val(""); 
      $("#min_"+i).val(""); 
      $("#3_"+i).val("");
      $("#cur_"+i).val("");
      $("#bcur_"+i).val("");
      $("#2_"+i).val("");
      $("#t_"+i).val("");
      $("#2_"+scid).attr("placeholder",""); 
      $("#qtyh_"+i).val("");
      $("#subcode_"+i).val("");
      $("#subcode_"+i).removeAttr("data-is_click");

    }
    $(".quns").css("display","none");
    $(".qunsb").css("display","none");
  }

  function empty_txt_box(){
    $("#store_from").val("");
    $("#store_to").val("");
    $("#officer").val("");
    $("#ref_des").val("");
    $("#ref_no").val("");
    $("#officer_id").val("");
    $("#store_to_id").val("");
    $("#store_from_id").val("");    
  }

//   function load_data(id){
//     loding();
//       $.post("index.php/main/load_data/t_internal_transfer/checkload", {
//           id: $("#id").val(),
//           cluster: $("#to_cluster").val(),
//           branchs: $("#t_branch").val()
//       },
//       function (r) {
//        //loding();
//           if (r == 1) {
//             set_msg("Please enter exsits ID");
//             return false;    
//           } else {
//                   empty_grid();
//                   //loding();
//                   empty_txt_box();

//                   $.post("index.php/main/load_data/t_internal_transfer/get_display", {
//                       max_no: id
//                   }, function(r){
//                           if(r=="2"){
//                              set_msg("No records");
//                           }else{
//                           $("#qno").val(id); 
//                           $("#dt").val(r.sum[0].ddate);
//                           $("#hid").val(id);  
//                           $("#id").val(id);   
//                           $("#ddate").val(r.sum[0].ddate);
//                           $("#ref_no").val(r.sum[0].ref_no);
//                           $("#note").val(r.sum[0].note);
//                           $("#order_no").val(r.sum[0].order_no);
//                           $("#store_from").val(r.sum[0].store);
//                           $("#to_cluster").val(r.sum[0].t_cl);   
//                           set_select("store_from","store_hid");
//                           set_select("to_cluster","to_cluster_hid");
//                           $.post("index.php/main/load_data/t_internal_transfer/to_branch", {
//                             cluster:r.sum[0].t_cl
//                           }, function(res){ 
//                               $("#to_branch").html(res);
//                               $("#t_branch").val(r.sum[0].to_bc);
//                               set_select("t_branch","to_branch_hid");
//                           },"text");

//                           if(r.sum[0].is_cancel==1)
//                           {
//                               $("#form_").css("background-image", "url('img/cancel.png')");
//                               $("#btnDelete").attr("disabled", true);
//                               $("#btnSave").attr("disabled", true);
//                           }

//                           $("#id").attr("readonly","readonly")            

//                           for(var i=0; i<r.det.length;i++){

//                               $("#0_"+i).val(r.det[i].item_code);
//                               $("#n_"+i).val(r.det[i].description);
//                               $("#m_"+i).val(r.det[i].model);

//                               $("#1_"+i).val(r.det[i].batch_no);
//                               $("#c_"+i).val(r.det[i].purchase_price);
//                               $("#min_"+i).val(r.det[i].min_price);
//                               $("#3_"+i).val(r.det[i].max_price);
//                               $("#cur_"+i).val(r.det[i].cur);
//                               $("#2_"+i).val(r.det[i].qty);
//                               $("#2_"+i).blur();
//                               $("#itemcode_"+i).val(r.det[i].item_code);
//                               if($("#df_is_serial").val()=='1'){
//                                 $("#numofserial_"+i).val(r.det[i].qty);
//                                 $("#setserial_"+i).removeAttr("title");
//                                 $("#setserial_"+i).removeAttr("value");
//                                 $("#setserial_"+i).attr("title",1);
//                                 $("#setserial_"+i).attr("value",1); 
//                                 check_is_serial_item2(r.det[i].item_code,i);
//                               }

//                              check_is_batch_item2(r.det[i].item_code,i);  
//                              is_sub_item(i);  
//                           }
//                           if($("#df_is_serial").val()=='1'){
//                             serial_items.splice(0);
//                             if(r.serial!=2){
//                               for(var i=0;i<r.serial.length;i++){
//                                 serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);
//                               }
//                             }  
//                           serial_items.sort();
//                           }
//                           input_active();           
//                         }
//                     //loding();
//                     }, "json");
//                   }
//                  loding();
//               }, "json");
// }


function load_data(id){
  var g=[];
  empty_grid();
  loding();
  empty_txt_box();

  $.post("index.php/main/load_data/t_internal_transfer/get_display", {
    max_no: id,
    type:$("#types").val(),
    sub_no:$("#sub_no").val()
  }, function(r){
    if(r=="2"){
     set_msg("No records");
   }else{
    $("#sub_qno").val(id);
    $("#sub_qno2").val(id);
    $("#p_type").val(r.sum[0].type);  
    $("#qno").val(r.sum[0].nno); 
    $("#dt").val(r.sum[0].ddate);
    $("#hid").val(r.sum[0].nno);  
    $("#id").val(r.sum[0].nno);   
    $("#ddate").val(r.sum[0].ddate);
    $("#ref_no").val(r.sum[0].ref_no);
    $("#note").val(r.sum[0].note);
    $("#order_no").val(r.sum[0].order_no);
    $("#store_from").val(r.sum[0].store);
    $("#to_cluster").val(r.sum[0].t_cl);  
    $("#sub_no").val(r.sum[0].sub_no);
    $("#sub_hid").val(r.sum[0].sub_no);
    $("#t_type").val(r.sum[0].type);
    $("#types").val(r.sum[0].type);

    $("#driver_id").val(r.sum[0].driver);
    $("#driver_name").val(r.sum[0].d_name);
    $("#helper_id").val(r.sum[0].helper);
    $("#helper_name").val(r.sum[0].h_name);

    $("#vehicle").val(r.sum[0].vehicle);
    $("#vehicle_des").val(r.sum[0].vehicle_des);
    $("#v_store").val(r.sum[0].location_store);

    $("#mode").val(r.sum[0].trns_mode);
    $("#tr_mode").val(r.sum[0].trns_mode);

    if(r.sum[0].trns_mode!=0){
      $(".fo").css("pointer-events","auto");
      $("#order_no").css("pointer-events","none");

    }

    $("#t_type").attr("disabled", "disabled");
    set_select('v_store','location_store_hid');
    set_select("store_from","store_hid");
    set_select("to_cluster","to_cluster_hid");
    $.post("index.php/main/load_data/t_internal_transfer/to_branch", {
      cluster:r.sum[0].t_cl
    }, function(res){ 
      $("#to_branch").html(res);
      $("#t_branch").val(r.sum[0].to_bc);
      set_select("t_branch","to_branch_hid");
    },"text");

    if(r.sum[0].is_cancel==1)
    {
      $("#form_").css("background-image", "url('img/cancel.png')");
      $("#btnDelete").attr("disabled", true);
      $("#btnSave").attr("disabled", true);
    }

    $("#id").attr("readonly","readonly")            

    for(var i=0; i<r.det.length;i++){

      $("#0_"+i).val(r.det[i].item_code);
      $("#n_"+i).val(r.det[i].description);
      $("#m_"+i).val(r.det[i].model);

      $("#1_"+i).val(r.det[i].batch_no);
      $("#c_"+i).val(r.det[i].purchase_price);
      $("#min_"+i).val(r.det[i].min_price);
      $("#3_"+i).val(r.det[i].max_price);
      $("#cur_"+i).val(r.det[i].cur);
      $("#bcur_"+i).val(r.det[i].branch_stock);
      $("#2_"+i).val(r.det[i].qty);
      $("#2_"+i).blur();
      $("#itemcode_"+i).val(r.det[i].item_code);
      if($("#df_is_serial").val()=='1'){
        $("#numofserial_"+i).val(r.det[i].qty);
        check_is_serial_item2(r.det[i].item_code,i); 
        for(var a=0;a<r.serial.length;a++){
         if(r.det[i].item_code==r.serial[a].item){
          g.push(r.serial[a].serial_no);
          $("#all_serial_"+i).val(g);
        }   
      }
      g=[]; 

    }

    check_is_batch_item2(r.det[i].item_code,i);  
    is_sub_item(i);  
  }


  input_active();           
}
loding();
}, "json");
}







function validate(){

  for(var t=0; t<25; t++){
    if($("#subcode_"+t).data("is_click")==1 && $("#subcode_"+t).val()==""){
      set_msg("Not enough sub items in ("+$("#0_"+t).val()+")" ,"error");
      return false;
    }
  }
  
  var code = $("#0_0").val();
  var store=$("#store_from_id").val();
  var save_chk = $("#save_chk").val();

  if (code==""||code==null)
  {
      //set_msg("Please add item code");
      //return false;
    }
    else if(store=="")
    {
      set_msg("Please select from store");
      return false;
    }
    else if($("#officer").val()=="")
    {
      set_msg("Please enter officer");
      return false;
    }
    else if($("#vehicle").val()==""){
      set_msg("Please Select transport Vehicle");
      return false;
    }
    else if($("#driver_id").val()==""){
      set_msg("Please Select Driver");
      return false;
    }
    else if($("#helper_id").val()==""){
      set_msg("Please Select helper");
      return false;
    }
    else
    {
      return true;
    }   

  }



  function set_delete(id){
    if(confirm("Are you sure delete "+id+"?")){
      loding();
      $.post("index.php/main/delete/t_quotation_sum", {
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


  function settings_vehicle(){
    $("#item_list .cl").click(function(){
      $("#vehicle").val($(this).children().eq(0).html());
      $("#vehicle_des").val($(this).children().eq(1).html());
      $("#v_store2").val($(this).children().eq(2).html());
      $("#pop_close2").click();
    });
  }

  function settings_driver(){
    $("#item_list .cl").click(function(){
      $("#driver_id").val($(this).children().eq(0).html());
      $("#driver_name").val($(this).children().eq(1).html());
      $("#pop_close11").click();
    });
  }

  function settings_helper(){
    $("#item_list .cl").click(function(){
      $("#helper_id").val($(this).children().eq(0).html());
      $("#helper_name").val($(this).children().eq(1).html());
      $("#pop_close12").click();
    });
  }

  function settings_orders(){
    $("#item_list .cl").click(function(){
      $("#order_no").val($(this).children().eq(0).html());
      $("#pop_close10").click();
    });
  }


  function settings(){
    $("#item_list .cl").click(function(){

      var qty=$(this).children().eq(4).html();

      //if(qty<1)
      //{
      //  set_msg("Item quantity not enough");
      // return false;
      //}
      //else
      //{
        if($(this).children().eq(0).html() != "&nbsp;"){
          if(check_item_exist($(this).children().eq(0).html())){
            if($("#df_is_serial").val()=='1'){
              check_is_serial_item2($(this).children().eq(0).html(),scid);
            }
            check_is_batch_item2($(this).children().eq(0).html(),scid);

            $("#h_"+scid).val($(this).children().eq(0).html());
            $("#0_"+scid).val($(this).children().eq(0).html());
            $("#n_"+scid).val($(this).children().eq(1).html());
            $("#1_"+scid).val($(this).children().eq(2).html());
            $("#3_"+scid).val($(this).children().eq(3).html());
            $("#2_"+scid).attr("placeholder", qty);
            $("#qtyh_"+scid).val(qty);
            $("#2_"+scid).focus();
            $("#pop_close").click();
            check_is_batch_item(scid);
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
          $("#t_"+scid).val("");
          $("#1_"+scid).attr("disabled", "disabled"); 
          $("#2_"+scid).attr("disabled", "disabled");
          $("#3_"+scid).attr("disabled", "disabled");
          $("#0_"+scid).focus();
          set_total();$("#pop_close").click();
        }
      //}
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





  function set_sub_total(){
    var x = parseFloat($("#1_"+scid).val());
    var y = parseFloat($("#2_"+scid).val());
    var z;
    if(! isNaN(x) && ! isNaN(y)){
      z = x*y;
      $("#t_"+scid).val(m_round(z));
    }else{
      $("#t_"+scid).val("0.00");
    }
    
    set_total();
  }

  function set_total2(){
    var t = tt = 0; 
    $(".tf").each(function(){;
      tt = parseFloat($(this).html());
      if(isNaN(tt)){ tt = 0;}
      t += tt;
    });
    
    $("#total2").val(m_round(t));
  }

  function set_total(){
    var gross = 0;
    var discount=0;
    var net=0;
    var t=0;
    $(".tf").each(function(){
      var x = parseFloat($("#2_"+t).val());
      var y = parseFloat($("#c_"+t).val());
      var z = parseFloat($("#5_"+t).val());
      if(isNaN(x)||isNaN(y)){ x=0; y=0;}
      gross += x*y;
      if(isNaN(z)){z=0;}
      discount +=z;
      net=gross-discount;
      t++;
    });
    
    $("#total2").val(m_round(gross));
    $("#discount").val(m_round(discount));
    $("#net_amount").val( m_round(net));
  }


  function total(){
    var w = parseFloat($("#4_"+scid).val());
    var x = parseFloat($("#2_"+scid).val());
    var y = parseFloat($("#c_"+scid).val());
    var z = parseFloat($("#5_"+scid).val());
    var a;
    var b;

    if(!isNaN(x) && !isNaN(y) && !isNaN(z) && !isNaN(w)){

     a=x*y;
     b=(a*w)/100;
     a=a-z;
     $("#t_"+scid).val(m_round(a));   
   }else if(!isNaN(x) && !isNaN(y) && !isNaN(z)){

     a=x*y-z;
     $("#t_"+scid).val(m_round(a));
   }else if(!isNaN(x) && !isNaN(y)){

     a=x*y;
     $("#t_"+scid).val(m_round(a));
   }else{

     $("#t_"+scid).val("0.00");
   }


 }

 function set_discount(){
  var x = parseFloat($("#3_"+scid).val());
  var y = parseFloat($("#2_"+scid).val());
  var z = x*y;
  var d = parseFloat($("#4_"+scid).val());
  if(isNaN(d)){ d = 0; }
  d = z*(d/100);
  if(isNaN(d)){d=0;}
  $("#5_"+scid).val(m_round(d));

}

function set_discount2(){
  var x = parseFloat($("#3_"+scid).val());
  var y = parseFloat($("#2_"+scid).val());
  var z = x*y;
  var d = parseFloat($("#5_"+scid).val());
  if(isNaN(d)){ d = 0; }
  d = z-d;

  d = parseFloat($("#5_"+scid).val());
  if(isNaN(d)){ d = 0; }


  $("#t_"+scid).val(m_round(d));
  var a=d*100/z;

  if(isNaN(a)){ a = 0; }
  $("#4_"+scid).val(a);
  $("#4_"+scid).click();

}   

function check_qty(){
  $(".chk").keydown(function(e){
    if(e.keyCode==13){
     set_cid($(this).attr("id"));
     var qty = $("#"+$(this).attr("id")).val();
     var item = $("#0_"+scid).val();
     $.post("index.php/main/load_data/t_dispatch_sum/check_qty",{
      item:item,
      qty:qty
    },
    function(res){
      if(parseFloat(res.a[0].qty) < parseFloat(qty) ){
        set_msg("quntity is insufficient");
        $("#2_"+scid).focus();
      }
    },"json");
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

function select_search4(){
  $("#pop_search4").focus();
}

function check_item_exist3(id){
  var v = true;
  return v;
}

function settings3(){

  $("#batch_item_list .cl").click(function(){
    if($(this).children().eq(0).html() != "&nbsp;"){
      if(check_item_exist3($(this).children().eq(0).html())){
        $("#1_"+scid).val($(this).children().eq(0).html());
                //$("#2_"+scid).val($(this).children().eq(1).html());
                $("#qtyh_"+scid).val($(this).children().eq(1).html());
                $("#3_"+scid).val($(this).children().eq(2).html());

                $("#min_"+scid).val($(this).children().eq(3).html());
                $("#c_"+scid).val($(this).children().eq(4).html());
                $("#cur_"+scid).val($(this).children().eq(1).html());

                $("#1_"+scid).attr("readonly","readonly");
                $("#2_"+scid).focus();

                $("#pop_close3").click();
              }else{
                set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
              }
            }else{
              $("#1_"+scid).val("");
              $("#5_"+scid).val("");
              $("#3_"+scid).val("");
              discount();
              amount();

              gross_amount();
              privilege_calculation();
              all_rate_amount();
              net_amount();
              $("#pop_close3").click();
            }
          });
}


function load_items3(x){
  $.post("index.php/main/load_data/t_internal_transfer/batch_item", {
    search : x,
    stores : $("#store_from").val()
  }, function(r){
    $("#sr3").html(r);
    settings3();
  }, "text");
}

function select_search3(){
  $("#pop_search3").focus();
}

function check_is_batch_item(scid){

  var store=$("#store_from").val();
  $.post("index.php/main/load_data/t_internal_transfer/is_batch_item",{
    code:$("#0_"+scid).val(),
    store:store
  },function(res){            
   if(res==1){
    $("#serch_pop3").center();
    $("#blocker3").css("display", "block");
    setTimeout("select_search3()", 100);
    load_items3($("#0_"+scid).val());
  } else if(res=='0'){
    $("#1_"+scid).val("0");
    $("#1_"+scid).attr("readonly","readonly");
  } else {
    $("#1_"+scid).val(res.split("-")[0]);
    $("#cur_"+scid).val(res.split("-")[1]);
    $("#5_"+scid).val(res.split("-")[1]);
    $("#c_"+scid).val(res.split("-")[2]);
    $("#min_"+scid).val(res.split("-")[3]);
    $("#3_"+scid).val(res.split("-")[4]);
    $("#bqty_"+scid).val(res.split("-")[1]);
    $("#1_"+scid).attr("readonly","readonly");
  }
},'text');
}

function check_is_batch_item2(x,scid){

  var store=$("#store_from").val();
  $.post("index.php/main/load_data/t_internal_transfer/is_batch_item",{
    code:x,
    store:store
  },function(res){
    $("#btnb_"+scid).css("display","none");
    if(res==1){
      $("#btnb_"+scid).css("display","block");
    }
  },'text');
}


function load_items(){
  $.post("index.php/main/load_data/t_internal_transfer/load_transfer_items", {
    search : $("#pop_search6").val(),
  }, function(r){
    $("#sr6").html(r);
    settings1();
  }, "text");
}



function settings1(){
  $("#item_list .cl").click(function(){
    if($(this).children().eq(0).html() != "&nbsp;"){
      empty_row(scid);
      if(check_item_exist($(this).children().eq(0).html())){
        if($("#df_is_serial").val()=='1'){
          check_is_serial_item2($(this).children().eq(0).html(),scid);
        }
        check_is_batch_item2($(this).children().eq(0).html(),scid);

        $("#h_"+scid).val($(this).children().eq(0).html());
        $("#0_"+scid).val($(this).children().eq(0).html());
        $("#n_"+scid).val($(this).children().eq(1).html());
        $("#m_"+scid).val($(this).children().eq(2).html());
        $("#1_"+scid).val($(this).children().eq(3).html());
        $("#c_"+scid).val($(this).children().eq(4).html());
        $("#min_"+scid).val($(this).children().eq(5).html());
        $("#3_"+scid).val($(this).children().eq(6).html());
        $("#cur_"+scid).val($(this).children().eq(7).html());
        branch_current_qty($(this).children().eq(0).html(),scid);

        $("#2_"+scid).focus();
        $("#pop_close6").click();

      }else{
        set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
      }
    }else{
      $("#h_"+scid).val("");
      $("#0_"+scid).val("");
      $("#n_"+scid).val("");
      $("#m_"+scid).val(""); 
      $("#1_"+scid).val(""); 
      $("#c_"+scid).val(""); 
      $("#min_"+scid).val("");
      $("#3_"+scid).val(""); 
      $("#cur_"+scid).val(""); 
      $("#bcur_"+scid).val(""); 
      $("#pop_close6").click();
    }
  });


}


function empty_row(scid){
  $("#h_"+scid).val("");
  $("#0_"+scid).val("");
  $("#n_"+scid).val("");
  $("#m_"+scid).val(""); 
  $("#1_"+scid).val(""); 
  $("#c_"+scid).val(""); 
  $("#min_"+scid).val("");
  $("#3_"+scid).val(""); 
  $("#cur_"+scid).val(""); 
  $("#bcur_"+scid).val(""); 
}

function branch_current_qty(item,scid){
  $.post("index.php/main/load_data/t_internal_transfer/branch_qty", {
    branch : $("#t_branch").val(),
    cluster: $("#to_cluster").val(),
    item:item
  }, function(r){
    $("#bcur_"+scid).val(r.qty);
  }, "json");
}
