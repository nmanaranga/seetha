
var sub_items=[];

$(document).ready(function(){


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
        // if($("#t_type").val()=='main_store'){
        //     view_main_store();
        // }else{
        //     $("#cluster").val("");
        // }
    });

  $("#request_no").keydown(function(e){
    if(e.keyCode==112){
       $("#pop_search").val($("#request_no").val());
        load_items();
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
    }
  });

  $("#to_cluster").change(function(){
    $.post("index.php/main/load_data/g_internel_transfer_receive/to_branch", {
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
  });

  $("#load_btn").click(function(){
    if($("#store_from").val()==0 || $("#request_no").val()==""){
      set_msg("Please select transfer issue number and store","error");
    }else{
      empty_grid();
      check_transfer_issue();      
    }
  });

  $("#sub_no").keydown(function(e){
    if(e.keyCode==13){
      
      load_data($(this).val());
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
          empty_grid();
          if($("#store_from").val()==0){
            set_msg("Please select store");
          }else{
            if($("#to_cluster").val()==0 || $("#t_branch").val()==0){
              set_msg("Please select from cluster and branch");
            }else{
              load_transfer_order();                           
            }
        }
      }
    });

    $("#btnDelete").click(function () {
        loding();
        $.post("index.php/main/load_data/g_internel_transfer_receive/checkdelete", {
            no: $("#id").val(),
            type:$("#types").val(),
            hid_nno:$("#hid_nno2").val()
        },
        function (r) {
         loding();
            if (r == 2) {
                set_msg("Please enter exsits transfer receive no");
                return false;    
            } else {
                var no = $("#id").val();
                loding();
                if (confirm("Are you sure delete transfer receive no " + no + "?")) {
                    $.post("index.php/main/delete/g_internel_transfer_receive/", {
                        id: $("#hid_nno").val(),
                        trans_no:$("#transCode").val(),
                        order_no:$("#order_no").val(),
                        date:$("#ddate").val(),
                        hid_cl:$("#hid_cl").val(),
                        hid_bc:$("#hid_bc").val(),
                        hid_store:$("#hid_store").val(),
                        hid_nno:$("#hid_nno2").val()
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
            
             
           
            
});

        //load_items();
        $("#pop_search").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
              load_items();
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

       
  
        });

  function load_items(){
    $.post("index.php/main/load_data/g_internel_transfer_receive/selection_list", {
        search : $("#pop_search").val(),
        type:$("#t_type").val()     
     }, function(r){
        $("#sr").html(r);
        settingss();
        
    }, "text");
  }

  function reject(){
    if (confirm("Are you sure reject order no " + $("#order_no").val() + "?")) {
      $.post("index.php/main/load_data/g_internel_transfer_receive/check_reject", {
            order_no:$("#order_no").val()
            }, function(res){ 
              if(res==1){
                      $.post("index.php/main/load_data/g_internel_transfer_receive/reject", {
                      order_no:$("#order_no").val()
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
    $.post("index.php/main/load_data/g_internel_transfer_receive/load_transfer_order", {
          order_no:$("#order_no").val()
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
                check_is_serial_item2(res[x].item_code,x);      
              
              }
            }
        },"json");
  }


   function load_transfer_order(){
        $.post("index.php/main/load_data/g_internel_transfer_receive/load_transfer_order", {
          order_no:$("#order_no").val()
          }, function(res){ 
            if(res==2){
              set_msg("No records or transfer order already issued");
            }else{
              for(var x=0;x<res.length;x++){          

                $("#0_"+x).val(res[x].item_code);
                $("#itemcode_"+x).val(res[x].item_code);
                $("#n_"+x).val(res[x].description);
                $("#c_"+x).val(res[x].cost);
                $("#max_"+x).val(res[x].price);
                $("#3_"+x).val(res[x].price);
                $("#cur_"+x).val(res[x].cur);
                $("#2_"+x).val(res[x].qty);
                $("#2_"+x).blur();
                check_is_serial_item2(res[x].item_code,x);
                
              }
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
    $("#dt").val($("#ddate").val());
    $("#req_n").val($("#request_no").val());
    $("#org_print").val(2);
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
            if(pid==1){
              $("#btnSave").attr("disabled",true);
              loding();
              if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                    $("#print_pdf").submit();
                }
                reload_form();
              }else{
                location.href="";
              }
      
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

  function load_data(id){
    var g=[];
    empty_grid();
    loding();
    empty_txt_box();
    $.post("index.php/main/load_data/g_internel_transfer_receive/get_display", {
        max_no: id,
        type:$("#types").val()
    }, function(r){
            if(r=="2"){
               set_msg("No records");
            }else{
            $("#hid").val(id);  
            $("#id").val(id); 
            $("#qno").val(id);  
            $("#dt").val(r.sum[0].receive_date);
            $("#issue_b").val(r.sum[0].issue_to_bc_name); 
            $("#req_n").val(r.sum[0].issue_no); 
            $("#st").val(r.sum[0].receive_store);
            $("#issue_cl").val(r.sum[0].issue_cl_name);

            $("#ddate").val(r.sum[0].receive_date);
            $("#ref_no").val(r.sum[0].receive_ref_no);
            $("#request_no").val(r.sum[0].issue_no);
            $("#request_hid").val(r.sum[0].issue_to_bc_name);

            $("#cluster").val(r.sum[0].issue_cl);
            $("#cluster_hid").val(r.sum[0].issue_cl_name);
            $("#issue_note").val(r.sum[0].issue_note);
            $("#vehicle").val(r.sum[0].vehicle);
            $("#v_des").val(r.sum[0].v_des);
            $("#v_store").val(r.sum[0].location_store);
            $("#location_des").val(r.sum[0].location_des);
            
            $("#hid_cl").val(r.sum[0].issue_cl);
            $("#hid_bc").val(r.sum[0].issue_bc);
            $("#hid_nno").val(r.sum[0].issue_no);
            $("#hid_nno2").val(r.sum[0].receive_no);
            $("#hid_store").val(r.sum[0].issue_store);           
            $("#store_from").val(r.sum[0].receive_store);
            set_select("store_from","store_hid");
            

            //$("#sub_no").val(r.sum[0].receive_sub_no);
            $("#sub_hid").val(r.sum[0].receive_sub_no);
            $("#t_type").val(r.sum[0].receive_type);
            $("#types").val(r.sum[0].receive_type);
            $("#t_type").attr("disabled", "disabled");

            if(r.sum[0].is_cancel==1)
            {
                $("#form_").css("background-image", "url('img/cancel.png')");
                $("#btnDelete").attr("disabled", true);
                $("#btnSave").attr("disabled", true);
            }
         
            $("#id").attr("readonly","readonly")            
           
            $("#btnSaveee").css('display','inline-block');
            $("#btnSave").css("display","none");
            $("#btnSaveee").attr('disabled','disabled');

            for(var i=0; i<r.det.length;i++){
               
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                var tot = parseFloat(r.det[i].item_cost)*parseInt(r.det[i].qty);
                $("#c_"+i).val(r.det[i].item_cost);
                $("#3_"+i).val(r.det[i].price);
                $("#t_"+i).val(tot);
                $("#cur_"+i).val(r.det[i].cur);
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

               //check_is_batch_item2(r.det[i].item_code,i);  
            
            }
            if($("#df_is_serial").val()=='1'){
              serial_items.splice(0);
              if(r.serial!=2){
                for(var i=0;i<r.serial.length;i++){
                  serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);
                }
              }  
            serial_items.sort();
            }
            input_active();           
    }
loding();
}, "json");
}







function validate(){

  // for(var t=0; t<25; t++){
  //   if($("#subcode_"+t).data("is_click")==1 && $("#subcode_"+t).val()==""){
  //     set_msg("Not enough sub items in ("+$("#0_"+t).val()+")" ,"error");
  //     return false;
  //   }
  // }
  
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
               
            }else{
                set_msg("Item deleting fail.");
            }
            loding();
        }, "text");
    }
}



function select_search(){
    $("#pop_search").focus();
}

// function load_items(){
//     $.post("index.php/main/load_data/t_damage_sum/item_list_all", {
//         search : $("#pop_search").val(),
//         stores : $("#store_from").val()
//     }, function(r){
//         $("#sr").html(r);
//         settings();
        
//     }, "text");
// }

function settings(){
    $("#item_list .cl").click(function(){
      alert();
      var qty=$(this).children().eq(4).html();
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){
              if($("#df_is_serial").val()=='1'){
                check_is_serial_item2($(this).children().eq(0).html(),scid);
             
              }
              
                 
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

function settingss(){
    $("#item_list .cl").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
          $("#request_no").val($(this).children().eq(0).html());
          $("#request_hid").val($(this).children().eq(1).html());
          $("#cluster").val($(this).children().eq(2).html());
          $("#cluster_hid").val($(this).children().eq(3).html());
          $("#issue_note").val($(this).children().eq(4).html());
          $("#hid_cl").val($(this).children().eq(5).html());
          $("#hid_bc").val($(this).children().eq(6).html());
          $("#hid_nno").val($(this).children().eq(7).html());
          $("#hid_store").val($(this).children().eq(8).html());

          $("#vehicle").val($(this).children().eq(9).html());
          $("#v_des").val($(this).children().eq(10).html());
          $("#v_store").val($(this).children().eq(11).html());
          $("#location_des").val($(this).children().eq(12).html());
          $("#max_nno").val($(this).children().eq(13).html());
          $("#pop_close").click();
          
        }
    });
}

function check_transfer_issue(){
  empty_grid();
 
    $.post("index.php/main/load_data/g_internel_transfer_receive/check_issue_transfer", {
      issue_no:$("#request_no").val(),
      cl:$("#hid_cl").val(),
      bc:$("#hid_bc").val(),
      type:$("#types").val()
      }, function(res){ 
        if(res==1){
          load_issue_transfer();
        }else{
          set_msg("Transfer not available");         
        }
    },"text");
}

function load_issue_transfer(){
  empty_grid();
    $.post("index.php/main/load_data/g_internel_transfer_receive/load_t_issue", {
          issue_no:$("#request_no").val(),
          cl:$("#hid_cl").val(),
          bc:$("#hid_bc").val(),
          type:$("#types").val()
          }, function(res){ 
            if(res==2){
              set_msg("No records or transfer order already issued");
            }else{
              for(var x=0;x<res.length;x++){
                $("#0_"+x).val(res[x].item_code);
                $("#itemcode_"+x).val(res[x].item_code);
                $("#n_"+x).val(res[x].description);
                //$("#1_"+x).val(5);
                $("#c_"+x).val(res[x].cost);
                $("#max_"+x).val(res[x].price);
                $("#3_"+x).val(res[x].price);
                $("#cur_"+x).val(res[x].cur);
                $("#2_"+x).val(res[x].qty);
                $("#2_"+x).blur();
                check_is_serial_item2(res[x].item_code,x);
               
              }
            }
        },"json");
}


function load_items3(x){
    $.post("index.php/main/load_data/g_internel_transfer_receive/batch_item", {
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
        $.post("index.php/main/load_data/g_internel_transfer_receive/is_batch_item",{
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
                $("#5_"+scid).val(res.split("-")[1]);
                $("#bqty_"+scid).val(res.split("-")[1]);
                $("#1_"+scid).attr("readonly","readonly");
           }
        },'text');
}

function check_is_batch_item2(x,scid){

        var store=$("#store_from").val();
        $.post("index.php/main/load_data/g_internel_transfer_receive/is_batch_item",{
            code:x,
            store:store
         },function(res){
            $("#btnb_"+scid).css("display","none");
           if(res==1){
            $("#btnb_"+scid).css("display","block");
            }
        },'text');
}

function get_sub_no(){

     var type=$("#t_type").val();
     $("#types").val(type);

        $.post("index.php/main/load_data/utility/get_max_no_in_type_transfer_echo",{
            sub_hid:$("#sub_hid").val(),
            type:type,
            table:'g_t_internal_transfer_sum',
            sub_no:'sub_no',
            t_code:'64'
        },function(res){             
           $("#sub_no").val(res);
        },"text"

        );     
}

