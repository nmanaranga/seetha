  var serial_items=[];
  var get_id;
  var serialWind;
  var current_serial_no;
  var sub_items=[];

$(document).ready(function(){

    var p_code = window.location.search.split('=')[2];

    if(p_code != undefined){
      load_data(p_code);
    }

  $(".quns").css("display","none");
  $(".subs").css("display","none");
  $("#btnSavee").css("display","none");
  $("#btnapprove").attr("disabled", "disabled");

	//$("#tgrid").tableScroll({height:50,width:1100});

  $("#btnapprove").click(function() {
   $("#approve").val("2");
   save();
  });
  
  $(document).on("click",".subs",function(){
    set_cid($(this).attr("id"));
    check_is_sub_item(scid); 
    $("#is_click_"+scid).val("1");    
  });	

  $("#inv_no").keyup(function(){
      this.value = this.value.replace(/[^0-9\.-_a-z,',A-Z]/g,'');
  });
    
	$("#tgrid1").tableScroll({height:200, width:895});

  $("#customer").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search4").val($("#customer").val());
      load_customer();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);   
    }
    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_customer();
      }
    });
    if(e.keyCode==46){
       $("#customer").val("");
       $("#customer_id").val("");
    }  
   });

  $("#sales_rep").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search6").val($("#sales_rep").val());
      load_emp();
      $("#serch_pop6").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search6').focus()", 100);   
    }
    $("#pop_search6").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_emp();
      }
    });
    if(e.keyCode==46){
       $("#sales_rep").val("");
       $("#sales_rep2").val("");
    }  
  });

  $("#stores").keydown(function(e){ 
    if(e.keyCode==112){
      $("#pop_search12").val($("#stores").val());
      load_stores();
      $("#serch_pop12").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search12').focus()", 100);   
    }
    $("#pop_search12").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
        load_stores();
      }
    });
    if(e.keyCode==46){
       $("#stores").val("");
       $("#stores_des").val("");
    }  
  });

  $("#btnApprove").click(function(){
    $("#app_status").val("2");
    if(validate()){
      save();    
    }
  });

	$("#btnPrint").click(function(){
	 	if($("#hid").val()=="0"){
	 		set_msg ("Please load data before print");
	 		return false;
	 	}else{
      $("#print_pdf").submit();
    }
  });

  $("#btnDelete5").click(function(){
    set_delete();
  });
    
  $("#tgrid").tableScroll({height:355});
  
  $("#stores").change(function(){
    set_select('stores','store_id');
  });
   
  $("#btnExit1").click(function(){
    document.getElementById('light').style.display='none';
    document.getElementById('fade').style.display='none';  
    $("#1_"+get_id).focus();
  });

  $("#free_fix,#pst").blur(function(){
    var get_code=$(this).val();
    $(this).val(get_code.toUpperCase());
  });

  $("#id").keydown(function(e){
    if(e.keyCode == 13){
      $(this).blur();
      load_data($(this).val());
    }
  });


  $(".price, .qty, .dis_pre").blur(function(){
    set_cid($(this).attr("id"));
    dis_prec();
    amount();
    gross_amount();
    total_discount();
    net_amount();
  });
 
  $(".dis").blur(function(){
    set_cid($(this).attr("id"));
    dis_prec();
    amount();
    gross_amount();
    total_discount();
    net_amount();
  });

  $(".return_reason").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode == 112){
        $("#pop_search11").val($("6_"+scid).val());
        load_data_reason(scid);
        $("#serch_pop11").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search11').focus()", 100);
    }

    $("#pop_search11").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
             load_data_reason(scid);
        }
    }); 
    if(e.keyCode == 46){
        $("#6_"+scid).val(""); 
        $("#ret_"+scid).val(""); 
    }
  });

  $(".fo").keydown(function(e){  
    set_cid($(this).attr("id"));
    if($("#stores").val()!=""){
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
    }else{
      set_msg("please select store");
    }
  
    if(e.keyCode==46){
      if($("#df_is_serial").val()=='1'){
        $("#all_serial_"+scid).val("");
      }
      $("#h_"+scid).val("");
      $("#0_"+scid).val("");
      $("#n_"+scid).val("");
      $("#mo_"+scid).val(""); 
      $("#cost_"+scid).val(""); 
      $("#min_"+scid).val(""); 
      $("#1_"+scid).val(""); 
      $("#2_"+scid).val(""); 
      $("#3_"+scid).val(""); 
      $("#4_"+scid).val(""); 
      $("#rmax_"+scid).val(""); 
      $("#5_"+scid).val("");
      $("#21h_"+scid).val("");
      $("#6_"+scid).val("");
      $("#ret_"+scid).val("");
      $("#subcode_"+scid).val(""); 
      $("#7_"+scid).val(""); 
      $("#8_"+scid).val(""); 
      $("#9_"+scid).val(""); 
      $("#subcode_"+scid).removeAttr("data-is_click");
      $("#btn_"+scid).css("display","none");
      $("#sub_"+scid).css("display","none");
      dis_prec();
      amount();
      gross_amount();
      total_discount();
      net_amount(); 
    }
  });

  $("#pop_search").gselect(); 

  $(".qty").blur(function(){
      is_sub_item(scid);
  });

  $(".del_item").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode==46){
      if($("#df_is_serial").val()=='1'){
        $("#all_serial_"+scid).val("");
      }
      $("#h_"+scid).val("");
      $("#0_"+scid).val("");
      $("#n_"+scid).val("");
      $("#mo_"+scid).val(""); 
      $("#cost_"+scid).val(""); 
      $("#min_"+scid).val(""); 
      $("#1_"+scid).val(""); 
      $("#2_"+scid).val(""); 
      $("#3_"+scid).val(""); 
      $("#4_"+scid).val(""); 
      $("#rmax_"+scid).val(""); 
      $("#5_"+scid).val("");
      $("#21h_"+scid).val("");
      $("#6_"+scid).val("");
      $("#ret_"+scid).val("");
      $("#subcode_"+scid).val(""); 
      $("#7_"+scid).val(""); 
      $("#8_"+scid).val(""); 
      $("#9_"+scid).val("");
      $("#subcode_"+i).removeAttr("data-is_click"); 
    }
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

