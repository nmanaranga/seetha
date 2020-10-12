var sub_items=[];
    var result;

    $(function(){

    $(".quns").css("display", "none");
    $(".qunsb").css("display","none");
    $(".subs").css("display","none");
    $("#payment_option").attr("checked", "checked");
    $("#btnSave").attr("disabled","disabled");

    $("#cutomer_create").click(function(){
      window.open($("#base_url").val()+"?action=m_customer","_blank");      
    });   

    $("#sales_rep_create").click(function(){
      window.open($("#base_url").val()+"?action=m_employee","_blank");  
    });

    $("#slide_arrow").click(function(){
        $("#det_box").slideToggle(); 
    });  

    $("#stores").change(function(){
        // $.post("index.php/main/load_data/t_cash_sales_sum/check_is_group_store", {
        //         code:$(this).val(),
        //         store:$("#stores").val()
        //     }, function(res){
        //         if(res!=0){
        //             $("#msg_box_inner").html(res);
        //             $("#msg_box").slideDown();
        //         }
        // },"text"); 
    });


    $(".subs").click(function(){
      set_cid($(this).attr("id"));
      check_is_sub_item(scid); 
    });

    $(".fo").dblclick(function(){
        if($(this).val()!=""){
            $.post("index.php/main/load_data/utility/get_sub_item_detail", {
                code:$(this).val(),
                store:$("#stores").val(),
                qty:$("#5_"+scid).val()
            }, function(res){
                if(res!=0){
                    $("#msg_box_inner").html(res);
                    $("#msg_box").slideDown();
                }
            },"text");
        } 
    });

    $("#btnDelete").click(function(){
        set_delete();
    });

    $("#free_fix,#pst").blur(function(){
      var get_code=$(this).val();
      $(this).val(get_code.toUpperCase());
    });

    $("#btnClearr").click(function(){
        location.reload();
    });

    // $("#showPayments").click(function(){
    //   payment_opt('t_credit_sales_sum',$("#net").val());
    //   $("#save_status").val("0");
    // });
    
   // $("#btnExit1").click(function(){
   //      document.getElementById('light').style.display='none';
   //      document.getElementById('fade').style.display='none';  
   //      $("#5_"+get_id).focus();
   //  });
   
    var save_status=1;

	$("#btnResett").click(function(){
   	location.href="?action=t_special_sales_sum";
	});

    $("#btnApprove").click(function(){
        alert("ok");
    });

    $("#id,#sub_no").keyup(function(){
        this.value = this.value.replace(/\D/g,'');
    });

    $("#ref_no").keyup(function(){
        this.value = this.value.replace(/[^0-9a-zA-Z]/g,'');
    });

    $("#tgrid").tableScroll({height:200});
    $("#tgrid2").tableScroll({height:100});
 
    // $(".fo").focus(function(){
    //     if($("#store_id").val()=="" || $("#stores").val()==0){
    //         alert("Please Select Store");
    //         $("#0_"+scid).val("");
    //         $("#stores").focus(); 
    //     }
    // });

 
//     $(".fo").keydown(function(e){  
//         set_cid($(this).attr("id"));
   
//            if(e.keyCode==112){
//                 $("#pop_search").val($("#0_"+scid).val());
//                 load_items();
//                 $("#serch_pop").center();
//                 $("#blocker").css("display", "block");
//                 setTimeout("select_search()", 100);
//             }
      

//         if(e.keyCode==13){
//             $.post("index.php/main/load_data/t_cash_sales_sum/get_item", {
//                 code:$("#0_"+scid).val(),
//                 group_sale:$("#groups").val(),
//                 stores:$("#stores").val()
//             }, function(res){
//                 if(res.a!=2){
//                     $("#0_"+scid).val(res.a[0].code);

//                         if(check_item_exist($("#0_"+scid).val())){

//                             if($("#df_is_serial").val()=='1')
//                             {
//                                 check_is_serial_item2(res.a[0].code,scid);
//                             }
//                             check_is_batch_item2(res.a[0].code,scid);
//                             check_is_sub_item2(res.a[0].code,scid);

               
//                             $("#h_"+scid).val(res.a[0].code);
//                             $("#n_"+scid).val(res.a[0].description);
//                             $("#0_"+scid).val(res.a[0].code);
//                             $("#2_"+scid).val(res.a[0].model);
//                             $("#3_"+scid).val(res.a[0].max_price);

//                             $("#free_price_"+scid).val(res.a[0].max_price);
               
//                             $("#1_"+scid).focus();
//                             $("#pop_close").click();
//                             check_is_batch_item(scid);
                          
//                         }else{
//                             alert("Item "+$("#0_"+scid).val()+" is already added.");
//                         }
//                 }else{
//                     set_msg($("#0_"+scid).val()+" Item not available in store","error");
//                     $("#0_"+scid).val("");
//                 }
//             }, "json");

//         }

//         if(e.keyCode==46){
//             if($("#df_is_serial").val()=='1'){
//                 deleteSerial(scid);
//             }
//             item_free_delete(scid);
//             $("#h_"+scid).val("");
//             $("#0_"+scid).val("");
//             $("#n_"+scid).val("");
//             $("#1_"+scid).val(""); 
//             $("#2_"+scid).val(""); 
//             $("#3_"+scid).val(""); 
//             $("#4_"+scid).val(""); 
//             $("#5_"+scid).val("");
//             $("#6_"+scid).val(""); 
//             $("#7_"+scid).val(""); 
//             $("#8_"+scid).val(""); 
//             $("#9_"+scid).val("");
//             $("#f_"+scid).val("");
//             $("#bal_free_"+scid).val("");
//             $("#bal_tot_"+scid).val("");
//             $("#free_price_"+scid).val("");
//             $("#issue_qty_"+scid).val("");
//             $("#subcode_"+scid).val("");
//             $("#bqty"+scid).val("");
//             $("#subcode_"+scid).removeAttr("data-is_click");
//             $("#5_"+scid).attr("readonly", false);


//             $("#btn_"+scid).css("display","none"); 
//             $("#btnb_"+scid).css("display","none");
//             $("#sub_"+scid).css("display","none");

//             $("#n_"+scid).closest("td").attr('style', 'width:132px; background-color: #f9f9ec !important');
//             $("#2_"+scid).attr('style', 'width: 100%; background-color: #f9f9ec !important');
//             $("#1_"+scid).closest("td").attr('style', 'width: 71px; background-color: #f9f9ec !important');
//             $("#1_"+scid).attr('style', 'margin:0;padding:0;width :56px; float: right; text-align:right; background-color: #f9f9ec !important');
//             $("#3_"+scid).closest("td").attr('style', 'width: 58px;');
//             $("#6_"+scid).closest("td").attr('style', 'width: 58px; background-color: #f9f9ec !important');
//             $("#8_"+scid).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');
//             $("#0_"+scid).closest("tr").attr('style', 'width:100%; background-color: #ffffff !important;');
                        

//                 //discount();
//                 dis_prec();
//                 amount();
//                 gross_amount();
//                 privilege_calculation();
//                 all_rate_amount();
//                 net_amount();
            
//         }
//     });


//      $(".foo").focus(function(){
//         set_cid($(this).attr("id"));
//         $("#serch_pop2").center();
//         $("#blocker2").css("display", "block");
//         setTimeout("select_search2()", 100);
//     });


//     $(".price, .qty, .dis_pre, .foc").blur(function(){
//      set_cid($(this).attr("id"));

//      var foc=parseFloat($("#4_"+scid).val());
//       if(isNaN(foc)){foc=0;}

//       if(foc==0){
//         //discount();
//         dis_prec();
//         amount();
//         gross_amount();
//         privilege_calculation();
//         all_rate_amount();
//         net_amount();
//       }else{
//         dis_prec();
//         amount();
//         gross_amount();
//         privilege_calculation();
//         all_rate_amount();
//         net_amount();
//       }
//     });


//     $(".qty").blur(function(){
//         set_cid($(this).attr("id"));
//          check_batch_qty(scid);
//     });

//     $(".dis,.price").blur(function(){
//         set_cid($(this).attr("id"));
//         dis_prec();

// //discount();

//         amount();
//         gross_amount();
//         privilege_calculation();
//         all_rate_amount();
//         net_amount();
//     });

//     $(".rate").blur(function(){
//         set_cid($(this).attr("id"));
//         rate_amount();
//         net_amount();
//     });

//     $(".aa").blur(function(){
//         set_cid($(this).attr("id"));
//         rate_pre();
//         net_amount();
//     });


//     $(".qunsb").click(function(){
//         set_cid($(this).attr("id"));
//         check_is_batch_item(scid);
      
//     });


//     $("#pop_search3").keyup(function(e){
//         if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
//             load_items3($("#0_"+scid).val());
//         }
//     });

//     $("#pop_search3").gselect();
//     load_items();
//     load_items2();
//     $("#pop_search").keyup(function(e){
//         if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
//         }
//     });

//     $("#pop_search2").keyup(function(e){
//         if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items2();
//         }
//     });    
  
     
//     $("#customer").change(function(){
//         set_select('customer','customer_id');
//     });
   

//     $("#stores").change(function(){
//         set_select('stores','store_id');
//         empty_grid();

//         $.post("index.php/main/load_data/validation/check_is_group_store", {
//             store_code:$("#stores").val()
//         }, function(res){
          
//             if(res==1){
//               if($("#groups").val()==0){
//                 set_msg("Please select group number","error");
//                 $("#stores").val("0");
//                 $("#store_id").val("");
//               }
//             }else{
//               if($("#groups").val()!=0){
//                 set_msg("Please select group store");

//                 $("#stores").val("0");
//                 $("#store_id").val("");
//               }
//             }


//         },"text");

//     });

    // $("#groups").change(function(){

    //       $("#stores").val("0");
    //       $("#store_id").val("");
          
    //     // if($(this).val()==0 && $("#stores").val()!=0){
    //     //   $.post("index.php/main/load_data/validation/check_is_group_store", {
    //     //     store_code:$("#stores").val()
    //     //     }, function(res){
    //     //     if(res==0){
    //     //         set_msg("Please select group store","error");
    //     //         $("#stores").val("0");
    //     //         $("#store_id").val("");
    //     //     }
    //     //   },"text");
    //     // }

    //     //  if($(this).val()==0){
    //     //     $("#stores").val("0");
    //     //     $("#store_id").val("");
    //     //  }
    //  });


      // $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
      //   width: 350,
      //   multiple: false,
      //   matchContains: true,
      //   formatItem: formatItems,
      //   formatResult: formatItemsResult
      //   });
    
      //   $("#customer").keydown(function(e){
      //       if(e.keyCode == 13){
      //          set_cus_values($(this));
      //       }
      //   });
    
      //   $("#customer").blur(function(){
      //       set_cus_values($(this));
      //   });


      //   $("#sales_rep").autocomplete('index.php/main/load_data/m_employee/auto_com', {
      //   width: 350,
      //   multiple: false,
      //   matchContains: true,
      //   formatItem: formatItems,
      //   formatResult: formatItemsResult
      //   });
    
      //   $("#sales_rep").keydown(function(e){
      //       if(e.keyCode == 13){
      //           set_cus_values2($(this));
      //       }
      //   });
    
      //   $("#sales_rep").blur(function(){
                
      //       set_cus_values2($(this));

      //   });

        $("#id").keydown(function(e){
            if(e.keyCode == 13){
                $(this).blur();
                load_data($(this).val());
                // load_payment_option_data($(this).val(),"4");
                // $("#btnSave").attr("disabled","disabled");
            }
        });


        // $("#pop_search").gselect();
        // $("#pop_search2").gselect();
        // $("#pop_search3").gselect();

        $("#btnPrint").click(function(){
            if($("#hid").val()=="0"){
            	alert("Please load data before print");
            	return false;
            }else{
                $("#print_pdf").submit();
            }  
        });


    //     $(".fo").blur(function(){
    //          var id=$(this).attr("id").split("_")[1];
    //          if($(this).val()=="" || $(this).val()=="0"){
    //          }else if($(this).val()!=$("#itemcode_"+id).val()){
    //             if($("#df_is_serial").val()=='1')
    //             {
    //                 deleteSerial(id);
    //             }
    //          }
    //       });

    //     $(".qty").blur(function(){
    //         item_free(scid);
    //     });

    //     $(".qty, .foc").blur(function(){
    //         balance_item_free(scid);
    //         dis_prec();
    //         check_qty(scid);
    //     });

    // $(".qty").blur(function(){
    //   is_sub_item(scid);
    // });

    // $("#btnSave").click(function(){
    //     for(var i=0; i<25; i++){     
    //        //$("#subcode_"+i).val("");
    //        is_sub_item(i);
    //     }
    // })

 });


