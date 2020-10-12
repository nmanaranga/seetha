$(document).ready(function(){

    $(".qunsb").css("display","none");

    $("#load_req_duplecate").css("display","none");
    $("#code").blur(function(){
        check_code();
    });
    
    $("#tabs").tabs();
     $("#tabs2").tabs();  
    $("#tgrid").tableScroll({height:355});

    $("#load_req").click(function(){
        if($("#supplier_id").val()!="" || $("#supplier").val()!=""){
		load_request_note();
        }else{
            set_msg("Please select supplier");
        }

    });

    $(".qunsb").click(function(){
        set_cid($(this).attr("id"));
        check_is_batch_item(scid);
      
    });

     $("#pop_search3").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
            load_items3($("#0_"+scid).val());
        }
    });

    
    $(".fo").keypress(function(e){
         set_cid($(this).attr("id"));
        if(e.keyCode=='46'){
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val("");
            $("#2_"+scid).val("");
            $("#3_"+scid).val("");
            $("#4_"+scid).val("");
            $("#5_"+scid).val("");
            $("#7_"+scid).val("");
            $("#8_"+scid).val("");

        }

    });

    $(".fo").keypress(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode==112){
        
         $("#pop_search").val($("#0_"+scid).val());
         load_items();
         $("#serch_pop").center();
         $("#blocker").css("display", "block");
         setTimeout("select_search()", 100);
    }
    });

    $("#btnDelete").click(function(){
       if($("#hid").val()!=0) {
            set_delete($("#hid").val());
       }
    });

    $("#btnPrint").click(function(){
    	if($("#hid").val()=="0")
    	{
    		set_msg("Please load data before print");
        	return false;
    	}
    	else{
        $("#print_pdf").submit();
    	}


    });

    $("#supplier_id").change(function(){
        empty_grid();
    });

    $("#customer").change(function(){
        set_select('customer','customer_id');
    });

    $("#customer").autocomplete('index.php/main/load_data/m_customer/auto_com', {
        width: 350,
        multiple: false,
        matchContains: true,
        formatItem: formatItems,
        formatResult: formatItemsResult
        });
    
        $("#customer").keypress(function(e){
            if(e.keyCode == 13){
               set_cus_values($(this));
            }
        });
    
        $("#customer").blur(function(){
            set_cus_values($(this));
        });


    //     $(".fo").keypress(function(e){  
    //     set_cid($(this).attr("id"));
   
    //        if(e.keyCode==112){
    //          $("#pop_search").val($("#0_"+scid).val());
    //             load_items();
    //             $("#serch_pop").center();
    //             $("#blocker").css("display", "block");
    //             setTimeout("select_search()", 100);
    //         }
      

    //     if(e.keyCode==13){
    //         $.post("/index.php/main/load_data/t_po_sum/get_item", {
    //             code:$("#0_"+scid).val()
    //         }, function(res){
    //             if(res.a!=2){
    //                 $("#0_"+scid).val(res.a[0].code);

    //                     if(check_item_exist($("#0_"+scid).val())){
    //                         $("#h_"+scid).val(res.a[0].code);
    //                         $("#n_"+scid).val(res.a[0].description);
    //                         $("#0_"+scid).val(res.a[0].code);
    //                         $("#2_"+scid).val(res.a[0].purchase_price);
                          
                           
    //                         $("#1_"+scid).focus();
    //                     }else{
    //                         set_msg("Item "+$("#0_"+scid).val()+" is already added.");
    //                     }

    //             }
    //         }, "json");

    //     }

    //     if(e.keyCode==46){

    //         $("#h_"+scid).val("");
    //         $("#0_"+scid).val("");
    //         $("#n_"+scid).val("");
    //         $("#1_"+scid).val(""); 
    //         $("#2_"+scid).val(""); 
    //         $("#3_"+scid).val(""); 
    //         $("#5_"+scid).val(""); 
    //         $("#5_"+scid).val("");
    //         $("#6_"+scid).val(""); 
    //         $("#7_"+scid).val(""); 
    //         $("#8_"+scid).val(""); 
    //         $("#9_"+scid).val(""); 

    //        $(".qty").focus();
            
    //     }


    // });

    // load_items();

       //  $("#pop_search").keyup(function(e){
       //      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
       //      }
       //  });
       // $("#pop_search").gselect(); 

       $(".qty, .price").blur(function(){

        set_cid($(this).attr("id"));
        tot_amount(scid);
      });

       $(".dis,.price").blur(function(){
            set_cid($(this).attr("id"));
            dis_prec();
            amount();
            gross_amount();
            //privilege_calculation();
            // all_rate_amount();
            net_amount();
        });


         $("#id").keypress(function(e){
            if(e.keyCode == 13){
                $(this).blur();
                load_data($(this).val());
                $("#load_req").css("display","none");
                $("#load_req_duplecate").css("display","inline");
            }
        });

        $("#cluster").change(function(){
            var clust=$("#cluster").val();
            $.post("index.php/main/select/login",{
                cluster:clust,

            },function(res){
               // alert(res.result);
               $("#branch").html(res.result);

            },"json"

            );     
        });

        $("#stores").change(function(){

           //alert($("#stores").attr("title"));


            set_select('stores','store_id');

        });
});