function check_is_sub_item(scid){        
    var store=$("#stores").val();
    $.post("index.php/main/load_data/utility/is_sub_item",{
        code:$("#0_"+scid).val(),          
    },function(res){        
       if(res==1)
        {
            $("#serch_pop3").center();
            $("#blocker3").css("display", "block");
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


function load_emp(){
  $.post("index.php/main/load_data/utility/f1_selection_list_emp", {
      filter_emp_cat:"salesman",
      search : $("#pop_search6").val() 
  }, function(r){
      $("#sr6").html(r);
      settings_emp();      
 }, "text");
}

function settings_emp(){
    $("#item_list .cl").click(function(){        
        $("#sales_rep").val($(this).children().eq(0).html());
        $("#sales_rep2").val($(this).children().eq(1).html());
        $("#pop_close6").click();                
    })    
}

function load_customer(){
  $.post("index.php/main/load_data/t_cash_sales_sum/customer_list", {
    search : $("#pop_search4").val() 
  }, function(r){
      $("#sr4").html(r);
      settings_cus();            
  }, "text");
}

function settings_cus(){
    $("#item_list .cl").click(function(){        
        $("#customer").val($(this).children().eq(0).html());
        $("#customer_id").val($(this).children().eq(1).html());
        $("#pop_close4").click();                
    })    
}

function load_stores(){
  $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"m_stores",
      field:"code",
      field2:"description",
      preview2:"Store Name",
      add_query: " AND cl='"+$("#cll").val()+"' AND bc='"+$("#bcc").val()+"'",
      search : $("#pop_search12").val() 
  }, function(r){
      $("#sr12").html(r);
      settings_stores();      
 }, "text");
}

function settings_stores(){
    $("#item_list .cl").click(function(){        
        $("#stores").val($(this).children().eq(0).html());
        $("#stores_des").val($(this).children().eq(1).html());
        $("#pop_close12").click();                
    })    
}


function is_sub_item(x){
  $.post("index.php/main/load_data/utility/is_sub_items_load", {
        code:$("#0_"+x).val(),
       hid:$("#hid").val(),
       type:'88'
      }, function(r){
        if(r!=2){
          sub_items=[];
          var a = "";
          for(var i=0; i<r.sub.length;i++){
            a=a+r.sub[i].sub_item+"-"+r.sub[i].qty+",";
          }  
          $("#subcode_"+x).val(a);
        }
      },"json");
}

function set_cus_values5(a) {
  var g = $(a).attr("id").split("_")[1];
  var b = a.val();
  b = b.split("-");
  if(2 == b.length){
      a.val(b[1]);
      $("#ret_"+g).val(b[0]);
  }
}


function select_search(){
  $("#pop_search").focus();
}

function load_items(){
  $.post("index.php/main/load_data/t_sales_return_sum_without_invoice/item_list_all", {
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
  $("#mo_"+scid).val(""); 
  $("#cost_"+scid).val(""); 
  $("#min_"+scid).val(""); 
  $("#1_"+scid).val(""); 
  $("#2_"+scid).val(""); 
  $("#3_"+scid).val(""); 
  $("#4_"+scid).val(""); 
  $("#rmax_"+scid).val(""); 
  $("#5_"+scid).val("");
  $("#21h_"+scid).val("");
  $("#6_"+scid).val("");
  $("#ret_"+scid).val("");
  $("#subcode_"+scid).val(""); 
  $("#7_"+scid).val(""); 
  $("#8_"+scid).val(""); 
  $("#9_"+scid).val(""); 
  $("#subcode_"+scid).removeAttr("data-is_click");
  $("#btn_"+scid).css("display","none");
  $("#sub_"+scid).css("display","none");
  dis_prec();
  amount();
  gross_amount();
  total_discount();
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
        check_is_sub_item2($(this).children().eq(0).html(),scid);

        $("#h_"+scid).val($(this).children().eq(0).html());
        $("#0_"+scid).val($(this).children().eq(0).html());
        $("#n_"+scid).val($(this).children().eq(1).html());
        $("#2_"+scid).val($(this).children().eq(2).html());
        $("#mo_"+scid).val($(this).children().eq(3).html());
        $("#cost_"+scid).val($(this).children().eq(4).html());
        $("#min_"+scid).val($(this).children().eq(5).html());
                
        if($(this).children().eq(4).html() == 1){
          $("#1_"+scid).autoNumeric({mDec:2});
        }else{
          $("#1_"+scid).autoNumeric({mDec:0});
        }
        $("#1_"+scid).removeAttr("disabled"); $("#2_"+scid).removeAttr("disabled"); $("#3_"+scid).removeAttr("disabled");
        $("#1_"+scid).focus();
        $("#pop_close").click();
        is_sub_item(scid);
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
      $("#pop_close").click();
    }
  });
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

function check_item_exist(id){
    var v = true;
    $("input[type='hidden']").each(function(){
        if($(this).val() == id){
            v = false;
        }
    });
    return v;
}



  function set_cus_values(f){
            var v = f.val();
            v = v.split("-");
            
                if(v.length == 2){
                f.val(v[0]);
                $("#customer_id").val(v[1]);
               // $("#customer_id").attr("class", "input_txt_f");
        }
  }


function save(){
  if($("#df_is_serial").val()=='1'){
    serial_items.sort();
    $("#srls").attr("title",serial_items);
    $("#srls").val(serial_items);
  }
    $("#dt").val($("#date").val());
    $("#qno").val($("#id").val());
    $("#cus_id").val($("#customer").val());
    $("#salesp_id").val($("#sales_rep").val());
    
    var frm = $('#form_');
    loding();
    $.ajax({
  	type: frm.attr('method'),
  	url: frm.attr('action'),
  	data: frm.serialize(),
  	success: function (pid){
              if(pid==1){
                $("#btnSave").attr("disabled",true);
                if(confirm("Save Completed, Do You Want A print?")){
                  if($("#is_prnt").val()==1){
                    $("#print_pdf").submit();
                  }
                  reload_form();
                }else{
                  location.href="";
                }
              }else{
                set_msg(pid,'error');
              }
            loding();
          }
      });
}


function reload_form(){
  setTimeout(function(){
    location.href = '';
  },100); 
}


function validate(){

  var v = false;
  var z = true;


  $(".return_reason").each(function(e){
    if($("#6_"+e).val()=="" && $("#0_"+e).val()!=""){  
      z= false;
    }
  });

  if( $("#customer").val() == "" || $("#customer_id").val() == "" || $("#customer_id").val() == $("#customer_id").attr("title") ){
    set_msg("Please enter customer.","error");
    return false;
  }else if($("#sales_rep").val() == ""){
    set_msg("Please enter sales rep.","error");
    return false;
  }else if($("#stores").val() == ""){
    set_msg("Please select store.","error");
    return false;
  }else if(z==false){
    set_msg("Please enter return reason","error");
  }else{
    return true;
}
}


function discount(){
    var qty=parseFloat($("#1_"+scid).val());
    var price=parseFloat($("#2_"+scid).val());
    var dis_pre=parseFloat($("#3_"+scid).val());
    var discount="";
   if(isNaN(qty)){qty=0;}
   if(isNaN(price)){price=0;}
   if(isNaN(dis_pre)){dis_pre=0;}

    if(!isNaN(qty)&& !isNaN(price) && !isNaN(dis_pre)){
    discount=(qty*price*dis_pre)/100;

       if(discount!=0){
            $("#4_"+scid).val(m_round(discount));
        }else{
            $("#4_"+scid).val("");
        }
    }
    
}

function dis_prec(){
    var qty=parseFloat($("#1_"+scid).val());
    var price=parseFloat($("#2_"+scid).val());
    var discount=parseFloat($("#4_"+scid).val());

    var dis_pre="";

   if(isNaN(qty)){qty=0;}
   if(isNaN(price)){price=0;}
   if(isNaN(discount)){discount=0;}

   if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    dis_pre=(discount*100)/(qty*price);
        if(isNaN(dis_pre) || !isFinite(dis_pre)){ 
            $("#3_"+scid).val("");
         }else{
            $("#3_"+scid).val(m_round(dis_pre));
         }
    }

}

function amount(){
 
    var qty=parseFloat($("#1_"+scid).val());
    var price=parseFloat($("#2_"+scid).val());
    var discount=parseFloat($("#4_"+scid).val()) * parseFloat($("#1_"+scid).val());
    var amount="";

   if(isNaN(qty)){qty=0;}
   if(isNaN(price)){price=0;}
   if(isNaN(discount)){discount=0;}

   //alert(discount);


    if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    amount=qty*price;
    amount=amount-discount;

        if(amount!=0){
           $("#5_"+scid).val(m_round(amount)); 
        }else{
           $("#5_"+scid).val(""); 
        }
   
    }else if(!isNaN(qty)&& !isNaN(price)){
    amount=(qty*price);
        
        if(amount!=0){
           $("#5_"+scid).val(m_round(amount)); 
        }else{
           $("#5_"+scid).val(""); 
        }
    }
}
    

