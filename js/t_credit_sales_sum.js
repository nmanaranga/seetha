  var result;
  var sub_items=[];


  $(function(){
    $("#dis_box").css("display","none");
    $("#tabs9").tabs();
    $(".qunsb").css("display","none");
    $(".quns").css("display","none");
    $(".subs").css("display","none");
    $("#btnSavee").css("display","none");
    $("#btnSave").attr("disabled","disabled");

    $("#cutomer_create").click(function(){
      window.open($("#base_url").val()+"?action=m_customer","_blank");      
    });

    setTimeout("default_customer_details();", 200);

    $("#cic").prop("checked", true); 

    $("#is_main").click(function(){
      if($("#is_main").is(':checked')){
        $(".bill_to_cus").css("display","block");
        $(".main_cus").css("display","none");
        $("#customer").css("display","none");
      }else{
        $(".main_cus").css("display","block");
        $(".bill_to_cus").css("display","none");
        $("#customer").css("display","block");
      }
    });

    $("input[type='text']").blur(function(){
      var net = parseFloat($("#net").val());
      $("#net_hid").val(net);
    });

    $("#bill_cus_id").keydown(function(e){
      if(e.keyCode == 112){
        $("#pop_search14").val($("#bill_cus_id").val());
        load_data99();
        $("#serch_pop14").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search14').focus()", 100);
      }
      $("#pop_search14").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
         load_data99();
       }
     }); 
      if(e.keyCode == 46){
        $("#bill_cus_id").val("");
        $("#bill_cuss_name").val("");
      }
    });

    $("#b_foc").click(function(){
      if($("#stores").val()!="0"){
        $("#pop_search").val();
        load_data10();
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
      }else{
        set_msg("Please select store")
      }
    });

    $(".price , .dis").blur(function(){
      set_cid($(this).attr("id"));
      check_min_price(scid);
    });

    $(".fo").dblclick(function(){
      set_cid($(this).attr("id"));
      if($(this).val()!=""){
        $.post("index.php/main/load_data/utility/get_sub_item_detail", {
          code:$(this).val(),
          store:$("#stores").val(),
          qty:$("#5_"+scid).val(),
          batch:$("#1_"+scid).val(),
          hid:$("#hid").val(),
          nno:$("#id").val(),
          table:"t_credit_sales_det"
        }, function(res){
          if(res!=0){
            $("#msg_box_inner").html(res);
            $("#msg_box").slideDown();
          }
        },"text");
      } 
    });

    $("#customer").keydown(function(e){
      if(e.keyCode == 112){
        $("#pop_search2").val($("#customer").val());
        make_delay('load_data9()');
        $("#serch_pop2").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search2').focus()", 100);
      }

      

      if(e.keyCode == 46){
        $("#customer").val("");
        $("#customer_id").val("");
        $("#address").val("");
        $("#customer_id").css("background-color","");
        $("#customer_id").addClass("hid_value main_cus");
        $("#customer_id").removeClass("input_txt");
      }
    });

    $("#pop_search2").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       make_delay('load_data9()');
     }
   }); 

    $("#sales_rep").keydown(function(e){
      if(e.keyCode == 112){
        $("#pop_search6").val($("#sales_rep").val());
        make_delay('load_data8()');
        $("#serch_pop6").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search6').focus()", 100);
      }

      $("#pop_search6").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
         make_delay('load_data8()');
       }
     }); 

      if(e.keyCode == 46){
        $("#sales_rep").val("");
        $("#sales_rep2").val("");
      }
    });


    $("#quotation").keydown(function(e){ 
      if($("#customer").val() != ""){ 
        if(e.keyCode==112){
          $("#pop_search4").val($("#quotation").val());
          make_delay('load_data2()');
          $("#serch_pop4").center();
          $("#blocker").css("display", "block");
          setTimeout("select_search4()", 100);                
        }

        $("#pop_search4").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
           make_delay('load_data2()');
         }
       });
            // $("#pop_search").keyup(function(e){

            //     load_data2();
            
            // });
          }
          else{

            set_msg("Please Select Customer","error");
          }
          
        });

    $("#sales_category").change(function(){
      $("#sales_category1").val($("#sales_category").val());
      if($("#sales_category").val()==0){
        $("#sub_no").val("0");
        set_msg("Please select sales category");
      }else{

        sales_category_max();            
      }
    }); 

    $(".price").keyup(function(){
      set_cid($(this).attr("id"));
      $("#free_price_"+scid).val($(this).val());
    });

    $(".subs").click(function(){
      set_cid($(this).attr("id"));
      check_is_sub_item(scid); 
    });

    $("#sales_rep_create").click(function(){
      window.open($("#base_url").val()+"?action=m_employee","_blank");  
    });

    $("#btnDelete").click(function(){
      set_delete();
    });

    $("#showPayments").click(function(){
      payment_opt('t_credit_sales_sum',$("#net").val());

      if($("#installment").val()=="" && $("#credit").val()==""){
        if($("#hid").val()=="0"){
          $("#credit").val($("#net").val());          
        }
      }else if($("#credit").val()==""){
        $("#installment").val($("#net").val());  
      }else if($("#installment").val()==""){
        $("#credit").val($("#net").val());
      }
      $("#save_status").val("0");
    });

    $("#payment_option").attr("checked", "checked");
    
    $("#free_fix,#pst").blur(function(){
      var get_code=$(this).val();
      $(this).val(get_code.toUpperCase());
    });

    $("#btnClearr").click(function(){
      location.reload();
    });

    $(".qunsb").click(function(){
      set_cid($(this).attr("id"));
      check_is_batch_item(scid);  
    });


    $( "#tabs" ).tabs();
    
    $("#btnExit1").click(function(){
      document.getElementById('light').style.display='none';
      document.getElementById('fade').style.display='none';  
      $("#5_"+get_id).focus();
    });
  });


  $(document).ready(function(){

  	$("#btnResett").click(function(){
      location.href="?action=t_credit_sales_sum";
    });

    $("#id,#sub_no").keyup(function(){
      this.value = this.value.replace(/\D/g,'');
    });

    $("#ref_no").keyup(function(){
      this.value = this.value.replace(/[^0-9a-zA-Z]/g,'');
    });

    $("#tgrid").tableScroll({height:200});
    $("#tgrid2").tableScroll({height:100});
    
    $(".fo").focus(function(){
      if($("#store_id").val()=="" || $("#stores").val()==0){
        set_msg("Please Select Store");
        $("#0_"+scid).val("");
        $("#stores").focus(); 
      }
    });

    
    $(".fo").keydown(function(e){  
      set_cid($(this).attr("id"));
      
      if(e.keyCode==112)
      {
        $("#pop_search").val($("#0_"+scid).val());
        
        make_delay('load_items()');
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
      }

      
    });
    $("#pop_search").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
        make_delay('load_items()');

      }


      
      if(e.keyCode==13)
      {
        $.post("index.php/main/load_data/t_credit_sales_sum/get_item", {
          code:$("#0_"+scid).val(),
          group_sale:$("#groups").val(),
          stores:$("#stores").val()
        }, function(res){
          if(res.a!=2)
          {
            $("#0_"+scid).val(res.a[0].code);
            if(check_item_exist($("#0_"+scid).val()))
            {

              if($("#df_is_serial").val()=='1')
              {
                check_is_serial_item2(res.a[0].code,scid);
              }
              check_is_batch_item2(res.a[0].code,scid);
              check_is_sub_item2(res.a[0].code,scid);

              $("#h_"+scid).val(res.a[0].code);
              $("#n_"+scid).val(res.a[0].description);
              $("#0_"+scid).val(res.a[0].code);
              $("#2_"+scid).val(res.a[0].model);
              $("#3_"+scid).val(res.a[0].max_price);
              $("#item_min_price_"+scid).val(res.a[0].min_price);
              $("#free_price_"+scid).val(res.a[0].max_price);
              $("#1_"+scid).focus();
              check_is_batch_item(scid);
            }
            else
            {
              set_msg("Item "+$("#0_"+scid).val()+" is already added.");
            }
          }else{
            set_msg($("#0_"+scid).val()+" Item not available in store","error");
            $("#0_"+scid).val("");
          }
        }, "json");

      }

      if(e.keyCode==46){
        if($("#df_is_serial").val()=='1')
        {
              //deleteSerial(scid);
              $("#all_serial_"+scid).val("");
            }
            item_free_delete(scid);
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); 
            $("#2_"+scid).val(""); 
            $("#3_"+scid).val(""); 
            $("#4_"+scid).val(""); 
            $("#5_"+scid).val("");
            $("#6_"+scid).val(""); 
            $("#7_"+scid).val(""); 
            $("#8_"+scid).val(""); 
            $("#9_"+scid).val(""); 
            $("#cost_"+scid).val("");

            $("#f_"+scid).val("");
            $("#bal_free_"+scid).val("");
            $("#bal_tot_"+scid).val("");
            $("#free_price_"+scid).val("");
            $("#issue_qty_"+scid).val("");
            $("#subcode_"+scid).val("");
            $("#bqty"+scid).val("");
            $("#item_min_price_"+scid).val("");
            $("#subcode_"+scid).removeAttr("data-is_click");
            $("#5_"+scid).attr("readonly", false);

            $("#btn_"+scid).css("display","none");
            $("#btnb_"+scid).css("display","none");
            $("#sub_"+scid).css("display","none");

            $("#n_"+scid).closest("td").attr('style', 'width:132px; background-color: #f9f9ec !important');
            $("#2_"+scid).attr('style', 'width: 100%; background-color: #f9f9ec !important');
            $("#1_"+scid).closest("td").attr('style', 'width: 71px; background-color: #f9f9ec !important');
            $("#1_"+scid).attr('style', 'margin:0;padding:0;width :56px; float: right; text-align:right; background-color: #f9f9ec !important');
            $("#3_"+scid).closest("td").attr('style', 'width: 58px; ');
            $("#6_"+scid).closest("td").attr('style', 'width: 58px; background-color: #f9f9ec !important');
            $("#8_"+scid).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');
            $("#0_"+scid).closest("tr").attr('style', 'width:100%; background-color: #ffffff !important;');
            
            // $("#n_"+scid).closest("td").attr('style', 'background-color: #f9f9ec !important');
            // $("#2_"+scid).attr('style', 'background-color: #f9f9ec !important');
            // $("#1_"+scid).attr('style', 'background-color: #f9f9ec !important');
            // $("#3_"+scid).closest("td").attr('style', 'background-color: #f9f9ec !important');
            // $("#8_"+scid).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');

            // $("#0_"+scid).closest("tr").attr('style', 'background-color: #ffffff !important;');
            

                //discount();
                dis_prec();
                amount();
                gross_amount();
                gross_amount1();
                gross_amount1();
                discount_amount();
                privilege_calculation();
                all_rate_amount();
                net_amount();
                
              }


            });


  $(".foo").focus(function(){
    set_cid($(this).attr("id"));
    $("#serch_pop7").center();
    $("#blocker2").css("display", "block");
    setTimeout("select_search7()", 100);
  });


  $(".price, .qty, .dis_pre, .foc").blur(function(){
   set_cid($(this).attr("id"));

   if($("#1_"+scid).val()!="" && $("#0_"+scid).val()!=""){
    check_item_in_grid($("#0_"+scid).val(),$("#1_"+scid).val(),scid);
  }

  var foc=parseFloat($("#4_"+scid).val());
  if(isNaN(foc)){foc=0;}

  if(foc==0){
        // discount();
        dis_prec();
        amount();
        gross_amount();
        gross_amount1();
        discount_amount();
        privilege_calculation();
        //all_rate_amount();
        net_amount();
      }else{
        free_tot();
        dis_prec();
        amount();
        gross_amount();
        gross_amount1();
        discount_amount();
        privilege_calculation();
        //all_rate_amount();
        net_amount();
      }


    });

  
  $(".qty").blur(function(){
    set_cid($(this).attr("id"));
    check_batch_qty(scid);
  });

  $(".dis").blur(function(){
    set_cid($(this).attr("id"));
    dis_prec();
    amount();
    gross_amount();
    gross_amount1();
    discount_amount();
    privilege_calculation();
        //all_rate_amount();
        net_amount();
      });

  $(".rate").blur(function(){
    set_cid($(this).attr("id"));
        //rate_amount();
        net_amount();
      });

  $(".aa").blur(function(){
    set_cid($(this).attr("id"));
    rate_pre();
    net_amount();
  });





  $("#pop_search3").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
      load_items3($("#0_"+scid).val());
    }
  });

  $("#pop_search3").gselect();
    //load_items();
    load_items2();

    $("#pop_search7").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items2();
      }
    });    

    
    $("#customer").change(function(){
      set_select('customer','customer_id');
    });
    

    $("#stores").change(function(){
      set_select('stores','store_id');
      empty_grid();
      
      $.post("index.php/main/load_data/validation/check_is_group_store", {
        store_code:$("#stores").val()
      }, function(res){

        if(res==1){
          if($("#groups").val()==0){
            set_msg("Please select group number","error");
            $("#stores").val("0");
            $("#store_id").val("");
          }
        }else{
          if($("#groups").val()!=0){
            set_msg("Please select group store");

            $("#stores").val("0");
            $("#store_id").val("");
          }
        }


      },"text");
    });

    $("#groups").change(function(){

      $("#stores").val("0");
      $("#store_id").val("");
      
        // if($(this).val()==0 && $("#stores").val()!=0){
        //   $.post("index.php/main/load_data/validation/check_is_group_store", {
        //     store_code:$("#stores").val()
        //     }, function(res){
        //     if(res==0){
        //         set_msg("Please select group store","error");
        //         $("#stores").val("0");
        //         $("#store_id").val("");
        //     }
        //   },"text");
        // }

        //  if($(this).val()==0){
        //     $("#stores").val("0");
        //     $("#store_id").val("");
        //  }
      });