function load_items(){
    $.post("index.php/main/load_data/t_hp/item_list_all", {
        search : $("#pop_search").val(),
        stores : $("#store_from").val()
    }, function(r){
        $("#sr").html(r);
        settings1();
        
    }, "text");
}

function tot_amount(scid){

           var qty=parseFloat($("#7_"+scid).val());
           var price=parseFloat($("#2_"+scid).val());
         
           
           if(isNaN(qty)){ qty=0; }
           if(isNaN(price)){ price=0; }

           var amount=qty*price;
           if(amount==0){amount="";}else{
             $("#4_"+scid).val(m_round(amount));
           }
          

           var loop=total_amount=0; 

           $(".amount").each(function(){

           var get_amount=parseFloat($("#4_"+loop).val()); 
           if(isNaN(get_amount)){ get_amount=0;}
           total_amount=total_amount+get_amount;
           loop++;
           });



           $("#total2").val(m_round(total_amount));
}


 

    function formatItems(row){
        return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
    }

    function formatItemsResult(row){
        return row[0]+"|"+row[1];
    }


function save(){
    var frm = $('#form_');
    loding();
    $.ajax({
	type: frm.attr('method'),
	url: frm.attr('action'),
	data: frm.serialize(),
	success: function (pid){
            if(pid == 1){               
                location.href='';   
            }else if(pid == 2){
                set_msg("No permission to add data.");
            }else if(pid == 3){
                set_msg("No permission to edit data.");
            }else{
                set_msg(pid);
            }
            loding();            
        }
    });
}



function validate(){
    if($("#id").val()==""){
        set_msg("Please enter id");
        return false
    }else if($("#supplier_id").val()=="" || $("#supplier").val()==""){
        set_msg("Please enter supplier.");
        return false;
    }else{
        return true;
    }
}
    
function set_delete(id){
    if(confirm("Are you sure to delete transaction no "+id+"?")){
        loding();
        $.post("index.php/main/delete/t_internal_transfer_order", {
            id : id
        }, function(res){
            if(res == 1){
                location.href="";
            }else if(res == 2){
                set_msg("No permission to delete data.");
            }else{
                set_msg(res);
            }
            loding();
        }, "text");
    }
}

function is_edit($mod)
{
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
    $.post("index.php/main/get_data/t_internal_transfer_order", {
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




// function check_item_exist(id){
//     var v = true;
//     $("input[type='hidden']").each(function(){
//         if($(this).val() == id){
//             v = false;
//         }
//     });
    
//     return v;
// }

function settings1(){
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
                $("#2_"+scid).val($(this).children().eq(3).html());

                
                //$("#2_"+scid).focus();
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
            //$("#4_"+scid).focus();
            set_total();$("#pop_close").click();
            $("#4_"+scid).focus();
        }
      //}
    });

    
}

function load_request_note(){
    empty_grid();
     $.post("index.php/main/load_data/t_po_sum/load_request_note",{
                supplier:$("#supplier_id").val()
              },function(r){
              	if(r.det!=2){
                	
                     for(var i=0; i<r.det.length;i++){
                        $("#h_"+i).val(r.det[i].item);
                        $("#0_"+i).val(r.det[i].item);
                        $("#n_"+i).val(r.det[i].description);
                        $("#1_"+i).val(r.det[i].model);
                        $("#2_"+i).val(r.det[i].cur_qty);
                        $("#3_"+i).val(r.det[i].approve_qty);
                        $("#4_"+i).val(r.det[i].purchase_price);
                        $("#5_"+i).val(r.det[i].total);                
                    }
                     $(".price").blur();
                }else{
                    set_msg("No Data");
                }
  },"json");
}


