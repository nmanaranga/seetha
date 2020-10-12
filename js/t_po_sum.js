$(document).ready(function(){
  $("#load_req_duplecate").css("display","none");
  $("#code").blur(function(){
    check_code();
  });


  $("#dpo").click(function(){
    if($(this).is(":checked")){
      $(".fo").css("pointer-events","auto");
    }else{
      $(".fo").css("pointer-events","none");
      empty_grid();
    }
  });



// alert($("#type").val());
    // $("#type").change(function(){
      $.post("index.php/main/load_data/t_po_sum/get_max_no_type", {
        table:'t_po_sum',
        nno:'nno',
        type:$("#type").val(),
        hid:$("#hid").val(),
      }, function(res){
        $("#id").val(res);
        empty_grid();
        empty_all();
      },"text");
    // });

    $(".fo").dblclick(function(){
      set_cid($(this).attr("id"));  
      if($(this).val()!=""){
        $.post("index.php/main/load_data/utility/get_sub_item_detail2", {
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
       $("#4_"+scid).val("");
       $("#ccc_"+scid).val("");
       $("#max_"+scid).val("");
       $("#min_"+scid).val("");
       $("#b1_"+scid).val("");
       $("#btn_"+scid).removeAttr("display");
       $("#btn_"+scid).attr("display","none");


       $("#lpm_"+scid).val("");
       $("#spm_"+scid).val("");


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


    $("#cost_print").click(function(){
      if($("#cost_print").is(":checked")){
        $("#cost_prnt").val("1");
      }else{
        $("#cost_prnt").val("0");
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

    $("#tgrid").tableScroll({height:355});

    $("#load_req").click(function(){
      if($("#approve_no").val()!=""){
        load_request_note();
      }else{
        set_msg("Please select approve no");
      }
    });

    $(".fo").keydown(function(e){
     set_cid($(this).attr("id"));

     $("#0_"+scid).val("");
     $("#n_"+scid).val("");
     $("#1_"+scid).val("");
     $("#2_"+scid).val("");
     $("#3_"+scid).val("");
     $("#4_"+scid).val("");
     $("#5_"+scid).val("");


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



    $("#approve_no").keydown(function(e){
     if(e.keyCode==112){
       $("#pop_search").val($("#0_"+scid).val());
       load_request();
       $("#serch_pop").center();
       $("#blocker").css("display", "block");
       setTimeout("select_search()", 100);
     } 
   });


    $("#pop_search").keyup(function(e){
      if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { 
        load_request();
      }
    });

    $("#pop_search").gselect(); 

    $(".qty, .price").blur(function(){
     set_cid($(this).attr("id"));
     var qty=parseFloat($("#3_"+scid).val());
     var price=parseFloat($("#4_"+scid).val());

     if(isNaN(qty)){ qty=0; }
     if(isNaN(price)){ price=0; }

     var amount=qty*price;
     if(amount==0){amount="";}else{
       $("#5_"+scid).val(m_round(amount));
     }


     var loop=total_amount=0; 

     $(".amount").each(function(){

       var get_amount=parseFloat($("#5_"+loop).val()); 
       if(isNaN(get_amount)){ get_amount=0;}
       total_amount=total_amount+get_amount;
       loop++;
     });



     $("#total2").val(m_round(total_amount));


   });


    $("#id").keydown(function(e){
      if(e.keyCode == 13){
        $(this).blur();
        load_data($(this).val());
        $(this).attr("readonly","readonly");
        $("#load_req").css("display","none");
        $("#load_req_duplecate").css("display","inline");
      }
    });

    $("#supplier_id").keydown(function(e){ 
      if(e.keyCode==112){
        $("#pop_search2").val($("#supplier_id").val());
        load_sup();
        $("#serch_pop2").center();
        $("#blocker2").css("display", "block");
        setTimeout("$('#pop_search2').focus()", 100);   
      }
      $("#pop_search2").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
          load_sup();
        }
      });
      if(e.keyCode==46){
       $("#supplier_id").val("");
       $("#supplier").val("");
     }  
   }); 
  });


function empty_all(){
  $("#supplier_id").val("");
  $("#supplier").val("");
  $("#approve_no").val("");
  $("#memo").val("");
  $("#ref_no").val("");
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
  $("#is_duplicate").val("1");
  var frm = $('#form_');
  loding();
  $.ajax({
   type: frm.attr('method'),
   url: frm.attr('action'),
   data: frm.serialize(),
   success: function (pid){
    var sid=pid.split('@');

    if(sid[0]==1){   
      var sid=pid.split('@');            
                    //$("#btnSave").css("display","none");
                    //$("#btnSavee").css("display","inline");
                    $("#btnSave").attr("disabled",true);
                    loding();
                    if(confirm("Save Completed, Do You Want A print?")){
                      if($("#is_prnt").val()==1){
                        $("#qno").val(sid[1]);
                        $("#print_pdf").submit();
                      }
                      reload_form();
                    }else{
                      location.href="";
                    }  
                  }else if(pid == 2){
                    set_msg("No permission to add data.");
                  }else if(pid == 3){
                    set_msg("No permission to edit data.");
                  }else{
                   loding();
                   set_msg(pid);
                 }

               }
             });
}


function reload_form(){
  setTimeout(function(){
    location.href= '';
  },50); 
}


function load_sup(){
  $.post("index.php/main/load_data/utility/f1_selection_list_supplier", {
    data_tbl:"m_supplier",
    field:"code",
    field2:"name",
    preview2:"Supplier Name",
    add_query:" AND is_inactive='0' AND is_blacklist='0'",
    search : $("#pop_search2").val() 
  }, function(r){
    $("#sr2").html(r);
    settings_sup();      
  }, "text");
}

function settings_sup(){
  $("#item_list .cl").click(function(){        
    $("#supplier_id").val($(this).children().eq(0).html());
    $("#supplier").val($(this).children().eq(1).html());
    $("#pop_close2").click();                
  })    
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
    $.post("index.php/main/delete/t_po_sum", {
      id : id,
      type: $("#type").val()
    }, function(res){
      if(res == 1){
        loding();
        delete_msg();
      }else if(res == 2){
        set_msg("No permission to cancel data.");
      }else{
        set_msg(res);
      }

    }, "text");
  }
}

function is_edit($mod){
  $.post("index.php/main/is_edit/user_permissions/is_edit", {
    module : $mod
  }, function(r){
   if(r==1){
    $("#btnSave").removeAttr("disabled", "disabled");
  }else{
    $("#btnSave").attr("disabled", "disabled");
  }
}, "json");
}

function set_edit(code){
  loding();
  $.post("index.php/main/get_data/t_po_sum", {
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

// function load_items(){
//     $.post("index.php/main/load_data/t_po_sum/item_list_all", {
//         search : $("#pop_search").val(),
//         stores : false
//     }, function(r){
//         $("#sr").html(r);
//         settings();

//     }, "text");
// }

function settings(){
  $("#item_list .cl").click(function(){
    if($(this).children().eq(0).html() != "&nbsp;"){
      $("#approve_no").val($(this).children().eq(0).html());
      $("#pop_close").click();
    }
  });
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

function load_request_note(){
  empty_grid();
  $.post("index.php/main/load_data/t_po_sum/load_request_note",{
    nno:$("#approve_no").val(),
    type:$("#type").val()
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
      $("#nno_"+i).val(r.det[i].nno);
      $("#bc_"+i).val(r.det[i].bc);
      $("#cl_"+i).val(r.det[i].cl);                    
    }

    $("#supplier_id").val(r.det[0].supplier);
    $("#supplier").val(r.det[0].name);
    $(".price").blur();
  }else{
    set_msg("No Data");
  }
},"json");
}


function load_data(id){
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_po_sum/", {
    id: id,
    type:$("#type").val()

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
      $("#supplier_id").val(r.supplier[0].supp_id);
      $("#supplier").val(r.supplier[0].name);
      $("#rep_sup").val(r.supplier[0].supp_id);
      $("#memo").val(r.sum[0].comment);
      $("#date").val(r.sum[0].ddate); 
      $("#ref_no").val(r.sum[0].ref_no);
      $("#total2").val(r.sum[0].total_amount);
      $("#deliver_date").val(r.sum[0].deliver_date);
      $("#ship_to_bc").val(r.sum[0].ship_to_bc);
      $("#rep_date").val(r.sum[0].ddate);
      $("#rep_deliver_date").val(r.sum[0].deliver_date);
      $("#approve_no").val(r.sum[0].approve_no);
      $("#type").val(r.sum[0].type);
      if(r.sum[0].dpo=='1'){
       $("#dpo").attr("checked","checked");
       $(".fo").css("pointer-events","auto");
     }

     for(var i=0; i<r.det.length;i++){
      $("#h_"+i).val(r.det[i].item);
      $("#0_"+i).val(r.det[i].item);
      $("#n_"+i).val(r.det[i].description);
      $("#1_"+i).val(r.det[i].model);
      $("#2_"+i).val(r.det[i].current_qty);
      $("#3_"+i).val(r.det[i].qty);
      $("#4_"+i).val(r.det[i].cost);
      $("#5_"+i).val(r.det[i].amount);
      if(r.sum[0].dpo=='1'){
        $("#3_"+i).removeClass("g_col_fixed");
        $("#3_"+i).removeAttr("readonly");
      }
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

function load_request(){
  var type=$("#type").val();

  $.post("index.php/main/load_data/t_po_sum/f1_selection_list_po", {
    search : $("#pop_search").val(),
    supplier:$("#supplier_id").val(),
    type: $("#type").val()
  }, function(r){
    $("#sr").html(r);
    settings();   
  }, "text");
}



function load_data_2(){
  $.post("index.php/main/load_data/t_po_sum/get_item_without_approve", {
    search:$("#pop_search10").val()
  }, function(r){
    $("#sr10").html(r);
    settings_item_w_po(); 
  }, "text");
}

function settings_item_w_po(){
  $("#item_list .cl").click(
    function(){   
     // if(check_item_exist($(this).children().eq(0).html())){
       $("#3_"+scid).removeClass("g_col_fixed");
       $("#3_"+scid).removeAttr("readonly");
       $("#h_"+scid).val($(this).children().eq(0).html());
       $("#0_"+scid).val($(this).children().eq(0).html());
       $("#n_"+scid).val($(this).children().eq(1).html());
       $("#1_"+scid).val($(this).children().eq(2).html());
       $("#2_"+scid).val($(this).children().eq(6).html());
       $("#4_"+scid).val($(this).children().eq(3).html());
       $("#nno_"+scid).val(0);
       $("#cl_"+scid).val($(this).children().eq(7).html());      
       $("#bc_"+scid).val($(this).children().eq(8).html());

       $("#pop_close10").click(); 
     /*}else{

     }*/
   })    
}
