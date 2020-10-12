    var p_code = window.location.search.split('=')[2];

    if(p_code != undefined){
        load_data(p_code);
    }

    var sub_items=[];
    var result;
    $(function(){
        $(".quns").css("display", "none");
        $(".qunsb").css("display","none");
        $(".subs").css("display","none");
        $("#payment_option").attr("checked", "checked");
        $("#btnSave").attr("disabled","disabled");
        $("#btnApprove").attr("disabled","disabled");
        $("#btnSavee").css("display","none");


        $("#btnApprove").click(function(){
            $("#app_status").val("1");
            if(validate()){
              save();    
          }
      });


        if(p_code != undefined){
            load_data(p_code);
            load_payment_option_data(p_code,"63");
        }
        setTimeout("default_customer_details();", 100);



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

        $("#cutomer_create").click(function(){
          window.open($("#base_url").val()+"?action=m_customer","_blank");      
      }); 

        $("#slide_arrow").click(function(){
       // $("#det_box").slideToggle(); 
   });  

        $(".price , .dis").blur(function(){
            set_cid($(this).attr("id"));
            check_min_price(scid);
        });

        $("#is_cash_bill").click(function(){
            if($("#is_cash_bill").is(':checked')){
                $("#btnSave").attr("disabled",false);
                $("#showPayments").click();
                $("#btnSave2").click();
            }else{
                $("#btnSave").attr("disabled",true);
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


        $(".freitm").click(function() {
            free3=loop=0;
            set_cid($(this).attr("id"));
            if($("#free_"+scid).is(":checked")){ 
                if($("#0_"+scid).val() != ""){
                    mark_as_free(scid,$("#0_"+scid).val());

                }else{
                    set_msg("Please Select Gift Voucher");
                    $("#free_"+scid).removeAttr('checked');
                }
            }else{
                uncheck_free(scid,$("#0_"+scid).val());
            }
        });



        $("#approve4").click(function(){
        // $.ajax({
        //     type:'POST',
        //     url:'index.php/main/load_data/utility/save_approval_level',
        //     data:'trans_code=4,trans_no='+$("#id").val(),
        //     success:function (pid){
        //         alert(pid);
        //     }
        // });
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

        $("#sales_rep_create").click(function(){
         window.open($("#base_url").val()+"?action=m_employee","_blank");  
     });

        $(".subs").click(function(){
         set_cid($(this).attr("id"));
         check_is_sub_item(scid); 
     });

        $(".price").keyup(function(){
            set_cid($(this).attr("id"));
            $("#free_price_"+scid).val($(this).val());
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

        $("#showPayments").click(function(){
          payment_opt('t_credit_sales_sum',$("#net").val());

          if($("#hid").val()=="0"){
              $("#cash").val($("#net").val());        
          }
          $("#save_status").val("0");
          if($("#app_status").val()=='0'){
            $("#btnApprove").attr('disabled',false);
        }
    });

        $("#btnExit1").click(function(){
            document.getElementById('light').style.display='none';
            document.getElementById('fade').style.display='none';  
            $("#5_"+get_id).focus();
        });

        var save_status=1;

        $("#btnResett").click(function(){
            location.href="?action=g_t_sales_sum";
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
                set_msg("Please Select Store","error");
                $("#0_"+scid).val("");
                $("#stores").focus(); 
            }
        });


        $("#sales_rep").keydown(function(e){
            if(e.keyCode == 112){
                $("#pop_search6").val();
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
                $("#sales_rep").val("");
                $("#sales_rep2").val("");
            }
        });



        $("#customer").keydown(function(e){
            if(e.keyCode == 112){
                $("#pop_search2").val($("#customer").val());
                load_data9();
                $("#serch_pop2").center();
                $("#blocker").css("display", "block");
                setTimeout("$('#pop_search2').focus()", 100);
            }

            $("#pop_search2").keyup(function(e){

                if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                   load_data9();
               }
           }); 

            if(e.keyCode == 46){
                $("#customer").val("");
                $("#customer_id").val("");
                $("#address").val("");
                $("#balance").val("");

            }
        });



        $("#quotation").keydown(function(e){ 
            if($("#customer").val() != ""){ 
                if(e.keyCode==112){
                    $("#pop_search4").val($("#quotation").val());
                    load_data2();
                    $("#serch_pop4").center();
                    $("#blocker").css("display", "block");
                    setTimeout("select_search4()", 100);   
                }
                $("#pop_search4").keyup(function(e){
                    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                       load_data2();
                   }
               });
            }else{
                set_msg("Please Select Customer","error");
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

            $("#pop_search").keyup(function(e){

                if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
                    load_items();
                }
            });


            if(e.keyCode==13){
                $.post("index.php/main/load_data/g_t_sales_sum/get_item", {
                    code:$("#0_"+scid).val(),
                    group_sale:$("#groups").val(),
                    stores:$("#stores").val()
                }, function(res){
                    if(res.a!=2){
                        $("#0_"+scid).val(res.a[0].code);

                        if(check_item_exist($("#0_"+scid).val())){

                            if($("#df_is_serial").val()=='1')
                            {
                                check_is_serial_item2(res.a[0].code,scid);
                            }


                            $("#h_"+scid).val(res.a[0].code);
                            $("#n_"+scid).val(res.a[0].description);
                            $("#0_"+scid).val(res.a[0].code);
                            $("#3_"+scid).val(res.a[0].max_price);
                            $("#1_"+scid).focus();
                            $("#pop_close").click();

                        }else{
                            set_msg("Item "+$("#0_"+scid).val()+" is already added.","error");
                        }
                    }else{
                        set_msg($("#0_"+scid).val()+" Item not available in store","error");
                        $("#0_"+scid).val("");
                    }
                }, "json");

            }

            if(e.keyCode==46){
                if($("#df_is_serial").val()=='1'){
                //deleteSerial(scid);
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
            $("#5_"+scid).attr("readonly", false);


            $("#btn_"+scid).css("display","none"); 
            $("#btnb_"+scid).css("display","none");
            $("#sub_"+scid).css("display","none");

            amount();
            net_amount();
            
        }
    });


        $(".foo").focus(function(){
            set_cid($(this).attr("id"));
            $("#serch_pop7").center();
            $("#blocker2").css("display", "block");
            setTimeout("select_search7()", 100);
        });


        $(".price, .qty").blur(function(){

           set_cid($(this).attr("id"));
           amount();
           net_amount();

       });



        $(".aa").blur(function(){
            set_cid($(this).attr("id"));
            rate_pre();
            net_amount();
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
        });



        $("#customer").blur(function(){
            set_cus_values($(this));
        });


        $("#sales_rep").blur(function(){
            set_cus_values2($(this));
        });

        $("#id").keydown(function(e){
            if(e.keyCode == 13){
                $(this).blur();
                load_data($(this).val());
                load_payment_option_data($(this).val(),"63");
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
           }else{
            $("#print_pdf").submit();
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



        $(".qty, .foc").blur(function(){
            check_qty(scid);
        });



        $("#sales_category").change(function() {
         get_group();
     });

        default_option();

    });


    function default_option(){

        $.post("index.php/main/load_data/utility/default_option", {
        }, function(r){
          if(r.use_sales_category!="0"){
            $(".ct").css("display","none");

        }
        var sale_cat=r.def_sales_category;
        $("#sales_category").val(sale_cat);
        $("#sales_category1").val(sale_cat);
        if(r.use_sales_group!="0"){
         $(".gr").css("display","none");
         $("#dealer_id").val(r.def_sales_group);
     }
     if(r.use_salesman!="0"){
        $("#sales_rep").val(r.def_salesman_code);
        $("#sales_rep2").val(r.def_salesman);
    }
    $("#stores").val(r.def_sales_store);
    $("#store_id").val(r.store);
    $("#customer").val(r.def_cash_customer);
    $("#customer_id").val(r.customer);
    if(r.def_cash_customer!=""){
      $("#address").val(r.address1+","+r.address2+","+r.address3);
  }
}, "json");
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



 function sales_category_max(){
  $.post("index.php/main/load_data/utility/get_max_sales_category", {
    nno:"sub_no",
    table:"g_t_sales_sum",
    category:$("#sales_category").val(),
    hid:$("#hid").val()
}, function(r){
    $("#sub_no").val(r);          
},"text");
}

function load_permission_det(nno){
    $.post("index.php/main/load_data/utility/check_approve_level",{
        nno:nno,
        table:'g_t_sales_sum',
        trans_code:63
    },function(res){
        html ="<table style='width:100%;'><tr><td colspan='3' style='border:1px solid #ccc;color:#fff;background:#283d66;'>User Permission Type</td></tr>";
        html+="<tr><td style='color:#fff;background:#476bb2;'>Code</td><td style='color:#fff;background:#476bb2;' colspan='2'>Description</td></tr>";
        html+="<tr><td style='border:1px solid #ccc;'>"+res.sum[0].code+"</td><td style='border:1px solid #ccc;' colspan='2'>"+res.sum[0].description+"</td></tr><table>";

        html+="<table style='width:100%;'><tr><tr><td colspan='3' style='color:#fff;background:#283d66;'>Approve List</td></tr>";
        html+="<tr><td style='color:#fff;background:#476bb2;'>Role Id</td><td style='color:#fff;background:#476bb2;' >Role Name</td><td style='color:#fff;background:#476bb2;'>Status</td>";
        for(x=0;res.det.length>x;x++){
            html+="<tr><td style='border:1px solid #ccc;'>"+res.det[x].role_id+"</td>";
            html+="<td style='border:1px solid #ccc;'>"+res.det[x].role_description+"</td>";
            html+="<td style='border:1px solid #ccc;'><input type='checkbox' /></td></tr>";      
        }

        $("#sr4").html(html);
    },"json");
}



function load_data_form(id){

    $("#id,#hid").val(id);
    $("#hid").attr("title",id);
    $("#btnSave").attr("disabled","disabled");

}




function select_search3(){
    $("#pop_search3").focus();
}



function load_items5(x,y){

 if(isNaN(parseInt($("#4_"+y).val())))
 {
    var qty=parseInt($("#5_"+y).val());
}
else
{
    var qty=parseInt($("#5_"+y).val())-parseInt($("#4_"+y).val());
}



var item=$("#0_"+y).val();

$.post("index.php/main/load_data/g_t_sales_sum/item_free_list",{
    quantity:qty,
    item:item,
    date:$("#date").val()
},function(r){   
    if(r!=2){ 

        $("#sr3").html(r);
        settings6();
            //$("#4_"+y).attr("readonly","readonly");
            $("#5_"+y).attr("readonly",true);
        }
    }, "text");
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

        $.post("index.php/main/load_data/m_customer/load",{
          code:cus,
      },function(rs){
       $("#address").val(rs.data.address1+", "+rs.data.address2+", "+rs.data.address3);
       $("#balance").val(rs.acc); 
       input_active();
   },"json");
    }
}

function formatItems(row){
    return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
    return row[0]+"-"+row[1];
}


function save(){
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
    if($("#df_is_serial").val()=='1'){
        serial_items.sort();
        $("#srls").attr("title",serial_items);
        $("#srls").val(serial_items);
    }

    $('#form_').attr('action',$('#form_id').val()+"g_t_sales_sum");
    var frm = $('#form_');

    $("#sales_type").val($("#type").val());
    $("#qno").val($("#id").val());
    $("#cus_id").val($("#customer").val());
    $("#salesp_id").val($("#sales_rep").val());
    $("#dt").val($("#date").val());


    loding();
    $.ajax({
       type: frm.attr('method'),
       url: frm.attr('action'),
       data: frm.serialize(),
       success: function (pid){

        if(pid == 0){
          set_msg("Transaction is not completed");
      }else if(pid == 2){
        set_msg("No permission to add data.");
    }else if(pid == 3){
        set_msg("No permission to edit data.");
    }else if(pid==1){
        loding();
        $("#btnSave").attr("disabled",true);
        $("#showPayments").attr("disabled",true);
              //$("#btnSavee").css("display","inline");
              save_status=0;  
              $("#save_status").val("0");
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
            set_msg(pid);
        }
        //loding();
    }
});
}


function reload_form(){
  setTimeout(function(){
    location.href= '';
},50); 
}





function check_code(){
    loding();
    var code = $("#code").val();
    $.post("index.php/main/load_data/g_t_sales_sum/check_code", {
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
     return false;
 }

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


/*--------------------------------------------------------------------------------------------*/

$(".fo").each(function(x){
    if($("#hid").val()=="" || $("#hid").val()=="0"){
       if($("#f_"+x).val() != ""){
        if($("#f_"+x).val() == "1"){
            $("#app_status").val("0");
        }
    }
}
});

/*--------------------------------------------------------------------------------------------*/
for(var f=0; f<25; f++){
    return check_qty(f);   
}

if($("#id").val() == ""){
    set_msg("Please Enter No","error");
    $("#id").focus();
    return false;
}

else if($("#customer_id").val()=="" || $("#customer_id").attr("title")==$("#customer_id").val()){
   set_msg("Please Enter Customer","error");

   $("#customer_id").focus();
   return false;
}


else if($("#type").val()==0){
   set_msg("Please Select Type","error");
   $("#type").focus();
   return false;
}

else if($("#date").val()==""){
    set_msg("Please Select Date","error");
    $("#date").focus();
    return false;
}
else if(v == false){
  set_msg("Please use minimum one item","error");
  return false;
}else if($("#store_id").val()=="" || $("#stores").val()==0){
    set_msg("Please Select Store","error");
    $("#stores").focus();
    return false;
}else if($("#sales_rep2").val()=="" || $("#sales_rep").val()==0){
    set_msg("Please Enter Sales Rep","error");
    $("#sales_rep").focus();

}else if($("#sales_category").val()=="0"){
 set_msg("Please Select Category","error");
 $("#sales_category").focus();
 return false;
}else if($("#load_opt").val()==0 && $("#payment_option").is(':checked')){ 

   if($("#type").val()==4){
      payment_opt('g_t_sales_sum',$("#net").val());
      return false; 
  }else if($("#type").val()==5){
      payment_opt('g_t_sales_sum',$("#net").val());
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
            $.post("index.php/main/delete/g_t_sales_sum", {
                trans_no:id,
            },function(r){
              if(r != 1){
                set_msg(r);
            }else{
                delete_msg();
            }
        }, "text");
        }
    }else{
        set_msg("Please load record","error");
    }
}


function select_search(){
    $("#pop_search").focus();

}


function select_search2(){
    $("#pop_search2").focus();

}

function load_items(){        
   $.post("index.php/main/load_data/g_t_sales_sum/item_list_all", {
    search : $("#pop_search").val(),
    stores : $("#stores").val(),
    group_sale:$("#groups").val()
}, function(r){
    $("#sr").html("");
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
    $("#5_"+scid).attr("readonly", false);
    $("#btn_"+scid).css("display","none"); 
    $("#btnb_"+scid).css("display","none");
    $("#sub_"+scid).css("display","none");

    amount();
    net_amount();
}

function settings(){
    $("#item_list .cl").click(function(){
        empty_row(scid);
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist($(this).children().eq(0).html())){

                if($("#df_is_serial").val()=='1'){
                    check_is_serial_item2($(this).children().eq(0).html(),scid);
                }

                $("#h_"+scid).val($(this).children().eq(0).html());
                $("#0_"+scid).val($(this).children().eq(0).html());
                $("#n_"+scid).val($(this).children().eq(1).html());
                $("#2_"+scid).val($(this).children().eq(2).html()); 
                $("#3_"+scid).val($(this).children().eq(3).html());

                $("#free_price_"+scid).val($(this).children().eq(3).html());
                $("#item_min_price_"+scid).val($(this).children().eq(4).html());
                $("#cost_"+scid).val($(this).children().eq(5).html());
                $("#1_"+scid).focus();
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
            $("#4_"+scid).val("");  
            $("#5_"+scid).val("");
            $("#6_"+scid).val(""); 
            $("#7_"+scid).val("");
            $("#8_"+scid).val("");  
            $("#9_"+scid).val("");

            amount();
            net_amount();

            $("#pop_close").click();
        }
    });
}


function settings10(){
    $("#item_list .cl").click(function(){        
        $("#quotation").val($(this).children().eq(0).html());
        $("#pop_close4").click(); 
        $("#pop_close").click();                
    })    
}

function settings11(){
    $("#item_list .cl").click(function(){        

        load_foc_items($(this).children().eq(0).html(),$("#date").val());    
        $("#is_foc").val($(this).children().eq(0).html());  
        $("#pop_close").click();                
    })    
}

function settings9(){
    $("#item_list .cl").click(function(){        
        $("#customer").val($(this).children().eq(0).html());
        $("#customer_id").val($(this).children().eq(1).html());
        $("#address").val($(this).children().eq(3).html());
        $("#balance").val($(this).children().find('input').eq(1).val());
        $("#blocker").css("display","none");
        $("#btnSave").attr("disabled",true);
        $("#pop_close2").click();                
    })    
}

function settings8(){
    $("#item_list .cl").click(function(){        
        $("#sales_rep").val($(this).children().eq(0).html());
        $("#sales_rep2").val($(this).children().eq(1).html());
        $("#pop_close6").click();                
    })    
}

function check_qty(scid){
    var foc  = $("#4_"+scid).val();
    var qtyy = $("#5_"+scid).val();
    var qty  = parseInt($("#5_"+scid).val());
    var issue_qtys = parseInt($("#issue_qty_"+scid).val());
    var item = $("#0_"+scid).val();
    var focs = parseInt($("#4_"+scid).val());


    if(foc=="" && qtyy != ""){
        if(qty>issue_qtys){
            set_msg("this item ("+item+") quantity should be less than "+issue_qtys,"error");
            $("#5_"+scid).val(issue_qtys);
            return false;
        }

    }else if(foc!=""){
        if(focs>issue_qtys){
            set_msg("this item ("+item+") FOC quantity should be less than "+issue_qtys,"error");
            $("#4_"+scid).val(issue_qtys);
            return false;
        }
    }

    return true;
}

function check_item_exist(id){
    var v = true;
    var sccid= (scid-1);
    $("input[type='hidden']").each(function(e){
        if($(this).val() == id && $("#f_"+sccid).val()==''){
            v = false;
        }
    });    
    return v;
}

function check_item_exist_free(id){
    var v = true;
    var sccid= (scid-1);
    
    $("input[type='hidden']").each(function(){

        if($(this).val() == id && $("#f_"+sccid).val()==1){
            v = false;
        }
    });    
    return v;
    uncheck_free(scid,id);
}




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
    var g=[];

    empty_grid();
    loding();
    $.post("index.php/main/get_data/g_t_sales_sum/", {
        id: id,
    }, function(r){
        var fre =parseFloat(0);
        if(r=="2"){
         set_msg("No records");
     }else{
            //alert();
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
            $("#app_status").val(r.sum[0].is_approve);  

            $("#cus_address").val(r.sum[0].cus_address);
            $("#bill_cuss_name").val(r.sum[0].cus_name); 

            if(r.sum[0].is_multi_payment==1){
              $("#payment_option").attr("checked", "checked");
              $("#payment_option").css("display","none");
          }else{
              $("#payment_option").removeAttr("checked");
          }

          $("#hid").val(id);
          $("#id").val(id);    
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
          $("#groups").val(r.sum[0].group_no);
          $("#stores").val(r.sum[0].store);
          set_select("stores","store_id");
          $("#ref_no").val(r.sum[0].ref_no);
          $("#memo").val(r.sum[0].memo);
          $("#sales_rep").val(r.sum[0].rep);
          $("#salesp_id").val(r.sum[0].rep);
          $("#sales_rep2").val(r.sum[0].rep_name);

          $("#net").val(r.sum[0].net_amount);
          $("#sales_category").val(r.sum[0].category);
          $("#sub_no").val(r.sum[0].sub_no);

          $("#sales_category").prop("disabled", true);

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
        $("#cost_"+i).val(r.det[i].cost);
        $("#5_"+i).val(r.det[i].qty);
        $("#8_"+i).val(r.det[i].amount);
        $("#3_"+i).val(r.det[i].price);
        $("#f_"+i).val(r.det[i].free);
        if(r.det[i].free=='1'){
            $("#0_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width:100px; background-color: rgb(224, 228, 146) !important');
            $("#0_"+i).removeClass('g_col_fixed').attr('style', ' width: 100%; background-color: rgb(224, 228, 146) !important');

            $("#n_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 350px; background-color: rgb(224, 228, 146) !important');
            $("#n_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; margin:0;padding:0;width :100%; ');
            $("#3_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
            $("#3_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');
            $("#5_"+i).closest("td").removeClass('g_col_fixed').attr('style', 'width: 58px; background-color: rgb(224, 228, 146) !important');
            $("#5_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 80%; text-align:right;');
            $("#8_"+i).removeClass('g_col_fixed').attr('style', 'background-color: rgb(224, 228, 146) !important; width : 100%; text-align:right;');
            $("#8_"+i).closest("tr").attr('style', 'background-color: rgb(224, 228, 146) !important;');
            $("#free_"+i).attr('checked','checked');
        }
    }

    $("#btnSave").attr("disabled", false);


    if(r.sum[0].pay_cash!=0.00 && r.sum[0].pay_ccard==0.00 && r.sum[0].pay_receive_chq==0.00 && r.sum[0].pay_cnote==0.00){
        $("#cash").val(r.sum[0].pay_cash);
        $("#is_cash_bill").attr("checked","checked");
        $("#btnSave").attr("disabled",false);
        $("#showPayments").click();
        $("#opt_balance").val("");                
        $("#btnSave2").click();
    }else{
        $("#btnSave").attr("disabled",true);
    }

    if(r.sum[0].is_cancel==1){
        $("#btnDelete").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#showPayments").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
    }
    if(r.sum[0].is_approve==1){
        $("#btnDelete5").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#btnApprove").attr("disabled", "disabled");
        $("#showPayments").attr("disabled", "disabled");
        $("#btnDelete").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/approved1.png')");
    }  

} 
loding();           
}, "json");
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


/*    function load_data9(){
        $.post("index.php/main/load_data/g_t_sales_sum/customer_list", {
            search : $("#pop_search2").val() 
        }, function(r){
            $("#sr2").html("");
            $("#sr2").html(r);
            settings9();            
        }, "text");
    }*/


    function load_data9(){
        $.post("index.php/main/load_data/utility/f1_selection_list_customer", {
            data_tbl:"m_customer",
            field:"code",
            field2:"name",
            field3:"nic",
            field_address:"field_address",
            preview1:"Customer ID",
            preview2:"Customer Name",
            preview3:"Customer NIC",
            search : $("#pop_search2").val() 
        }, function(r){
            $("#sr2").html("");
            $("#sr2").html(r);
            settings9();            
        }, "text");
    }

    function load_data10(){
        $.post("index.php/main/load_data/g_t_sales_sum/load_b_foc", {
            date:$("#date").val(),
            search : $("#pop_search").val() 
        }, function(r){
            $("#sr").html(r);
            settings11();            
        }, "text");
    }


    function load_data8(){
        $.post("index.php/main/load_data/utility/f1_selection_list", {
            data_tbl:"m_employee",
            field:"code",
            field2:"name",
            preview2:"Employee Name",
            search : $("#pop_search6").val() 
        }, function(r){
            $("#sr6").html("");
            $("#sr6").html(r);
            settings8();            
        }, "text");
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
         $("#cost_"+i).val("");
         $("#free_price_"+i).val("");
         $("#issue_qty_"+i).val("");
         $("#subcode_"+i).val("");
         $("#bqty"+i).val("");
         $("#subcode_"+i).removeAttr("data-is_click");
         $("#item_min_price_"+i).val("");
         $("#btn_"+i).css("display","none"); 
         $("#btnb_"+i).css("display","none");
         $("#sub_"+i).css("display","none");

     }
     net_amount();
     $(".quns").css("display","none");
     $(".qunsb").css("display","none");
 }


 function amount(){
    if($("#5_"+scid).val()!="" && $("#3_"+scid).val()!="" && $("#f_"+scid).val()!="1"){
        var amount = parseFloat($("#3_"+scid).val()) * parseInt($("#5_"+scid).val());
        $("#8_"+scid).val(m_round(amount));
    }
}

function net_amount(){
    var net = parseFloat(0);
    for(var t=0; t<25; t++){
        if($("#8_"+t).val()!=""){
            net+=parseFloat($("#8_"+t).val())
        }
    }
    $("#net").val(m_round(net));
}



function select_search4(){
    $("#pop_search4").focus();
}

function check_is_group_store(){
    $.post("index.php/main/load_data/validation/check_is_group_store",{
        store:$("#stores").val(),
        batch_no:$("#1_"+scid).val(),
        code:$("#0_"+scid).val(),
        hid:$("#hid").val()
    },function(res){

        if(parseFloat(res)<0){
            res=0;
        }

        if(parseFloat(res) < parseFloat($("#5_"+scid).val())){
          $("#5_"+scid).val("");
          $("#5_"+scid).focus();
          set_msg("There is not enough quantity in this batch");
      }
  },"text");
}

function check_if_reached_to_minimum_price(){

    var confirmation=0;
    for(x=0;x<25;x++){
        if($("#0_"+x).val()!="" && $("#3_"+x).val()!=""){
            if($("#3_"+x).val()<$("#item_min_price_"+x).val()){
              confirmation=1;
          }
      }
  }



  if(confirmation==1){
    $("#btnSave").attr("disabled","disabled");
    $("#btnApprove").removeAttr("disabled");
}else{
    $("#btnApprove").attr("disabled","disabled");
        //$("#btnSave").removeAttr("disabled");
    }


}


function get_group(){
    $.post("index.php/main/load_data/r_groups/select_by_category", {
        category_id : $("#sales_category").val()
    }, function(r){
     $("#groups").html(r);
 }, "text");

}

function default_customer_details(){
   $.post("index.php/main/load_data/g_t_sales_sum/load_default_customer",{
    cluster : $("#cl").val(),
    branch : $("#bc").val()
}, function(r){
    if(r!=2)
    {
        $("#customer").val(r[0].def_customer);
        $("#customer_id").val(r[0].c_name);
        $("#stores").val(r[0].def_sales_store);
        set_select('stores','store_id');
        $("#sales_category").val(r[0].def_sales_category);
        $("#sales_category1").val(r[0].def_sales_category);
            //$("#groups").val(r[0].def_sales_group);  
        }       
    }, "json");     
}

function mark_as_free(i,item){
  if(check_item_exist_free($("#0_"+scid).val())){
      $("#free_"+scid).prop('disabled', false);
      $("#0_"+scid).closest("tr").find("td").css('background-color', 'rgb(224, 228, 146)');
      $("#0_"+scid).closest("tr").find("input").removeClass('g_col_fixed');
      $("#0_"+scid).closest("tr").find("input").css('background-color', 'rgb(224, 228, 146)');
      $("#0_"+scid).closest("tr").find("input[type='button']").css('background-color','');
      $("#issue_qty_"+i).val($("#5_"+i).val());
      $("#f_"+i).val("1"); 
      $("#free_"+i).attr('checked', true);
      $("#8_"+i).val("0.00"); 
      net_amount();
  }else{
    set_msg("Item "+$("#0_"+scid).val()+" is already added Free.");
}

}

function uncheck_free(scid,item){

  $("#0_"+scid).closest("tr").find("td").css('background-color', '#ffffff');
  $("#0_"+scid).closest("tr").find("input").css('background-color', '#ffffff');
  $("#0_"+scid).closest("tr").find("input[type='button']").css('background-color','');

  $("#n_"+scid).closest("td").css('background-color','#f9f9ec');
  $("#n_"+scid).css('background-color','#f9f9ec');

  $("#5_"+scid).closest("td").css('background-color','#f9f9ec');
  $("#5_"+scid).css('background-color','#f9f9ec');

  $("#3_"+scid).closest("td").css('background-color','#f9f9ec');
  $("#3_"+scid).css('background-color','#f9f9ec');

  $("#8_"+scid).closest("td").css('background-color','#f9f9ec');
  $("#8_"+scid).css('background-color','#f9f9ec');

  $("#f_"+scid).val("0");
  $("#free_"+scid).attr('checked', false);
  $("#4_"+scid).val("0"); 

  amount();
  net_amount();

}



