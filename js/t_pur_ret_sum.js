var sub_items=[];
var is_cancel;

$(document).ready(function(){

  var p_code = window.location.search.split('=')[2];

  if(p_code != undefined){
    load_data(p_code);
  }

  $(".qunsb").css("display","none");
  $(".quns").css("display","none");
  $("#btnSavee").css("display","none");

  $("#btnApprove").attr("disabled", true);  

  $("#update_po").click(function(){
    if($("#01_0").val()!="" && $("#01_0").val()!="0"){
      if($(this).is(":checked")){
        $("#update_po_status").val("1");
        load_update_po();
        $(this).attr("disabled",true);
        
      }else{
        $("#update_po_status").val("0");
      }      
    }else{
      set_msg("Please load GRN no");
      $(this).attr("checked", false);
    }
  });

  $("#tgrid").tableScroll({height:200, width:1120});
  
  $("#tgrid1").tableScroll({height:200, width:1120});

  $(".fo").dblclick(function(){
    set_cid($(this).attr("id"));  
    if($(this).val()!=""){
      $.post("index.php/main/load_data/utility/get_sub_item_detail", {
        code:$(this).val(),
        store:$("#stores").val(),
        po:$("#pono").val(),
        qty:$("#2_"+scid).val(),
        batch:$("#3_"+scid).val()
      }, function(res){
        if(res!=0){
          $("#msg_box_inner").html(res);
          $("#msg_box").slideDown();
        }
      },"text");
    } 
  });
  

  $("#btnApprove").click(function(){
    $("#app_status").val("2");
    if(validate()){
      $("#btnSave").attr("disabled",true); 
      save();    
    }
  });

  $("#btnReset").click(function(){    
   location.href="?action=t_pur_ret_sum";   
 });


  $("#btnPrint").click(function(){
    if($("#hid").val()=="0"){
      set_msg("Please load data before print");
      return false;
    }
    else{
     $("#print_pdf").submit();
   }
 })

  $("#free_fix,#pst").blur(function(){
    var get_code=$(this).val();
    $(this).val(get_code.toUpperCase());
  });

  $("#btnDelete5").click(function(){
    set_delete();
  });


  $(document).on('keyup','.vali', function(){
    set_cid($(this).attr("id"));
    if($("#2_"+scid).val() !="" )
    {
     if(parseFloat($("#2_"+scid).val())>parseFloat($("#rmax_"+scid).val()))
     {
      set_msg("Maximum available quantity is " +$("#rmax_"+scid).val(),"error");	
     			//$(this).val("0");
     		}
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

  $(".reason").autocomplete("index.php/main/load_data/return_reason/auto_com3", {
    width: 350,
    multiple: false,
    matchContains: true,
    formatItem: formatItems,
    formatResult: formatItemsResult
  });
  $(".reason").keydown(function(a) {
    if (13 == a.keyCode) set_cus_values5($(this));
  });
  $(".reason").blur(function() {
    set_cus_values5($(this));
  }); 


  //------------------------
  $(".reason").keydown(function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode == 112){
      $("#pop_search11").val($("7_"+scid).val());
      load_data_reason();
      $("#serch_pop11").center();
      $("#blocker").css("display", "block");
      setTimeout("$('#pop_search11').focus()", 100);
    }

    $("#pop_search11").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
       load_data_reason();

     }
   }); 

    if(e.keyCode == 46){
      $("#7_"+scid).val(""); 
      $("#ret_"+scid).val(""); 
    }
  });
  //-----------------------

  function set_cus_values5(a) {
    var g = $(a).attr("id").split("_")[1];
    var b = a.val();
    b = b.split("|");
    if(2 == b.length){
      $("#ret_"+g).val(b[0]);
      $("#7_"+g).val(b[1]);
    }
  }
  

  $("#btnExit1").click(function(){
    document.getElementById('light').style.display='none';
    document.getElementById('fade').style.display='none';  
    $("#2_"+get_id).focus();
  });

  
  $("#tgrid").tableScroll({height:200});
  $("#tgrid2").tableScroll({height:100});

  $("#id").keyup(function(){
    this.value = this.value.replace(/[^0-9\.a-z,',A-Z]/g,'');
  });

  
  $("#id").keydown(function(e){
    if(e.keyCode == 13){
      $(this).blur();
      load_data($(this).val());
    }
  });


  $("#stores").change(function(){
    set_select('stores','store_id');
  });

  
  $(".fo").focus(function(){
    if($("#stores").val()==0)
    {
      set_msg("Please select store");
    }
  });

  $(document).on('keydown','.fo', function(e){
    set_cid($(this).attr("id"));
    if(e.keyCode==112){
      if($("#stores").val()==0){
        set_msg("Please select store");
        return false;
      }else if($("#ret_type").val()=="1"){
        return false;
      }else{
        $("#pop_search").val($("#0_"+scid).val());
        load_items();    
        $("#serch_pop").center();
        $("#blocker").css("display", "block");
        setTimeout("select_search()", 100);
      }
    }

    if(e.keyCode==13){
      $.post("index.php/main/load_data/t_pur_ret_sum/get_item", {
        code:$("#0_"+scid).val(),
        stores:$("#stores").val()
      }, function(res){
        if(res.a!=2){
          $("#0_"+scid).val(res.a[0].code);
          if(check_item_exist($("#0_"+scid).val())){

            if($("#df_is_serial").val()=='1'){
              check_is_serial_item2(res.a[0].code,scid);
            }
            check_is_batch_item2(res.a[0].code,scid); 

            $("#h_"+scid).val(res.a[0].code);
            $("#n_"+scid).val(res.a[0].description);
            $("#0_"+scid).val(res.a[0].code);
            $("#1_"+scid).val(res.a[0].model);
            $("#4_"+scid).val(res.a[0].purchase_price);
            check_is_batch_item(scid);
            $("#1_"+scid).removeAttr("disabled"); 
            $("#2_"+scid).removeAttr("disabled"); 
            $("#3_"+scid).removeAttr("disabled");
            $("#3_"+scid).focus();
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
      if($("#df_is_serial").val()=='1'){
        $("#all_serial_"+scid).val("");      
      }
      $("#h_"+scid).val("");
      $("#0_"+scid).val("");
      $("#n_"+scid).val("");
      $("#1_"+scid).val(""); 
      $("#2_"+scid).val(""); 
      $("#21h_"+scid).val(""); 
      $("#3_"+scid).val(""); 
      $("#4_"+scid).val(""); 
      $("#rmax_"+scid).val("");
      $("#5_"+scid).val(""); 
      $("#6_"+scid).val(""); 
      $("#7_"+scid).val(""); 
      $("#t_"+scid).val("");
      $("#ret_"+scid).val("");
      $("#maxretqty_"+scid).val("");
      $("#subcode_"+scid).val("");
      $("#subcode_"+scid).removeAttr("data-is_click");
      $("#btn_"+scid).css("display","none");
      $("#btnb_"+scid).css("display","none"); 
      //discount();
      dis_prec();
      amount(); 
      gross_amount();
      all_rate_amount();
      additional_amount();
      net_amount();
    }
  });


  $(".foo").focus(function(){
    set_cid($(this).attr("id"));
    $("#serch_pop2").center();
    $("#blocker2").css("display", "block");
    setTimeout("select_search2()", 100);
  });


  $(".price, .qty, .dis_pre").blur(function(){
    set_cid($(this).attr("id"));
    //discount();
    dis_prec();
    amount();
    gross_amount();
    all_rate_amount();
    additional_amount();
    net_amount();
  });


  $(".dis").blur(function(){
    set_cid($(this).attr("id"));
    dis_prec();
    amount();
    gross_amount();
    all_rate_amount();
    additional_amount();
    net_amount();
  });


  $(".price1, .qty1, .dis_pre1").blur(function(){
    set_cid($(this).attr("id"));
    discount2();
    amount2();
    // gross_amount();
    // all_rate_amount();
    // additional_amount();
    // net_amount();
  });

  $(".dis1").blur(function(){
    set_cid($(this).attr("id"));
    dis_prec2();
    amount2();
    // gross_amount();
    // all_rate_amount();
    // additional_amount();
    // net_amount();
  });

  $(".rate").blur(function(){
    set_cid($(this).attr("id"));
    rate_amount();
    additional_amount();
    net_amount();
  });

  $(".aa").blur(function(){
    set_cid($(this).attr("id"));
    rate_pre();
    additional_amount();
    net_amount();
  });
  
  load_items();
  load_items2();

  $("#pop_search").gselect();
  $("#pop_search2").gselect(); 
  $("#pop_search3").gselect(); 

  $("#pop_search").keyup(function(e){
    if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
    }
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



  $("#grnno").keydown(function(e){
    if(e.keyCode == 13){
      empty_grid2();
      empty_grid()
      
      $.post("index.php/main/load_data/t_pur_ret_sum/get_invoice", {
        code:$("#grnno").val()
      },function(r){
      	if(r.det==2){
      		set_msg("No records");
      	}else{     
          if(r.status!=1){
            set_msg(r.status,"error");
          }      
          $("#supplier_id").val(r.sum[0].supp_id);
          $("#supplier").val(r.sum[0].name);
          $("#stores").val(r.sum[0].store);
          $("#store_id").val(r.sum[0].store_name);
          $("#update_po_no").val(r.sum[0].po_no);
          is_cancel=r.sum[0].is_cancel;
          $("#supplier_id").attr("readonly", "readonly");
          $("#supplier").attr("readonly", "readonly");
          $("#stores").attr("readonly", "readonly");
          $("#store_id").attr("readonly", "readonly");

          for(var i=0; i<r.det.length;i++){

            if($("#df_is_serial").val()=='1'){
              $("#numofserial_"+i).val(r.det[i].qty);
              $("#setserial_"+i).removeAttr("title");
              $("#setserial_"+i).removeAttr("value");
              $("#setserial_"+i).attr("title",1);
              $("#setserial_"+i).attr("value",1);  
            }
            $("#h1_"+i).val(r.det[i].code);
            $("#01_"+i).val(r.det[i].code);
            $("#n1_"+i).val(r.det[i].description);
            $("#101_"+i).val(r.det[i].model);
            $("#21_"+i).val(r.det[i].qty);
            $("#31_"+i).val(r.det[i].batch_no);
            $("#41_"+i).val(r.det[i].price);
            $("#51_"+i).val(r.det[i].discountp);
            $("#61_"+i).val(r.det[i].discount);
            $("#rq_"+i).val(r.det[i].return_qty);
            $("#qno").val($("#inv_no").val()); 
            $(".price1").blur();
            $(".dis1").blur();
            
            $("#0_"+i).attr("readonly", "readonly");
            $("#n_"+i).attr("readonly", "readonly");
            $("#1_"+i).attr("readonly", "readonly");
            $("#3_"+i).attr("readonly", "readonly");
            $("#4_"+i).attr("readonly", "readonly");
            $("#t_"+i).attr("readonly", "readonly");




          }

          $("#grnno").attr("readonly", "readonly");
          $("#hid_grn").val($("#grnno").val());
          
          for(var i=0; i<r.max.length;i++){
            $("#rmax1_"+i).val(r.max[i].return_qty);
          }

          if($("#df_is_serial").val()=='1'){
            serial_items.splice(0);
            for(var i=0;i<r.serial.length;i++){
              serial_items.push(r.serial[i].item+"-"+r.serial[i].serial_no);
            }
            serial_items.sort();
          }
        }
        settings1();
      }, 
      "json")

      




    }
  });


  $(".qunsb").click(function(){
    set_cid($(this).attr("id"));
    check_is_batch_item(scid);
  });

  $(".qty").keyup(function(){
    set_cid($(this).attr("id"));
    var return_qty=parseInt($("#2_"+scid).val());
    var actual_qty=parseInt($("#21h_"+scid).val());
    if(return_qty>actual_qty)
    {
      set_msg("Available maximum quantity is "+actual_qty,"error");
      $("#2_"+scid).val("");
    }
  });

  $(".qty").blur(function(){
    is_sub_item(scid);
  });

});


/*function approve(id){
  $.post("index.php/main/load_data/t_pur_ret_sum/approve/",{
          id:id,
          app:$("#app_status").val()
      },function(res){
         if(res=="2"){
          location.href="";
         }
      },"json");
    }*/


    function is_sub_item(x){
      sub_items=[];
      $("#subcode_"+x).val("");
      $.post("index.php/main/load_data/utility/is_sub_items", {
        code:$("#0_"+x).val(),
        qty:$("#2_"+x).val(),
        batch:$("#3_"+x).val()
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
        batch:$("#3_"+x).val(),
        hid:$("#hid").val(),
        trans_type:"10",
        store:$("#stores").val()
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


    function check_is_batch_item(scid){       
      $.post("index.php/main/load_data/t_pur_ret_sum/is_batch_item",{
        code:$("#0_"+scid).val(),
        store:$("#stores").val()
      },function(res){

        if(res==1){
          $("#serch_pop3").center();
          $("#blocker3").css("display", "block");
          setTimeout("select_search3()", 100);
          load_items3($("#0_"+scid).val());
          $("#pop_search3").keyup(function(e){
            if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
              load_items3($("#0_"+scid).val());
            }
          });

          $("#pop_search3").gselect();
        }else if(res=='0'){
          $("#3_"+scid).val("0");
          $("#3_"+scid).attr("readonly","readonly");
        }else{
          $("#3_"+scid).val(res.split("-")[0]);
          $("#2_"+scid).val(res.split("-")[1]);
          $("#3_"+scid).attr("readonly","readonly");
        }
      },'text');
    }

    function check_is_batch_item2(x,scid){
      $.post("index.php/main/load_data/t_pur_ret_sum/is_batch_item",{
        code:x,
        store:$("#stores").val()
      },function(res){
        $("#btnb_"+scid).css("display","none");
        if(res==1){
          $("#btnb_"+scid).css("display","block");
        }
      },'text');
    }

    function settings1(){
      $("#tgrid .cl1").dblclick(function(){        
        if($(this).children().eq(0).html() != "&nbsp;"){

         var row_value1=$(this).children().find('input').eq(0).val();
         var row_value2=$(this).children().find('input').eq(2).val();
         var row_value3=$(this).children().find('input').eq(3).val();
         var row_value4=$(this).children().find('input').eq(5).val();
         var row_value5=$(this).children().find('input').eq(4).val();
         var row_value6=$(this).children().find('input').eq(6).val();
         var row_value7=$(this).children().find('input').eq(7).val();
         var row_value8=$(this).children().find('input').eq(8).val();
         var row_value9=$(this).children().find('input').eq(9).val();
         var row_value10=$(this).children().find('input').eq(6).val();
         var row_value11=$(this).children().find('input').eq(11).val();
         var row_value12=$(this).children().find('input').eq(12).val();
         var row_value13=$(this).children().find('input').eq(10).val();
         
         for(var i=0; i<25 ;i++){

          if(row_value1 == "0" || row_value1 =="")
          {
          }
          else
          {
           if($("#0_"+i).val()=="")
           {
                    //alert(item_qty(row_value1)+"-"+row_value10)
                    if(parseInt(item_qty(row_value1))>=parseInt(row_value10)){
                      set_msg("This Item ("+row_value1+") Maximum return qty is "+ row_value10);
                    }else{
                      if($("#df_is_serial").val()=='1'){
                        check_is_serial_item2($(this).children().find('input').eq(0).val(),i);
                      }
                      //check_is_batch_item2($(this).children().find('input').eq(0).val(),i);
                      $("#0_"+i).val(row_value1);
                      $("#n_"+i).val(row_value2);
                      $("#1_"+i).val(row_value3);
                      $("#3_"+i).val(row_value4);
                      $("#4_"+i).val(row_value7);
                      $("#5_"+i).val(row_value8);
                      $("#6_"+i).val(row_value9);
                      $("#t_"+i).val(row_value11);
                      $("#rmax_"+i).val(row_value10);
                      break;		
                    }
                  }
                }
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
              
            } 
            
          });
    }


    function item_qty(item){
      var cont=parseInt(0);
      $(".fo").each(function(e){
        if(item==$("#0_"+e).val()){
          cont++;
        }
      });
      return cont;
    }


    function load_update_po(){
      empty_grid();
      for(var i=0; i<25; i++){
        if($("#df_is_serial").val()=='1'){
          check_is_serial_item2($("#01_"+i).val(),i);
        }
        $("#0_"+i).val($("#01_"+i).val());
        $("#n_"+i).val($("#n1_"+i).val());
        $("#1_"+i).val($("#101_"+i).val());
        $("#2_"+i).val($("#21_"+i).val());
        $("#3_"+i).val($("#31_"+i).val());
        $("#4_"+i).val($("#41_"+i).val());
        $("#5_"+i).val($("#51_"+i).val());
        $("#6_"+i).val($("#61_"+i).val());
        $("#t_"+i).val($("#t1_"+i).val());
        $("#rmax_"+i).val($("#rmax1_"+i).val());  
        
        $("#2_"+i).blur(); 
        $("#0_"+i).removeClass("fo"); 

        $("#0_"+i).attr("readonly","readonly");
        $("#n_"+i).attr("readonly","readonly");
        $("#1_"+i).attr("readonly","readonly");
        $("#2_"+i).attr("readonly","readonly");
        $("#3_"+i).attr("readonly","readonly");
        $("#4_"+i).attr("readonly","readonly");
        $("#5_"+i).attr("readonly","readonly");
        $("#6_"+i).attr("readonly","readonly");
        $("#t_"+i).attr("readonly","readonly");


      }
      
    }





    function empty_grid2(){
     for(var i=0; i<25; i++){
       $("#h1_"+i).val(0);
       $("#01_"+i).val("");
       $("#n1_"+i).val("");
       
       $("#101_"+i).val("");
       $("#21_"+i).val("");
       $("#31_"+i).val("");
       $("#41_"+i).val("");
       $("#51_"+i).val("");
       $("#61_"+i).val("");
       $("#71_"+i).val("");
       $("#t1_"+i).val("");
       $("#rq_"+i).val("");
       $("#7_"+i).val("");

       
     }
     $("#gross_amount").val("");
     $("#total2").val("");
     $("#net_amount").val("");
     $("#customer").val("");
     $("#customer_id").val("");
     $("#sales_rep").val("");
     $("#sales_rep2").val("");
     $(".quns").css("display","none");
   }

   function select_search(){
    $("#pop_search").focus();
  }

  function select_search2(){
    $("#pop_search2").focus();
  }


  function select_search3(){
    $("#pop_search3").focus();
  }

  function select_search4(){
    $("#pop_search4").focus();
  }

  function select_search5(){
    $("#pop_search5").focus();
  }

  function load_data_supf1(){
    $.post("index.php/main/load_data/utility/f1_selection_list_supplier", {
      data_tbl:"m_supplier",
      field:"code",
      field2:"name",
      preview2:"Supplier Name",
      add_query:" AND is_inactive='0' ",
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

  function load_data_reason(){
    $.post("index.php/main/load_data/utility/f1_selection_list", {
      data_tbl:"r_return_reason",
      field:"code",
      field2:"description",
      preview2:"Return Reason Name",
      add_query: " AND type='1'",
      search : $("#pop_search11").val() 
    }, 
    function(r){
      $("#sr11").html(r);
      settings_reasonf1();            
    }, "text");
  }

  function settings_reasonf1(){
    $("#item_list .cl").click(function(){     
      $("#ret_"+scid).val($(this).children().eq(0).html());
      $("#7_"+scid).val($(this).children().eq(1).html());
      $("#pop_close11").click();                
    })    
  }


  function load_items3(x){
    $.post("index.php/main/load_data/t_pur_ret_sum/batch_item", {
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
          $("#3_"+scid).val($(this).children().eq(0).html());
          $("#2_"+scid).val($(this).children().eq(1).html());
          $("#4_"+scid).val($(this).children().eq(2).html());
          $("#3_"+scid).attr("readonly","readonly");
          $("#5_"+scid).focus();
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
        $("#3_"+scid).val("");
        $("#2_"+scid).val("");
        $("#5_"+scid).val("");
      //discount();
      dis_prec();
      amount();
      gross_amount();
      all_rate_amount();
      net_amount();
      $("#pop_close3").click();
    }
  });
  }


  function load_items(){
    $.post("index.php/main/load_data/m_items/item_list_all", {
      search : $("#pop_search").val(),
      stores : $("#stores").val()
    }, function(r){
      $("#sr").html(r);
      settings();
    }, "text");
  }



  function settings(){
    $("#item_list .cl").click(function(){        
      if($(this).children().eq(0).html() != "&nbsp;"){
       // if(check_item_exist($(this).children().eq(0).html())){
         if($("#df_is_serial").val()=='1'){
           check_is_serial_item2($(this).children().eq(0).html(),scid);
         }

         check_is_batch_item2($(this).children().eq(0).html(),scid); 
         $("#h_"+scid).val($(this).children().eq(0).html());
         $("#0_"+scid).val($(this).children().eq(0).html());
         $("#n_"+scid).val($(this).children().eq(1).html());
         $("#1_"+scid).val($(this).children().eq(2).html());
         $("#4_"+scid).val($(this).children().eq(3).html());
         
         
         check_is_batch_item(scid);
         $("#1_"+scid).removeAttr("disabled"); 
         $("#2_"+scid).removeAttr("disabled"); 
         $("#3_"+scid).removeAttr("disabled");
         $("#3_"+scid).focus();
         $("#pop_close").click();
       /*}else{
        set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
      }*/
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
      $("#3_"+scid).focus();
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
          }else{
            $("#11_"+scid).autoNumeric({mDec:2});
          }

          rate_amount();
          additional_amount();
          net_amount();
          $("#pop_close2").click();

          
        }else{
          set_msg("Item "+$(this).children().eq(1).html()+" is already added.");
        }
      }else{
        $("#hh_"+scid).val("");
        $("#00_"+scid).val("");
        $("#nn_"+scid).val("");
        $("#11_"+scid).val(""); 
        $("#22_"+scid).val(""); 
        $("#hhh_"+scid).val("");         
        
        rate_amount();
        additional_amount();
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
    $("input[type='hidden']").each(function(){
      if($(this).val() == id){
        v = false;
      }
    });
    
    return v;
  }

  function check_item_exist3(id){
    var v = true;
    return v;
  }


  function load_items(){
   $.post("index.php/main/load_data/t_pur_ret_sum/item_list_all", {
    search : $("#pop_search").val(),
    stores : $("#stores").val()
  }, function(r){
    $("#sr").html(r);
    settings();
  }, "text");
 }

 function load_items2(){
   $.post("index.php/main/load_data/r_additional_items/item_list_all", {
    search : $("#pop_search2").val(),
    stores : $("#stores").val(),
    type : "1"
  }, function(r){
    $("#sr2").html(r);
    settings2();
  }, "text");
 }


 function select_search(){
  $("#pop_search").focus();
}


function select_search2(){
  $("#pop_search2").focus();
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
  if($("#df_is_serial").val()=='1'){
    serial_items.sort();
    $("#srls").attr("title",serial_items);
    $("#srls").val(serial_items);
  }
  $("#dt").val($("#date").val());
  $("#qno").val($("#id").val());
  $("#drn").val($("#drn_no").val());
  $("#v_id").val($("#supplier_id").val());
  $("#netAmnt").val($("#net_amount").val());
  $("#is_duplicate").val("1");

  
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
      }else if(pid == 0){
        set_msg("Transaction is NOT completed");
             // location.href="";
           }else if(pid == 2){
            set_msg("No permission to add data.");
          }else if(pid == 3){
            set_msg("No permission to edit data.");
          }else if(sid[0]==1){
              //$("#btnSave").css("display","none");
              //$("#btnSavee").css("display","inline");
              $("#btnSave").attr("disabled",true); 
              if(confirm("Save Completed, Do You Want A print?")){
                if($("#is_prnt").val()==1){
                  $("#qno").val($("#id").val());
                  //  $("#qno").val(sid[1]);
                  $("#print_pdf").submit();
                }
                reload_form();
              }else{
                location.href="";
              }


            }else{
              set_msg(pid,"error");
            }
            loding();
          }
        });
}


function reload_form(){
  setTimeout(function(){
    window.location = '';
  },1); 
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
  var v = false;
  var z = true;
  
  for(var i=0; i<25 ;i++){
   if($("#2_"+i).val() !="" || $("#2_"+i).val() !="0") {
    if(parseFloat($("#2_"+i).val())>parseFloat($("#rmax_"+i).val())){
      set_msg("Maximum available quntity is " +$("#rmax_"+i).val(),"error");	
      return false;
    }
  } 

  var cont=parseInt(0);
  for(var a=0; a<25 ;a++){
    if($("#01_"+i).val()==$("#0_"+a).val()){
      if($("#2_"+a).val()!=''){
        cont=cont+parseInt($("#2_"+a).val());
        $("#maxretqty_"+a).val(cont);          
        if(cont>parseInt($("#rmax_"+a).val())){
          set_msg("This item "+$("#0_"+a).val()+" maximum return qty is "+$("#rmax_"+a).val());
          return false;
        }
      }
    }
  }

}

for(var t=0; t<25; t++){
  if($("#subcode_"+t).data("is_click")==1 && $("#subcode_"+t).val()==""){
    set_msg("Not enough sub items in ("+$("#0_"+t).val()+")" ,"error");
    return false;
  }
}


$("input[type='hidden']").each(function(){
  if($(this).val() != "" && $(this).val() != 0){
    v = true;
  }
});


$(".reason").each(function(e){
  if($("#7_"+e).val()=="" && $("#0_"+e).val()!=""){  
    z= false;
  }
});

total_discount();

if($("#id").val().length==0 || $("#id").val().length<0){
  set_msg("Please enter no");
  return false;
}else if($("#supplier").val() === $("#supplier").attr('title') || $("#supplier").val() == ""){
  set_msg("Please Select Supplier");
  $("#supplier_id").focus();
  return false;
}else if($("#stores").val() == 0 ){
  set_msg("Please Select Store");
  $("#stores").focus();
  return false;
}else if(v == false){
  set_msg("Please use minimum one item.");
  return false;
}else if(z==false){
 set_msg("Please enter return reason","error");
}else if(check_item_exist_new()=='0'){
  return false;
}else{
  var supplier=$("#supplier_id").val();
  var grn_no=$("#hid_grn").val();
  
  if(grn_no!=0){
    $.post("index.php/main/load_data/t_pur_ret_sum/check_grn_no/",{
      supplier_id:supplier,
      grn_no:grn_no
    },function(res){
      check_grn_result(res);
    },"text");
  }else{
    return true;
  }
}
}


function check_grn_result(res){   
  if(res==1){  
   save();
 }else{  
  set_msg("Please check the grn no with supplier");
  return false;
}

}



function check_item_exist_new(){

  for(var fr=0;fr<25;fr++){
    if(typeof($("#0_"+fr).val()) != 'undefined' && $("#0_"+fr).val()!=""){
      var item=$("#0_"+fr).val();
      var batch=$("#3_"+fr).val();
      for(var e=fr+1;e<25;e++){
        if(typeof($("#0_"+e).val()) != 'undefined' && $("#0_"+fr).val()!=""){
         if(item==$("#0_"+e).val() && batch==$("#3_"+e).val()){
          set_msg(" Item "+$("#0_"+e).val()+" is already added.","error");
          return "0";
        }
      }
    }
  }

}

}


function set_delete(){
  var id = $("#hid").val();
  if(id != 0){
    if(confirm("Are you sure to delete this purchase return ["+$("#hid").val()+"]? ")){
      $.post("index.php/main/delete/t_pur_ret_sum", {
        trans_no:$("#hid").val(),
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
    $("#5_"+scid).val(m_round(dis_pre));
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


function discount2(){
  var qty=parseFloat($("#21_"+scid).val());
  var price=parseFloat($("#41_"+scid).val());
  var dis_pre=parseFloat($("#51_"+scid).val());
  var discount="";

  if(!isNaN(qty)&& !isNaN(price) && !isNaN(dis_pre)){
    discount=(qty*price*dis_pre)/100;
    $("#61_"+scid).val(m_round(discount));
  }
  
}

function dis_prec2(){
  var qty=parseFloat($("#21_"+scid).val());
  var price=parseFloat($("#41_"+scid).val());
  var discount=parseFloat($("#61_"+scid).val());
  var dis_pre="";

  if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    dis_pre=(discount*100)/(qty*price);
    $("#51_"+scid).val(m_round(dis_pre));
  }

}

function amount2(){
  var qty=parseFloat($("#21_"+scid).val());
  var price=parseFloat($("#41_"+scid).val());
  var discount=parseFloat($("#61_"+scid).val());
  var amount="";


  if(!isNaN(qty)&& !isNaN(price) && !isNaN(discount)){
    dis_pre=(qty*price)-discount;
    $("#t1_"+scid).val(m_round(dis_pre));
  }else if(!isNaN(qty)&& !isNaN(price)){
    dis_pre=(qty*price);
    $("#t1_"+scid).val(m_round(dis_pre));
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
  var additional=loop=add_plus=add_deduct=0;
  $(".tf").each(function(){
    var add=parseFloat($("#22_"+loop).val());
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
  $("#total2").val(m_round(additional));
  $("#additional_add").val(m_round(add_plus));
  $("#additional_deduct").val(m_round(add_deduct));
}

function net_amount(){
  var additional=parseFloat($("#total2").val());
  var gross_amount=parseFloat($("#gross_amount").val());
  var net_amount=0;

  if(!isNaN(additional)&& !isNaN(gross_amount)){
    net_amount=gross_amount+additional;
    $("#net_amount").val(m_round(net_amount));
  }else{
    $("#net_amount").val(net_amount);
  }
  
}

function total_discount(){
  var td=0;
  set_cid($(".dis").attr("id"));
  $(".dis").each(function(){
    if(!isNaN($("#6_"+scid).val())){
      td=td+parseFloat($("#6_"+scid).val());
    }
  });

  $("#total_discount").val(td);
}

function load_data(id){
  var g=[];
  empty_grid();
  empty_grid2();
  loding();

  $.post("index.php/main/get_data/t_pur_ret_sum/", {
    id: id
  }, function(r){
    if(r=="2"){
     set_msg("No records");
   }else{
    $("#hid").val(id);   
    $("#id").val(id);
    $("#supplier_id").val(r.sum[0].supplier_id);
    $("#date").val(r.sum[0].ddate);
    $("#type").val(r.sum[0].type);
    $("#ref_no").val(r.sum[0].ref_no);
    $("#supplier_id").val(r.sum[0].supp_id);
    $("#supplier").val(r.sum[0].supplier_name);
    $("#sales_rep2").val(r.sum[0].rep_name);
    $("#date").val(r.sum[0].ddate); 
    $("#ref_no").val(r.sum[0].ref_no);
    $("#grnno").val(r.sum[0].grn_no);
    $("#drn_no").val(r.sum[0].drn_no);
    $("#update_po_no").val(r.sum[0].po_no);
    $("#memo").val(r.sum[0].memo);
    $("#stores").val(r.sum[0].store);
    set_select("stores","store_id");
    $("#gross_amount").val(r.sum[0].gross_amount);
    $("#total2").val(r.sum[0].other);
    $("#net_amount").val(r.sum[0].net_amount);
    $("#dt").val(r.sum[0].ddate);
    $("#qno").val(id);
    $("#drn").val(r.sum[0].drn_no);
    $("#v_id").val(r.sum[0].supp_id);
    $("#netAmnt").val(r.sum[0].net_amount);
    $("#del_officer").val(r.sum[0].del_officer);
    $("#vehicleNo").val(r.sum[0].vehicle_no);
    $("#additional_add").val(r.sum[0].additional_add);
    $("#additional_deduct").val(r.sum[0].additional_deduct);

    $("#btnApprove").attr("disabled", false);  
    
    if(r.sum[0].is_approve==1){
      $("#btnDelete5").attr("disabled", "disabled");
      $("#btnSave").attr("disabled", "disabled");
      $("#btnApprove").attr("disabled", "disabled");
      $("#mframe").css("background-image", "url('img/approved1.png')");
    }
    $("#btnSave").attr("disabled", "disabled");
    if(r.sum[0].is_cancel==1){
      $("#btnDelete5").attr("disabled", "disabled");
      $("#btnSave").attr("disabled", "disabled");
      $("#mframe").css("background-image", "url('img/cancel.png')");
    }

    if(r.sum[0].is_po_update==1){
      $("#update_po_status").val("1");
      $("#update_po").attr("checked",true);
      $("#update_po").attr("disabled",true);     
    }

    if(r.sum[0].grn_no=="0"){
      $("#ret_type").val('2');
    }

    for(var i=0; i<r.det.length;i++){
      $("#itemcode_"+i).val(r.det[i].icode);
      
      if($("#df_is_serial").val()=='1'){
        check_is_serial_item2(r.det[i].icode,i); 
        $("#numofserial_"+i).val(r.det[i].qty);              
        for(var a=0;a<r.serial.length;a++){
          if(r.det[i].icode==r.serial[a].item && r.det[i].batch_no==r.serial[a].batch){
          g.push(r.serial[a].serial_no);
          $("#all_serial_"+i).val(g);
        }   
      }
      g=[];
    }

    $("#h_"+i).val(r.det[i].icode);
    $("#0_"+i).val(r.det[i].icode);
    $("#n_"+i).val(r.det[i].idesc);
    $("#1_"+i).val(r.det[i].model);
    $("#2_"+i).val(r.det[i].qty);
    $("#3_"+i).val(r.det[i].batch_no);
    $("#4_"+i).val(r.det[i].price);
    $("#5_"+i).val(r.det[i].discountp);
    $("#6_"+i).val(r.det[i].discount);
    $("#ret_"+i).val(r.det[i].reason);
    $("#7_"+i).val(r.det[i].rdesc);
    
    scid=i; 
    amount();  
    
    check_is_batch_item2(r.det[i].icode,i);
    is_sub_item(i);
  }
  gross_amount();

  if(r.add!=2){
    for(var i=0; i<r.add.length;i++){      
      $("#hhh_"+i).val(r.add[i].type);
      $("#00_"+i).val(r.add[i].type);
      $("#nn_"+i).val(r.add[i].description);
      $("#11_"+i).val(r.add[i].rate_p);
      $("#22_"+i).val(r.add[i].amount);
      get_sales_type(i);             
    }
  }


  input_active();
}
loding();
}, "json"); 
}


function empty_grid(){
  for(var i=0; i<25; i++){
    $("#h_"+i).val("");
    $("#0_"+i).val("");
    $("#n_"+i).val("");
    $("#1_"+i).val(""); 
    $("#2_"+i).val(""); 
    $("#21h_"+i).val(""); 
    $("#3_"+i).val(""); 
    $("#4_"+i).val(""); 
    $("#rmax_"+i).val("");
    $("#5_"+i).val(""); 
    $("#6_"+i).val(""); 
    $("#7_"+i).val(""); 
    $("#t_"+i).val("");
    $("#ret_"+i).val("");
    $("#maxretqty_"+i).val("");
    $("#subcode_"+i).val("");
    $("#subcode_"+i).removeAttr("data-is_click");
    $("#hhh_"+i).val("");
    $("#hh_"+i).val("");
    $("#00_"+i).val("");
    $("#nn_"+i).val("");
    $("#11_"+i).val("");
    $("#22_"+i).val("");
    
  }
  $(".quns").css("display","none");
  $(".qunsb").css("display","none");
}

function get_sales_type(i){
  $.post("index.php/main/load_data/r_additional_items/get_type",{
   id:$("#00_"+i).val()
 },function(res){      
  $("#hh_"+i).val(res);     
},"text");
  
}