// function is_sub_item(x){
//   sub_items=[];
  
//   $("#subcode_"+x).val("");
//   $.post("index.php/main/load_data/utility/is_sub_items", {
//         code:$("#0_"+x).val(),
//         qty:$("#5_"+x).val(),
//         batch:$("#1_"+x).val()
//       }, function(r){
//         if(r!=2){
//           for(var i=0; i<r.sub.length;i++){
//             add(x,r.sub[i].sub_item,r.sub[i].qty);
//         }  
//         $("#subcode_"+x).attr("data-is_click","1");
//       }
//      },"json");
// }

// function add(x,items,qty){
//   $.post("index.php/main/load_data/utility/is_sub_items_available", {
//       code:items,
//       qty:qty,
//       grid_qty:$("#5_"+x).val(),
//       batch:$("#1_"+x).val(),
//       hid:$("#hid").val(),
//       trans_type:"4",
//       store:$("#stores").val()
//     }, function(res){
//         if(res!=2){
//           sub_items.push(res.sub[0].sub_item+"-"+res.sub[0].qty);
//           $("#subcode_"+x).val(sub_items);
//         }else{
//           set_msg("Not enough quantity in this sub item ("+items+")","error");
//           $("#subcode_"+x).val("");
//         }
//   },"json");
// }