function gross_amount(){
    var gross=loop=0;
    $(".amount").each(function(){
        var gs=parseFloat($("#5_"+loop).val());
        var dis=parseFloat($("#4_"+loop).val());
        if(!isNaN(gs)){   
        //alert($("#is_free_0").val()); 
          if($("#is_free_"+loop).val()=="0" || $("#is_free_"+loop).val()==""){
            gross=gross+gs+dis;
          }
        
        }    
        loop++;
    });
    $("#total2").val(m_round(gross));
}    


function total_discount(){
    var discount=loop=0;
    $(".dis").each(function(){
        var gs=parseFloat($("#4_"+loop).val()) * parseFloat($("#1_"+loop).val());
        if(!isNaN(gs)){   
        discount=discount+gs;
        }    
        loop++;
    });
    $("#discount").val(m_round(discount));
}


function net_amount(){
    var gross_amount=parseFloat(0);
    var net_amount=parseFloat(0);

    $(".fo").each(function(e){
      if($("#2_"+e).val()!="" && $("#5_"+e).val()!=""){
        gross_amount+=parseFloat($("#2_"+e).val()) * parseFloat($("#1_"+e).val());
        net_amount+=parseFloat($("#5_"+e).val());
      }
    });
    $("#total2").val(m_round(gross_amount))
    $("#net").val(m_round(net_amount));
}


