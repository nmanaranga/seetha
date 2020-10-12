var a=[];
var approve_qty=[];

$(document).ready(function(){
    $("#btnApprove").attr("disabled",true);
    var p_code = window.location.search.split('=')[2];
    if(p_code != undefined){
       load_data(p_code);
    }

    $("#load_item_request_duplicate").css("display","none");
    $("#code").blur(function(){
        check_code();
    });

    $("#type").change(function(){
        empty_grid2(); 
    });

    $(document).on('click','.bc_qty',function(){
      set_cid($(this).attr("id"));
      var cc = $(this).closest('tr').find("td:nth-child(1)").html();
      var item = $(this).closest('tr').find("td:nth-child(4)").html();
      var cl = cc.split("-")[1];

      $("#serch_pop10").center();
      $("#pop_search10").css("display", "block");
      $("#blocker4").css("display", "block");
      setTimeout("$('#pop_search10').focus()", 100);  
      qty_b_wise(cl,item);

    });


    $("#load_pendings").click(function(){
          load_pen();
          $("#serch_pop13").center();
          $("#pop_search13").css("display", "block");
          $("#blocker").css("display", "block");
          setTimeout("$('#pop_search13').focus()", 100);   
    })

    $("#pop_search13").keyup(function(e){
        if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
            load_pen();
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
    
    $(".fooo").dblclick(function(){
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

    $("#id").keydown(function(e){
        if(e.keyCode == 13){

          load_data($(this).val());
          $("#load_item_request").css("display", "none");
          $("#load_item_request_duplicate").css("display","inline");
          $("#id").attr("readonly","readonly");
        }
    });

    $("#supplier_id").keydown(function(e){ 
        if(e.keyCode==112){
          $("#supplier_id").val($("#supplier_id").val());
          load_sup();
          $("#serch_pop").center();
          $("#pop_search").css("display", "block");
          $("#blocker").css("display", "block");
          setTimeout("$('#pop_search').focus()", 100);   
        }
        $("#pop_search").keyup(function(e){
          if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 && e.keyCode != 112 ) { 
            load_sup();
          }
        });
        if(e.keyCode==46){
           $("#supplier_id").val("");
           $("#supplier").val("");
        }  
    });

    $("#btnDelete").click(function(){
        if($("#hid").val()!="0"){
           set_delete($("#id").val()); 
       }else{
        set_msg("Please load data to cancel");
       }
        
    });

    $("#btnApprove").click(function(){
        if(validate()){
           save();    
        }
    });

    $("#btnSave1").click(function(){
        $("#app_status").attr("disabled","disabled");
        if(validate()){
           save();    
        }
    });

    $("#slide_arrow").click(function(){
        //$("#det_box").slideToggle(); 
    }); 


    $("#supplier_id").keydown(function(e){
        if(e.keyCode=='46'){
            $("#supplier_id").val("");
            $("#supplier").val("");
        }
    })

    $("#pop_search").css("display","none");
    $("#load_item_request").click(function(){ 
        if($("#type").val()=="0"){
            set_msg("Please select type");
        }else{
            load_sup_data($("#supplier_id").val());
        }      
    });

    $("#tgrid").tableScroll({height:300});
    $(".fo").click(function(){
        set_cid($(this).attr("id"));         
        $("#light").css("display","block");
        $("#fade").css("display","block");
        if($("#hid").val()==0){
            load_req_details(scid);
        }else{
           load_det_already_saved(); 
        }
    });

    $("#popclose").click(function(){
        $("#light").css("display","none");
        $("#fade").css("display","none");
    });

    $(".ap").autoNumeric({mDec:0});

    $(document).on('keyup','.ap',function(){
      if($("#note_"+scid).val()=="" && $("#appr_qty_"+scid).val()!=""){
        set_msg("Please Enter Note");
        $("#appr_qty_"+scid).val("");
      }else{
        approve(scid);
      }
    });

    $("#sr").delegate(".ap", "blur", function(){
        for(var i=0;i<25;i++){
            if( parseFloat($("#cq_0").val() ) < parseFloat($("#0").val()) ){
                set_msg("Current Qty is not enough");
        	}
        } 
    });


    $("#sr").delegate(".ap , .bc", "blur", function(){
        var b = $(this).attr("id");
        var x = $("#bc_"+b).html();
        var y = $("#"+$(this).attr("id")).val();
        var z = $("#nn_"+b).html();
        var a = $("#itm_"+b).html();
        $(this).autoNumeric({mDec:0});
    });

           

});

function qty_b_wise(cl,item){
  $.post("index.php/main/load_data/t_req_approve_sum/qty_branch", {
      cluster:cl,
      item:item
  }, function(r){
      $("#sr10").html(r);
 }, "text");
}

function load_pen(){
  $.post("index.php/main/load_data/t_req_approve_sum/pending_req", {
      supplier:$("#supplier_id").val(),
      search : $("#pop_search13").val(), 
      type:$("#type").val()
  }, function(r){
      $("#sr13").html(r);
      settings_pen();      
 }, "text");
}


function settings_pen(){
    $("#item_list .cl").click(function(){        
        $("#supplier_id").val($(this).children().eq(2).html());
        $("#supplier").val($(this).children().eq(3).html());
        $("#pop_close13").click();                
    })    
}





function load_sup(){
  $.post("index.php/main/load_data/utility/f1_selection_list_supplier", {
      data_tbl:"m_supplier",
      field:"code",
      field2:"name",
      preview2:"Supplier Name",
      add_query:" AND is_inactive='0' AND is_blacklist='0' ",
      search : $("#pop_search").val() 
  }, function(r){
      $("#sr").html(r);
      settings_sup();      
 }, "text");
}

function settings_sup(){
    $("#item_list .cl").click(function(){        
        $("#supplier_id").val($(this).children().eq(0).html());
        $("#supplier").val($(this).children().eq(1).html());
        $("#pop_close").click();                
    })    
}


function load_approve(x){
    var det = $("#approve_"+x).val();
    if(det!=""){
        var get_app=det.split(",");
        var count=get_app.length;
        for(var c=0; c<count; c++){
            got_approve=get_app[c].split("~")[11];
            got_note=get_app[c].split("~")[12];
            $("#appr_qty_"+c).val(got_approve);
            $("#note_"+c).val(got_note);
        }
    }
}



function approve(y){
    approve_qty=[];
    app_qty=0;
    $(".ap").each(function(x){ 
        approve_qty.push($("#0_"+y).val()+"~"+$("#req_det_"+x).val()+"~"+this.value+"~"+$("#note_"+x).val());
        $("#approve_"+y).val(approve_qty);
        qty=parseInt(this.value);
        if(isNaN(qty)){
            qty=0;
        }
        app_qty=parseInt(app_qty)+qty;
    });
    
    $("#5_"+y).val(app_qty);
}




function select_search(){
    $("#0").focus();
}


function set_cus_values(f){
        var v = f.val();
        v = v.split("|");
        if(v.length == 2){
        f.val(v[0]);
        $("#supplier").val(v[1]);
        // $("#supplier").attr("class", "input_txt_f");

        }
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
                $("#btnSave").attr("disabled",true);
                loding();
                sucess_msg();
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

function validate(){
    var appr = parseInt(0);
    for(var g=0; g<25; g++){
        if($("#5_"+g).val()!=""){
            appr=appr+parseInt($("#5_"+g).val());
        }  
    }
    if(appr==0){
        set_msg("Approve quantity should be grater than 0 (zero)","error");
        return false;
    }else if($("#supplier_id").val()==""){
        set_msg("Supplier shouldn't be empty","error");
        return false;
    }
    return true;
}
    
function set_delete(code){
    if(confirm("Are you sure to cancel transaction no "+code+"?")){
        loding();
        $.post("index.php/main/delete/t_req_approve_sum", {
            code : code
        }, function(res){
            if(res == 1){
               loding();
               delete_msg();
            }else if(res == 2){
                set_msg("No permission to delete data.");
            }else{
                loding();
                 set_msg(res);
            }
            
        }, "text");
    }
}

function is_edit($mod) {
    $.post("index.php/main/is_edit/user_permissions/is_edit", {
        module : $mod
    }, function(r){
       if(r==1){
            $("#btnSave").removeAttr("disabled", "disabled");
        }else{
            $("#btnSave").attr("disabled", "disabled");
        }
    },"json");
}
    
function set_edit(code){
    loding();
    $.post("index.php/main/get_data/t_req_approve_sum_new", {
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
        $("#4_"+i).val("");
        $("#5_"+i).val("");
        $("#approve_"+i).val("");
        $("#item_price_"+i).val("");
    }
}

function load_sup_data(supplier){
    empty_grid2(); 
    loding();
    $.post("index.php/main/load_data/t_req_approve_sum/load_sup_data", {
        sup:supplier,
        type:$("#type").val()
    },function(r){
        var x=0;
            if(r=="1"){
               set_msg("No records");
            }else{
                $("#type").val(r.det[0].type);
            for(var i=0;i<r.det.length;i++){
                $("#0_"+i).val(r.det[i].code);
                $("#n_"+i).val(r.det[i].description);
                $("#1_"+i).val(r.det[i].model);
                $("#2_"+i).val(r.det[i].r_qty);
                $("#3_"+i).val(r.det[i].roq);
                $("#4_"+i).val(r.det[i].bal_qty);
                $("#5_"+i).val("0");
                $("#6_"+i).removeAttr("disabled");
            } 

            input_active();
    }
    loding();
    }, "json");
}

function load_items(item){
    if($("#hid").val()=="0"){
        var path="index.php/main/load_data/t_req_approve_sum/item_list_all"
    }else{
        var path="index.php/main/load_data/t_req_approve_sum/item_load_list_all"
    }
}

function settings(){
    $("#item_list .cl .ap").blur(function(){
       $("#5_"+scid).val(set_total()); 
    });
}

function set_total(){
    var total=0;
    $("#item_list .cl .ap").each(function(e){
        if(!isNaN(parseFloat($("#"+e).val()))){
            total+=parseFloat($("#"+e).val());
        }
        $("#branch_num").val($(this).children().eq(1).html());
    });

   
    return parseFloat(total);
}

function qty_save(x,y,z,a){  
    $.post("index.php/main/load_data/t_req_approve_sum/qty_save", {
        x:x,
        y:y,
        z:z,
        a:a
    }, function(r){},
    "json");
}

function empty_grid(){
    $("#supplier_id").val("");
    $("#supplier").val("");
    $("#date").val("");
    
    for(var x=0; x<25; x++){
        $("#0_"+x).val("");
        $("#n_"+x).val("");
        $("#1_"+x).val("");
        $("#2_"+x).val("");
        $("#3_"+x).val("");
        $("#4_"+x).val("");
        $("#5_"+x).val("");
    }
    $(".br").attr("disabled", true);
}


function empty_grid2(){
    
    for(var x=0; x<25; x++){
        $("#0_"+x).val("");
        $("#n_"+x).val("");
        $("#1_"+x).val("");
        $("#2_"+x).val("");
        $("#3_"+x).val("");
        $("#4_"+x).val("");
        $("#5_"+x).val("");
    }
    $(".br").attr("disabled", true);
}


function load_data_form(id){
    $("#id,#hid").val(id);
    $("#hid").attr("title",id);
    load_data(id);
    $("#btnSave").attr("disabled","disabled");
    $("#det_box").slideToggle(); 
}

function load_data(id){
  empty_grid();
  loding();
  $.post("index.php/main/get_data/t_req_approve_sum/", {
    id: id
  }, function(r){
    if(r=="2"){
      set_msg("No records");
    }else{
      $("#id").val(id);  
      $("#hid").val(id);    
      $("#date").val(r.sum[0].ddate);
      $("#supplier_id").val(r.sum[0].supplier);
      $("#supplier").val(r.sum[0].name);
      $("#type").val(r.sum[0].type);
      //$("#sub_hid").val(r.transfer[0].sub_no);
      $("#btnApprove").attr("disabled",false);

      if(r.sum[0].is_level_3_approved==1){
        $("#mframe").css("background-image", "url('img/approved1.jpg')");
        $("#btnApprove").attr("disabled",true);
        $("#btnSave1").attr("disabled",true);
        $("#btnDelete").attr("disabled",true);
      }

      if(r.sum[0].is_cancel==1){
        $("#btnDelete").attr("disabled", "disabled");
        $("#btnSave").attr("disabled", "disabled");
        $("#mframe").css("background-image", "url('img/cancel.png')");
      }
      
   
      for(var i=0; i<r.det.length;i++){
        $("#0_"+i).val(r.det[i].code);
        $("#n_"+i).val(r.det[i].description);
        $("#1_"+i).val(r.det[i].model);
        $("#2_"+i).val(r.det[i].request);
        $("#3_"+i).val(r.det[i].roq);
        $("#4_"+i).val(r.det[i].current_qty);
        $("#5_"+i).val(r.det[i].approve_qty);
        $("#6_"+i).removeAttr("disabled");


        item_det=[];
        item_det.push(r.det[i].cost+"~"+r.det[i].last_price+"~"+r.det[i].max_price+"~"+r.det[i].last_pre+"~"+r.det[i].max_pre);
        $("#item_price_"+i).val(item_det);
        
        app_qty=[];
        for(var x = 0; x<r.table.length; x++){
            if(r.det[i].code==r.table[x].item){
                app_qty.push(r.table[x].item+"~"+r.table[x].sub_cl+"~"+r.table[x].sub_bc+"~"+r.table[x].sub_nno+"~"+r.table[x].rol+"~"+r.table[x].roq+"~"+r.table[x].current_qty+"~"+r.table[x].request+"~"+r.table[x].cl_name+"~"+r.table[x].bc_name+"~"+r.table[x].req_0_level_approve+"~"+r.table[x].approve+"~"+r.table[x].note);            
                
                $("#approve_"+i).val(app_qty);
            }
        }
      }

     input_active();
    }
  loding();
  }, "json");
}


function load_req_details(){
    html=item_det="";
    description=$("#n_"+scid).val();
    model=$("#1_"+scid).val();

    item_det ="<table style='width:100%;margin-top:-20px;padding:5px 0px;'>";
    item_det+="<tr><td style='width:30%;color:#fff;background:#476bb2;'>Supplier Code</td><td style='width:70%;color:#fff;background:#476bb2;'>Supplier</td></tr>";
    item_det+="<tr><td style='width:30%;border:1px solid #aaa;'>"+$("#supplier_id").val()+"</td><td style='width:70%;border:1px solid #aaa;'>"+$("#supplier").val()+"</td></tr></table>";
    
    item_det+="<table style='width:100%;margin-top:-10px;padding:5px 0px;'>";
    item_det+="<tr><td style='width:15%;color:#fff;background:#476bb2;'>Item Code</td>";
    item_det+="<td style='width:25%;color:#fff;background:#476bb2;'>Description</td>";
    item_det+="<td style='width:15%;color:#fff;background:#476bb2;'>Model</td>";
    item_det+="<td style='width:10%;color:#fff;background:#476bb2;'>Cost</td>";
    item_det+="<td style='width:10%;color:#fff;background:#476bb2;'>Last Price</td>";
    item_det+="<td style='width:10%;color:#fff;background:#476bb2;'>Selling Price</td>";
    item_det+="<td style='width:7.5%;color:#fff;background:#476bb2;'>Last Sale %</td>";
    item_det+="<td style='width:7.5%;color:#fff;background:#476bb2;'>Selling %</td></tr>";
    
    item_det+="<tr><td style='width:15%;border:1px solid #aaa;'>"+$("#0_"+scid).val()+"</td>";
    item_det+="<td style='width:width:25%;border:1px solid #aaa;'>"+description+"</td>";
    item_det+="<td style='width:width:15%;border:1px solid #aaa;'>"+model+"</td>";

    $.post("index.php/main/load_data/t_req_approve_sum/load_req_details", {
       code:$("#0_"+scid).val(),
       supplier:$("#supplier_id").val(),
       type:$("#type").val()
    },function(r){

        if(r.qty_cl.length>0){
            current_stock="<table style='padding:5px 0px;'>";
            current_stock+="<tr><td style='color:#fff;background:#5b89e5;'>Current Quantity Cluster Wise</td><td style='color:#fff;background:#5b89e5;'>QTY</td></tr>";
            for(x=0;x<r.qty_cl.length;x++){
                current_stock+="<tr><td style='border:1px solid #aaa;padding:3px 5px;'>" + r.qty_cl[x].description + " - "+ r.qty_cl[x].cl+ "</td> <td style='border:1px solid #aaa;padding:3px 5px;'>"+r.qty_cl[x].qty+"</td><td><input type='button' class='bc_qty' title='..' id='bqty_"+x+"' /></td><td style='display:none;'>"+$("#0_"+scid).val()+"</td></tr>";
            }
            current_stock+="</table>";
        }else{
            current_stock="";
        }  
        
        item_det+="<td style='width:10%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+r.item_price[0].purchase_price+"</td>";
        item_det+="<td style='width:10%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+r.item_price[0].min_price+"</td>";
        item_det+="<td style='width:10%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+r.item_price[0].max_price+"</td>";
        item_det+="<td style='width:7.5%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+price_presentage(r.item_price[0].min_price,r.item_price[0].purchase_price)+"</td>";
        item_det+="<td style='width:7.5%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+price_presentage(r.item_price[0].max_price,r.item_price[0].purchase_price)+"</td></tr></table>";
        
        $("#item_price_"+scid).val($("#0_"+scid).val()+"~"+r.item_price[0].purchase_price+"~"+r.item_price[0].min_price+"~"+r.item_price[0].max_price+"~"+price_presentage(r.item_price[0].min_price,r.item_price[0].purchase_price)+"~"+price_presentage(r.item_price[0].max_price,r.item_price[0].purchase_price));
        
        if(r.req_det.length>0){
            request_qty ="<table style='padding:5px 0px;'>";
            request_qty+="<tr><td style='color:#fff;background:#5b89e5;'>Request Quantity Cluster Wise</td><td style='color:#fff;background:#5b89e5;'>QTY</td></tr>";
            for(x=0;x<r.req_det.length;x++){
                request_qty+="<tr><td style='border:1px solid #aaa;padding:3px 5px;'>" + r.req_det[x].description +" -("+ r.req_det[x].cl+")</td> <td style='border:1px solid #aaa;padding:3px 5px;'>"+r.req_det[x].qty+"</td></tr>";
            }
            request_qty+="</table>";
        }else{
            request_qty ="";
        }

        if(r.tbl_det.length>0){
            d_table  ="<table style='padding:5px 0px;width:100%;'>";
            d_table +="<tr><td style='color:#fff;background:#84adff;'>CL</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Cluster Name</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>BC</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Branch Name</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>No</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>ROL</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>ROQ</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Cur Stock</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Branch Requested Qty</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Request QTY</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Approve Qty</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Note</td></tr>";


            for(x=0;x<r.tbl_det.length;x++){

                d_table +="<tr><td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].cl+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].cl_name+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].bc+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].bc_name+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].nno+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].rol+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].roq+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].bal_qty+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].lel_0+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+r.tbl_det[x].r_qty+"</td>";
                d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>";
                d_table +="<input type='hidden' id='req_det_"+x+"' name='req_det' value='"+r.tbl_det[x].cl+"~"+r.tbl_det[x].bc+"~"+r.tbl_det[x].nno+"~"+r.tbl_det[x].rol+"~"+r.tbl_det[x].roq+"~"+r.tbl_det[x].bal_qty+"~"+r.tbl_det[x].r_qty+"~"+r.tbl_det[x].cl_name+"~"+r.tbl_det[x].bc_name+"~"+r.tbl_det[x].lel_0+"' />";
                d_table +="<input type='text' id='appr_qty_"+x+"' style='width:100%;height:100%;border:none;text-align:right' class='ap' title='' value=''/></td>";
                d_table +="<td style=' width:250px;border:1px solid #aaa;padding:3px 5px;'><input type='text' name='note_"+x+"'  id='note_"+x+"' style='width:100%;height:100%;border:none;text-align:right'/></td></tr>";
           }

           d_table +="</table>";

        }else{
            d_table="";
        }

        $("#item_det").html(item_det);
        $("#current_qty_det").html(current_stock);
        $("#d_table").html(d_table);
        load_approve(scid);
        $("#request_qty_det").html(request_qty);
        
    
    },"json");
}


function load_det_already_saved(){

    html=item_det="";
    description=$("#n_"+scid).val();
    model=$("#1_"+scid).val();

    item_det="<table style='width:100%;margin-top:-20px;padding:5px 0px;'>";
    item_det+="<tr><td style='width:30%;color:#fff;background:#476bb2;'>Supplier Code</td><td style='width:70%;color:#fff;background:#476bb2;'>Supplier</td></tr>";
    item_det+="<tr><td style='width:30%;border:1px solid #aaa;'>"+$("#supplier_id").val()+"</td><td style='width:70%;border:1px solid #aaa;'>"+$("#supplier").val()+"</td></tr></table>";
    
    item_det+="<table style='width:100%;margin-top:-10px;padding:5px 0px;'>";
    item_det+="<tr><td style='width:15%;color:#fff;background:#476bb2;'>Item Code</td>";
    item_det+="<td style='width:25%;color:#fff;background:#476bb2;'>Description</td>";
    item_det+="<td style='width:15%;color:#fff;background:#476bb2;'>Model</td>";
    item_det+="<td style='width:10%;color:#fff;background:#476bb2;'>Cost</td>";
    item_det+="<td style='width:10%;color:#fff;background:#476bb2;'>Last Price</td>";
    item_det+="<td style='width:10%;color:#fff;background:#476bb2;'>Selling Price</td>";
    item_det+="<td style='width:7.5%;color:#fff;background:#476bb2;'>Last Sale %</td>";
    item_det+="<td style='width:7.5%;color:#fff;background:#476bb2;'>Selling %</td></tr>";
    
    item_det+="<tr><td style='width:15%;border:1px solid #aaa;'>"+$("#0_"+scid).val()+"</td>";
    item_det+="<td style='width:width:25%;border:1px solid #aaa;'>"+description+"</td>";
    item_det+="<td style='width:width:15%;border:1px solid #aaa;'>"+model+"</td>";

    var det = $("#item_price_"+scid).val();
    if(det!=""){
        var count=det.length;
            item_det+="<td style='width:10%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+det.split("~")[0]+"</td>";
            item_det+="<td style='width:10%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+det.split("~")[1]+"</td>";
            item_det+="<td style='width:10%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+det.split("~")[2]+"</td>";
            item_det+="<td style='width:7.5%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+det.split("~")[3]+"</td>";
            item_det+="<td style='width:7.5%;border:1px solid #aaa;padding:3px 5px;text-align:right;'>"+det.split("~")[4]+"</td></tr></table>";
    }


            d_table  ="<table style='padding:5px 0px;width:100%;'>";
            d_table +="<tr><td style='color:#fff;background:#84adff;'>CL</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Cluster Name</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>BC</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Branch Name</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>No</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>ROL</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>ROQ</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Cur Stock</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Branch Requested Qty</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Request QTY</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Approve Qty</td>";
            d_table +="<td style='color:#fff;background:#84adff;'>Note</td></tr>";

                var det = $("#approve_"+scid).val();
                if(det!=""){
                var get_app=det.split(",");
                   
                var count  =get_app.length;
                for(var c=0; c<count; c++){
                   
                    d_table +="<tr><td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[1]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[8]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[2]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[9]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[3]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[4]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[5]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[6]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[10]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[7]+"</td>";
                    d_table +="<td style='border:1px solid #aaa;padding:3px 5px; width:60px;'>";
                    d_table +="<input type='hidden' id='req_det_"+c+"' name='req_det' value='"+get_app[c].split("~")[1]+"~"+get_app[c].split("~")[2]+"~"+get_app[c].split("~")[3]+"~"+get_app[c].split("~")[4]+"~"+get_app[c].split("~")[5]+"~"+get_app[c].split("~")[6]+"~"+get_app[c].split("~")[7]+"~"+get_app[c].split("~")[8]+"~"+get_app[c].split("~")[9]+"~"+get_app[c].split("~")[10]+"' />";
                    d_table +="<input type='text' id='appr_qty_"+c+"' style='width:100%;height:100%;border:none;text-align:right' class='ap' title='' value='"+ get_app[c].split("~")[11]+"'/></td>";
                    d_table +="<td style=' width:250px;border:1px solid #aaa;padding:3px 5px;'><input type='text' name='note_"+c+"' id='note_"+c+"' style='width:100%;height:100%;border:none;text-align:right' value='"+get_app[c].split("~")[7]+"'/></td></tr>";
                }
            }


            
            request_qty ="<table style='padding:5px 0px;'>";
            request_qty+="<tr><td style='color:#fff;background:#5b89e5;'>Request Quantity</td><td style='color:#fff;background:#5b89e5;'>QTY</td></tr>";
            for(var c=0; c<count; c++){
                request_qty+="<tr><td style='border:1px solid #aaa;padding:3px 5px;'>" + get_app[c].split("~")[8] +" -("+get_app[c].split("~")[1]+")</td> <td style='border:1px solid #aaa;padding:3px 5px;'>"+get_app[c].split("~")[7]+"</td></tr>";
            }
            request_qty+="</table>";

            $.post("index.php/main/load_data/t_req_approve_sum/load_req_details2", {
                code:$("#0_"+scid).val(),
                date:$("#date").val()
            },function(r){

            if(r.qty_cl.length>0){
                current_stock ="<table style='padding:5px 0px;'>";
                current_stock+="<tr><td style='color:#fff;background:#5b89e5;'>Current Quantity Cluster Wise</td><td style='color:#fff;background:#5b89e5;'>QTY</td></tr>";
            for(x=0;x<r.qty_cl.length;x++){
                current_stock+="<tr><td style='border:1px solid #aaa;padding:3px 5px;'>" + r.qty_cl[x].description + " - "+ r.qty_cl[x].cl+ "</td> <td style='border:1px solid #aaa;padding:3px 5px;'>"+r.qty_cl[x].qty+"</td><td><input type='button' class='bc_qty' title='..' id='bqty_"+x+"' /></td><td style='display:none;'>"+$("#0_"+scid).val()+"</td></tr>";
            }
                current_stock+="</table>";
            }else{
                current_stock="";
            }  
            $("#current_qty_det").html(current_stock);

            },"json");

    $("#d_table").html(d_table);
    $("#item_det").html(item_det);
    $("#request_qty_det").html(request_qty);
    load_approve(scid);
}


function price_presentage(price,purchase_price){
    purchase_price=parseFloat(purchase_price);
    price         =parseFloat(price);
    precentage    = ((price - purchase_price)*100)/price;
    price_pre     = m_round(precentage);
    return price_pre;
}


function disable_form(){}