// function balance_item_free(id){

//    //$("#bal_tot_"+id).val("");
//    var qty = parseInt($("#5_"+id).val());
//    var foc = parseInt($("#4_"+id).val());
//    var bal = parseInt($("#bal_free_"+id).val());
//    var each_price = parseFloat($("#3_"+id).val());
//    var price = parseFloat($("#free_price_"+id).val());
//    var is_free_item = $("#f_"+id).val();
//    //alert($("#4_"+id).val());

//     if($("#4_"+id).val()!=""){
//         bal = bal-foc;
//         $("#bal_tot_"+id).val(bal+"-"+each_price*bal);
//         $("#f_"+id).val("2");
//     }else{
//         bal = bal-qty;
//         $("#bal_tot_"+id).val(bal+"-"+price*bal);
//     }
// }

// function item_free_delete(no){

//    if(isNaN(parseInt($("#4_"+no).val()))){
//     var qty=parseInt($("#5_"+no).val());
//    }else{
//     var qty=parseInt($("#5_"+no).val())-parseInt($("#4_"+no).val());
//    }
//     var item=$("#0_"+no).val();

//     $.post("index.php/main/load_data/t_cash_sales_sum/item_free_delete",{
//         quantity:qty,
//         item:item
//      },function(r){

//         if(r!='2'){
//             $("#f_"+no).val("2");
//                 for(var x=0; r.a.length>x;x++){
//                     for(var i=0; i<25;i++){ 
//                         if($("#0_"+i).val()==item || $("#0_"+i).val()==r.a[x].code  && $("#f_"+i).val()==1){
//                             console.log($("#0_"+i).val());
//                             $(this).val("");
//                             $("#h_"+i).val("");
//                             $("#0_"+i).val("");
//                             $("#n_"+i).val("");
//                             $("#1_"+i).val(""); 
//                             $("#2_"+i).val(""); 
//                             $("#3_"+i).val(""); 
//                             $("#4_"+i).val(""); 
//                             $("#5_"+i).val("");
//                             $("#6_"+i).val(""); 
//                             $("#7_"+i).val(""); 
//                             $("#8_"+i).val(""); 
//                             $("#9_"+i).val("");
//                             $("#f_"+i).val("");
//                             $("#bal_free_"+i).val("");
//                             $("#bal_tot_"+i).val("");
//                             $("#free_price_"+i).val("");
//                             $("#issue_qty_"+i).val("");
//                             $("#subcode_"+i).val("");
//                             $("#bqty"+i).val("");
//                             $("#subcode_"+i).removeAttr("data-is_click");
//                             $("#5_"+i).attr("readonly", false);


//                             $("#h_"+no).val("");
//                             $("#0_"+no).val("");
//                             $("#n_"+no).val("");
//                             $("#1_"+no).val(""); 
//                             $("#2_"+no).val(""); 
//                             $("#3_"+no).val(""); 
//                             $("#4_"+no).val(""); 
//                             $("#5_"+no).val("");
//                             $("#6_"+no).val(""); 
//                             $("#7_"+no).val(""); 
//                             $("#8_"+no).val(""); 
//                             $("#9_"+no).val("");
//                             $("#f_"+no).val("");
//                             $("#bal_free_"+no).val("");
//                             $("#bal_tot_"+no).val("");
//                             $("#free_price_"+no).val("");
//                             $("#issue_qty_"+no).val("");
//                             $("#subcode_"+no).val("");
//                             $("#bqty"+no).val("");
//                             $("#subcode_"+no).removeAttr("data-is_click");
//                             $("#5_"+no).attr("readonly", false);

//                             $("#n_"+i).closest("td").attr('style', 'width:132px; background-color: #f9f9ec !important');
//                             $("#2_"+i).attr('style', 'width: 100%; background-color: #f9f9ec !important');
//                             $("#1_"+i).closest("td").attr('style', 'width: 71px; background-color: #f9f9ec !important');
//                             $("#1_"+i).attr('style', 'margin:0;padding:0;width :56px; float: right; text-align:right; background-color: #f9f9ec !important');
//                             $("#3_"+i).closest("td").attr('style', 'width: 58px;');
//                             $("#6_"+i).closest("td").attr('style', 'width: 58px; background-color: #f9f9ec !important');
//                             $("#8_"+i).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');

//                             $("#0_"+i).closest("tr").attr('style', 'background-color: #ffffff !important;');
                        
//                             $("#n_"+no).closest("td").attr('style', 'width:132px; background-color: #f9f9ec !important');
//                             $("#2_"+no).attr('style', 'width: 100%; background-color: #f9f9ec !important');
//                             $("#1_"+no).closest("td").attr('style', 'width: 71px; background-color: #f9f9ec !important');
//                             $("#1_"+no).attr('style', 'margin:0;padding:0;width :56px; float: right; text-align:right; background-color: #f9f9ec !important');
//                             $("#3_"+no).closest("td").attr('style', 'width: 58px; ');
//                             $("#6_"+no).closest("td").attr('style', 'width: 58px; background-color: #f9f9ec !important');
//                             $("#8_"+no).attr('style', 'background-color: #f9f9ec !important; width : 100%; text-align:right;');

//                             $("#0_"+no).closest("tr").attr('style', 'background-color: #ffffff !important;');
                        


//                             $("#btn_"+i).css("display","none"); 
//                             $("#btnb_"+i).css("display","none");
//                             $("#sub_"+i).css("display","none");

//                             $("#btn_"+no).css("display","none"); 
//                             $("#btnb_"+no).css("display","none");
//                             $("#sub_"+no).css("display","none");

//                             //discount();
//                             dis_prec();
//                             amount();
//                             gross_amount();
//                             privilege_calculation();
//                             all_rate_amount();
//                             net_amount();

//                         }
//                     }
//                 } 
            
//         }
//     }, "json");
// }


// function item_free(no){

//    if(isNaN(parseInt($("#4_"+no).val())))
//    {
//     var qty=parseInt($("#5_"+no).val());
//    }
//    else
//    {
//     var qty=parseInt($("#5_"+no).val())-parseInt($("#4_"+no).val());
//    }


//     var item=$("#0_"+no).val();

//     $.post("index.php/main/load_data/t_cash_sales_sum/item_free",{
//         quantity:qty,
//         item:item,
//         date:$("#date").val()
//      },function(r){
//         if(r!='2')
//         {
//             for(i=0; i<r.a.length; i++)
//             {
//                 if(r.a[i].code == item)
//                 {
                    