/*      $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
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
          });*/
          
          $("#customer").blur(function(){
            set_cus_values($(this));
          });

/*        $("#sales_rep").autocomplete('index.php/main/load_data/m_employee/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
        });
    
        $("#sales_rep").keydown(function(e){
          if(e.keyCode == 13){
            set_cus_values2($(this));
          }
        });*/
        
        $("#sales_rep").blur(function(){
          set_cus_values2($(this));
        });

        $("#id").keydown(function(e){
          if(e.keyCode == 13){
            $(this).blur();
            load_data($(this).val());
            load_payment_option_data($(this).val(),"5");
            $("#btnSave").attr("disabled","disabled");
          }
        });

        $("#pop_search").gselect();
        $("#pop_search2").gselect();
        $("#pop_search3").gselect();
        
        $("#btnPrint").click(function(){
        	if($("#hid").val()=="0"){
           set_msg("Please load data before print");
           return false;
         }
         else
         {
           $("#print_pdf").submit();
         }
       });


        $(".fo").blur(function(){
         var id=$(this).attr("id").split("_")[1];
         if($(this).val()=="" || $(this).val()=="0"){
         }else if($(this).val()!=$("#itemcode_"+id).val()){
          if($("#df_is_serial").val()=='1')
          {
            deleteSerial(id);
          }
        }
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


        $(".qty").blur(function(){
          item_free(scid);
        });

        $(".qty, .foc").blur(function(){
          balance_item_free(scid);
          dis_prec();
          check_qty(scid);
        });

        $(".qty").blur(function(){
          is_sub_item(scid);
        });

        $("#sales_category").change(function() {
         get_group();
       });


      });


  function load_data9(){
    $.post("index.php/main/load_data/t_cash_sales_sum/customer_list", {
      search : $("#pop_search2").val() 
    }, function(r){
      $("#sr2").html(r);
      settings9();            
    }, "text");
  }

  function check_min_price(scid){
    var p = parseFloat($("#3_"+scid).val());
    var discount = parseFloat($("#7_"+scid).val());
    var price = p-discount;
    var min = parseFloat($("#item_min_price_"+scid).val());

    if(price<min){
     set_msg("Price couldn't  Be lower than ("+m_round(min)+")");
     $("#3_"+scid).focus();
   }
 }

/*function load_data9(){
    $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
        data_tbl:"m_customer",
        field:"code",
        field2:"name",
        field3:"nic",
        preview1:"Customer ID",
        preview2:"Customer Name",
        preview3:"Customer NIC",
        search : $("#pop_search2").val() 
    }, function(r){
        $("#sr2").html("");
        $("#sr2").html(r);
        settings9();            
    }, "text");
  }*/

  function load_data8(){
    $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
      filter_emp_cat:"salesman",
      search : $("#pop_search6").val() 
    }, function(r){
      $("#sr6").html(r);
      settings8();            
    }, "text");
  }


  function settings9(){
    $("#item_list .cl").click(function(){        
      $("#customer").val($(this).children().eq(0).html());
      $("#customer_id").val($(this).children().eq(1).html());
      $("#address").val($(this).children().eq(3).html());
      $("#balance").val($(this).children().eq(4).find('input').val());

      $("#privi_card").val($(this).children().eq(7).find('input').val());
     // $("#points").val($(this).children().eq(8).find('input').val());

     $("#blocker").css("display","none");
     var main_color_code=$(this).children().eq(5).find('input').val();
     get_cus_color(main_color_code);
     $("#btnSave").attr("disabled",true);

     var cr_limit=parseFloat($(this).children().eq(6).find('input').val());
     var cr_bal  =parseFloat($(this).children().eq(4).find('input').val());
     if(cr_limit > 0){
      if(cr_bal >= cr_limit){
        alert("This Customer ("+$(this).children().eq(1).html()+") Reached His Credit Limit");
      }
    }
    customer_balance($(this).children().eq(0).html());
    $("#pop_close2").click();                    
  })    
  }

  function customer_balance($cus){
    $.post("index.php/main/load_data/t_cash_sales_sum/customer_balance", {
      code : $cus
    }, function(r){
      $("#balance").val(r);
    }, "text");
  }

  function get_cus_color(code){
    $.post("index.php/main/load_data/utility/select_color",{
     color_code:code
   },function(r){
    if(r.a!=2){
      $("#customer_id").removeClass("hid_value main_cus");
      $("#customer_id").addClass("input_txt");
      $("#customer_id").css("background-color",r.a[0].color);
    }else {
     $("#customer_id").addClass("hid_value main_cus");
     $("#customer_id").css("background-color","");
   }
 },"json");
  }


  function settings8(){
    $("#item_list .cl").click(function(){        
      $("#sales_rep").val($(this).children().eq(0).html());
      $("#sales_rep2").val($(this).children().eq(1).html());
      $("#pop_close6").click();                
    })    
  }


  function sales_category_max(){
    $.post("index.php/main/load_data/utility/get_max_sales_category", {
      nno:"sub_no",
      table:"t_credit_sales_sum",
      category:$("#sales_category").val(),
      hid:$("#hid").val()
    }, function(r){
      $("#sub_no").val(r);          
    },"text");
  }


  function is_sub_item(x){
    sub_items=[];
    $("#subcode_"+x).val("");
    $.post("index.php/main/load_data/utility/is_sub_items", {
      code:$("#0_"+x).val(),
      qty:$("#5_"+x).val(),
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
      grid_qty:$("#5_"+x).val(),
      batch:$("#1_"+x).val(),
      hid:$("#hid").val(),
      trans_type:"5",
      store:$("#stores").val()
    }, function(res){ 
      if(res!=2){
        sub_items.push(res.sub[0].sub_item+"-"+res.sub[0].qty);
        $("#subcode_"+x).val(sub_items);         
      }else{
        set_msg("Not enough quantity in this sub item ("+items+")","error");
        $("#subcode_"+x).val("");
          //$("#5_"+x).val(0);
        }
      },"json");
  }


  function balance_item_free(id){
   var qty = parseInt($("#5_"+id).val());
   var foc = parseInt($("#4_"+id).val());
   var bal = parseInt($("#bal_free_"+id).val());
   var each_price = parseFloat($("#3_"+id).val());
   var price = parseFloat($("#free_price_"+id).val());
   var is_free_item = $("#f_"+id).val();

   if($("#4_"+id).val()!=""){
    bal = bal-foc;
    $("#bal_tot_"+id).val(bal+"-"+each_price*bal);
    $("#f_"+id).val("2");
  }else{
    bal = bal-qty;
    $("#bal_tot_"+id).val(bal+"-"+price*bal);
  }
}