function load_data(id){
    empty_grid();
    loding();
    $.post("index.php/main/get_data/t_internal_transfer_order/", {
        id: id
    }, function(r){

            if(r=="2"){
               set_msg("No records");
            }else{
                if(r.sum[0].is_cancel=="1"){
                    
                    $("#mframe").css("background-image", "url('img/cancel.png')");
                    $("#btnSave").attr('disabled','disabled');
                    $("#btnDelete").attr('disabled','disabled');
                    
                }
                


            $("#hid").val(id);   
            $("#id").val(id); 
            $("#qno").val(id);
            // var cluster = "<option>" + r.sum[0].description +"-"+r.sum[0].cl + "</option>";
            $("#cluster").val(r.sum[0].cl);
            var branch = "<option>" + r.sum[0].name+"-"+r.sum[0].bc + "</option>";
            $("#branch").append(branch);
            $("#note").val(r.sum[0].note);
            $("#date").val(r.sum[0].ddate); 
            $("#ref_no").val(r.sum[0].ref_no);
            $("#total2").val(r.sum[0].total_amount);
           

            for(var i=0; i<r.det.length;i++){
                $("#6_"+i).val(r.det[i].nno);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                $("#1_"+i).val(r.det[i].model);
                $("#7_"+i).val(r.det[i].item_cost);
                $("#8_"+i).val(r.det[i].min_price);
                $("#2_"+i).val(r.det[i].max_price);
                $("#3_"+i).val(r.det[i].qty);
                $("#4_"+i).val(r.det[i].quantity);
                tot_amount(i);
            }

          
            }

            loding();
        }, "json");

  
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
        $("#5_"+i).val("");
        $("#4_"+i).val("");
        $("#6_"+i).val("");
        $("#7_"+i).val("");
        $("#8_"+i).val("");
        $("#9_"+i).val("");
        
    }

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

function check_is_batch_item2(x,scid){


        var store=$("#stores").val();
        $.post("index.php/main/load_data/t_hp/is_batch_item",{
            code:x,
            store:store
         },function(res){
            
            $("#btnb_"+scid).css("display","none");
           if(res==1){
            $("#btnb_"+scid).css("display","block");
            }
        },'text');
}


function check_is_batch_item(scid){


        var store=$("#stores").val();
        $.post("index.php/main/load_data/t_hp/is_batch_item",{
            code:$("#0_"+scid).val(),
            store:store
         },function(res){
            
           if(res==1){
            $("#serch_pop3").center();
            $("#blocker3").css("display", "block");
            setTimeout("select_search3()", 100);
            load_items3($("#0_"+scid).val());
            } else if(res=='0'){
                $("#6_"+scid).val("0");
                $("#6_"+scid).attr("readonly","readonly");
            } else {
                $("#6_"+scid).val(res.split("-")[0]);
                $("#7_"+scid).val(res.split("-")[1]);
                //$("#bqty_"+scid).val(res.split("-")[1]);
                $("#1_"+scid).attr("readonly","readonly");
           }
        },'text');
}