//                     var free_qty=parseInt(r.a[i].qty)
//                     var issue_qty = qty/free_qty;

//                     $("#5_"+no).val(Math.floor(issue_qty)+qty);
//                     $("#4_"+no).val(Math.floor(issue_qty));
//                 }
//             }


//             $("#serch_pop3").center();
//             $("#blocker3").css("display", "block");
//             setTimeout("select_search3()", 100);
//             load_items5($("#0_"+scid).val(),no);
//         }

//     }, "json");
// }


// function check_is_batch_item(scid){
//     var store=$("#stores").val();

//     $.post("index.php/main/load_data/t_cash_sales_sum/is_batch_item",{
//         code:$("#0_"+scid).val(),
//         store:store
//      },function(res){
       
//        if(res==1){
        
//         $("#serch_pop3").center();
//         $("#blocker3").css("display", "block");
//         setTimeout("select_search3()", 100);
//         load_items3($("#0_"+scid).val());
//         }else if(res=='0'){

//             $("#1_"+scid).val("0");
//             $("#1_"+scid).attr("readonly","readonly");
//         }else{
            
//             $("#1_"+scid).val(res.split("-")[0]);
//            // $("#5_"+scid).val(res.split("-")[1]);
//             $("#bqty_"+scid).val(res.split("-")[1]);
//             $("#1_"+scid).attr("readonly","readonly");
//        }
//     },'text');
// }


// function check_is_batch_item_free(scid){
//     var store=$("#stores").val();
//     $.post("index.php/main/load_data/t_cash_sales_sum/is_batch_item",{
//         code:$("#0_"+scid).val(),
//         store:store
//      },function(res){
       
//        if(res==1){
        
//         $("#serch_pop3").center();
//         $("#blocker3").css("display", "block");
//         setTimeout("select_search3()", 100);
//         load_items3($("#0_"+scid).val());
//         }else if(res=='0'){

//             $("#1_"+scid).val("0");
//             $("#1_"+scid).attr("readonly","readonly");
//         }else{
            
//             $("#1_"+scid).val(res.split("-")[0]);
//            // $("#5_"+scid).val(res.split("-")[1]);
//            $("#bqty_"+scid).val(res.split("-")[1]);
//             $("#1_"+scid).attr("readonly","readonly");
//        }
//     },'text');
// }


// function check_is_batch_item2(x,scid){
//     var store=$("#stores").val();
//     $.post("index.php/main/load_data/t_cash_sales_sum/is_batch_item",{
//         code:x,
//         store:store
//      },function(res){
//        $("#btnb_"+scid).css("display","none"); 
//        if(res==1){
//        $("#btnb_"+scid).css("display","block");
//        }
//     },'text');
// }

// function select_search3(){
//     $("#pop_search3").focus();
// }


// function check_is_sub_item(scid){        
//     var store=$("#stores").val();
//     $.post("index.php/main/load_data/utility/is_sub_item",{
//         code:$("#0_"+scid).val(),          
//     },function(res){        
//        if(res==1)
//         {
//             $("#serch_pop3").center();
//             $("#blocker3").css("display", "block");
//             setTimeout("select_search3()", 100);
//             load_items4($("#0_"+scid).val(),$("#1_"+scid).val());
//         }
//     },'text');
// }




// function check_is_sub_item2(x,scid){
//     var store=$("#stores").val();
//     $.post("index.php/main/load_data/utility/is_sub_item",{
//         code:x          
//      },function(res){
//         $("#sub_"+scid).css("display","none");    
//         if(res==1){
//             $("#sub_"+scid).css("display","block");
//         }
//     },'text');
// }



// function load_items3(x){
//     $.post("index.php/main/load_data/t_cash_sales_sum/batch_item", {
//         search : x,
//         stores : $("#stores").val()
//     }, function(r){
//         $("#sr3").html(r);
//         settings3();
//     }, "text");
// }


// function load_items4(x,batch){
//     $.post("index.php/main/load_data/utility/sub_item", {
//         search : x,
//         batch :batch
//     }, function(r){
//         $("#sr3").html(r);
//     }, "text");
// }


// function load_items5(x,y){

//    if(isNaN(parseInt($("#4_"+y).val())))
//    {
//     var qty=parseInt($("#5_"+y).val());
//    }
//    else
//    {
//     var qty=parseInt($("#5_"+y).val())-parseInt($("#4_"+y).val());
//    }



//     var item=$("#0_"+y).val();

//     $.post("index.php/main/load_data/t_cash_sales_sum/item_free_list",{
//         quantity:qty,
//         item:item,
//         date:$("#date").val()
//      },function(r){   
//         if(r!=2){ 
             
//             $("#sr3").html(r);
//             settings6();
//             //$("#4_"+y).attr("readonly","readonly");
//             $("#5_"+y).attr("readonly",true);
//         }
//     }, "text");
// }


// function settings3(){
//     $("#batch_item_list .cl").click(function(){
//         if($(this).children().eq(0).html() != "&nbsp;"){
//             if(check_item_exist3($(this).children().eq(0).html())){
//                 $("#1_"+scid).val($(this).children().eq(0).html());
//                 //$("#5_"+scid).val($(this).children().eq(1).html());
//                 $("#bqty_"+scid).val($(this).children().eq(1).html());
//                 $("#3_"+scid).val($(this).children().eq(2).html());
//                 $("#1_"+scid).attr("readonly","readonly");
//                 $("#5_"+scid).focus();
//                 //discount();
//                 dis_prec();
//                 amount();
//                 gross_amount();
//                 all_rate_amount();
//                 net_amount();
//                 $("#pop_close3").click();
//             }else{
//                 alert("Item "+$(this).children().eq(1).html()+" is already added.");
//             }
//         }else{
//             $("#1_"+scid).val("");
//             $("#5_"+scid).val("");
//             $("#3_"+scid).val("");
//                 //discount();
//                 dis_prec();
//                 amount();
        
//                 gross_amount();
//                 privilege_calculation();
//                 all_rate_amount();
//                 net_amount();
//             $("#pop_close3").click();
//         }
//     });
// }


// function check_item_exist3(id){
//     var v = true;
//     return v;
// }

// function set_cus_values2(f){
//     var v = f.val();
//     v = v.split("-");
//     if(v.length == 2){
//         f.val(v[0]);
//         $("#sales_rep2").val(v[1]);
//     }
// }

// function set_cus_values(f){
//     var v = f.val();
//     v = v.split("-");

//         if(v.length == 2){
       
//         f.val(v[0]);
//         $("#customer_id").val(v[1]);
//         var cus=$("#customer").val();
    
//         $.post("index.php/main/load_data/m_customer/load",{
//           code:cus,
//         },function(rs){
//          $("#address").val(rs.data.address1+", "+rs.data.address2+", "+rs.data.address3);
//          $("#balance").val(rs.acc); 
//           input_active();
//          },"json");
//     }
// }