function item_free_delete(no){
 if(isNaN(parseInt($("#4_"+no).val()))){
  var qty=parseInt($("#5_"+no).val());
}else{
  var qty=parseInt($("#5_"+no).val())-parseInt($("#4_"+no).val());
}
var item=$("#0_"+no).val();

$.post("index.php/main/load_data/t_credit_sales_sum/item_free_delete",{
  quantity:qty,
  item:item
},function(r){

  if(r!='2'){
    $("#f_"+no).val("2");
    for(var x=0; r.a.length>x;x++){
      for(var i=0; i<25;i++){ 
        if($("#0_"+i).val()==item || $("#0_"+i).val()==r.a[x].code  && $("#f_"+i).val()==1){
          console.log($("#0_"+i).val());
          $(this).val("");
          $("#h_"+i).val(0);
          $("#0_"+i).val("");
          $("#n_"+i).val("");
          $("#t_"+i).html("&nbsp;");
          $("#1_"+i).val("");
          $("#2_"+i).val("");
          $("#3_"+i).val("");
          $("#4_"+i).val("");
          $("#5_"+i).val("");
          $("#6_"+i).val("");
          $("#7_"+i).val("");
          $("#8_"+i).val("");
          $("#9_"+i).val("");
          $("#f_"+i).val("");
          $("#subcode_"+i).removeAttr("data-is_click");
          $("#5_"+i).attr("readonly", false);

          $("#h_"+no).val(0);
          $("#0_"+no).val("");
          $("#n_"+no).val("");
          $("#t_"+no).html("&nbsp;");
          $("#1_"+no).val("");
          $("#2_"+no).val("");
          $("#3_"+no).val("");
          $("#4_"+no).val("");
          $("#5_"+no).val("");
          $("#6_"+no).val("");
          $("#7_"+no).val("");
          $("#8_"+no).val("");
          $("#9_"+no).val("");
          $("#f_"+no).val("");
          $("#subcode_"+no).removeAttr("data-is_click");
          $("#5_"+no).attr("readonly", false);

          $("#n_"+i).closest("td").attr('style', 'width:132px; background-color: #f9f9ec !important');
          $("#2_"+i).attr('style', 'width: 100%; background-color: #f9f9ec !important');
          $("#1_"+i).closest("td").attr('style', 'width: 71px; background-color: #f9f9ec !important');
          $("#1_"+i).attr('style', 'margin:0;padding:0;width :56px; float: right; text-align:right; background-color: #f9f9ec !important');
          $("#3_"+i).closest("td").attr('style', 'width: 58px;');
          $("#6_"+i).closest("td").attr('style', 'width: 58px; background-color: #f9f9ec !important');
          $("#8_"+i).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');

          $("#0_"+i).closest("tr").attr('style', 'background-color: #ffffff !important;');
          
          $("#n_"+no).closest("td").attr('style', 'width:132px; background-color: #f9f9ec !important');
          $("#2_"+no).attr('style', 'width: 100%; background-color: #f9f9ec !important');
          $("#1_"+no).closest("td").attr('style', 'width: 71px; background-color: #f9f9ec !important');
          $("#1_"+no).attr('style', 'margin:0;padding:0;width :56px; float: right; text-align:right; background-color: #f9f9ec !important');
          $("#3_"+no).closest("td").attr('style', 'width: 58px;');
          $("#6_"+no).closest("td").attr('style', 'width: 58px; background-color: #f9f9ec !important');
          $("#8_"+no).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');

          $("#0_"+no).closest("tr").attr('style', 'background-color: #ffffff !important;');
          


                            // $("#n_"+i).closest("td").attr('style', 'background-color: #f9f9ec !important');
                            // $("#2_"+i).attr('style', 'background-color: #f9f9ec !important');
                            // $("#1_"+i).attr('style', 'background-color: #f9f9ec !important');
                            // $("#3_"+i).closest("td").attr('style', 'background-color: #f9f9ec !important');
                            // $("#8_"+i).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');

                            // $("#0_"+i).closest("tr").attr('style', 'background-color: #ffffff !important;');
                            
                            // $("#n_"+no).closest("td").attr('style', 'background-color: #f9f9ec !important');
                            // $("#2_"+no).attr('style', 'background-color: #f9f9ec !important');
                            // $("#1_"+no).attr('style', 'background-color: #f9f9ec !important');
                            // $("#3_"+no).closest("td").attr('style', 'background-color: #f9f9ec !important');
                            // $("#8_"+no).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');

                            // $("#0_"+no).closest("tr").attr('style', 'background-color: #ffffff !important;');
                            


                            $("#btn_"+i).css("display","none"); 
                            $("#btnb_"+i).css("display","none");
                            $("#sub_"+i).css("display","none");

                            $("#btn_"+no).css("display","none"); 
                            $("#btnb_"+no).css("display","none");
                            $("#sub_"+no).css("display","none");

                            //discount();
                            dis_prec();
                            amount();
                            gross_amount();
                            gross_amount1();
                            discount_amount();
                            privilege_calculation();
                            all_rate_amount();
                            net_amount();

                          }
                        }
                      } 
                      
                    }
                  }, "json");
}

function load_items5(x,y){
  if(isNaN(parseInt($("#4_"+y).val()))){
    var qty=parseInt($("#5_"+y).val());
  }else{
    var qty=parseInt($("#5_"+y).val())-parseInt($("#4_"+y).val());
  }
  var item=$("#0_"+y).val();
  $.post("index.php/main/load_data/t_credit_sales_sum/item_free_list",{
    quantity:qty,
    item:item,
    date:$("#date").val()
  },function(r){   
    if(r!=2){ 
      $("#sr3").html(r);
      settings6();
      $("#5_"+y).attr("readonly","readonly");
    }
  }, "text");
}

function load_data2(){

  $.post("index.php/main/load_data/utility/f1_selection_list", {

    data_tbl:"t_quotation_sum",
    field:"nno",
    field2:"cus_id",
    preview2:"Customer ID",
    add_query:"AND cus_id = " + "'" + $("#customer").val() + "'",
    search : $("#pop_search4").val() 
  }, function(r){

    $("#sr4").html(r);
    settings10();
    
  }, "text");
}



function load_data99(){
  $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
    data_tbl:"m_customer",
    field:"code",
    field2:"name",
    field3:"nic",
    field_address:"field_address",
    preview1:"Customer ID",
    preview2:"Customer Name",
    preview3:"Customer NIC",
    search : $("#pop_search14").val() 
  }, function(r){
    $("#sr14").html(r);
    settings99();            
  }, "text");
}


function settings99(){
  $("#item_list .cl").click(function(){        
    $("#bill_cus_id").val($(this).children().eq(0).html());
    $("#bill_cuss_name").val($(this).children().eq(1).html());
    $("#cus_address").val($(this).children().eq(3).html());
    $("#pop_close14").click();                
  })    
}


