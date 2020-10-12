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

    $(".fo").blur(function(){
     var id=$(this).attr("id").split("_")[1];
     if($(this).val()=="" || $(this).val()=="0"){
     }else if($(this).val()!=$("#itemcode_"+id).val()){
      if($("#df_is_serial").val()=='1'){
        deleteSerial(id);
      }
     }
   });

    $("#store_to,#store_from").change(function(){
        empty_grid();
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



    $("#btnDelete").click(function () {

        loding();
        $.post("index.php/main/load_data/t_damage_sum/checkdelete", {
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
                if (confirm("Are you sure delete " + no + "?")) {

                    $.post("index.php/main/load_data/t_damage_sum/delete", {
                        id: no,
                    },
                    function (r) {
                        if(r==1)
                        {
                           loding();
                           delete_msg();
                        }
                        else if(r==2)
                        {
                            set_msg("Not enough quatity to cancel");
                        }
                        else if(r==3)
                        {
                            set_msg("Serial not available");
                        }
                        else
                        {
                           loding();
                           set_msg(r);
                        }
                       
                    }, "text")
                }
            }
           
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

$(".qunsb").click(function(){

    set_cid($(this).attr("id"));
    check_is_batch_item(scid);
  
});



          $("#id").keydown(function(e){
            if(e.keyCode == 13){
                $("#save_chk").attr({title:"1",value:"1"});
                $("#pdf_id").val($(this).val());
                $(this).blur();
                $("#id").attr("readonly","readonly")  
                load_data($(this).val());
            }
        });
    $("#store_to").change(function(){
       set_select("store_to","store_to_id");
       
    });

    $("#store_from").change(function(){
       set_select("store_from","store_from_id");
       empty_grid();
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
            if(e.keyCode==112){
                
                 $("#pop_search").val($("#0_"+scid).val());
                 load_items();
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
                $("#4_"+scid).val(""); 
                $("#5_"+scid).val("");
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
            

            if(e.keyCode==13){
            $.post("index.php/main/load_data/t_damage_sum/get_item", {
                code:$("#0_"+scid).val(),
                stores:$("#store_from").val()
            }, function(res){
                if(res.a!=2){
                    $("#0_"+scid).val(res.a[0].item);

                        if(check_item_exist($("#0_"+scid).val())){

                            if($("#df_is_serial").val()=='1'){
                              check_is_serial_item2(res.a[0].item,scid);
                            }
                            check_is_batch_item2(res.a[0].item,scid);

                            $("#h_"+scid).val(res.a[0].item);
                            $("#n_"+scid).val(res.a[0].description);
                            $("#0_"+scid).val(res.a[0].item);
                            $("#1_"+scid).val(res.a[0].batch_no);
                            $("#3_"+scid).val(res.a[0].cost);
                            $("#qtyh_"+scid).val(res.a[0].qty);
                            $("#2_"+scid).attr("placeholder",res.a[0].qty);
                            $("#2_"+scid).focus();
         
                            check_is_batch_item(scid);
                          
                        }else{
                            set_msg("Item "+$("#0_"+scid).val()+" is already added.");
                        }
                }else{
                  set_msg($("#0_"+scid).val()+" Item not available in stock", "error");
                  $("#0_"+scid).val("");
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

        // $("#btnDelete").click(function(){
        //     var id=$("#id").val();
        //         if(id==""){
        //             set_msg('Please enter no');
        //         }else{      
        //         var id=$("#id").val();
        //          $.post("index.php/main/load_data/t_quotation_sum/check_code",{
        //             id:id,
        //          },function(r){
                   
        //             if(r==1){
        //                 set_delete(id);
        //             }else{
        //                set_msg("There is no data "); 
        //             }
        //          });      
        //         }
        //     });

        });

    
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
          trans_type:"14"
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
                loding();
                set_msg(pid,"error");
            }
            
        }
    });

        
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

    $.post("index.php/main/load_data/t_damage_sum/get_display", {
        max_no: id
    }, function(r){
            if(r=="2"){
               set_msg("No records");
            }else{
            $("#hid").val(id);  
            $("#qno").val(id);   

            $("#store_from").val(r.sum[0].store_from);
            $("#store_to").val(r.sum[0].store_to);
            set_select("store_to","store_to_id");
            set_select("store_from","store_from_id");
             
    
            $("#officer").val(r.sum[0].officer);
            $("#officer_id").val(r.sum[0].name);
            //set_select("officer", "officer_id"); 
            
            $("#ddate").val(r.sum[0].ddate);
            $("#ref_no").val(r.sum[0].ref_no);
            $("#ref_des").val(r.sum[0].memo);
           
           
            $("#net_amount").val(r.sum[0].net_amount);
            if(r.sum[0].is_cancel==1)
            {
                $("#form_").css("background-image", "url('img/cancel.png')");
                $("#btnDelete").attr("disabled", true);
                $("#btnSave").attr("disabled", true);
            }
         
            // $("#id").attr("readonly","readonly")            
           
            for(var i=0; i<r.det.length;i++){
               
                $("#0_"+i).val(r.det[i].code);
                $("#n_"+i).val(r.det[i].description);
                $("#1_"+i).val(r.det[i].batch_no);
                $("#2_"+i).val(r.det[i].qty);
                $("#3_"+i).val(r.det[i].cost);
                $("#t_"+i).val(r.det[i].amount);
                $("#itemcode_"+i).val(r.det[i].code);
                if($("#df_is_serial").val()=='1'){
                  $("#numofserial_"+i).val(r.det[i].qty);
                  check_is_serial_item2(r.det[i].code,i);
                  for(var a=0;a<r.serial.length;a++){
                    if(r.det[i].code==r.serial[a].item){
                      g.push(r.serial[a].serial_no);
                      $("#all_serial_"+i).val(g);
                    }   
                  }
                  g=[];  
                }

               check_is_batch_item2(r.det[i].code,i);  
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
      set_msg("Please add item code");
      return false;
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

function load_items(){
    $.post("index.php/main/load_data/t_damage_sum/item_list_all", {
        search : $("#pop_search").val(),
        stores : $("#store_from").val()
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
  $("#4_"+scid).val(""); 
  $("#5_"+scid).val("");
  $("#qtyh_"+scid).val("");
  $("#subcode_"+scid).val("");
  $("#subcode_"+scid).removeAttr("data-is_click");
  $("#2_"+scid).attr("placeholder",""); 
  $("#btn_"+scid).css("display","none");
  $("#btnb_"+scid).css("display","none"); 
  $("#t_"+scid).val(""); 
  set_discount();
  total();
  set_total();
}

function settings(){
    $("#item_list .cl").click(function(){
      empty_row(scid);

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
        var y = parseFloat($("#3_"+t).val());
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
    var y = parseFloat($("#3_"+scid).val());
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
                $("#2_"+scid).val($(this).children().eq(1).html());
                $("#qtyh_"+scid).val($(this).children().eq(1).html());
                $("#3_"+scid).val($(this).children().eq(2).html());
                $("#1_"+scid).attr("readonly","readonly");
                $("#5_"+scid).focus();
                          
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
    $.post("index.php/main/load_data/t_damage_sum/batch_item", {
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
        $.post("index.php/main/load_data/t_damage_sum/is_batch_item",{
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
        $.post("index.php/main/load_data/t_damage_sum/is_batch_item",{
            code:x,
            store:store
         },function(res){
            $("#btnb_"+scid).css("display","none");
           if(res==1){
            $("#btnb_"+scid).css("display","block");
            }
        },'text');
}

