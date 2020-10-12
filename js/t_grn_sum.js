var sub_items =[];
var is_click=0;
$(document).ready(function(){

  $(".subs").css("display","none");
  $("#btnSavee").css("display","none");

  $("#prnt_srl").click(function(){
    if($("#prnt_srl").is(":checked")){
      $("#prnt_srl_p").val("1");
    }else{
      $("#prnt_srl_p").val("0");
    }
  });

  
  $("#is_po").click(function(){
   if($("#is_po").attr("checked")=="checked"){
    $("#po_update").val("1");
    $("#pono").attr("readonly","readonly");
    $("#pono").val("");
  }else{
    $("#po_update").val("0");
    $("#pono").removeAttr("readonly");
  }

});

  $(".fo").dblclick(function(){
    set_cid($(this).attr("id"));  
    if($(this).val()!=""){
      $.post("index.php/main/load_data/utility/get_sub_item_detail3", {
        code:$(this).val(),
        store:$("#stores").val(),
        po:$("#pono").val(),
        qty:$("#2_"+scid).val(),
        date:$("#date").val()
      }, function(res){
        if(res!=0){
          $("#msg_box_inner").html(res);
          $("#msg_box").slideDown();
        }
      },"text");
    } 
  });

  $(document).on('click','#load_qty',function(){
    $.post("index.php/main/load_data/utility/previous_qty", {
      avg_from:$("#avg_from").val(),
      avg_to:$("#avg_to").val(), 
      item:$("#0_"+scid).val(),             
    }, function(res){
      $("#grn_qty").val(res.grn);
      $("#sale_qty").val(res.sales);

    },"json");    
  });


  $(".ad_cst").click(function(){
    var t = parseFloat(0);

    $(".ad_cst").each(function(x){
      if($("#hh_"+x).val()=="1"){
        if($(this).is(":checked")){
          t+=parseFloat($("#22_"+x).val());  
        }   
      }
    });
    $("#tot_add_cost").val(m_round(t));

    for(var i=0; i<25; i++){
      if($("#0_"+i).val()!=""){
        var item_cost = parseFloat($("#ccc_"+i).val());
        var trsprt_amount = parseFloat(t);
        var qty = parseFloat($("#2_"+i).val());
        var grn_amount = parseFloat($("#gross_amount").val());

        var add_cost = ((trsprt_amount/grn_amount)*item_cost*qty)/qty;
        $("#4_"+i).val(m_round(add_cost+item_cost));

        $("#2_"+i).blur();
        calculate_last_price_margin();
        calculate_sales_price_margin();
      }
    }  
    
    calculate_free_total();
    dis_prec();
    amount();
    additional_amount();
    gross_amount2();
    net_amount2();
  });

  var r=1;
  $(document).on('keyup','.srl_count',function(e){
    set_cid($(this).attr("id"));
    if(scid=='0'){r=1;}
    if(e.keyCode == 13){
      $("#serial_"+r).focus();
      r++;
    }
  });



  $(document).on('focus','.input_date_down_future',function(){
    $(".input_date_down_future").datepicker({
      showButtonPanel: false,
      changeMonth: true,
      changeYear: true,
      dateFormat: 'yy-mm-dd',
      beforeShow: function (input, inst) {
        var offset = $(input).offset();
        var height = $(input).height();
        window.setTimeout(function () {
          inst.dpDiv.css({ top: (offset.top + height + 4) + 'px', left: offset.left + 'px' })
        }, 1);
      }        
    });
  });

  $("#typess").change(function(){
    $("#pono").val("");
    empty_grid();
  }); 

  $("#chkserial").click(function() {
    check_serials();
  });

  $("#pop_close12").click(function(){
    $("#serch_pop12").css("display", "none");
    $("#blocker2").css("display", "none");
  });

  $(".price").keyup(function(){
    calculate_last_price_margin();
    calculate_sales_price_margin();
  }); 

  $(".quns").css("display", "none");
  $("#code").blur(function(){
    check_code();
  });

  $("#supplier_create").click(function(){
    window.open($("#base_url").val()+"?action=m_supplier","_blank");   
    return false;   
  });

  $("#btnDelete5").click(function(){
    set_delete();
  });

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

  $("#id").keydown(function(e){
    if(e.keyCode == 13){
      $(this).blur();
      load_data($(this).val());
    }
  });

  $(".qty").keyup(function(){
    $("#qtyt_"+scid).val($("#2_"+scid).val()); 
  });

  $(".foc").keyup(function(){    
   tot_qty();
   calculate_free_total();
 });

  $(".foc , .qty").blur(function(){    
   tot_qty();
   calculate_free_total();
 });

  $("#supplier_id").keydown(function(e){
    if(e.keyCode == 112){
      $("#pop_search4").val($("#supplier_id").val());
      load_data_supf1();
      $("#serch_pop4").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search4').focus()", 100);
    }
    $("#pop_search4").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_data_supf1();
     }
   }); 
    if(e.keyCode == 46){
      $("#supplier_id").val("");
      $("#supplier").val("");
      $("#is_tax_sup").val(0);
    }
  });

  function load_data_supf1(){
    $.post("index.php/main/load_data/utility/f1_selection_list_supplier", {
      data_tbl:"m_supplier",
      field:"code",
      field2:"name",
      preview2:"Supplier Name",
      add_query:" AND is_inactive='0' AND is_blacklist='0' ",
      search : $("#pop_search4").val(),
      hid_field:'is_tax' 
    }, 
    function(r){
      $("#sr4").html(r);
      settings_supf1();            
    }, "text");
  }

  function settings_supf1(){
    $("#item_list .cl").click(
      function(){        
        $("#supplier_id").val($(this).children().eq(0).html());
        $("#supplier").val($(this).children().eq(1).html());
        $("#is_tax_sup").val($(this).children().eq(2).find('input').val());
        load_supplier_credit_period($(this).children().eq(0).html());
        $("#pop_close4").click();                
      })    
  }
  
  $("#btnDelete").click(function(){
    if($("#hid").val()!=0) {
      set_delete($("#hid").val());
    }
  });

  $("#inv_no").keyup(function(){
    this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
  });

  $("#inv_no").css("text-transform","uppercase");
  $("#inv_no").val().toUpperCase();


  $("#btnExit1").click(function(){
    document.getElementById('light').style.display='none';
    document.getElementById('fade').style.display='none';  
    $("#2_"+get_id).focus();
  });

  $("#id").keyup(function(){
    this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
  });

  $(".price, .qty, .dis_pre, .foc").blur(function(){
    set_cid($(this).attr("id"));
    //discount();
    calculate_free_total();
    dis_prec();
    amount();
    gross_amount();
    discount_amount();
    //all_rate_amount();
    additional_amount();
    net_amount();
  });

  $(".dis").blur(function(){
    set_cid($(this).attr("id"));
    dis_prec();
    amount();
    gross_amount();
    discount_amount();
    //all_rate_amount();
    additional_amount();
    net_amount();
  });

  $(".rate").blur(function(){
    set_cid($(this).attr("id"));
    //rate_amount();
    additional_amount();
    net_amount();
  });

  $(".aa").blur(function(){
    set_cid($(this).attr("id"));
    rate_pre();
    additional_amount();
    net_amount();
  });

  
  $(".fo").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode == 112){
      $("#pop_search10").val();
      if($("#po_update").val()!="0"){
        load_data_2();
        $("#serch_pop10").center();
        $("#blocker").css("display", "block");
        setTimeout("$('#pop_search10').focus()", 100);
      }

    }
    $("#pop_search10").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       if($("#po_update").val()!="0"){
        load_data_2();
      }

    }
  }); 
    if(e.keyCode == 46){

     $("#h_"+scid).val("");
     $("#0_"+scid).val("");
     $("#n_"+scid).val("");
     $("#1_"+scid).val("");
     $("#2_"+scid).val("");
     $("#t_"+scid).val("");
     $("#4_"+scid).val("");
     $("#ccc_"+scid).val("");
     $("#max_"+scid).val("");
     $("#min_"+scid).val("");
     $("#b1_"+scid).val("");
     $("#btn_"+scid).removeAttr("display");
     $("#btn_"+scid).attr("display","none");
     $("#lpm_"+scid).val("");
     $("#spm_"+scid).val("");

     dis_prec();
     amount();
     gross_amount();
     discount_amount();
    //all_rate_amount();
    additional_amount();
    net_amount();

  }

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


  $(".foo").keydown(function(e){
    if(e.keyCode==112){
      set_cid($(this).attr("id"));
      $("#serch_pop2").center();
      $("#blocker2").css("display", "block");
      setTimeout("select_search2()", 100);
    }
    if(e.keyCode==46){
     set_cid($(this).attr("id"));
     $("#00_"+scid).val("");
     $("#hh_"+scid).val("");
     $("#hhh_"+scid).val("");
     $("#nn_"+scid).val("");  
     $("#11_"+scid).val("");  
     $("#22_"+scid).val(""); 
     $("#cost_"+scid).attr("checked",false);         
     $("#22_"+scid).blur(); 

   }
 });

  $("#tgrid").tableScroll({height:200, width:1170});
  $("#tgrid2").tableScroll({height:100,width:760});


  $("#id,#sub_no").keyup(function(){
    this.value = this.value.replace(/\D/g,'');
  });

  $("#ref_no").keyup(function(){
    this.value = this.value.replace(/[^0-9a-zA-Z]/g,'');
  });

  $("#stores").change(function(){
    set_select("stores","store_no");
  });

  $("#supplier_id").autocomplete('index.php/main/load_data/m_supplier/auto_com', {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });


  $("#supplier_id").keydown(function(e){
    if(e.keyCode == 13){
      set_cus_values($(this));
    }
  });

  $("#supplier_id").blur(function(){
    set_cus_values($(this));
  });

  load_items();
  load_items2();
  load_po_no();


  $("#pop_search2").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112)
    {
      load_items2();
    }
  });  


  $("#pono").keydown(function(e){
    var p = $("#pono").val();
    if(e.keyCode == 13){
      empty_grid();  
      $.post("index.php/main/load_data/t_grn_sum/get_pono", {
        po_no:$("#pono").val(),
        type:$("#typess").val()
      }, function(r){
        if(r.det==2){
          set_msg("No records");
        }else{
          for(var i=0; i<r.det.length;i++){
            $("#pono").val(p);
            $("#h_"+i).val(r.det[i].item_code);
            $("#0_"+i).val(r.det[i].item_code);
            $("#n_"+i).val(r.det[i].description);
            $("#1_"+i).val(r.det[i].model); 
            $("#b1_"+i).val(r.det[i].balance);      
            $("#4_"+i).val(r.det[i].cost);
            $("#ccc_"+i).val(r.det[i].cost);
            $("#max_"+i).val(r.det[i].max_price);
            $("#min_"+i).val(r.det[i].min_price);
            $("#trate_"+i).val(r.det[i].rate);

            var lpm= parseFloat(0);
            lpm = (parseFloat(r.det[i].min_price)-parseFloat(r.det[i].cost))/parseFloat(r.det[i].min_price)*100;
            $("#lpm_"+i).val(m_round(lpm)+"%");

            var spm= parseFloat(0);
            spm = (parseFloat(r.det[i].max_price)-parseFloat(r.det[i].cost))/parseFloat(r.det[i].max_price)*100;
            $("#spm_"+i).val(m_round(spm)+"%");

            check_is_serial_item2(r.det[i].item_code,i);
            check_is_sub_item2(r.det[i].item_code,i); 
            is_sub_item(i);
          } 

          $("#pono").attr("readonly","readonly");
          $("#supplier_id").val(r.sum[0].supplier);
          $("#supplier").val(r.sum[0].name);
        }
        settings();
      }, 
      "json");  
    }
    if(e.keyCode==112){
      $("#pop_search").val($("#pono").val());
      load_po_no();    
      $("#serch_pop").center();
      $("#blocker").css("display", "block");
      setTimeout("select_search()", 100);
      $("#pop_search").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112){
          load_po_no();
        }
      });  
    }
  });


  $("#pop_search").gselect();
  $("#pop_search2").gselect(); 

  $(document).on("click",".approve",function(){
    var q="";
    $(".approve").each(function(e){
      if($('#app_' +e).is(":checked")){
        q=q+$("#sub_"+e).text()+"-"+$("#subqty_"+e).text()+",";
      }
    });
    $("#subcode_"+scid).val(q);
  });



  $(document).on("click",".subs",function(){
    set_cid($(this).attr("id"));
    check_is_sub_item(scid); 
    $("#is_click_"+scid).val("1");

  });

  $(".approve").blur(function(){
    //is_sub_item(scid);
  });

});