function load_data(id){
    var g=[];
    empty_grid();
    loding();
    $.post("index.php/main/get_data/t_sales_return_sum_without_invoice/", {
        id: id
    }, function(r){
            if(r=="2"){
               set_msg("No records");
            }else{
              if(r.sum[0].return_type ==2){
                $("#hid").val(id);   
                $("#id").val(id); 
                $("#customer_id").val(r.sum[0].customer_name);
                $("#customer").val(r.sum[0].cus_id);
                $("#cus_id").val(r.sum[0].cus_id);
                $("#types").val(r.sum[0].sales_type);
                $("#inv_no").val(r.sum[0].inv_no);
                $("#crn_no").val(r.sum[0].crn_no);
                $("#sales_rep").val(r.sum[0].rep);
                $("#salesp_id").val(r.sum[0].rep);
                $("#sales_rep2").val(r.sum[0].rep_name);
                $("#date").val(r.sum[0].ddate); 
                $("#ref_no").val(r.sum[0].ref_no);gross_amount
                $("#total2").val(r.sum[0].gross_amount);
                $("#discount").val(r.sum[0].discount); 
                $("#net").val(r.sum[0].net_amount);
                $("#memo").val(r.sum[0].memo);
                $("#stores").val(r.sum[0].store);
                $("#stores_des").val(r.sum[0].store_name);
                
                $("#dt").val(r.sum[0].ddate);
                $("#inv_no").attr("readonly", "readonly");
                set_select("stores","store_id");
                for(var i=0; i<r.det.length;i++){
                  $("#itemcode_"+i).val(r.det[i].code);

                  if($("#df_is_serial").val()=='1'){
                    check_is_serial_item2(r.det[i].code,i);
                    $("#numofserial_"+i).val(r.det[i].qty);
                    for(var a=0;a<r.serial.length;a++){
                      if(r.det[i].code==r.serial[a].item){
                        g.push(r.serial[a].serial_no);
                        $("#all_serial_"+i).val(g);
                      }   
                    }
                    g=[];                 
                  }
                  check_is_sub_item2(r.det[i].code,i);

                  $("#h_"+i).val(r.det[i].code);
                  $("#0_"+i).val(r.det[i].code);
                  $("#n_"+i).val(r.det[i].item_des);
                  $("#1_"+i).val(r.det[i].qty);
                  $("#2_"+i).val(r.det[i].price);
                  $("#cost_"+i).val(r.det[i].cost);
                  $("#min_"+i).val(r.det[i].min_price);
                  $("#mo_"+i).val(r.det[i].model);                
                  $("#3_"+i).val(r.det[i].discountp);
                  $("#4_"+i).val(r.det[i].discount);
                  $("#5_"+i).val(r.det[i].amount);
                  $("#6_"+i).val(r.det[i].description);
                  $("#ret_"+i).val(r.det[i].reason);
                  $("#is_free_"+i).val(r.det[i].is_free);
                  $("#qno").val(id);
                  is_sub_item(i);
              }

            $("#btnapprove").attr("disabled", false);  
            if(r.sum[0].is_approve==1){
              $("#btnDelete5").attr("disabled", "disabled");
              $("#btnSave").attr("disabled", "disabled");
              $("#btnapprove").attr("disabled", true);
              if(r.sum[0].is_cancel!=1){
                $("#mframe").css("background-image", "url('img/approved1.png')");
              }
            }  
            if(r.sum[0].is_cancel==1){
              $("#btnDelete5").attr("disabled", "disabled");
              $("#btnSave").attr("disabled", "disabled");
              $("#mframe").css("background-image", "url('img/cancel.png')");
            }
          }else{
            if(confirm("This Sales Return Based On Sales Invoice, Do You Want Load In Sales Return With Invoice Form? ")){
              location.href="?action=t_sales_return_sum&xxx="+r.sum[0].nno+"";
            }else{
              location.href="?action=t_sales_return_sum_without_invoice";
            }
          }
        }
        loding();            
      }, "json");
  }

  function empty_grid(){
    for(var i=0; i<25; i++){
      $("#h_"+i).val("");
      $("#0_"+i).val("");
      $("#n_"+i).val("");
      $("#mo_"+i).val(""); 
      $("#cost_"+i).val(""); 
      $("#min_"+i).val(""); 
      $("#1_"+i).val(""); 
      $("#2_"+i).val(""); 
      $("#3_"+i).val(""); 
      $("#4_"+i).val(""); 
      $("#rmax_"+i).val(""); 
      $("#5_"+i).val("");
      $("#21h_"+i).val("");
      $("#6_"+i).val("");
      $("#ret_"+i).val("");
      $("#subcode_"+i).val(""); 
      $("#7_"+i).val(""); 
      $("#8_"+i).val(""); 
      $("#9_"+i).val("");
      $("#subcode_"+i).removeAttr("data-is_click"); 
      $("#btn_"+i).css("display","none");    
    }
      $(".quns").css("display","none");
      $(".subs").css("display","none");
    }