function settings6(){
  if(isNaN(parseInt($("#4_"+scid).val()))){
    var qty=parseInt($("#5_"+scid).val());
  }else{
    var qty=parseInt($("#5_"+scid).val())-parseInt($("#4_"+scid).val());
  }

  if($("#4_"+scid).val() != ""){
    $("#bal_free_"+scid).val($("#4_"+scid).val());
    $("#issue_qty_"+scid).val($("#4_"+scid).val());
  }

  var free_qty = "";

  $("#free_item_list .cl").click(function(){
    if($(this).children().eq(0).html() != "&nbsp;"){
      free_qty=parseInt($(this).children().eq(4).html());
      if(check_item_exist2($(this).children().eq(0).html())){

       var get=$(this).children().eq(0).html();
       var name=$(this).children().eq(1).html();
       var modal=$(this).children().eq(2).html();
       var price=$(this).children().eq(3).html();
                 // free_qty=parseInt($(this).children().eq(4).html());
                 var sign=$(this).children().eq(5).html();

                 var issue_qty = qty/free_qty;



                 for(var i=0; i<25 ;i++){
                  if($("#0_"+i).val()==get)
                  {
                    return false;
                  }
                  else if($("#0_"+i).val()=="")
                  {
                    if($("#df_is_serial").val()=='1')
                    {
                      check_is_serial_item2(get,i);
                    }
                    

                    
                    
                    $("#0_"+i).val(get);
                    $("#h_"+i).val(get);
                    $("#n_"+i).val(name);
                    $("#2_"+i).val(modal);
                    $("#3_"+i).val(price);
                    $("#free_price_"+i).val(price);
                    $("#5_"+i).val(Math.floor(issue_qty));
                    $("#issue_qty_"+i).val(Math.floor(issue_qty));
                    $("#f_"+i).val(sign);
                    $("#bal_free_"+i).val(Math.floor(issue_qty));


                    $("#n_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width:132px; background-color: rgb(224, 228, 146) !important');
                    $("#2_"+i).removeClass('g_col_fixed').attr('style', ' width: 100%; background-color: rgb(224, 228, 146) !important');
                    
                    $("#1_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 71px; background-color: rgb(224, 228, 146) !important');

                    $("#1_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; margin:0;padding:0;width :56px; float: right; text-align:right;');
                    $("#3_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
                    $("#6_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
                    $("#8_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');

                    $("#0_"+i).closest("tr").attr('style', 'background-color: rgb(224, 228, 146) !important;');
                    $("#5_"+i).focus();
                    $("#3_"+i).blur();
                    check_is_batch_item2(get,i);
                    check_is_sub_item2(get,i);
                    check_is_batch_item_free(i);
                    check_is_batch_item(i);
                    
                    break;                



                  }
                }          
                $("#11_"+scid).focus();
                all_rate_amount();
                net_amount();
                $("#pop_close2").click();  
              }else{
                var ff = qty/free_qty;
                for(var a=0; a<25 ;a++){
                  if($("#0_"+a).val()==$(this).children().eq(0).html())
                  {
                    $("#5_"+a).val(Math.floor(ff));
                  }
                }     
                set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
              }
            }else{
              $("#h_"+scid).val("");
              $("#0_"+scid).val("");
              $("#n_"+scid).val("");
              $("#1_"+scid).val(""); 
              $("#t_"+scid).val(""); 
              
              all_rate_amount();
              net_amount();
              
              $("#pop_close2").click();
            }
          });
}

function check_qty(scid){

  var ss = 1;
  var foc = $("#4_"+scid).val();

  var qty = parseInt($("#5_"+scid).val());
  var issue_qtys = parseInt($("#issue_qty_"+scid).val());
  var item = $("#0_"+scid).val();
  var focs = parseInt($("#4_"+scid).val());


  if(foc==""){
    if(qty>issue_qtys){
      set_msg("this item ("+item+") quantity should be less than "+issue_qtys,"error");
      $("#5_"+scid).val(issue_qtys);
      return false;
    }

  }else{
    if(focs>issue_qtys){
      set_msg("this item ("+item+") FOC quantity should be less than "+issue_qtys,"error");
      $("#4_"+scid).val(issue_qtys);
      return false;
    }
  }

  return true;
}









function item_free(no){

 if(isNaN(parseInt($("#4_"+no).val())))
 {
  var qty=parseInt($("#5_"+no).val());
}
else
{
  var qty=parseInt($("#5_"+no).val())-parseInt($("#4_"+no).val());
}


var item=$("#0_"+no).val();

$.post("index.php/main/load_data/t_credit_sales_sum/item_free",{
  quantity:qty,
  item:item,
  date:$("#date").val()
},function(r){
  if(r!='2')
  {
    for(i=0; i<r.a.length; i++)
    {
      if(r.a[i].code == item)
      {

        var free_qty=parseInt(r.a[i].qty)
        var issue_qty = qty/free_qty;

        $("#5_"+no).val(Math.floor(issue_qty)+qty);
        $("#4_"+no).val(Math.floor(issue_qty));
      }
    }


    $("#serch_pop3").center();
    $("#blocker3").css("display", "block");
    setTimeout("select_search3()", 100);
    load_items5($("#0_"+scid).val(),no);
  }

}, "json");
}







function check_is_batch_item_free(scid){
  var store=$("#stores").val();
  $.post("index.php/main/load_data/t_credit_sales_sum/is_batch_item",{
    code:$("#0_"+scid).val(),
    store:store
  },function(res){

   if(res==1){

    $("#serch_pop3").center();
    $("#blocker3").css("display", "block");
    setTimeout("select_search3()", 100);
    load_items3($("#0_"+scid).val());
  }else if(res=='0'){

    $("#1_"+scid).val("0");
    $("#1_"+scid).attr("readonly","readonly");
  }else{

    $("#1_"+scid).val(res.split("-")[0]);
           // $("#5_"+scid).val(res.split("-")[1]);
           $("#bqty_"+scid).val(res.split("-")[1]);
           $("#1_"+scid).attr("readonly","readonly");
         }
       },'text');
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
      load_items4($("#0_"+scid).val(),$("#1_"+scid).val());
    }
  },'text');
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


function check_is_batch_item(scid){
  var store=$("#stores").val();
  $.post("index.php/main/load_data/t_credit_sales_sum/is_batch_item",{
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
         // $("#5_"+scid).val(res.split("-")[1]);
         $("#bqty_"+scid).val(res.split("-")[1]);
         $("#1_"+scid).attr("readonly","readonly");
       }
     },'text');
}

function check_is_batch_item2(x,scid){
        // set_cid($(this).attr("id"));
        var store=$("#stores").val();
        $.post("index.php/main/load_data/t_credit_sales_sum/is_batch_item",{
          code:x,
          store:store
        },function(res){
          $("#btnb_"+scid).css("display","none");
          if(res==1){
            $("#btnb_"+scid).css("display","block");
          }
        },'text');
      }


      function select_search3(){
        $("#pop_search3").focus();
      }


      function load_items3(x){
        $.post("index.php/main/load_data/t_credit_sales_sum/batch_item", {
          search : x,
          stores : $("#stores").val()
        }, function(r){
          $("#sr3").html(r);
          settings3();
        }, "text");
      }

      function load_items4(x,batch){
        $.post("index.php/main/load_data/utility/sub_item", {
          search : x,
          batch:batch
        }, function(r){
          $("#sr3").html(r); 
        }, "text");
      }



      function settings3(){
        $("#batch_item_list .cl").click(function(){
          if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist3($(this).children().eq(0).html())){
              $("#1_"+scid).val($(this).children().eq(0).html());
                //$("#5_"+scid).val($(this).children().eq(1).html());
                $("#bqty_"+scid).val($(this).children().eq(1).html());
                $("#3_"+scid).val($(this).children().eq(2).html());
                $("#free_price_"+scid).val($(this).children().eq(2).html());

                $("#item_min_price_"+scid).val($(this).children().eq(3).html());
                $("#cost_"+scid).val($(this).children().eq(4).html());
                
                $("#1_"+scid).attr("readonly","readonly");

                $("#5_"+scid).focus();
                
                //discount();
                dis_prec();
                amount();
                gross_amount();
                gross_amount1();
                discount_amount();
                all_rate_amount();
                net_amount();
                $("#pop_close3").click();
              }else{
                set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
              }
            }else{



              $("#1_"+scid).val("");
              $("#5_"+scid).val("");
              $("#3_"+scid).val("");
                //discount();
                dis_prec();
                amount();
                gross_amount();
                gross_amount1();
                discount_amount();
                privilege_calculation();
                all_rate_amount();
                net_amount();
                $("#pop_close3").click();
              }
            });
      }

      function settings10(){
        $("#item_list .cl").click(function(){


          $("#quotation").val($(this).children().eq(0).html());
          $("#pop_close4").click();
          
        })

        
      }



      function check_item_exist3(id){
        var v = true;
        return v;
      }

      function set_cus_values2(f){
        var v = f.val();
        v = v.split("-");
        if(v.length == 2){
          f.val(v[0]);
          $("#sales_rep2").val(v[1]);
          
        }
      }


      function set_cus_values(f){
        var v = f.val();
        v = v.split("-");
        if(v.length == 2){
          f.val(v[0]);
          $("#customer_id").val(v[1]);
          var cus=$("#customer").val();
          $.post("index.php/main/load_data/m_customer/load",
          {
            code:cus,
          },function(rs){
           $("#address").val(rs.data.address1+", "+rs.data.address2+", "+rs.data.address3); 
           $("#balance").val(rs.acc); 
           input_active();
         },"json");
          

          $.post("index.php/main/load_data/t_credit_sales_sum/load_crdt_period",
          {
            code:cus,
          },function(rs){
           $("#credit_period").val(rs); 
         },"json");
        }
      }


      function save(){
        $("#btnSave").attr("disabled","disabled");
        for(var i=0; i<25; i++){
          if($("#f_"+i).val()==1 && $("#0_"+i).val()!=""){
            is_sub_item(i);
          }
        }
        var a =0;
        var net=0; 
        $(".tt").each(function(){    
          var d= parseFloat($("#tt_"+a).val());
          var f= $("#hh_"+a).val();
          if(f==1){
            if(isNaN(d)){d=0;}
            net=net+d;
          }else{
            if(isNaN(d)){d=0;}
            net=net-d; 
          }
          a++;
        });

        $("#additional_amount").val(net);
        
        if($("#df_is_serial").val()=='1')
        {
          serial_items.sort();
          $("#srls").attr("title",serial_items);
          $("#srls").val(serial_items);    
        }

        $('#form_').attr('action',$('#form_id').val()+"t_credit_sales_sum");

        var frm = $('#form_');
        $("#is_duplicate").val("1");
        $("#sales_type").val($("#type").val());
        $("#qno").val($("#id").val());
        $("#cus_id").val($("#customer").val());
        $("#salesp_id").val($("#sales_rep").val());
        $("#dt").val($("#date").val());

    // var confirmation=0;
    // for(x=0;x<25;x++){
    //     if($("#0_"+x).val()!=""){
    //         if($("#3_"+x).val()<$("#item_min_price_"+x).val()){
    //           confirmation=1;
    //         }
    //     }
    // }

    // var xyz=0;
    // if(confirmation==1){
    //     var r = confirm("For Approval");
    //     if(r == true) {
    //        xyz = 1;
    //        $("#approve_status").val("0");
    //     } else {
    //        xyz = 2;
    //        $("#approve_status").val("1");
    //     } 
    // }

    // if(xyz==2){
    //     return false;
    // }



    loding();
    $.ajax({
    	type: frm.attr('method'),
    	url: frm.attr('action'),
    	data: frm.serialize(),
    	success: function (pid){

        var sid=pid.split('@');
        if(sid[0] ==0){
          set_msg("Transaction is not completed");
                  //location.href="";
                }else if(sid[0]== 2){
                  set_msg("No permission to add data.");
                }else if(sid[0] == 3){
                  set_msg("No permission to edit data.");
                }else if(sid[0]==1){
                  loding();
                  $("#btnSave").attr("disabled",true);
                  $("#showPayments").attr("disabled",true);
                  //$("#btnSavee").css("display","inline");
                  save_status=0;
                  $("#save_status").val("0");

                  if(confirm("Save Completed, Do You Want A print?")){
                    if($("#is_prnt").val()==1){
                      $("#qno").val(sid[1]);
                      $("#print_pdf").submit();
                    }
                    reload_form();
                  }else{
                    location.href="";
                  }
                }else{
                  loding();
                  set_msg(pid,"error");
                  $("#btnSave").attr("disabled",false);

                }
       //loding();         
     }
   });

 // }
}


function reload_form(){
  setTimeout(function(){
    location.href = '';
  },50); 
}


function check_code(){
  loding();
  var code = $("#code").val();
  $.post("index.php/main/load_data/t_credit_sales_sum/check_code", {
    code : code
  }, function(res){
    if(res == 1){
      if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
        set_edit(code);
      }else{
        $("#code").val('');
        $("#code").attr("readonly", false);
      }
    }
    loding();
  }, "text");
}