function load_data_2(){
  $.post("index.php/main/load_data/t_grn_sum/get_item_without_po", {
    search:$("#pop_search10").val()
  }, function(r){
    $("#sr10").html(r);
    settings_item_w_po(); 
  }, "text");
}

function settings_item_w_po(){
  $("#item_list .cl").click(
    function(){   
      /*if(check_item_exist($(this).children().eq(0).html())){*/
        $("#h_"+scid).val($(this).children().eq(0).html());
        $("#0_"+scid).val($(this).children().eq(0).html());
        $("#n_"+scid).val($(this).children().eq(1).html());
        $("#1_"+scid).val($(this).children().eq(2).html());
        $("#4_"+scid).val($(this).children().eq(3).html());
        $("#ccc_"+scid).val($(this).children().eq(3).html());
        $("#max_"+scid).val($(this).children().eq(4).html());
        $("#min_"+scid).val($(this).children().eq(5).html());


        var lpm= parseFloat(0);
        lpm = (parseFloat($(this).children().eq(5).html())-parseFloat($(this).children().eq(3).html()))/parseFloat($(this).children().eq(5).html())*100;
        $("#lpm_"+scid).val(m_round(lpm)+"%");
        var spm= parseFloat(0);
        spm = (parseFloat($(this).children().eq(4).html())-parseFloat($(this).children().eq(3).html()))/parseFloat($(this).children().eq(4).html())*100;
        $("#spm_"+scid).val(m_round(spm)+"%");
        $("#2_"+scid).focus();
        if($("#df_is_serial").val()!="0"){
          check_is_serial_item2($(this).children().eq(0).html(),scid);
        }
        check_is_sub_item2($(this).children().eq(0).html(),scid); 
        is_sub_item(scid);
        $("#pop_close10").click(); 
      })    
}


