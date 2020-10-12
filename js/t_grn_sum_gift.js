var sub_items =[];
var is_click=0;
$(document).ready(function(){
$("#btnSavee").css("display","none");

  $(document).on('click','#load_qty',function(){
    $.post("index.php/main/load_data/utility/previous_qty_sub", {
        avg_from:$("#avg_from").val(),
        avg_to:$("#avg_to").val(), 
        item:$("#0_"+scid).val(),             
    }, function(res){
            $("#grn_qty").val(res.grn);
            $("#sale_qty").val(res.sales);
    },"json");    
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

  $("#p_serial").click(function(){
    if($("#p_serial").is(":checked")){
      $("#is_print_serial").val("1");
    }else{
      $("#is_print_serial").val("0");
    }
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
    }
  });

  function load_data_supf1(){
      $.post("index.php/main/load_data/utility/f1_selection_list", {
          data_tbl:"m_supplier",
          field:"code",
          field2:"name",
          preview2:"Supplier Name",
          search : $("#pop_search4").val() 
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
          $("#pop_close4").click();                
      })    
  }
  
  $("#btnDelete").click(function(){
    if($("#hid").val()!=0) {
      set_delete($("#hid").val());
    }
  });


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
    amount();
    gross_amount();
    net_amount();
  });
  
  $(".fo").keydown(function(e){
      set_cid($(this).attr("id"));
    if(e.keyCode==112){
    		$("#pop_search").val($("#0_"+scid).val());
        load_items();    
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);

        $("#pop_search").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 )
            {
              load_items();
            }
        });
	    }
   
      if(e.keyCode==13){
        $.post("index.php/main/load_data/t_grn_sum_gift/get_item", {
                code:$("#0_"+scid).val(),
                supp:$("#supplier_id").val()
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
                    $("#4_"+scid).val(res.a[0].cost);
                    $("#max_"+scid).val(res.a[0].price);
                    
                    $("#2_"+scid).focus();
                  }else{
                    set_msg("Item "+$("#0_"+scid).val()+" is already added.");
                  }
                }else{
                  set_msg($("#0_"+scid).val()+" Item not available in item list","error");
                  $("#0_"+scid).val("");
                }
            }, "json");
      }
    
      if(e.keyCode==46){
        set_cid($(this).attr("id"));
        if($("#df_is_serial").val()=='1')
        {
          $("#all_serial_"+scid).val("");
        }
        $("#h_"+scid).val("");
        $("#0_"+scid).val("");
        $("#n_"+scid).val("");
        $("#2_"+scid).val(""); 
        $("#4_"+scid).val("");
        $("#ccc_"+scid).val("");  
        $("#5_"+scid).val(""); 
        $("#max_"+scid).val("");  
        $("#t_"+scid).val(""); 
        $("#btn_"+scid).css("display","none"); 
               
        amount();
        gross_amount();
        net_amount();
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


  $("#pop_search").gselect();
  $("#pop_search2").gselect(); 


});


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

function select_search2(){
  $("#pop_search2").focus();
}

function load_items(){
     $.post("index.php/main/load_data/t_grn_sum_gift/item_list_all", {
        search : $("#pop_search").val(),
        supp : $("#supplier_id").val(),
        stores : false
    }, function(r){
        $("#sr").html(r);
        settings();
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
       		$("#h_"+scid).val($(this).children().eq(0).html());
         	$("#0_"+scid).val($(this).children().eq(0).html());
         	$("#n_"+scid).val($(this).children().eq(1).html());
         	$("#4_"+scid).val($(this).children().eq(2).html());
          $("#ccc_"+scid).val($(this).children().eq(2).html());
          $("#max_"+scid).val($(this).children().eq(3).html());
        $("#2_"+scid).focus();
        $("#pop_close").click();
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
      $("#2_"+scid).val(""); 
      $("#4_"+scid).val(""); 
      $("#t_"+scid).val(""); 
      $(".qty").blur();
      $("#pop_close").click();
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
  }
}




function formatItems(row){
  return "<strong> " +row[0] + "</strong> | <strong> " +row[1] + "</strong>";
}

function formatItemsResult(row){
  return row[0]+"|"+row[1];
}


function save(){

	loding();
  $("#qno").val($("#id").val()); 
  $("#inv_date").val($("#date").val());     
  $("#inv_nop").val($("#inv_no").val());
 
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
    if(pid==1){
      loding();      
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
        loding();
        set_msg(pid,"error");
      }
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
      $.post("index.php/main/delete/t_grn_sum_gift", {
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
  $.post("index.php/main/load_data/t_grn_sum_gift/check_code", {
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

  if($("#supplier").val() === $("#supplier").attr('title') || $("#supplier").val() == "")
  {
    set_msg("Please Select Supplier");
    $("#supplier_id").focus();
    return false;
  }
  else if($("#inv_no").val() == "")
  {
    set_msg("Please Enter Invoice Number");
    $("#id").focus();
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
  }
  //else if($("#net_amount").val() == 0 )
  //{
   
    //$("#stores").focus();
   // return false;
 // }
  else if(g==false){
    return false;
  }else{
    return true;
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


function net_amount(){
 var net_amount=0;
 for(var x=0; x<25; x++){
  if($("#t_"+x).val()!=""){
    net_amount+=parseFloat($("#t_"+x).val());
  }
 }
 
 $("#net_amount").val(m_round(net_amount));
 
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


function load_data(id){
  var g=[];
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_grn_sum_gift/", {
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
      $("#date").val(r.sum[0].ddate);
      $("#inv_no").val(r.sum[0].inv_no);
      $("#ref_no").val(r.sum[0].ref_no);
      $("#ddate").val(r.sum[0].inv_date);
      $("#gross_amount").val(r.sum[0].gross_amount);
      $("#total2").val(r.sum[0].additional);
      $("#net_amount").val(r.sum[0].net_amount);
      $("#id").attr("readonly","readonly")    
      $("#qno").val(id); 
      $("#inv_date").val(r.sum[0].inv_date);     
      $("#inv_nop").val(r.sum[0].inv_no);
      $("#note").val(r.sum[0].memo);
      $("#memo").val(r.sum[0].memo);

      if(r.sum[0].is_cancel==1){
        $("#btnDelete").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
      }
     
      for(var i=0; i<r.det.length;i++){
        $("#h_"+i).val(r.det[i].icode);
        $("#0_"+i).val(r.det[i].icode);
        $("#n_"+i).val(r.det[i].idesc);
        $("#2_"+i).val(r.det[i].qty);
        $("#4_"+i).val(r.det[i].price);
        $("#ccc_"+i).val(r.det[i].price);
        $("#t_"+i).html(r.det[i].amount);
        $("#max_"+i).val(r.det[i].max_price);  
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
      }
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
    $("#sub_"+i).removeAttr("data-is_click");
  }
  $(".subs").css("display","none");
  $(".quns").css("display","none");
}