// function formatItems(row){
//     return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
// }

// function formatItemsResult(row){
//     return row[0]+"-"+row[1];
// }


function save(){
    
  //   var a =0;
  //   var net=0; 
  //   $(".tt").each(function(){    
  //            var d= parseFloat($("#tt_"+a).val());
  //            var f= $("#hh_"+a).val();

  //            if(f==1){
  //               if(isNaN(d)){d=0;}
  //               net=net+d;
  //            }else{
  //               if(isNaN(d)){d=0;}
  //               net=net-d; 
  //          }
  //     a++;
  //   }); //close forloop

  //   $("#additional_amount").val(net);

  //   if($("#df_is_serial").val()=='1'){
  //       serial_items.sort();
  //       $("#srls").attr("title",serial_items);
  //       $("#srls").val(serial_items);
  //   }

  //   $('#form_').attr('action',$('#form_id').val()+"t_cash_sales_sum");
  //   var frm = $('#form_');

  // $("#sales_type").val($("#type").val());
  // $("#qno").val($("#id").val());
  // $("#cus_id").val($("#customer").val());
  // $("#salesp_id").val($("#sales_rep").val());
  // $("#dt").val($("#date").val());

//if(save_status!=0){

$.ajax({
	type: frm.attr('method'),
	url: frm.attr('action'),
	data: frm.serialize(),
	success: function (pid){
             
            if(pid == 0){
              alert("Transaction is not completed");
             }else if(pid == 2){
                alert("No permission to add data.");
             }else if(pid == 3){
                alert("No permission to edit data.");
             }else if(pid==1){
              save_status=0;  
              $("#save_status").val("0");
              $("#print_pdf").submit();
              if($("#df_is_serial").val()=='1')
              {
                serial_items.splice(0);
              }
              
              reload_form();
            }else{
                  set_msg(pid);
                }

        }
    });
  // }
}


function reload_form(){
  setTimeout(function(){
    window.location = '';
  },50); 
}





// function check_code(){
//     loding();
//     var code = $("#code").val();
//     $.post("index.php/main/load_data/t_cash_sales_sum/check_code", {
//         code : code
//     }, function(res){
//         if(res == 1){
//             if(confirm("This code ("+code+") already added. \n\n Do you need edit it?")){
//                 set_edit(code);
//             }else{
//         $("#code").val('');
// 		$("#code").attr("readonly", false);
//             }
//         }
//         loding();
//     }, "text");
// }

function validate(){
   
   for(var t=0; t<25; t++){
      if($("#subcode_"+t).data("is_click")==1 && $("#subcode_"+t).val()==""){
        set_msg("Not enough sub items in ("+$("#0_"+t).val()+")" ,"error");
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
 
    


       if($("#id").val() == ""){
            alert("Please Enter No");
            $("#id").focus();
            return false;

        }

        else if($("#customer_id").val()=="" || $("#customer_id").attr("title")==$("#customer_id").val()){
             alert("Please Enter Customer");
              $("#customer_id").focus();
              return false;
        }


        else if($("#type").val()==0){
             alert("Please Select Type");
              $("#type").focus();
              return false;
        }

        else if($("#date").val()==""){
            alert("Please Select Date");
              $("#date").focus();
              return false;
        }
        
         else if(v == false){
          alert("Please use minimum one item.");
         return false;
        }
        

         else if($("#store_id").val()=="" || $("#stores").val()==0){
            alert("Please Select Store");
              $("#stores").focus();
              return false;
        }

        else if($("#sales_rep2").val()=="" || $("#sales_rep").val()==0){
            alert("Please Enter Sales Rep");
              $("#sales_rep").focus();
              return false;
        }


        else if($("#sales_category").val()==0){

            alert("Please Select Category");
             $("#sales_category").focus();
            return false;
         }else if($("#load_opt").val()==0 && $("#payment_option").is(':checked')){ 
           
                 if($("#type").val()==4){
                      payment_opt('t_cash_sales_sum',$("#net").val());
                      return false; 
                }else if($("#type").val()==5){
                      payment_opt('t_cash_sales_sum',$("#net").val());
                      return false;
                } 

           
        }else{
                return true;
        }  

}


    
function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure to delete this cash sale ["+$("#hid").val()+"]? ")){
            $.post("index.php/main/delete/t_cash_sales_sum", {
            trans_no:id,
        },function(r){
          if(r != 1){
            alert(r);
          }else{
            $("#btnReset").click();
            location.href="";
          }
        }, "text");
    }
  }else{
    alert("Please load record");
  }
}


// function select_search(){
//     $("#pop_search").focus();
  
// }


// function select_search2(){
//     $("#pop_search2").focus();
 
// }

// function load_items(){
//      $.post("index.php/main/load_data/t_cash_sales_sum/item_list_all", {
//         search : $("#pop_search").val(),
//         stores : $("#stores").val(),
//         group_sale:$("#groups").val()
//     }, function(r){
//         $("#sr").html(r);
//         settings();
//     }, "text");
// }

// function load_items2(){
//      $.post("index.php/main/load_data/r_additional_items/item_list_all", {
//         search : $("#pop_search2").val(),
//         stores : false
//     }, function(r){
//         $("#sr2").html(r);
//         settings2();
//     }, "text");
// }

// function settings(){
//     $("#item_list .cl").click(function(){
//         if($(this).children().eq(0).html() != "&nbsp;"){
//             if(check_item_exist($(this).children().eq(0).html())){

//                 if($("#df_is_serial").val()=='1')
//                 {
//                     check_is_serial_item2($(this).children().eq(0).html(),scid);
//                 }
//                 check_is_batch_item2($(this).children().eq(0).html(),scid);
//                 check_is_sub_item2($(this).children().eq(0).html(),scid);

//                 $("#h_"+scid).val($(this).children().eq(0).html());
//                 $("#0_"+scid).val($(this).children().eq(0).html());
//                 $("#n_"+scid).val($(this).children().eq(1).html());
//                 $("#2_"+scid).val($(this).children().eq(2).html()); 
//                 $("#3_"+scid).val($(this).children().eq(3).html());

//                 $("#free_price_"+scid).val($(this).children().eq(3).html());
               
//                 $("#1_"+scid).focus();
//                 $("#pop_close").click();
//                  check_is_batch_item(scid);
//             }else{
//                 alert("Item "+$(this).children().eq(1).html()+" is already added.");
//             }
//         }else{
//             $("#h_"+scid).val("");
//             $("#0_"+scid).val("");
//             $("#n_"+scid).val("");
//             $("#1_"+scid).val(""); 
//             $("#2_"+scid).val(""); 
//             $("#3_"+scid).val("");
//             $("#4_"+scid).val("");  
//             $("#5_"+scid).val("");
//             $("#6_"+scid).val(""); 
//             $("#7_"+scid).val("");
//             $("#8_"+scid).val("");  
//             $("#9_"+scid).val("");
              