function is_sub_item(x){

  $.post("index.php/main/load_data/utility/is_sub_items_load", {
    code:$("#0_"+x).val(),
    hid:$("#hid").val(),
    type:'3'
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

function tot_qty(){
 var tot_qty = parseInt($("#qtyt_"+scid).val())+parseInt($("#3_"+scid).val());
 if(!isNaN(tot_qty)){
  $("#2_"+scid).val(tot_qty);
}
}

function select_search(){
  $("#pop_search").focus();
}

function select_search3(){
  $("#pop_search3").focus();
}

function calculate_last_price_margin(){
  var cost = parseFloat($("#4_"+scid).val()) ;
  var last = parseFloat($("#min_"+scid).val()) ;
  var lpm =  parseFloat(0);

  if(!isNaN(cost) && !isNaN(last)){
    lpm = ((last-cost)/last)*100;
    $("#lpm_"+scid).val(m_round(lpm)+"%");
  }

}

function calculate_free_total(){
  var foc_qty=parseInt($("#3_"+scid).val());
  var price=parseFloat($("#4_"+scid).val());
  var foc_total=parseFloat(0);

  foc_total=price*foc_qty;

  if(!isNaN(foc_total)){
    $("#freet").val(foc_total);
  }else{
    //$("#freet").val("0");
  }


}

function calculate_sales_price_margin(){
  var cost = parseFloat($("#4_"+scid).val()) ;
  var max = parseFloat($("#max_"+scid).val()) ;
  var lpm =  parseFloat(0);

  if(!isNaN(cost) && !isNaN(max)){
    spm = ((max-cost)/max)*100;
    $("#spm_"+scid).val(m_round(spm)+"%");
  }
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

function load_items4(x){
  $.post("index.php/main/load_data/utility/sub_item_window", {
    search : x,
  }, function(r){
    $("#sr3").html(r);
    match_sub_item();
  }, "text");

}


// function emptyElement(element) {
//   if (element == null || element == 0 || element.toString().toLowerCase() == 'false' || element == '')
//     return false;
//     else return true;
//   }



function select_search2(){
  $("#pop_search2").focus();
}

function load_items(){
 $.post("index.php/main/load_data/t_grn_sum/item_list_all", {
  search : $("#pop_search").val(),
  id:$("#pono").val(),
  stores : false
}, function(r){
  $("#sr").html(r);
  settings();
}, "text");
}

function load_items2(){
  $.post("index.php/main/load_data/r_additional_items/item_list_all", {
    search : $("#pop_search2").val(),
    stores : false,
    type : "1"
  }, function(r){
    $("#sr2").html(r);
    settings2();
  }, "text");
}

function load_po_no(){
 $.post("index.php/main/load_data/t_grn_sum/load_po_nos", {
  search : $("#pop_search").val(),
  type: $("#typess").val(),
  sup : $("#supplier_id").val()
}, function(r){
  $("#sr").html(r);
  settings_po();
}, "text");
}

function settings(){
  $("#item_list .cl").click(function(){
    if($(this).children().eq(0).html() != "&nbsp;"){
      if(check_item_exist($(this).children().eq(0).html())){
        if($("#df_is_serial").val()=='1')
        {
          check_is_serial_item2($(this).children().eq(0).html(),scid);
        }
        check_is_sub_item2($(this).children().eq(0).html(),scid);

        $("#h_"+scid).val($(this).children().eq(0).html());
        $("#0_"+scid).val($(this).children().eq(0).html());
        $("#n_"+scid).val($(this).children().eq(1).html());
        $("#1_"+scid).val($(this).children().eq(2).html());
        $("#4_"+scid).val($(this).children().eq(3).html());
        $("#ccc_"+scid).val($(this).children().eq(3).html());
        $("#max_"+scid).val($(this).children().eq(4).html());
        $("#min_"+scid).val($(this).children().eq(5).html());

        $("#2_"+scid).focus();
        $("#pop_close").click();
        //$("#pono").attr('readonly', true);
        $("#pono").addClass("hid_value"); 

      }
      else
      {
        set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
      }
    }
    else
    {
      $("#h_"+scid).val("");
      $("#0_"+scid).val("");
      $("#n_"+scid).val("");
      $("#1_"+scid).val(""); 
      $("#2_"+scid).val(""); 
      $("#3_"+scid).val(""); 
      $("#4_"+scid).val(""); 
      $("#5_"+scid).val(""); 
      $("#6_"+scid).val(""); 
      $("#t_"+scid).val(""); 
      $(".qty").blur();
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
        if($(this).children().eq(4).html() == 1){
          $("#11_"+scid).autoNumeric({mDec:2});
        }
        else
        {
          $("#11_"+scid).autoNumeric({mDec:2});
        }
        // $("#1_"+scid).removeAttr("disabled"); 
        // $("#2_"+scid).removeAttr("disabled"); 
        // $("#3_"+scid).removeAttr("disabled");
        //$("#11_"+scid).focus();
        rate_amount();
        additional_amount();
        net_amount();
        $("#pop_close2").click();
        //$("#blocker2").css("display","none");

      }
      else
      {
       set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
     }
   }
   else
   {
    $("#hh_"+scid).val("");
    $("#00_"+scid).val("");
    $("#nn_"+scid).val("");
    $("#11_"+scid).val(""); 
    $("#22_"+scid).val(""); 
    $("#hhh_"+scid).val("");
      // $("#3_"+scid).val(""); 
      // $("#t_"+scid).html("&nbsp;");
      // $("#1_"+scid).attr("disabled", "disabled"); 
      // $("#2_"+scid).attr("disabled", "disabled"); 
      // $("#3_"+scid).attr("disabled", "disabled");
      rate_amount();
      additional_amount();
      net_amount();
      $("#pop_close2").click();
    }
  });
}

function settings_po(){
  $("#po_list .cl").click(function(e){   
    $("#pono").val($(this).children().eq(0).html());     
    $("#pop_close").click(); 
    $("#pono").focus();
     //alert($(this).children().eq(0).html());
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
  $(".ad").each(function(){
 //$("input[type='hidden']").each(function(){
  if($(this).val() == id){
    v = false;
  }
});
  return v;
}

function set_cus_values(f){
  var v = f.val();
  v = v.split("|");
  if(v.length == 2){
    f.val(v[0]);
    $("#supplier").val(v[1]);
    load_supplier_credit_period(v[0]);
  }
}




function formatItems(row){
  return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
  return row[0]+"|"+row[1];
}


function load_supplier_credit_period(code){
  $.post("index.php/main/load_data/t_grn_sum/supplier_credit_period", {
    code:code
  },function(r){
    $("#credit_period").val(r);
  }, "text");
}


function save(){

	loding();
  $("#qno").val($("#id").val()); 
  $("#inv_date").val($("#date").val());     
  $("#inv_nop").val($("#inv_no").val());
  $("#po_nop").val($("#pono").val());
  $("#po_dt").val($("#ddate").val());
  $("#is_duplicate").val("1");
  $("#credit_prd").val($("#credit_period").val());

  if($("#df_is_serial").val()=='1')
  {
    serial_items.sort();
    $("#srls").attr("title",serial_items);
    $("#srls").val(serial_items);    
  }

  var frm = $('#form_');
  
  $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    success: function (pid){
     var sid=pid.split('@');
     if(pid == 0){
       // set_msg("Trsdfasdfansaction is NOT completed");
       // location.href="";
     }else if(pid == 2){
      set_msg("No permission to add data.");
    }else if(pid == 3){
      set_msg("No permission to edit data.");
    }else if(sid[0]==1){
      loding();
      //$("#btnSave").css("display","none");
      //$("#btnSavee").css("display","inline");
      $("#btnSave").attr("disabled",true);
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
    }
    loding();
  }
});
}

function reload_form(){
  setTimeout(function(){
    window.location = '';
  },50); 
}

function set_delete(){

  if($("#df_is_serial").val()=='1'){
    serial_items.sort();
    $("#srls").attr("title",serial_items);
    $("#srls").val(serial_items);   
  }

  var id = $("#hid").val();
  if(id != 0){
    if(confirm("Are you sure to delete this purchase ["+$("#id").val()+"]? ")){
      $.post("index.php/main/delete/t_grn_sum", {
        trans_no:id,
        type:$("#typess").val()
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

function check_code(){
  loding();
  var code = $("#code").val();
  $.post("index.php/main/load_data/t_grn_sum/check_code", {
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
  var g = true;

  $(".balq").each(function(e){

    if($("#b1_"+e).val()!="" && parseFloat($("#b1_"+e).val()) < parseFloat($("#2_"+e).val())){
      set_msg($("#b1_"+e).val() +"<"+  $("#2_"+e).val());
      set_msg("Quantity should be less than balance quantity","error");
      g=false;
    }
    if(parseFloat($("#min_"+e).val())<parseFloat($("#4_"+e).val())){
      set_msg($("#0_"+e).val()+" Cost Price should be less than min price ","error");
      $("#0_"+e).focus();
      g=false;
    }
    if(parseFloat($("#4_"+e).val())>parseFloat($("#max_"+e).val())){
      set_msg($("#0_"+e).val()+" Cost Price should be less than Max price","error");
      $("#0_"+e).focus();
      g=false;
    }
    if(parseFloat($("#min_"+e).val())>parseFloat($("#max_"+e).val())){
      set_msg($("#0_"+e).val()+" min Price should be less than Max price","error");
      $("#0_"+e).focus();
      g=false;
    }
    if($("#0_"+e).val()!="" && $("#2_"+e).val()>0 && $("#4_"+e).val()<=0){
      set_msg($("#0_"+e).val()+" cost price should be greater than zero","error");
      $("#0_"+e).focus();
      g=false;
    }

  });

  for(var i=0; i<25; i++){
    if($("#is_click_"+i).val()!=1 && $("#sub_"+i).data("is_click")==1){
      set_msg("Please check sub items in ("+$("#0_"+i).val()+")" ,"error");
      return false;
    } 
  }
  
  if($("#supplier").val() === $("#supplier").attr('title') || $("#supplier").val() == "")
  {
    set_msg("Please Select Supplier");
    $("#supplier_id").focus();
    return false;
  }
  else if($("#inv_no").val() == "")
  {
    set_msg("Please Enter Invoice Number");
    $("#inv_no").focus();
    return false;
  }
  else if($("#id").val() == "")
  {
    set_msg("Please Enter Number");
    $("#id").focus();
    return false;
  }
  else if($("#stores").val() == 0 )
  {
    set_msg("Please Select Store");
    $("#stores").focus();
    return false;
  }else if($("#ddate").val() =="" ){
    set_msg("please select invoice date");
    $("#ddate").blur();
    return false;
  }else if(g==false){
    return false;
  }else{
    return true;
  }
}


function discount(){
  var qty=parseFloat($("#2_"+scid).val());
  var price=parseFloat($("#4_"+scid).val());
  var dis_pre=parseFloat($("#5_"+scid).val());
  var discount="";

  if(!isNaN(qty)&& !isNaN(price) && !isNaN(dis_pre)){
    discount=(qty*price*dis_pre)/100;
    $("#6_"+scid).val(m_round(discount));
  }  
}

function dis_prec(){
  var qty=parseFloat($("#2_"+scid).val());
  var price=parseFloat($("#4_"+scid).val());
  var discount=parseFloat($("#6_"+scid).val());
  var dis_pre="";

  if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    dis_pre=(discount*100)/(qty*price);
    if(isNaN(dis_pre) || !isFinite(dis_pre)){    
      $("#5_"+scid).val("");
    }else{
      $("#5_"+scid).val(m_round(dis_pre));
    }
  }
}

function amount(){
  var qty=parseFloat($("#2_"+scid).val());
  var price=parseFloat($("#4_"+scid).val());
  var discount=parseFloat($("#6_"+scid).val());
  var amount="";
  
  if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    dis_pre=(qty*price)-discount;
    $("#t_"+scid).val(m_round(dis_pre));
  }else if(!isNaN(qty)&& !isNaN(price)){
    dis_pre=(qty*price);
    $("#t_"+scid).val(m_round(dis_pre));
  }
  cal_tax();
}


function gross_amount2(){
  var t=tt=0;
  $(".tf").each(function(e){
    var q = parseInt($("#2_"+e).val());
    var p = parseInt($("#ccc_"+e).val());
    if(!isNaN(p)){    
      t = t + parseFloat(q*p);
    }
  });
  $(".ad_cst").each(function(x){
    if($("#hh_"+x).val()=="1"){
      if($("#cost_"+x).is(":checked")){
        tt+=parseFloat($("#22_"+x).val());     
      }   
    }
  });
  $("#gross_amount").val(m_round(t+tt)); 
  $("#gross_amount222").val(m_round(t));
}


function gross_amount(){
  var gross=loop=0;
  $(".tf").each(function(){
    var gs=parseFloat($("#t_"+loop).val());
    if(!isNaN(gs)){    
      gross=gross+gs;
    }    
    loop++;
  });
  $("#gross_amount").val(m_round(gross));
}

function discount_amount(){
  var dis=loop=0;
  $(".dis").each(function(){
    var gs=parseFloat($("#6_"+loop).val());
    if(!isNaN(gs)){    
      dis=dis+gs;
    }    
    loop++;
  });
  $("#dis_amount").val(m_round(dis));
}

function rate_amount(){
  var rate_pre=parseFloat($("#11_"+scid).val());
  var gross_amount=parseFloat($("#gross_amount").val());
  var rate_amount="";
  if(!isNaN(rate_pre)&& !isNaN(gross_amount)){
    rate_amount=(gross_amount*rate_pre)/100;
    $("#22_"+scid).val(m_round(rate_amount));
  }
}


function rate_pre(){
  var gross_amount=parseFloat($("#gross_amount").val());
  var rate=parseFloat($("#22_"+scid).val());
  var rate_amount_pre="";

  if(!isNaN(rate)&& !isNaN(gross_amount)){
    rate_amount_pre=(rate*100)/gross_amount;
    $("#11_"+scid).val(m_round(rate_amount_pre));
  }
}


function all_rate_amount(){
  var gross_amount=parseFloat($("#gross_amount").val());  
  var additional=loop=0;
  
  $(".rate").each(function(){
    var rate=parseFloat($("#11_"+loop).val());
    var rate_amount=0;
    if(!isNaN(rate) && !isNaN(rate_amount) ){ 
      rate_amount=(gross_amount*rate)/100;
      $("#22_"+loop).val(m_round(rate_amount));
    }    
    loop++;
  });
}

function additional_amount(){
  var additional=loop=t=0;
  $(".tf").each(function(){
    var add=parseFloat($("#22_"+loop).val());
    var f= $("#hh_"+loop).val();

    if(!isNaN(add)){
      if(f==1){
        additional=additional+add;
        
      }
      else
      {
        additional=additional-add; 

      }
    }    

    if($("#hh_"+loop).val()=="1"){
      if($("#cost_"+loop).is(":checked")){
        t+=parseFloat($("#22_"+loop).val());     
      }      
    }



    loop++;
  });
  $("#total2").val(m_round(additional-t));
  $("#total22").val(m_round(additional));
}

function net_amount(){
  var additional=parseFloat($("#total2").val());
  var gross_amount=parseFloat($("#gross_amount").val());
  var net_amount=0;
  var free =parseFloat($("#freet").val());

  if(isNaN(free)){
    free =0;
  }

  if(!isNaN(additional)&& !isNaN(gross_amount)){
    net_amount=(gross_amount+additional)-free;
    $("#net_amount").val(m_round(net_amount));
  }
  else
  {
    $("#net_amount").val(m_round(net_amount-free));
  }
}

function net_amount2(){
  var additional=parseFloat($("#total22").val());
  var gross_amount=parseFloat($("#gross_amount222").val());
  var net_amount=0;

  if(!isNaN(additional)&& !isNaN(gross_amount)){
    net_amount=gross_amount+additional;
    $("#net_amount").val(m_round(net_amount));
  }
  else
  {
    $("#net_amount").val(net_amount);
  }
}

function loadPO(id){
  //empty_grid();
  loding();
  $.post("index.php/main/load_data/t_grn_sum/get_purchase_order", {
    id:id
  }, function(r){
   $("#sr").html(r);
   settings();
   loding();        
 }, "text");
}


function load_data(id){
  var g=[];
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_grn_sum/", {
    id: id
  }, function(r){
    if(r=="2"){
      set_msg("No records");
    }
    else
    {
      $("#hid").val(id);    
      $("#supplier_id").val(r.sum[0].scode);
      $("#supplier").val(r.sum[0].name);
      $("#stores").val(r.sum[0].stcode);
      set_select("stores","store_no");
      $("#pono").val(r.sum[0].po_no);
      $("#credit_period").val(r.sum[0].credit_period);
      $("#pono2").val(r.sum[0].po_no2);
      $("#pono3").val(r.sum[0].po_no3);
      $("#date").val(r.sum[0].ddate);
      $("#inv_no").val(r.sum[0].inv_no);
      $("#ref_no").val(r.sum[0].ref_no);
      $("#ddate").val(r.sum[0].inv_date);
      $("#gross_amount").val(r.sum[0].gross_amount);
      //$("#gross_amount222").val(r.sum[0].gross_amount);
      $("#total2").val(r.sum[0].additional);
      //$("#total22").val(r.sum[0].additional);
      $("#net_amount").val(r.sum[0].net_amount);
      $("#id").attr("readonly","readonly")    
      $("#qno").val(id); 
      $("#inv_date").val(r.sum[0].inv_date);     
      $("#inv_nop").val(r.sum[0].inv_no);
      $("#po_nop").val(r.sum[0].po_no);
      $("#po_dt").val(r.sum[0].inv_date);
      $("#credit_prd").val(r.sum[0].credit_period);
      $("#note").val(r.sum[0].memo);
      $("#memo").val(r.sum[0].memo);
      $("#tot_tax").val(r.sum[0].tax_amount);
      $("#typess").val(r.sum[0].type);

      if(r.sum[0].is_cancel==1){
        $("#btnDelete").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
      }

      for(var i=0; i<r.det.length;i++){
        $("#h_"+i).val(r.det[i].icode);
        $("#0_"+i).val(r.det[i].icode);
        $("#n_"+i).val(r.det[i].idesc);
        $("#1_"+i).val(r.det[i].model);
        $("#2_"+i).val(r.det[i].qty);
        $("#3_"+i).val(r.det[i].foc);
        $("#4_"+i).val(r.det[i].price);
        $("#ccc_"+i).val(r.det[i].price);
        $("#5_"+i).val(r.det[i].discountp);
        $("#6_"+i).val(r.det[i].discount);
        $("#t_"+i).html(r.det[i].amount);
        $("#max_"+i).val(r.det[i].max_price);  
        $("#min_"+i).val(r.det[i].min_price);
        $("#trate_"+i).val(r.det[i].tax_rate);  

        var lpm= parseFloat(0);
        lpm = (parseFloat(r.det[i].min_price)-parseFloat(r.det[i].price))/parseFloat(r.det[i].min_price)*100;
        $("#lpm_"+i).val(m_round(lpm)+"%");

        var spm= parseFloat(0);
        spm = (parseFloat(r.det[i].max_price)-parseFloat(r.det[i].price))/parseFloat(r.det[i].max_price)*100;
        $("#spm_"+i).val(m_round(spm)+"%");


        scid=i; 
        amount();
        

        $("#itemcode_"+i).val(r.det[i].icode);
        $("#2_"+i).val(r.det[i].qty);

        if($("#df_is_serial").val()=='1')
        {
          check_is_serial_item2(r.det[i].icode,i);
          $("#numofserial_"+i).val(r.det[i].qty);
          for(var a=0;a<r.serial.length;a++){
            if(r.det[i].icode==r.serial[a].item){
              g.push(r.serial[a].serial_no+"-"+r.serial[a].other_no1+"-"+r.serial[a].other_no2);
              $("#all_serial_"+i).val(g);
            }   
          }
          g=[];  
        }
        check_is_sub_item2(r.det[i].icode,i); 
        is_sub_item(i);

        $("#qtyt_"+i).val(parseFloat(r.det[i].qty)-parseFloat(r.det[i].foc))
        $("#freet").val(parseFloat(r.det[i].price)*parseFloat(r.det[i].foc))



      }
      //gross_amount();


      if(r.add!=2){
       for(var i=0; i<r.add.length;i++){           
        $("#hhh_"+i).val(r.add[i].type);
        $("#00_"+i).val(r.add[i].type);
        $("#nn_"+i).val(r.add[i].description);
        $("#11_"+i).val(r.add[i].rate_p);
        $("#22_"+i).val(r.add[i].amount);
        get_sales_type(i);

        if(r.add[i].add_to_cost=="1"){
          $("#cost_"+i).attr("checked",true);
        }
      }
    }

     // gross_amount2();
     // net_amount2();
     discount_amount();
     input_active();
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
    $("#h_"+i).val("");
    $("#0_"+i).val("");
    $("#n_"+i).val("");
    $("#1_"+i).val(""); 
    $("#b1_"+i).val(""); 
    $("#2_"+i).val(""); 
    $("#3_"+i).val(""); 
    $("#4_"+i).val("");
    $("#ccc_"+i).val(""); 
    $("#5_"+i).val(""); 
    $("#6_"+i).val(""); 
    $("#max_"+i).val("");  
    $("#min_"+i).val("");  
    $("#subcode_"+i).val(""); 
    $("#is_click_"+i).val("");       
    $("#t_"+i).val(""); 
    $("#subcode_"+i).val("");
    $("#is_click_"+i).val("");
    $("#hh_"+i).val("");
    $("#hhh_"+i).val("");
    $("#00_"+i).val("");
    $("#nn_"+i).val("");
    $("#11_"+i).val("");
    $("#22_"+i).val("");
    $("#lpm_"+i).val("");
    $("#spm_"+i).val("");
    $("#trate_"+i).val("");
    $("#sub_"+i).removeAttr("data-is_click");
    $(".clr_"+i).css("background-color","white");
  }
  $(".subs").css("display","none");
  $(".quns").css("display","none");
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

function cal_tax(){
  var total      = parseFloat("0.00");
  for(var x=0; x<25; x++){
    if($("#t_"+x).val() > 0){
      var qty        = parseFloat($("#2_"+x).val());
      var price      = parseFloat($("#4_"+x).val());
      var discount   = parseFloat($("#6_"+x).val());
      var item_price = parseFloat(0);
      var rate       = parseFloat($("#trate_"+x).val());

      if(isNaN(qty)){qty=0}
        if(isNaN(price)){price=0}
          if(isNaN(discount)){discount=0}

            item_price = (price*qty) - (discount);      

          if(isNaN(item_price)){item_price=0}
            if(isNaN(rate)){rate=0}

              total += (item_price * rate) / 100 ;
          }
        }
        $("#tot_tax").val(m_round(total));
      }