function validate(){

 if($("#sales_category").val()=="0"){
   set_msg("Please Select Category","error");
   $("#sales_category").focus();
   $("#btnSave").attr("disabled",false);
   return false;
 }


 for(var t=0; t<25; t++){
  if($("#subcode_"+t).data("is_click")==1 && $("#subcode_"+t).val()==""){
    set_msg("Not enough sub items in ("+$("#0_"+t).val()+")" ,"error");
    $("#btnSave").attr("disabled",false);
    return false;
  }

  if($("#0_"+t).val()!="" && $("#3_"+t).val()>0 && $("#5_"+t).val()==""){
    set_msg("please enter item quantity");
    $("#btnSave").attr("disabled",false);
    return false;
  }
}
var v = false;
$("input[type='hidden']").each(function(){
  if($(this).val() != "" && $(this).val() != 0){
    v = true;
  }
});

for(var f=0; f<25; f++){
  return check_qty(f);
}

for(var i=0; i<25; i++){
  if($("#sub_"+i).data("is_click")==1 && ($("#subcode_").val()!=0 || $("#subcode_").val()!="")){
    set_msg("Please check sub items in ("+$("#0_"+i).val()+")" ,"error");
    $("#btnSave").attr("disabled",false);
    g=false;
    break;
  } 
}

if($("#id").val() == ""){
  set_msg("Please Enter No");
  $("#id").focus();
  $("#btnSave").attr("disabled",false);
  return false;
}
else if($("#customer_id").val()=="" || $("#customer_id").attr("title")==$("#customer_id").val()){
 set_msg("Please Enter Customer");
 $("#customer_id").focus();
 $("#btnSave").attr("disabled",false);
 return false;
}

else if($("#type").val()==0){
 set_msg("Please Select Type");
 $("#type").focus();
 $("#btnSave").attr("disabled",false);
 return false;
}


else if($("#date").val()==""){
  set_msg("Please Select Date");
  $("#date").focus();
  $("#btnSave").attr("disabled",false);
  return false;
}

else if(v == false){
  set_msg("Please use minimum one item.");
  $("#btnSave").attr("disabled",false);
  return false;
}


else if($("#store_id").val()=="" || $("#stores").val()==0){
  set_msg("Please Select Store");
  $("#stores").focus();
  $("#btnSave").attr("disabled",false);
  return false;
}

else if($("#sales_rep2").val()=="" || $("#sales_rep").val()==0){
  set_msg("Please Enter Sales Rep");
  $("#sales_rep").focus();
  $("#btnSave").attr("disabled",false);
  return false;
}

        // else if($("#net").val()=="" || $("#net").val()==0){

        //     return false;
        // }
        else if($("#sales_category").val()==0){
          set_msg("Please Select Category");
          $("#sales_category").focus();
          $("#btnSave").attr("disabled",false);
          return false;
        }else if($("#load_opt").val()==0 && $("#payment_option").is(':checked')){ 
         if($("#type").val()==4){
          payment_opt('t_credit_sales_sum',$("#net").val());
          $("#btnSave").attr("disabled",false);
          return false; 
        }else if($("#type").val()==5){
          payment_opt('t_credit_sales_sum',$("#net").val());
          $("#btnSave").attr("disabled",false);
          return false;
        } 

        
      }else{
        return true;
      }  

    }


    
    function set_delete(){
      var id = $("#hid").val();
      if(id != 0){
        if(confirm("Are you sure to delete this credit sale ["+$("#hid").val()+"]? ")){
          $.post("index.php/main/delete/t_credit_sales_sum", {
            trans_no:id,
          },function(r){
            if(r != 1){
              set_msg(r);
            }else{
              delete_msg();
              $("#btnReset").click();
            }
          }, "text");
        }
      }else{
        set_msg("Please load record");
      }
    }


    
    function set_edit(code){
      loding();
      $.post("index.php/main/get_data/t_credit_sales_sum", {
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
        
        loding(); input_active();
      }, "json");
    }



    function select_search(){
      $("#pop_search").focus();
      
    }


    function select_search2(){
      $("#pop_search2").focus();
      
    }

    function load_items(){
     $.post("index.php/main/load_data/t_credit_sales_sum/item_list_all", {
      search : $("#pop_search").val(),
      stores : $("#stores").val(),
      group_sale:$("#groups").val()
    }, function(r){
      $("#sr").html(r);
      settings();
    }, "text");
   }

   function load_items2(){
     $.post("index.php/main/load_data/r_additional_items/item_list_all", {
      search : $("#pop_search7").val(),
      stores : false
    }, function(r){
      $("#sr7").html(r);
      settings2();
    }, "text");
   }

   function empty_row(scid){
    if($("#df_is_serial").val()=='1')
    {
      $("#all_serial_"+scid).val("");
    }
    item_free_delete(scid);
    $("#h_"+scid).val("");
    $("#0_"+scid).val("");
    $("#n_"+scid).val("");
    $("#1_"+scid).val(""); 
    $("#2_"+scid).val(""); 
    $("#3_"+scid).val(""); 
    $("#4_"+scid).val(""); 
    $("#5_"+scid).val("");
    $("#6_"+scid).val(""); 
    $("#7_"+scid).val(""); 
    $("#8_"+scid).val(""); 
    $("#9_"+scid).val(""); 
    $("#cost_"+scid).val("");

    $("#f_"+scid).val("");
    $("#bal_free_"+scid).val("");
    $("#bal_tot_"+scid).val("");
    $("#free_price_"+scid).val("");
    $("#issue_qty_"+scid).val("");
    $("#subcode_"+scid).val("");
    $("#bqty"+scid).val("");
    $("#item_min_price_"+scid).val("");
    $("#subcode_"+scid).removeAttr("data-is_click");
    $("#5_"+scid).attr("readonly", false);

    $("#btn_"+scid).css("display","none");
    $("#btnb_"+scid).css("display","none");
    $("#sub_"+scid).css("display","none");

    dis_prec();
    amount();
    gross_amount();
    gross_amount1();
    gross_amount1();
    discount_amount();
    privilege_calculation();
    all_rate_amount();
    net_amount();
  }

  function settings(){
    $("#item_list .cl").click(function(){
      empty_row(scid);
      if($(this).children().eq(0).html() != "&nbsp;"){
            //if(check_item_exist($(this).children().eq(0).html())){
              if($("#df_is_serial").val()=='1')
              {
                check_is_serial_item2($(this).children().eq(0).html(),scid);
              }
              check_is_batch_item2($(this).children().eq(0).html(),scid);
              check_is_sub_item2($(this).children().eq(0).html(),scid);

              $("#h_"+scid).val($(this).children().eq(0).html());
              $("#0_"+scid).val($(this).children().eq(0).html());
              $("#n_"+scid).val($(this).children().eq(1).html());
              $("#2_"+scid).val($(this).children().eq(2).html()); 
              $("#3_"+scid).val($(this).children().eq(3).html());
              $("#free_price_"+scid).val($(this).children().eq(3).html());
              $("#item_min_price_"+scid).val($(this).children().eq(4).html());
              $("#cost_"+scid).val($(this).children().eq(5).html());
              $("#9_"+scid).val($(this).children().eq(6).html());
              $("#1_"+scid).focus();
              $("#pop_close").click();
              check_is_batch_item(scid);
            //}else{
            //    set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
            //}
          }else{
            $("#h_"+scid).val("");
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val(""); 
            $("#2_"+scid).val(""); 
            $("#3_"+scid).val("");
            $("#4_"+scid).val("");  
            $("#5_"+scid).val("");
            $("#6_"+scid).val(""); 
            $("#7_"+scid).val("");
            $("#8_"+scid).val("");  
            $("#9_"+scid).val("");
            
                //discount();
                dis_prec();
                amount();
                
                gross_amount();
                gross_amount1();
                discount_amount();
                privilege_calculation();
                all_rate_amount();
                net_amount();

                $("#pop_close").click();
              }
            });
  }

  function settings2(){

    $("#item_list2 .cl").click(function(){
      if($(this).children().eq(0).html() != "&nbsp;"){
        if(check_item_exist2($(this).children().eq(0).html())){

          $("#hh_"+scid).val($(this).children().eq(3).html());
          $("#00_"+scid).val($(this).children().eq(0).html());
          $("#nn_"+scid).val($(this).children().eq(1).html());
          $("#11_"+scid).val($(this).children().eq(2).html());
          $("#hhh_"+scid).val($(this).children().eq(0).html());
          $("#11_"+scid).focus();
          all_rate_amount();
          net_amount();
          $("#pop_close7").click();  
        }else{
          set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
        }
      }else{
        $("#hh_"+scid).val("");
        $("#00_"+scid).val("");
        $("#nn_"+scid).val("");
        $("#11_"+scid).val(""); 
        $("#tt_"+scid).val(""); 
        $("#hhh_"+scid).val("");
        all_rate_amount();
        net_amount();
        
        $("#pop_close2").click();
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



  function check_item_exist2(id){
    var v = true;
    $(".foo").each(function(e){
      if($("#hhh_"+e).val() == id){
        v = false;
      }
    });    
    return v;
  }



  function load_data(id){
    var g=[];
    empty_grid();
    loding();
    $.post("index.php/main/get_data/t_credit_sales_sum/", {
      id: id
    }, function(r){
      var fre =parseFloat(0);
      if(r=="2"){
       set_msg("No records");
     }else{

      $("#load_opt").val("");    
      $("#hid_cash").val(r.sum[0].pay_cash);
      $("#hid_cheque_issue").val(r.sum[0].pay_issue_chq);
      $("#hid_credit_card").val(r.sum[0].pay_ccard);
      $("#hid_credit_note").val(r.sum[0].pay_cnote);
      $("#hid_debit_note").val(r.sum[0].previlliage_card_no);
      $("#hid_bank_debit").val(r.sum[0].pay_bank_debit);
      $("#hid_discount").val(r.sum[0].previlliage_card_no);
      $("#hid_advance").val(r.sum[0].pay_advance);
      $("#hid_gv").val(r.sum[0].pay_gift_voucher);
      $("#hid_credit").val(r.sum[0].pay_credit);
      $("#hid_pc").val(r.sum[0].previlliage_card_no);
      $("#hid_pc_type").val(r.sum[0].previlliage_card_no);  
      $("#hid_priv_card").val(r.sum[0].pay_privi_card);
      $("#credit_period").val(r.sum[0].credit_period);

      $("#hid_ins_period_by_days").val(r.sum[0].ins_period_by_days);
      $("#hid_ins_down_payment").val(r.sum[0].ins_down_payment);
      $("#hid_ins_rate_per_month").val(r.sum[0].ins_rate_per_month);
      $("#hid_num_of_installment").val(r.sum[0].num_of_installment);
      
      $("#ttl_amount").val(r.sum[0].net_amount);
      $("#period").val(r.sum[0].ins_period_by_days);
      $("#down_payment").val(r.sum[0].ins_down_payment);
      $("#rate_per_month").val(r.sum[0].ins_rate_per_month);
      $("#num_of_installment").val(r.sum[0].num_of_installment);

      $("#bill_cuss_name").val(r.sum[0].cus_name);
      $("#cus_address").val(r.sum[0].cus_address);
      $("#bill_cus_id").val(r.sum[0].cus_code);

      $("#do_no").val(r.sum[0].do_no);
      $("#rcpt_no").val(r.sum[0].receipt_no);
      
      if(r.sum[0].is_multi_payment==1){
        $("#payment_option").attr("checked", "checked");
        $("#payment_option").css("display","none");
      }else{
        $("#payment_option").removeAttr("checked");
      }

      $("#load_opt").val("");    
      $("#cash").val(r.sum[0].pay_cash);
      $("#cheque_issue").val(r.sum[0].pay_issue_chq);
      $("#cheque_recieve").val(r.sum[0].pay_receive_chq);
      $("#credit_card").val(r.sum[0].pay_ccard);
      $("#credit_note").val(r.sum[0].pay_cnote);
      $("#debit_note").val(r.sum[0].pay_dnote);
      $("#bank_debit").val(r.sum[0].pay_bank_debit);
      $("#discount").val(r.sum[0].pay_discount);
      $("#advance").val(r.sum[0].pay_advance);
      $("#gv").val(r.sum[0].pay_gift_voucher);
      $("#credit").val(r.sum[0].pay_credit);
      $("#pc").val(r.sum[0].pay_privi_card);  
      $("#installment").val(r.sum[0].pay_installment); 
      $("#hid").val(id);   
      $("#qno").val(id); 
      $("#customer").val(r.sum[0].cus_id);
      $("#cus_id").val(r.sum[0].cus_id);
      $("#balance").val(r.balance);
      $("#customer_id").val(r.sum[0].name);
      $("#address").val(r.sum[0].address1+", "+r.sum[0].address2+", "+r.sum[0].address3);
      $("#date").val(r.sum[0].ddate); 
      $("#dt").val(r.sum[0].ddate);
      $("#type").val(r.sum[0].type);
      $("#sales_type").val(r.sum[0].type);
      $("#sales_category").val(r.sum[0].category);
      $("#sales_category1").val(r.sum[0].category);
      $("#sub_no").val(r.sum[0].sub_no);
      $("#groups").val(r.sum[0].group_no);
      $("#stores").val(r.sum[0].store);
      $("#quotation").val(r.sum[0].quotation);
      set_select("stores","store_id");
      $("#ref_no").val(r.sum[0].ref_no);
      $("#memo").val(r.sum[0].memo);
      $("#sales_rep").val(r.sum[0].rep);
      $("#salesp_id").val(r.sum[0].rep);
      $("#sales_rep2").val(r.sum[0].rep_name);
      $("#gross").val(r.sum[0].gross_amount);
      $("#gross1").val(r.sum[0].gross_amount);
      $("#net").val(r.sum[0].net_amount);
      $("#is_foc").val(r.sum[0].is_bulk_foc);
      $("#additional_add").val(r.sum[0].additional_add);
      $("#additional_deduct").val(r.sum[0].additional_deduct);

      $("#privi_card").val(r.sum[0].previlliage_card_no);
      $("#points").val(r.sum[0].previlliage_point_add);


      $("#sales_category").prop("disabled", true);

      $("#credit").val(r.sum[0].pay_credit);

      if(r.sum[0].crn_no!=0){
        $("#crn_no").val(r.sum[0].crn_no);
        $("#crn_no_hid").val(r.sum[0].crn_no);
      }else{
        $("#crn_no").val(r.crn);
        $("#crn_no_hid").val(0);
      }

      $("#amount8_0").val(r.sum[0].pay_privillege_card);
      $("#pc").val(r.sum[0].pay_privillege_card);
      $("#id").attr("readonly","readonly");
      
      var total_discount=0;
      var gross_amount=parseFloat(r.sum[0].gross_amount); 
      var t_dis=0;      

      for(var i=0; i<r.det.length;i++){
        $("#h_"+i).val(r.det[i].code);
        $("#0_"+i).val(r.det[i].code);
        $("#itemcode_"+i).val(r.det[i].code);
        if($("#df_is_serial").val()=='1')
        {
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
      
      $("#n_"+i).val(r.det[i].item_des);
      $("#1_"+i).val(r.det[i].batch_no);
      $("#2_"+i).val(r.det[i].model);
      $("#3_"+i).val(r.det[i].price);
      $("#4_"+i).val(r.det[i].foc);
      $("#5_"+i).val(r.det[i].qty);
      $("#6_"+i).val(r.det[i].discountp);
      $("#7_"+i).val(r.det[i].discount);
      $("#8_"+i).val(r.det[i].amount);
      $("#9_"+i).val(r.det[i].warranty);
      $("#cost_"+i).val(r.det[i].cost);
      $("#item_min_price_"+i).val(r.det[i].min_price);

      $("#f_"+i).val(r.det[i].is_free);
      
      $("#free_price_"+i).val(r.det[i].price);

      if(r.det[i].is_free!=0){
      }else{
        t_dis+=(r.det[i].price-r.det[i].min_price) * r.det[i].qty;
      }
      
      if(r.det[i].is_free=="1")
      {
        if(r.det[i].foc==""){
          $("#bal_free_"+i).val(parseFloat(r.det[i].free_balance)+parseFloat(r.det[i].qty));
          $("#issue_qty_"+i).val(parseFloat(r.det[i].free_balance)+parseFloat(r.det[i].qty));
        }else{
         $("#bal_free_"+i).val(parseFloat(r.det[i].free_balance)+parseFloat(r.det[i].foc));
         $("#issue_qty_"+i).val(parseFloat(r.det[i].free_balance)+parseFloat(r.det[i].foc));
       }
     }
     


     
     
     if(r.det[i].is_free=='1'){
       fre +=parseFloat(r.det[i].amount);

     }

     if(r.det[i].is_free=='1')
     {

      $("#n_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width:132px; background-color: rgb(224, 228, 146) !important');
      $("#2_"+i).removeClass('g_col_fixed').attr('style', ' width: 100%; background-color: rgb(224, 228, 146) !important');
      $("#1_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 71px; background-color: rgb(224, 228, 146) !important');
      $("#1_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; margin:0;padding:0;width :56px; float: right; text-align:right;');
      $("#3_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
      $("#6_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
      $("#8_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');
      $("#0_"+i).closest("tr").attr('style', 'background-color: rgb(224, 228, 146) !important;');
      
                      // $("#n_"+i).closest("td").removeClass('g_col_fixed');
                      // $("#2_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important');
                      // $("#1_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important');
                      // $("#3_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important');
                      // $("#8_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');
                      // $("#0_"+i).closest("tr").attr('style', 'background-color: rgb(224, 228, 146) !important;');
                      
                    }

                    var bal_tot=parseFloat(r.det[i].price)*r.det[i].free_balance;
                    $("#bal_tot_"+i).val(r.det[i].free_balance+"-"+bal_tot);

                    check_is_batch_item2(r.det[i].code,i); 
                    check_is_sub_item2(r.det[i].code,i);   
                    total_discount=total_discount+parseFloat(r.det[i].discount);  
                    is_sub_item(i);      
                  }
                  $("#dis_box").css("display","block");
                  $("#dis_val").text(m_round(t_dis));
                  result=r.add.length;
                  for(var i=0; i<r.add.length;i++){
                    $("#hhh_"+i).val(r.add[i].sales_type);
                    $("#00_"+i).val(r.add[i].sales_type);
                    $("#nn_"+i).val(r.add[i].description);
                    $("#11_"+i).val(r.add[i].rate_p);
                    $("#tt_"+i).val(r.add[i].amount);
                    get_sales_type(i);
                  }



                  if(r.sum[0].is_cancel==1){
                    $("#btnDelete").attr("disabled", "disabled");
                    $("#btnSave").attr("disabled", "disabled");
                    $("#mframe").css("background-image", "url('img/cancel.png')");
                  }

                  $("#total_discount").val(m_round(total_discount));
                  $("#free_tot").val(m_round(fre));
                  $("#total_amount").val(m_round(gross_amount-fre));
                  discount_amount();
                }  
                loding();
              }, "json");
}


function get_sales_type(i){
  $.post("index.php/main/load_data/r_additional_items/get_type",{
   id:$("#00_"+i).val()
 },function(res){      
  $("#hh_"+i).val(res);
},"text");

}



function empty_grid(){
  for(var i=0; i<25; i++){
    $("#h_"+i).val(0);
    $("#0_"+i).val("");
    $("#n_"+i).val("");
    $("#t_"+i).html("&nbsp;");
    $("#1_"+i).val("");
    $("#2_"+i).val("");
    $("#3_"+i).val("");
    $("#4_"+i).val("");
    $("#5_"+i).val("");
    $("#6_"+i).val("");
    $("#7_"+i).val("");
    $("#8_"+i).val("");
    $("#9_"+i).val("");
    $("#cost_"+i).val("");
    $("#f_"+i).val("");

    $("#5_"+i).attr("readonly", false);
    $("#bal_free_"+i).val("");
    $("#bal_tot_"+i).val("");
    $("#free_price_"+i).val("");
    $("#issue_qty_"+i).val("");
    $("#subcode_"+i).val("");
    $("#bqty"+i).val("");
    $("#subcode_"+i).removeAttr("data-is_click");
    $("#item_min_price_"+i).val("");
    $("#btn_"+i).css("display","none"); 
    $("#btnb_"+i).css("display","none");
    $("#sub_"+i).css("display","none");

    $("#n_"+i).closest("td").attr('style', 'width:132px; background-color: #f9f9ec !important');
    $("#2_"+i).attr('style', 'width: 100%; background-color: #f9f9ec !important');
    $("#1_"+i).closest("td").attr('style', 'width: 71px; background-color: #f9f9ec !important');
    $("#1_"+i).attr('style', 'margin:0;padding:0;width :56px; float: right; text-align:right; background-color: #f9f9ec !important');
    $("#3_"+i).closest("td").attr('style', 'width: 58px;');
    $("#6_"+i).closest("td").attr('style', 'width: 58px; background-color: #f9f9ec !important');
    $("#8_"+i).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');

    $("#0_"+i).closest("tr").attr('style', 'background-color: #ffffff !important;');
    
    
  }


  for(var i=0; i<25; i++){
    $("#hh_"+i).val(0);
    $("#hhh_"+i).val(0);
    $("#00_"+i).val("");
    $("#nn_"+i).val("");
    $("#tt_"+i).val("");
    $("#11_"+i).val("");
  }
  $(".quns").css("display","none");
  $(".qunsb").css("display","none");
}


function discount(){
  var qty=parseFloat($("#5_"+scid).val());
  var price=parseFloat($("#3_"+scid).val());
  var dis_pre=parseFloat($("#6_"+scid).val());
  var discount="";
  if(isNaN(qty)){qty=0;}
  if(isNaN(price)){price=0;}
  if(isNaN(dis_pre)){dis_pre=0;}


  if(!isNaN(qty)&& !isNaN(price) && !isNaN(dis_pre)){
    discount=(qty*price*dis_pre)/100;


    if(discount!=0){
      $("#7_"+scid).val(m_round(discount));
    }else{
      $("#7_"+scid).val("");
    }
  }
  
}

function dis_prec(){
  var qty=parseFloat($("#5_"+scid).val());
  var price=parseFloat($("#3_"+scid).val());
  var discount=parseFloat($("#7_"+scid).val());
  var dis_pre=0;

  if(isNaN(qty)){qty=0;}
  if(isNaN(price)){price=0;}
  if(isNaN(discount)){discount=0;}


  if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
   // dis_pre=(discount*100)/(qty*price);
   dis_pre=(discount*100)/price;

   if(isNaN(dis_pre) || !isFinite(dis_pre)){       
    $("#6_"+scid).val("");
  }else{
    $("#6_"+scid).val(m_round(dis_pre));
  }
}
}

function free_tot(){
  $(".foc").each(function(e){
    if($("#4_"+e).val()!=""){
      var free_qty=$("#4_"+e).val();
      var price=$("#3_"+e).val();

      var free_tot=(free_qty*price);
      var free_tot1= $("#free_tot").val();
      $("#free_tot2").val(free_tot);

    }
  });
}

function amount(){

  var all_foc=0;
  $(".tot_foc").each(function(e){
    var f=parseFloat($("#tot_foc_"+e).val());
    if(!isNaN(f)){
      all_foc=all_foc+parseFloat(f);
    }
  });



  $("#all_foc_amount").val(m_round(all_foc));
  var qty=parseFloat($("#5_"+scid).val());
  var price=parseFloat($("#3_"+scid).val());
  var foc=parseFloat($("#4_"+scid).val());
  var amount="";

  if(isNaN(qty)){qty=0;}
  if(isNaN(price)){price=0;}
  if(isNaN(foc)){foc=0;}


    // if(!isNaN(foc) && !isNaN(price)){
    //     if(foc!=0){
    //      $("#7_"+scid).val(m_round(foc*price));     
    //     }
    
    // }
    var total_dis=0;
    var total_foc=m_round(price*foc);
    $("#tot_foc_"+scid).val(m_round(total_foc));
    var dis_pre=0;
    var total_dis=0;
    var discount=parseFloat($("#7_"+scid).val());
    if(isNaN(discount)){discount=0;}

    if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount) && !isNaN(foc)){
        // amount=(qty+foc)*price;
        amount=(qty)*price;
        total_dis=(qty)*discount;
        amount=amount-total_dis;
        dis_pre=(discount*100)/price;

        if(isNaN(dis_pre) || !isFinite(dis_pre)){
          $("#6_"+scid).val("");
        }else{
          $("#6_"+scid).val(m_round(dis_pre));
        }

        $("#tot_dis_"+scid).val(m_round(total_dis));
        
        $("#8_"+scid).val(m_round(amount)); 

      }else if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
        amount=(qty*price)-discount;
        
        if(amount!=0){
         $("#8_"+scid).val(m_round(amount)); 
       }else{
         $("#8_"+scid).val(""); 
       }
       
     }else if(!isNaN(qty)&& !isNaN(price)){
      amount=(qty*price);
      
      if(amount!=0){
       $("#8_"+scid).val(m_round(amount)); 
     }else{
       $("#8_"+scid).val(""); 
     }
   }
 }

 function gross_amount(){
  var gross=loop=free2=0;
  var free=parseFloat(0);

  $(".amount").each(function(){
    var gs=parseFloat($("#8_"+loop).val());
    if(!isNaN(gs)){    
      gross=gross+gs;
    }  
    if($("#f_"+loop).val()==1){
      free+=parseFloat($("#8_"+loop).val())
    }  
    loop++;
    var free2=$("#free_tot2").val();
  });
  $("#gross").val(m_round(gross));
  $("#free_tot").val(m_round(free+free2));
  $("#free_tot").focus();
}

function gross_amount1(){
  var gross=loop=0;
  var free=parseFloat(0);

  $(".amount").each(function(){
    var gs=parseFloat($("#5_"+loop).val()*$("#3_"+loop).val());
    if(!isNaN(gs)){    
      gross=gross+gs;
    }  
    if($("#f_"+loop).val()==1){
      free+=parseFloat($("#5_"+loop).val()*$("#3_"+loop).val())
    }  
    loop++;
  });
  $("#gross1").val(m_round(gross));
  $("#free_tot").focus();
}

function discount_amount(){
  var dis=loop=0;
  $(".amount").each(function(){
    var gs=parseFloat($("#7_"+loop).val())*parseInt($("#5_"+loop).val());
    if(!isNaN(gs)){    
      dis=dis+gs;
    }    
    loop++;
  });
  
  $("#dis_amount").val(m_round(dis));
  $("#total_discount").val(m_round(dis));

}


function rate_amount(){
  var rate_pre=parseFloat($("#11_"+scid).val());
  var gross_amount=parseFloat($("#gross").val());
  var rate_amount="";

  if(!isNaN(rate_pre)&& !isNaN(gross_amount)){
    rate_amount=(gross_amount*rate_pre)/100;
    $("#tt_"+scid).val(m_round(rate_amount));
  }
}


function rate_pre(){
  var gross_amount=parseFloat($("#gross").val());
  var rate=parseFloat($("#tt_"+scid).val());
  var rate_amount_pre="";



  if(!isNaN(rate)&& !isNaN(gross_amount)){
    rate_amount_pre=(rate*100)/gross_amount;
    $("#11_"+scid).val(m_round(rate_amount_pre));
  }

}



function all_rate_amount(){
  var gross_amount=parseFloat($("#gross").val());  
  var additional=loop=0;
  

  $(".rate").each(function(){
    var rate=parseFloat($("#11_"+loop).val());
    var rate_amount=0;
    if(!isNaN(rate) && !isNaN(rate_amount) ){ 
      rate_amount=(gross_amount*rate)/100;
      $("#tt_"+loop).val(m_round(rate_amount));
    }    
    loop++;
  });

  
}

function net_amount(){
  var gross_amount=parseFloat($("#gross").val());
  var free_amount=parseFloat($("#free_tot").val());
  var free_tot2=parseFloat($("#free_tot2").val());
  var free=parseFloat(0);
  var net_amount=additional=loop=add_plus=add_deduct=0;
  $(".foo").each(function(){
    var add=parseFloat($("#tt_"+loop).val());
    var f= $("#hh_"+loop).val();

    if(!isNaN(add)){
      if(f==1){
        additional=additional+add;
        add_plus+=add;
      }else{
        additional=additional-add;  
        add_deduct+=add;
      }
    }    
    loop++;
  });

  if(isNaN(free_tot2)){
    free_tot2='0.00';
  }

  if(!isNaN(additional)&& !isNaN(gross_amount)){
    net_amount=gross_amount+additional;
    $("#net").val(m_round(net_amount-free_amount-free_tot2));
  }else{
    $("#net").val(net_amount-free_amount-free_tot2);
  }

  var discount=0;
  
  $(".tot_discount").each(function(e){
    if(!isNaN(parseFloat($("#tot_dis_"+e).val()))){
      discount=discount+parseFloat($("#tot_dis_"+e).val());
    }
    if($("#f_"+e).val()==1){
      free+=parseFloat($("#8_"+e).val())
    }
  });
    //alert(free);  
    $("#free_tot").val(m_round(free));
    $("#total_discount").val(m_round(discount));
    $("#total_amount").val(m_round(gross_amount+discount-free-free_tot2));
    $("#additional_add").val(m_round(add_plus));
    $("#additional_deduct").val(m_round(add_deduct));

    
  }


  function check_batch_qty(scid){
    $.post("index.php/main/load_data/t_cash_sales_sum/get_batch_qty",{
      store:$("#stores").val(),
      batch_no:$("#1_"+scid).val(),
      code:$("#0_"+scid).val(),
      hid:$("#hid").val(),
      group:$("#groups").val()
    },function(res){

      if(res.a[0].qty<0){
        res.a[0].qty=0;
      }

      if(res.a[0].ret_qty>0){  
        if(parseFloat(res.a[0].qty-res.a[0].ret_qty) < parseFloat($("#5_"+scid).val())){
          $("#4_"+scid).val("");
          $("#5_"+scid).val("");
          set_msg("This sale have a not approved PRN, There for not enough quantity in this batch","error");
              //alert("This Sale Have a Not Approved PRN, '"+res.a[0].ret_qty+"' Qty");
            }
          }else{
            if(parseFloat(res.a[0].qty) < parseFloat($("#5_"+scid).val())){
              $("#4_"+scid).val("");
              $("#5_"+scid).val("");
              set_msg("There is not enough quantity in this batch","error");
            }
          }

        },"json");
  }



  function select_search4(){
    $("#pop_search4").focus();
  }

  function get_group(){
    $.post("index.php/main/load_data/r_groups/select_by_category", {
      category_id : $("#sales_category").val()
    }, function(r){
     $("#groups").html(r);
   }, "text");

  }

  function load_data10(){
    $.post("index.php/main/load_data/t_cash_sales_sum/load_b_foc", {
      date:$("#date").val(),
      search : $("#pop_search").val() 
    }, function(r){
      $("#sr").html(r);
      settings11();            
    }, "text");
  }

  function settings11(){
    $("#item_list .cl").click(function(){        

      load_foc_items($(this).children().eq(0).html(),$("#date").val()); 
      $("#is_foc").val($(this).children().eq(0).html());      
      $("#pop_close").click();                
    })    
  }


  function load_foc_items(code,date){
    loding();
    empty_grid();
    $.post("index.php/main/load_data/t_cash_sales_sum/load_foc_items", {
      code:code,
      date:date
    }, function(r){ 
      loding();          
      for(var i=0; i<r.a.length;i++){
        $("#h_"+i).val(r.a[i].code);
        $("#0_"+i).val(r.a[i].code);
        $("#itemcode_"+i).val(r.a[i].code);
        if($("#df_is_serial").val()=='1')
        {
          check_is_serial_item2(r.a[i].code,i); 
        }
        $("#n_"+i).val(r.a[i].description);
        $("#2_"+i).val(r.a[i].model);
        $("#cost_"+i).val(r.a[i].cost);
        $("#5_"+i).val(r.a[i].qty);
        $("#item_min_price_"+i).val(r.a[i].min_price);
        $("#free_price_"+i).val(r.a[i].price);
        $("#free_price_"+i).val(r.a[i].price);
        $("#3_"+i).val(r.a[i].price);
        $("#free_price_"+i).val(r.a[i].price);

        check_is_batch_item2(r.a[i].code,i);
        check_is_sub_item2(r.a[i].code,i); 
        is_sub_item(i);  
        check_is_batch_item(i);

        if(r.a[i].is_free==1){
          $("#f_"+i).val("1");
          $("#n_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width:132px; background-color: rgb(224, 228, 146) !important');
          $("#2_"+i).removeClass('g_col_fixed').attr('style', ' width: 100%; background-color: rgb(224, 228, 146) !important');
          $("#1_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 71px; background-color: rgb(224, 228, 146) !important');
          $("#1_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; margin:0;padding:0;width :56px; float: right; text-align:right;');
          $("#3_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
          $("#6_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
          $("#8_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');
          $("#0_"+i).closest("tr").attr('style', 'background-color: rgb(224, 228, 146) !important;');
          
        }
        $("#5_"+i).blur();
        
      }

    }, "json");

  }

  function check_item_in_grid(item,batch,id){
    $(".qty").each(function(e){
      if($("#0_"+e).val()==item && $("#1_"+e).val()==batch && e!=id){
        set_msg("Item ("+item+") in same batch ("+batch+") already added");
        $("#h_"+scid).val("");
        $("#0_"+scid).val("");
        $("#n_"+scid).val("");
        $("#1_"+scid).val(""); 
        $("#2_"+scid).val(""); 
        $("#3_"+scid).val(""); 
        $("#4_"+scid).val(""); 
        $("#5_"+scid).val("");
        $("#6_"+scid).val(""); 
        $("#7_"+scid).val(""); 
        $("#8_"+scid).val(""); 
        $("#9_"+scid).val("");
        $("#f_"+scid).val("");
        $("#cost_"+scid).val("");
        $("#bal_free_"+scid).val("");
        $("#bal_tot_"+scid).val("");
        $("#free_price_"+scid).val("");
        $("#issue_qty_"+scid).val("");
        $("#subcode_"+scid).val("");
        $("#bqty"+scid).val("");
        $("#item_min_price_"+scid).val("");
        $("#subcode_"+scid).removeAttr("data-is_click");
        $("#btn_"+scid).css("display","none"); 
        $("#btnb_"+scid).css("display","none");
      }
    });
  }

  function default_customer_details(){
   $.post("index.php/main/load_data/t_cash_sales_sum/load_default_customer",{
    cluster : $("#cl").val(),
    branch : $("#bc").val()
  }, function(r){
    if(r!=2)
    {
      $("#stores").val(r[0].def_sales_store);
      set_select('stores','store_id');
      $("#sales_category").val(r[0].def_sales_category);
      $("#sales_category1").val(r[0].def_sales_category);
    }       
  }, "json");     
 }