//                 //discount();
//                 dis_prec();
//                 amount();
        
//                 gross_amount();
//                 privilege_calculation();
//                 all_rate_amount();
//                 net_amount();

//             $("#pop_close").click();
//         }
//     });
// }

// function settings2(){
//     $("#item_list2 .cl").click(function(){
//         if($(this).children().eq(0).html() != "&nbsp;"){
//             if(check_item_exist2($(this).children().eq(0).html())){
                
//                 $("#hh_"+scid).val($(this).children().eq(3).html());
//                 $("#00_"+scid).val($(this).children().eq(0).html());
//                 $("#nn_"+scid).val($(this).children().eq(1).html());
//                 $("#11_"+scid).val($(this).children().eq(2).html());
//                 $("#hhh_"+scid).val($(this).children().eq(0).html());
//                 $("#11_"+scid).focus();
//                 all_rate_amount();
//                 net_amount();
//                 $("#pop_close2").click();  
//             }else{
//                 alert("Item "+$(this).children().eq(1).html()+" is already added.");
//             }
//         }else{
//             $("#hh_"+scid).val("");
//             $("#00_"+scid).val("");
//             $("#nn_"+scid).val("");
//             $("#11_"+scid).val(""); 
//             $("#tt_"+scid).val(""); 
//             $("#hhh_"+scid).val("");
//             all_rate_amount();
//             net_amount();
            
//             $("#pop_close2").click();
//         }
//     });

//}


// function settings6(){


//    if(isNaN(parseInt($("#4_"+scid).val()))){
//     var qty=parseInt($("#5_"+scid).val());
//    }else{
//     var qty=parseInt($("#5_"+scid).val())-parseInt($("#4_"+scid).val());
//    }

//    // var qty = parseInt($("#5_"+scid).val());
//     if($("#4_"+scid).val() != "")
//     {
//         $("#bal_free_"+scid).val($("#4_"+scid).val());
//         $("#issue_qty_"+scid).val($("#4_"+scid).val());
//     }





// $("#free_item_list .cl").click(function(){
//         if($(this).children().eq(0).html() != "&nbsp;"){
//             if(check_item_exist2($(this).children().eq(0).html())){

//                  var get=$(this).children().eq(0).html();
//                  var name=$(this).children().eq(1).html();
//                  var modal=$(this).children().eq(2).html();
//                  var price=$(this).children().eq(3).html();
//                  var free_qty=parseInt($(this).children().eq(4).html());
//                  var sign=$(this).children().eq(5).html();

//                  var issue_qty = qty/free_qty;



//                   for(var i=0; i<25 ;i++){
//                     if($("#0_"+i).val()==get)
//                       {
//                         return false;
//                       }
//                     else if($("#0_"+i).val()=="")
//                       {
//                         if($("#df_is_serial").val()=='1')
//                         {
//                             check_is_serial_item2(get,i);
//                         }
                       
                        
//                         $("#0_"+i).val(get);
//                         $("#h_"+i).val(get);
//                         $("#n_"+i).val(name);
//                         $("#2_"+i).val(modal);
//                         $("#free_price_"+i).val(price);
//                         $("#5_"+i).val(Math.floor(issue_qty));
//                         $("#issue_qty_"+i).val(Math.floor(issue_qty));
//                         $("#f_"+i).val(sign);
//                         $("#bal_free_"+i).val(Math.floor(issue_qty));

//                         $("#n_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width:132px; background-color: rgb(224, 228, 146) !important');
//                         $("#2_"+i).removeClass('g_col_fixed').attr('style', ' width: 100%; background-color: rgb(224, 228, 146) !important');
                        
//                         $("#1_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 71px; background-color: rgb(224, 228, 146) !important');

//                         $("#1_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; margin:0;padding:0;width :56px; float: right; text-align:right;');
//                         $("#3_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
//                         $("#6_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
//                         $("#8_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');

//                         $("#0_"+i).closest("tr").attr('style', 'background-color: rgb(224, 228, 146) !important;');
//                         $("#5_"+i).focus();
//                         check_is_batch_item2(get,i);
//                         //is_sub_item(i);
//                         check_is_sub_item2(get,i);
//                         check_is_batch_item_free(i);
//                         check_is_batch_item(i);
                        

//                         break;                



//                       }
//                   }          
//                 $("#11_"+scid).focus();
//                 all_rate_amount();
//                 net_amount();
//                 $("#pop_close2").click();  
//             }else{
//                 alert("Item "+$(this).children().eq(1).html()+" is already added.");
//             }
//         }else{
//             $("#h_"+scid).val("");
//             $("#0_"+scid).val("");
//             $("#n_"+scid).val("");
//             $("#1_"+scid).val(""); 
//             $("#t_"+scid).val(""); 
           
//             all_rate_amount();
//             net_amount();
            
//             $("#pop_close2").click();
//         }
//     });
// }

// function check_qty(scid){

//     var foc = $("#4_"+scid).val();
//     var qtyy = $("#5_"+scid).val();

//     var qty = parseInt($("#5_"+scid).val());
//     var issue_qtys = parseInt($("#issue_qty_"+scid).val());
//     var item = $("#0_"+scid).val();
//     var focs = parseInt($("#4_"+scid).val());


//     if(foc=="" && qtyy != ""){
//         if(qty>issue_qtys){
//             set_msg("this item ("+item+") quantity should be less than "+issue_qtys,"error");
//             $("#5_"+scid).val(issue_qtys);
//             return false;
//         }

//     }else if(foc!=""){
//         if(focs>issue_qtys){
//             set_msg("this item ("+item+") FOC quantity should be less than "+issue_qtys,"error");
//             $("#4_"+scid).val(issue_qtys);
//             return false;
//         }
//     }

//     return true;
// }

// function check_item_exist(id){
//     var v = true;
//     $("input[type='hidden']").each(function(){
//         if($(this).val() == id){
//             v = false;
//         }
//     });    
//     return v;
// }



function check_item_exist2(id){
    var v = true;
    $("input[type='hidden']").each(function(){
        if($(this).val() == id){
            v = false;
        }
    });    
    return v;
}