function empty_grid2(){
  for(var i=0; i<25; i++){
    $("#h1_"+i).val(0);
    $("#01_"+i).val("");
    $("#n1_"+i).val("");
   
    $("#rq_"+i).val("");
    $("#bt1_"+i).val("");
    $("#mo_"+i).val("");
    $("#cost_"+i).val(""); 
    $("#min_"+i).val(""); 
    $("#11_"+i).val("");
    $("#21_"+i).val("");
    $("#31_"+i).val("");
    $("#41_"+i).val("");
    $("#51_"+i).val("");
  }
  if($("#customer").val() == ""){
    $("#customer").val("");
    $("#customer_id").val("");
  }
  $("#sales_rep").val("");
  $("#sales_rep2").val("");
  $("#total2").val("");
  $("#discount").val("");
  $("#net").val("");
}



  function insertSerial(x){   
    serial_window(x);
  }

 function load_data_reason(scid){
      $.post("index.php/main/load_data/utility/f1_selection_list", {
          data_tbl:"r_return_reason",
          field:"code",
          field2:"description",
          preview2:"Return Reason Name",
          add_query: " AND type='2'",
          search : $("#pop_search11").val() 
      }, 
      function(r){ 
          $("#sr11").html(r);
          settings_reasonf1(scid);            
      }, "text");
  }

   function settings_reasonf1(scid){
      $("#item_list .cl").click(function(){     
          $("#ret_"+scid).val($(this).children().eq(0).html());
          $("#6_"+scid).val($(this).children().eq(1).html());
          //alert(scid);
          $("#pop_close11").click();                
      })    
  }

function serial_window(x){ //if remove
       var item_code=$("#item_code").val();
       var count=$("#qty").val();
       var serial=x; 
       serial_items.push($("#item_code").val()+"-"+serial);// if remove
       document.getElementById('light').style.display='none';
       document.getElementById('fade').style.display='none';
       $("#btnExit1").removeAttr("disabled");

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

function set_delete(){
    var id = $("#hid").val();
    if(id != 0){
        if(confirm("Are you sure to delete this sales return ["+$("#hid").val()+"]? ")){
            $.post("index.php/main/delete/t_sales_return_sum_without_invoice", {
            trans_no:id,
            inv_no:$("#inv_no").val(),
            type:$("#types").val()
        },function(r){
          if(r != 1){
            set_msg(r);
          }else{
            delete_msg();
          }
        }, "text");
    }
  }else{
    set_msg("Please load record");
  }
}