function dis_prec(){

    
   
   var qty=parseFloat($("#7_"+scid).val());
   var price=parseFloat($("#2_"+scid).val());
   var discount=parseFloat($("#3_"+scid).val());
   var dis_pre=0;
   

   if(isNaN(qty)){qty=0;}
   if(isNaN(price)){price=0;}
   if(isNaN(discount)){discount=0;}

   if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    
    dis_pre=(discount*100)/price;
    //dis_pre=(discount*100)/(qty*price); //discount pre for all item

        if(isNaN(dis_pre) || !isFinite(dis_pre)){    
            $("#9_"+scid).val("");
        }else{
            $("#9_"+scid).val(m_round(dis_pre));
        }
    }

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

    var qty=parseFloat($("#7_"+scid).val());
    var price=parseFloat($("#2_"+scid).val());
    var foc=parseFloat($("#8_"+scid).val());
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
    var discount=parseFloat($("#3_"+scid).val());
    var dis_pre=0;
    if(isNaN(discount)){discount=0;}

    if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount) && !isNaN(foc)){

        //amount=(qty+foc)*price;
        amount=(qty)*price;
        total_dis=(qty)*discount;
        amount=amount-total_dis;
        dis_pre=(discount*100)/price;

        if(isNaN(dis_pre) || !isFinite(dis_pre)){
            $("#9_"+scid).val("");
        }else{
            $("#9_"+scid).val(m_round(dis_pre));
        }
        $("#tot_dis_"+scid).val(m_round(total_dis));
        $("#4_"+scid).val(m_round(amount)); 

    }else if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
        amount=(qty*price)-discount;
        if(amount!=0){
           $("#4_"+scid).val(m_round(amount)); 
        }else{
           $("#4_"+scid).val(""); 
        }
   
    }else if(!isNaN(qty)&& !isNaN(price)){
        amount=(qty*price);
        if(amount!=0){
           $("#4_"+scid).val(m_round(amount)); 
        }else{
           $("#4_"+scid).val(""); 
        }
    }
}


function gross_amount(){
    var gross=loop=0;

    $(".amount").each(function(){
        var gs=parseFloat($("#4_"+loop).val());
        if(!isNaN(gs)){    
        gross=gross+gs;
        }    
        loop++;
    });
    $("#gross").val(m_round(gross));
}

function net_amount(){
   
    var gross_amount=parseFloat($("#gross").val());
    var net_amount=additional=loop=0;
    $(".foo").each(function(){
        var add=parseFloat($("#tt_"+loop).val());
        var f= $("#hh_"+loop).val();

        if(!isNaN(add)){
        if(f==1){
            additional=additional+add;
            }else{
            additional=additional-add;    
        }
    }    
        loop++;
    });

    if(!isNaN(additional)&& !isNaN(gross_amount)){
        net_amount=gross_amount+additional;
        $("#net").val(m_round(net_amount));
    }else{
        $("#net").val(net_amount);
    }

    var discount=0;
    // $(".dis").each(function(e){
    //     if(!isNaN(parseFloat($("#7_"+e).val()))){
    //         discount=discount+parseFloat($("#7_"+e).val());
    //     }
    // });

    $(".tot_discount").each(function(e){
        if(!isNaN(parseFloat($("#tot_dis_"+e).val()))){
          discount=discount+parseFloat($("#tot_dis_"+e).val());
        }
    });

    $("#total_discount").val(m_round(discount));
    $("#total_amount").val(m_round(gross_amount+discount));

}

function load_items3(x){
    $.post("index.php/main/load_data/t_cash_sales_sum/batch_item", {
        search : x,
        stores : $("#stores").val()
    }, function(r){
        $("#sr3").html(r);
        settings3();
    }, "text");
}

function settings3(){
    $("#batch_item_list .cl").click(function(){
        if($(this).children().eq(0).html() != "&nbsp;"){
            if(check_item_exist3($(this).children().eq(0).html())){
                $("#6_"+scid).val($(this).children().eq(0).html());
                //$("#5_"+scid).val($(this).children().eq(1).html());
                $("#bqty_"+scid).val($(this).children().eq(1).html());
                $("#2_"+scid).val($(this).children().eq(2).html());
                $("#6_"+scid).attr("readonly","readonly");
                $("#7_"+scid).focus();
                //discount();
                dis_prec();
                amount();
                gross_amount();
                all_rate_amount();
                net_amount();
                $("#pop_close3").click();
            }else{
                set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
            }
        }else{
            $("#6_"+scid).val("");
            $("#7_"+scid).val("");
            $("#2_"+scid).val("");
                //discount();
                dis_prec();
                amount();
        
                gross_amount();
                privilege_calculation();
                all_rate_amount();
                net_amount();
            $("#pop_close3").click();
        }
    });
}

function select_search3(){
    $("#pop_search3").focus();
}

function check_item_exist3(id){
    var v = true;
    return v;
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

function set_cus_values(f){

    var v = f.val();
    v = v.split("|");

    if(v.length == 2){
   
        f.val(v[0]);
        $("#customer_id").val(v[1]);
     
       
    }
}