function load_data(id){
    empty_grid();
    loding();
    $.post("index.php/main/get_data/t_special_sales_sum/", {
        id: id
    }, function(r){

            if(r=="2"){
               alert("No records");
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

            if(r.sum[0].is_multi_payment==1){
              $("#payment_option").attr("checked", "checked");
              $("#payment_option").css("display","none");
            }else{
              $("#payment_option").removeAttr("checked");
            }

            $("#hid").val(id);   
            $("#qno").val(id); 
            $("#customer").val(r.sum[0].cus_id);
            $("#cus_id").val(r.sum[0].cus_id);
            $("#customer_id").val(r.sum[0].name);
            $("#address").val(r.sum[0].address1+", "+r.sum[0].address2+", "+r.sum[0].address3);
            $("#date").val(r.sum[0].ddate); 
            $("#dt").val(r.sum[0].ddate);
            $("#type").val(r.sum[0].type);
            $("#sales_type").val(r.sum[0].type);
            $("#sales_category").val(r.sum[0].category);
            $("#groups").val(r.sum[0].group_no);
            $("#stores").val(r.sum[0].store);
            set_select("stores","store_id");
            $("#ref_no").val(r.sum[0].ref_no);
            $("#memo").val(r.sum[0].memo);
            $("#sales_rep").val(r.sum[0].rep);
            $("#salesp_id").val(r.sum[0].rep);
            $("#sales_rep2").val(r.sum[0].rep_name);
            $("#gross").val(r.sum[0].gross_amount);
            $("#net").val(r.sum[0].net_amount);

            if(r.sum[0].crn_no!=0){
                $("#crn_no").val(r.sum[0].crn_no);
                $("#crn_no_hid").val(r.sum[0].crn_no);
            }else{
                 $("#crn_no").val(r.crn);
                 $("#crn_no_hid").val(0);
            }


            $("#amount8_0").val(r.sum[0].pay_privillege_card);
            $("#pc").val(r.sum[0].pay_privillege_card);
            $("#id").attr("readonly","readonly")       

           var total_discount=0;
           var gross_amount=parseFloat(r.sum[0].gross_amount);
            for(var i=0; i<r.det.length;i++){
                $("#h_"+i).val(r.det[i].code);
                $("#0_"+i).val(r.det[i].code);
                $("#itemcode_"+i).val(r.det[i].code);
                if($("#df_is_serial").val()=='1')
                {
                    $("#numofserial_"+i).val(r.det[i].qty);
                    $("#setserial_"+i).removeAttr("title");
                    $("#setserial_"+i).removeAttr("value");
                    $("#setserial_"+i).attr("title",1);
                    $("#setserial_"+i).attr("value",1);                     
                }

                $("#n_"+i).val(r.det[i].item_des);
                $("#1_"+i).val(r.det[i].batch_no);
                $("#2_"+i).val(r.det[i].model);
                $("#3_"+i).val(r.det[i].price);
                $("#4_"+i).val(r.det[i].foc);
                $("#5_"+i).val(r.det[i].qty);
                $("#6_"+i).val(r.det[i].discountp);
                $("#7_"+i).val(r.det[i].discount);
                total_discount=total_discount+parseFloat(r.det[i].discount);

                $("#8_"+i).val(r.det[i].amount);
                $("#9_"+i).val(r.det[i].warranty);
                $("#f_"+i).val(r.det[i].is_free);
                
                 $("#free_price_"+i).val(r.det[i].price);

                // if(r.det[i].is_free!="0")
                // {
                //  if(r.det[i].foc==""){
                //     $("#bal_free_"+i).val(parseFloat(r.det[i].free_balance)+parseFloat(r.det[i].qty));
                //     $("#issue_qty_"+i).val(parseFloat(r.det[i].free_balance)+parseFloat(r.det[i].qty));
                // }else{
                //     $("#bal_free_"+i).val(parseFloat(r.det[i].free_balance)+parseFloat(r.det[i].foc));
                //     $("#issue_qty_"+i).val(parseFloat(r.det[i].free_balance)+parseFloat(r.det[i].foc));
                // }
                // }
                
                // if(r.det[i].is_free!='0')
                // {
                //     $("#n_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width:132px; background-color: rgb(224, 228, 146) !important');
                //     $("#2_"+i).removeClass('g_col_fixed').attr('style', ' width: 100%; background-color: rgb(224, 228, 146) !important');
                //     $("#1_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 71px; background-color: rgb(224, 228, 146) !important');
                //     $("#1_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; margin:0;padding:0;width :56px; float: right; text-align:right;');
                //     $("#3_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
                //     $("#6_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
                //     $("#8_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');
                //     $("#0_"+i).closest("tr").attr('style', 'background-color: rgb(224, 228, 146) !important;');
            
                //     // $("#3_"+i).val("");
                //     // $("#n_"+i).closest("td").removeClass('g_col_fixed');
                //     // $("#2_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important');
                //     // $("#1_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important');
                //     // $("#3_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important');
                //     // $("#8_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');
                //     // $("#0_"+i).closest("tr").attr('style', 'background-color: rgb(224, 228, 146) !important;');
                        
                // }

                // if(r.det[i].is_free=='1'){
                //     $("#3_"+i).val("");
                // }else{
                //     $("#3_"+i).val(r.det[i].price);
                // }

                // var bal_tot=parseFloat(r.det[i].price)*r.det[i].free_balance;
                // $("#bal_tot_"+i).val(r.det[i].free_balance+"-"+bal_tot);

                // if($("#df_is_serial").val()=='1')
                // {
                //    check_is_serial_item2(r.det[i].code,i);   
                // }
                // check_is_batch_item2(r.det[i].code,i);
                // check_is_sub_item2(r.det[i].code,i); 
                // is_sub_item(i);              
            }



             result=r.add.length;
             for(var i=0; i<r.add.length;i++){
              
                $("#hhh_"+i).val(r.add[i].sales_type);
                $("#00_"+i).val(r.add[i].sales_type);
                $("#nn_"+i).val(r.add[i].description);
                $("#11_"+i).val(r.add[i].rate_p);
                $("#tt_"+i).val(r.add[i].amount);
                  get_sales_type(i);
            }

            // if($("#df_is_serial").val()=='1'){
            //     serial_items.splice(0);
            //     for(var i=0;i<r.serial.length;i++){
            //       serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);
            //     }
            //     serial_items.sort();
            // }

          if(r.sum[0].is_cancel==1){
            $("#btnDelete").attr("disabled", "disabled");
            $("#btnSave").attr("disabled", "disabled");
            $("#mframe").css("background-image", "url('img/cancel.png')");
          }
            $("#total_discount").val(m_round(total_discount));
            $("#total_amount").val(m_round(gross_amount+total_discount));
            
            } 
            loding();           
        }, "json");

  
}


    // function get_sales_type(i){
    //     $.post("index.php/main/load_data/r_additional_items/get_type",{
    //          id:$("#00_"+i).val()
    //         },function(res){      
    //           $("#hh_"+i).val(res);
    //      },"text");

    // }



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
            $("#6_"+i).val(""); 
            $("#7_"+i).val(""); 
            $("#8_"+i).val(""); 
            $("#9_"+i).val("");
            $("#f_"+i).val("");
            $("#bal_free_"+i).val("");
            $("#bal_tot_"+i).val("");
            $("#free_price_"+i).val("");
            $("#issue_qty_"+i).val("");
            $("#subcode_"+i).val("");
            $("#bqty"+i).val("");
            $("#subcode_"+i).removeAttr("data-is_click");

            $("#btn_"+i).css("display","none"); 
            $("#btnb_"+i).css("display","none");
            $("#sub_"+i).css("display","none");
            
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







