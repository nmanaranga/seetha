$(document).ready(function(){
    $("#load_req_duplecate").css("display","none");
    $("#code").blur(function(){
        check_code();
    });
     
    $("#tgrid").tableScroll({height:355});

    $("#load_req").click(function(){
        if($("#supplier_id").val()!="" || $("#supplier").val()!=""){
		load_request_note();
        }else{
            set_msg("Please select supplier");
        }

    });

    $("#t_type").change(function(){
        empty_grid();
        get_sub_no();
        if($("#t_type").val()=='main_store'){
            view_main_store();
        }else{
            $("#cluster").val("");
        }
    });

    $(".fo").keydown(function(e){
         set_cid($(this).attr("id"));
        if(e.keyCode=='46'){
            $("#0_"+scid).val("");
            $("#n_"+scid).val("");
            $("#1_"+scid).val("");
            $("#3_"+scid).val("");
            $("#4_"+scid).val("");
            $("#5_"+scid).val("");
            $("#8_"+scid).val("");

        }

    });

    $(".fo").keydown(function(e){
    set_cid($(this).attr("id"));
    if($("#t_type").val()!='request'){
         if(e.keyCode==112){        
             $("#pop_search").val($("#0_"+scid).val());
             load_items();
             $("#serch_pop").center();
             $("#blocker").css("display", "block");
             setTimeout("select_search()", 100);
        }
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



    //     $(".fo").keydown(function(e){  
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

       $("#pop_search").keyup(function(){
            load_items();
       })

         $("#sub_no").keydown(function(e){
            if(e.keyCode == 13){
                $(this).blur();
                load_data($(this).val());
                $("#load_req").css("display","none");
                $("#load_req_duplecate").css("display","inline");
            }
        });

        $("#cluster").change(function(){
            
            var clust=$("#cluster").val();
            $.post("index.php/main/load_data/g_internel_transfer_order/select_branch",{
                cluster:clust,

            },function(res){             
               $("#branch").html(res);
            },"text"

            );     
        });

        // load_items();
        // $("#pop_search").keyup(function(e){
        //     if(e.keyCode != 13 && e.keyCode != 38 && e.keyCode != 40 ) { load_items();
        //     }
        // });
});

function view_main_store(){
     $.post("index.php/main/load_data/utility/main_store_select",{
            
        },function(res){    
            $("#cluster").val(res[0].cl); 
            $("#branch").append("<option value='"+res[0].bc+"' title='"+res[0].name+"'>" +res[0].bc+ "-" +res[0].name+ "</option>");     
           //alert(res[0].bc);
        },"json");  

}
function get_sub_no(){

     var type=$("#t_type").val();
     $("#types").val(type);

        $.post("index.php/main/load_data/utility/get_max_no_in_type_echo",{
            sub_hid:$("#sub_hid").val(),
            type:type,
            table:'g_t_internal_transfer_order_sum',
            sub_no:'sub_no'
        },function(res){             
           $("#sub_no").val(res);
        },"text"

        );     
}

function load_items(){
    $.post("index.php/main/load_data/g_internel_transfer_order/item_list_all", {
        search : $("#pop_search").val(),
        branch : $("#branch").val()
    }, function(r){
        $("#sr").html(r);
        settings1();
        
    }, "text");
}

function tot_amount(scid){

           var qty=parseFloat($("#4_"+scid).val());
           var price=parseFloat($("#7_"+scid).val());
         
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
                if(confirm("Save Completed, Do You Want A print?")){
                        if($("#is_prnt").val()==1){
                          $("#qno").val($("#id").val());
                          $("#org_print").val(1);
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
        $.post("index.php/main/delete/g_internel_transfer_order", {
            id : $("#sub_no").val(),
            type:$("#types").val(),
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
    $.post("index.php/main/get_data/g_internel_transfer_order", {
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
                $("#7_"+scid).val($(this).children().eq(2).html());
                $("#2_"+scid).val($(this).children().eq(3).html());
                $("#3_"+scid).val($(this).children().eq(4).html());
               
                
                $("#2_"+scid).focus();
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
    $.post("index.php/main/get_data/g_internel_transfer_order/", {
        id: id,
        type:$("#types").val()
    }, function(r){

        if(r=="2"){
           set_msg("No records");
        }else{
            $("#id").attr("readonly","readonly");
            if(r.sum[0].is_cancel=="1"){ 
                $("#mframe").css("background-image", "url('img/cancel.png')");
                $("#btnSave").attr('disabled','disabled');
                $("#btnDelete").attr('disabled','disabled');
            }
            if(r.sum[0].status=="2"){ 
                set_msg("Transfer order number already issued");
                $("#btnSave").attr('disabled','disabled');
                $("#btnDelete").attr('disabled','disabled');
            }else if(r.sum[0].status=="3"){
                set_msg("Transfer order number already Rejected");
                $("#btnSave").attr('disabled','disabled');
                $("#btnDelete").attr('disabled','disabled');
            }       
              
            $("#hid").val(id);   
            $("#id").val(id); 
            $("#qno").val(id);
            // var cluster = "<option>" + r.sum[0].description +"-"+r.sum[0].cl + "</option>";
            $("#cluster").val(r.sum[0].cl);
            var branch = "<option value='"+r.sum[0].to_bc+"' title='"+ r.sum[0].name+"'>" + r.sum[0].name+"-"+r.sum[0].to_bc+ "</option>";
            $("#branch").append(branch);
            $("#note").val(r.sum[0].note);
            $("#date").val(r.sum[0].ddate); 
            $("#ref_no").val(r.sum[0].ref_no);
            $("#total2").val(r.sum[0].total_amount);
            $("#sub_no").val(r.sum[0].sub_no);
            $("#sub_hid").val(r.sum[0].sub_no);
            $("#t_type").val(r.sum[0].type);
            $("#types").val(r.sum[0].type);
            $("#t_type").attr("disabled", "disabled");

            var c=1;
            for(var i=0; i<r.det.length;i++){

                $("#6_"+i).val(c);
                $("#0_"+i).val(r.det[i].item_code);
                $("#n_"+i).val(r.det[i].description);
                $("#1_"+i).val(r.det[i].model);
                $("#7_"+i).val(r.det[i].item_cost);
                $("#2_"+i).val(r.det[i].price);
                $("#3_"+i).val(r.det[i].qty);
                $("#4_"+i).val(r.det[i].quantity);
                tot_amount(i);
                c++;
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
        $("#2_"+i).val("");
        $("#3_"+i).val("");
        $("#5_"+i).val("");
        $("#4_"+i).val("");
        //$("#6_"+i).val("");
        $("#7_"+i).val("");
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

        var store=$("#store_from").val();
        $.post("index.php/main/load_data/t_damage_sum/is_batch_item",{
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

        var store=$("#store_from").val();
        $.post("index.php/main/load_data/t_damage_sum/is_batch_item",{
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
                $("#5_"+scid).val(res.split("-")[1]);
                $("#bqty_"+scid).val(res.split("-")[1]);
                $("#1_"+scid).attr("readonly","readonly");
           }
        },'text');
}