// function discount(){

//    var qty=parseFloat($("#5_"+scid).val());
//    var price=parseFloat($("#3_"+scid).val());
//    var dis_pre=parseFloat($("#6_"+scid).val());
//    var discount="";
//    if(isNaN(qty)){qty=0;}
//    if(isNaN(price)){price=0;}
//    if(isNaN(dis_pre)){dis_pre=0;}


//     if(!isNaN(qty)&& !isNaN(price) && !isNaN(dis_pre)){
//     discount=(qty*price*dis_pre)/100;


//        if(discount!=0){
//             $("#7_"+scid).val(m_round(discount));
//         }else{
//             $("#7_"+scid).val("");
//         }
//     }
    
// }

// function dis_prec(){
   
//    var qty=parseFloat($("#5_"+scid).val());
//    var price=parseFloat($("#3_"+scid).val());
//    var discount=parseFloat($("#7_"+scid).val());
//    var dis_pre="";

//    if(isNaN(qty)){qty=0;}
//    if(isNaN(price)){price=0;}
//    if(isNaN(discount)){discount=0;}

//    if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
//     dis_pre=(discount*100)/(qty*price);
//         if(isNaN(dis_pre) || !isFinite(dis_pre)){    
//             $("#6_"+scid).val("");
//         }else{
//             $("#6_"+scid).val(m_round(dis_pre));
//         }
//     }

// }

// function amount(){
//     var qty=parseFloat($("#5_"+scid).val());
//     var price=parseFloat($("#3_"+scid).val());
//     var foc=parseFloat($("#4_"+scid).val());
//     var amount="";

//    if(isNaN(qty)){qty=0;}
//    if(isNaN(price)){price=0;}
//    if(isNaN(foc)){foc=0;}


//     if(!isNaN(foc) && !isNaN(price)){
//         if(foc!=0){
//          $("#7_"+scid).val(m_round(foc*price));     
//         }
       
//     }

//     var discount=parseFloat($("#7_"+scid).val());
//     if(isNaN(discount)){discount=0;}

//     if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount) && !isNaN(foc)){
//         //amount=(qty+foc)*price;
//         amount=(qty)*price;
//         amount=amount-discount;
        
       
//          $("#8_"+scid).val(m_round(amount)); 
//     }else if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
//     amount=(qty*price)-discount;
       
//         if(amount!=0){
//            $("#8_"+scid).val(m_round(amount)); 
//         }else{
//            $("#8_"+scid).val(""); 
//         }
   
//     }else if(!isNaN(qty)&& !isNaN(price)){
//     amount=(qty*price);
       
//         if(amount!=0){
//            $("#8_"+scid).val(m_round(amount)); 
//         }else{
//            $("#8_"+scid).val(""); 
//         }
//     }
// }

// function gross_amount(){
//     var gross=loop=0;

//     $(".amount").each(function(){
//         var gs=parseFloat($("#8_"+loop).val());
//         if(!isNaN(gs)){    
//         gross=gross+gs;
//         }    
//         loop++;
//     });
//     $("#gross").val(m_round(gross));
// }

// function rate_amount(){
//     var rate_pre=parseFloat($("#11_"+scid).val());
//     var gross_amount=parseFloat($("#gross").val());
//     var rate_amount="";

//     if(!isNaN(rate_pre)&& !isNaN(gross_amount)){
//     rate_amount=(gross_amount*rate_pre)/100;
//     $("#tt_"+scid).val(m_round(rate_amount));
//     }
// }


// function rate_pre(){
//     var gross_amount=parseFloat($("#gross").val());
//     var rate=parseFloat($("#tt_"+scid).val());
//     var rate_amount_pre="";



//     if(!isNaN(rate)&& !isNaN(gross_amount)){
//     rate_amount_pre=(rate*100)/gross_amount;
//     $("#11_"+scid).val(m_round(rate_amount_pre));
//     }

// }



// function all_rate_amount(){
//     var gross_amount=parseFloat($("#gross").val());  
//     var additional=loop=0;
    

//     $(".rate").each(function(){
//         var rate=parseFloat($("#11_"+loop).val());
//         var rate_amount=0;
//         if(!isNaN(rate) && !isNaN(rate_amount) ){ 
//         rate_amount=(gross_amount*rate)/100;
//         $("#tt_"+loop).val(m_round(rate_amount));
//         }    
//         loop++;
//     });

    
// }

// function net_amount(){
   
//     var gross_amount=parseFloat($("#gross").val());
//     var net_amount=additional=loop=0;
//     $(".foo").each(function(){
//         var add=parseFloat($("#tt_"+loop).val());
//         var f= $("#hh_"+loop).val();

//         if(!isNaN(add)){
//         if(f==1){
//             additional=additional+add;
//             }else{
//             additional=additional-add;    
//         }
//     }    
//         loop++;
//     });

//     if(!isNaN(additional)&& !isNaN(gross_amount)){
//         net_amount=gross_amount+additional;
//         $("#net").val(m_round(net_amount));
//     }else{
//         $("#net").val(net_amount);
//     }

//     var discount=0;
//     $(".dis").each(function(e){
//         if(!isNaN(parseFloat($("#7_"+e).val()))){
//             discount=discount+parseFloat($("#7_"+e).val());
//         }
//     });

//     $("#total_discount").val(m_round(discount));
//     $("#total_amount").val(m_round(gross_amount+discount));

// }


// function check_batch_qty(scid){
//     $.post("index.php/main/load_data/t_cash_sales_sum/get_batch_qty",{
//         store:$("#stores").val(),
//         batch_no:$("#1_"+scid).val(),
//         code:$("#0_"+scid).val(),
//         hid:$("#hid").val()
//     },function(res){

//         if(parseFloat(res)<0){
//             res=0;
//         }

//         if(parseFloat(res) < parseFloat($("#5_"+scid).val())){
//           //$("#5_"+scid).val("");
//           $("#4_"+scid).val("");
//           //$("#5_"+scid).focus();
//           set_msg("There is not enough quantity in this batch","error");
//         }
//     },"text");
// }

// function select_search4(){
//     $("#pop_search4").focus();
// }

// function check_is_group_store(){
//     $.post("index.php/main/load_data/validation/check_is_group_store",{
//         store:$("#stores").val(),
//         batch_no:$("#1_"+scid).val(),
//         code:$("#0_"+scid).val(),
//         hid:$("#hid").val()
//     },function(res){

//         if(parseFloat(res)<0){
//             res=0;
//         }

//         if(parseFloat(res) < parseFloat($("#5_"+scid).val())){
//           $("#5_"+scid).val("");
//           $("#5_"+scid).focus();
//           alert("There is not enough quantity in this batch");
//         }
//     },"text");
// }







